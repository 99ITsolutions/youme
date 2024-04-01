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
</style>

   
           <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '636') { echo $langlbl['title'] ; } } ?></h2>
                            <?php if($user_details[0]['role'] == 2) { ?>
                            <ul class="header-dropdown">
                                <li><a href="<?=$baseurl?>schools/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '640') { echo $langlbl['title'] ; } } ?></a></li>
                                <li><a href="javascript:void(0)"  title="Delete All" data-str= "Schools" data-url = "schools/deleteallschools"  id="deleteallschools" class="btn btn-sm btn-success approve"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1532') { echo $langlbl['title'] ; } } ?> </a></li>
                            </ul>
                            <?php } ?>
                           <!-- <h2><a href="<?=$baseurl?>schools/export" title="Export" class="btn btn-sm btn-success mt-4">Export in Excel</a> </h2>-->
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="school_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '637') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1423') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '638') { echo $langlbl['title'] ; } } ?></th>
                                            <!--<th>Expiry Date</th>-->
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '639') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                    <?php
                                    if($user_details[0]['role'] == 2) 
                                    { 
                                        $n=1;
                                        foreach($school_details as $value)
                                        {
                                            $total_count = $value['examass_sts'] + $value['gallery_sts'] + $value['student_sts'] + $value['subject_sts'] + $value['class_sts'] + $value['teacher_sts'] + $value['subjcls_sts'] + $value['knowledge_sts']; 
                                            $stu_delreq = $value['stud_delreq'];
                                            $casecount = $value['contactyoume_sts'];
                                            $rsninactv = $value['reason_inactive'] == "" ? "" : "(".substr($value['reason_inactive'], 0,25).")";
                                            ?>
                                            <tr>
                                                 <td class="width45">
                                                    <label class="fancy-checkbox">
                                                        <input class="checkbox-tick" type="checkbox" name="checkbox"  id="<?= $value['id'] ?>">
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold"><?= $value['comp_name'] ?></span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold"><?= $value['status_reason']. $rsninactv  ?></span>
                                                </td>
                                                <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '667') { $inactv =  $langlbl['title'] ; } } ?>
                                                <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '668') { $actv = $langlbl['title'] ; } } ?>
                                                <?php
                                                if($value['status'] == 0)
                                                {
                                                ?>
                                                <td><span><a href="javascript:void()" data-url="schools/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="School" class="btn btn-sm btn-outline-danger schoolstatusreview" title="Status" data-type="status_change"><?=str_replace("0",$inactv,str_replace("1",$actv,$value['status']))?> </a></span></td>
                                                <?php
                                                }
                                                else
                                                {
                                                ?>
                                                <td><span><a href="javascript:void()" data-url="schools/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="School" class="btn btn-sm btn-outline-success schoolstatusreview" title="Status" data-type="status_change"><?=str_replace("0",$inactv,str_replace("1",$actv,$value['status']))?> </a></span></td>
                                                <?php
                                                    }
                                                ?>
                                               
                                                <td>
                                                        <a href="<?=$baseurl?>schools/update/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                    
                                                        <button type="button" data-url="schools/delete" data-id="<?=$value['id']?>" data-str="School" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                            
                                                        <a href="<?=$baseurl?>schools/schedular/<?= md5($value['id'])?>" title="Schedular" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-calendar"></i></a>
                                                        <!--<p id="p<?=$n?>" style="display:none;"><?=$fullurl . "login";?></p>
                                                        <button onclick="copyToClipboard('#p<?=$n?>')" title="Copy Url " class="btn btn-sm btn-outline-secondary"><i class="fa fa-files-o"></i></button>-->
                                                   
                                                        <a href="javascript:void(0)" data-id="<?= $value['id'] ?>" data-type="1" data-language="<?= $langua ?>" data-genactvity="super" data-password = "<?= $value['password'] ?>" data-email="<?= $value['email'] ?>"  title="View" class="btn btn-sm btn-outline-secondary schooldash" ><i class="icon-speedometer"></i></a>
                                                        <input type="hidden" name="type" id="type" class="type" value="1" >
                                    				    <input type="hidden" name="email" id="email"  class="email" value="<?= $value['email'] ?>" >
                                    				    <input type="hidden" name="password" id="password"  class="password" value="<?= $value['password'] ?>" >
                                    				    <input type="hidden" name="school_id" id="school_id"  class="school_id" value="<?= $value['id'] ?>" >
                                    				    <a href="<?=$baseurl?>schools/exams/<?= md5($value['id'])?>" title="Exams Listing" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-file-text-o"></i></a>
                                    				    <a href="<?=$baseurl?>schools/approveStatus/<?= md5($value['id'])?>" title="Approval Listing" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><img src="img/status.png" width="17px" height="17px"></a><span class="badge"><?= $total_count ?></span>
                                                        <a href="<?=$baseurl?>schools/deleterequest/<?= md5($value['id'])?>" title="Student Delete Request Listing" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><img src="icons/stu_del.png" width="17px" height="17px"></a><span class="badge"><?= $stu_delreq ?></span>
                                                        <a href="<?=$baseurl?>schools/casesyoume/<?= md5($value['id'])?>" title="Cases You-Me" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-envelope"></i></a><span class="badge"><?= $casecount ?></span>
                                    				       
                                                </td>
                                            </tr>
                                            <?php
                                            $n++;
                                        }
                                    }
                                    elseif($user_details[0]['role'] == 3)
                                    {
                                        $n = 1;
                                        $priv = explode(",", $user_details[0]['privilages']);
                                        foreach($school_details as $value)
                                        {
                                            if(in_array($value['id'], $priv)) 
                                            {
                                                $total_count = $value['examass_sts'] + $value['gallery_sts'] + $value['student_sts'] + $value['subject_sts'] + $value['class_sts'] + $value['teacher_sts'] + $value['subjcls_sts'] + $value['knowledge_sts']; 
                                                $stu_delreq = $value['stud_delreq'];
                                                $casecount = $value['contactyoume_sts'];
                                                
                                                $rsninactv = $value['reason_inactive'] == "" ? "" : "(".substr($value['reason_inactive'], 0,25).")";
                                                ?>
                                                <tr>
                                                    <td class="width45">
                                                        <label class="fancy-checkbox">
                                                            <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <span class="font-weight-bold"><?= $value['comp_name'] ?></span>
                                                    </td>
                                                    <td> 
                                                        <span class="font-weight-bold"><?= $value['status_reason']. $rsninactv  ?></span>
                                                    </td>
                                                    <!--<td>
                                                        <span><?= $value['students'] ?></span>
                                                    </td>-->
                                                    <td>
                                                        <span><?=str_replace("0","Inactive",str_replace("1","Active",$value['status']))?> </span>
                                                    </td>
                                                    <!--<td>
                                                        <span><?=date('d-m-Y', strtotime($value['expiry_date']))?></span>
                                                    </td>-->
                                                    <td>
                                                            <a href="<?=$baseurl?>schools/update/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                        
                                                            <button type="button" data-url="schools/delete" data-id="<?=$value['id']?>" data-str="School" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
        
                                                            <a href="<?=$baseurl?>schools/schedular/<?= md5($value['id'])?>" title="Schedular" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-calendar"></i></a>
                                                            <!--<p id="p<?=$n?>" style="display:none;"><?=$fullurl . "login";?></p>
                                                            <button onclick="copyToClipboard('#p<?=$n?>')" title="Copy Url " class="btn btn-sm btn-outline-secondary"><i class="fa fa-files-o"></i></button>-->
                                                       
                                                            <a href="javascript:void(0)" data-id="<?= $value['id'] ?>" data-type="1" data-password = "<?= $value['password'] ?>" data-email="<?= $value['email'] ?>"  title="View" class="btn btn-sm btn-outline-secondary schooldash" ><i class="icon-speedometer"></i></a>
                                                            <input type="hidden" name="type" id="type" class="type" value="1" >
                                        				    <input type="hidden" name="email" id="email"  class="email" value="<?= $value['email'] ?>" >
                                        				    <input type="hidden" name="password" id="password"  class="password" value="<?= $value['password'] ?>" >
                                        				    <input type="hidden" name="school_id" id="school_id"  class="school_id" value="<?= $value['id'] ?>" >
                                        				    <a href="<?=$baseurl?>schools/exams/<?= md5($value['id'])?>" title="Exams Listing" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-file-text-o"></i></a>
                                        				    <a href="<?=$baseurl?>schools/approveStatus/<?= md5($value['id'])?>" title="Approval Listing" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><img src="img/status.png" width="17px" height="17px"></a><span class="badge"><?= $total_count ?></span>
                                                            <a href="<?=$baseurl?>schools/deleterequest/<?= md5($value['id'])?>" title="Student Delete Request Listing" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><img src="icons/stu_del.png" width="17px" height="17px"></a><span class="badge"><?= $stu_delreq ?></span>
                                                            <a href="<?=$baseurl?>schools/casesyoume/<?= md5($value['id'])?>" title="Cases You-Me" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-envelope"></i></a><span class="badge"><?= $casecount ?></span>
                                    				   
                                                    </td>
                                                </tr>
                                                <?php
                                                $n++;
                                            }
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
  <div class="modal-dialog" role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">School Approval Status </h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                
            </div>
             
        </div>
    </div>
</div>         

<div class="modal fade" id="sclapprovests" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">School Status Reason</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                 <?php	echo $this->Form->create(false , ['url' => ['action' => 'status'] , 'id' => "stsrsnform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" id="sclsts" name="sclsts">
                    <input type="hidden" id="sclid" name="sclid">
                    <div class="col-md-12">
                        <div class="form-group">    
                            <label>Status Update Reason</label>
                            <select class="form-control" name="statusrsn" id="statusrsn" required onchange ="getstssn(this.value);">
                                <option value="">Choose Reason</option>
                                <option value="No Payment Received">No Payment Received</option>
                                <option value="No Longer Member">No Longer Member</option>
                                <option value="Other, please explain">Other, please explain</option>
                            </select>
                        </div>
                        <div class="form-group" id="rsnsts" style="display:none">   
                            <label>Reason</label>
                            <textarea name="inactvrsn" id="inactvrsn" class="form-control" ></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="sclstserror"></div>
                        <div class="success" id="sclstssuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary sclstsbtn" id="sclstsbtn">Submit</button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
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

function getstssn(val)
{
    $("#rsnsts").css("display", "none");
    $("#inactvrsn").removeAttr("required");
    if(val == "Other, please explain")
    {
        $("#rsnsts").css("display", "block");
        $("#inactvrsn").prop('required');
    }
}

</script>    

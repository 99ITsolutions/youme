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
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2199') { $lbl2199 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2200') { $lbl2200 = $langlbl['title'] ; } 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
    if($langlbl['id'] == '640') { $lbl640 = $langlbl['title'] ; }
    if($langlbl['id'] == '667') { $inactv =  $langlbl['title'] ; }
    if($langlbl['id'] == '668') { $actv = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2247') { $lbl2247 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2248') { $lbl2248 = $langlbl['title'] ; }
    if($langlbl['id'] == '631') { $lbl631 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2213') { $lbl2213 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '1738') { $lbl1738 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1405') { $lbl1405 = $langlbl['title'] ; }
}
?>
   
           <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?= $lbl2199 ?></h2>
                            <?php if($user_details[0]['role'] == 2) { ?>
                            <ul class="header-dropdown">
                                <li><a href="<?=$baseurl?>Canteenvendors/add" title="Add" class="btn btn-sm btn-success"><?= $lbl640 ?></a></li>
                                <li><a href="javascript:void(0)"  title="<?= $lbl41 ?>"  onclick="goBack()"  class="btn btn-sm btn-success"><?= $lbl41 ?> </a></li>
                            </ul>
                            <?php } ?>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem vendor_table" id="vendor_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><?= $lbl2247 ?></th>
                                            <th><?= $lbl2248 ?></th>
                                            <th><?= $lbl2213 ?></th>
                                            <th>Email</th>
                                            <th><?= $lbl631 ?></th>
                                            <th><?= $lbl1738 ?></th>
                                            <th><?= $lbl1405 ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n=1;
                                    foreach($vendor_details as $value)
                                    {
                                        ?>
                                        <tr>
                                            <td>
                                                <img src="<?= $baseurl ?>canteen/<?= $value['logo'] ?>" width="50px" />
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?= ucfirst($value['vendor_company']) ?></span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?= $value['l_name']." ".$value['f_name']  ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['email']  ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['password']  ?></span>
                                            </td>
                                            <?php
                                            if($value['status'] == 0)
                                            {
                                            ?>
                                            <td><span><a href="javascript:void()" data-url="canteenvendors/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Canteen Vendors Status" class="btn btn-sm btn-outline-danger schoolstatusreview" title="Status" data-type="status_change"><?=str_replace("0",$inactv,str_replace("1",$actv,$value['status']))?> </a></span></td>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                            <td><span><a href="javascript:void()" data-url="canteenvendors/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Canteen Vendors Status" class="btn btn-sm btn-outline-success schoolstatusreview" title="Status" data-type="status_change"><?=str_replace("0",$inactv,str_replace("1",$actv,$value['status']))?> </a></span></td>
                                            <?php
                                                }
                                            ?>
                                            <td>
                                                <span><?= date("d-m-Y h:i A", $value['created_date'])  ?></span>
                                            </td>
                                            <td>
                                                <a href="<?=$baseurl?>canteenvendors/update/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                <button type="button" data-url="canteenvendors/delete" data-id="<?=$value['id']?>" data-str="Vendor" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                <a href="<?=$baseurl?>canteenvendors/schoollist/<?= $value['id']?>" title="View data according to school" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                        $n++;
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

<!--<div class="modal fade" id="sclapprovests" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
</div>    -->

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

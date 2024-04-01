<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
    .col-sm-2 {
        -ms-flex: 0 0 18%;
        flex: 0 0 18.2%;
        max-width: 18.2%;
        padding-right:10px !important;
        padding-left:10px !important;
    }
    @media (min-width: 576px) {
    .col-sm-3 {
        -ms-flex: 0 0 25%;
        flex: 0 0 24%;
        max-width: 24%;
    }

}
@media screen and (max-width: 391px) {
    .card .header .header-dropdown {

    top: 44px;
    right: 98px;
    list-style: none;
}
}
</style>
<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '9') { $lbl9 = $langlbl['title'] ; }
    if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; }
    if($langlbl['id'] == '2222') { $lbl2222 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2223') { $lbl2223 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2224') { $lbl2224 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2225') { $lbl2225 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2226') { $lbl2226 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2208') { $lbl2208 = $langlbl['title'] ; }
    if($langlbl['id'] == '2228') { $lbl2228 = $langlbl['title'] ; }
    if($langlbl['id'] == '566') { $lbl566 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '635') { $lbl635 = $langlbl['title'] ; }
    if($langlbl['id'] == '2391') { $lbl2391 = $langlbl['title'] ; }
    if($langlbl['id'] == '2342') { $lbl2342 = $langlbl['title'] ; }
    if($langlbl['id'] == '2392') { $lbl2392 = $langlbl['title'] ; }
    if($langlbl['id'] == '2393') { $lbl2393 = $langlbl['title'] ; }
    if($langlbl['id'] == '2394') { $lbl2394 = $langlbl['title'] ; }
    if($langlbl['id'] == '2395') { $lbl2395 = $langlbl['title'] ; }
    if($langlbl['id'] == '243') { $lbl243 = $langlbl['title'] ; }
    if($langlbl['id'] == '1815') { $lbl1815 = $langlbl['title'] ; }
    if($langlbl['id'] == '1577') { $lbl1577 = $langlbl['title'] ; }
    if($langlbl['id'] == '2189') { $lbl2189 = $langlbl['title'] ; }
    
} 
$del = explode(",", $deliv); 
$delvs = implode("~", $del);
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-3 heading"><?= $lbl2391 ?></h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0)" title="Deliver All" data-str= "All Order Status" data-url="orderfood/deliverallfood" id="deliverfoodall" class="btn btn-sm btn-success deliverfoodall"><?= $lbl2228 ?></a></li>
                                 
                                <li><a href="<?=$baseurl?>Orderfood/export/<?=$sclids?>/<?= $enddate1 ?>/<?= $time ?>/<?= $delvs ?>" <?= $stylecss ?> class="btn btn-sm btn-success" id="exportreport"><?= $lbl566 ?></a></li>
                                
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="body" id="gen_pdf">
                        <div class="row clearfix col-md-12 ">
                            <div class="error" id="summryerror"><?= $error ?></div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-12 row"  style="max-width:100%">
    	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
    	                    <input type="hidden" name="searchby" value="1">
    	                    <div class="row col-md-12">
    	                        <div class="col-12 col-md-3 col-lg-2">
        	                        <label><?= $lbl635 ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control listschool" id="listschool" name="schoolid" required>
                                            <option value="">Choose School</option>
                                            <?php
                                            foreach($scl_details as $key => $val){
                                                if($sclids != '' && ($val['id'] == $sclids))
                                                {
                                                    $sel = "selected";
                                                }
                                                else
                                                {
                                                    $sel = "";
                                                }
                                            ?>
                                              <option  value="<?=$val['id']?>" <?= $sel ?>><?php echo $val['comp_name'];?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-12 col-md-3 col-lg-2">
        	                        <label>Section(s) *</label>
                                    <div class="form-group">
                                        <select class="form-control sections" id="chosesctn" name="chosesctn[]" multiple required>
                                            <option value="">Choose Option</option>
                                            <?php $sctns = explode(",", $sctn); ?>
                                            <option value="Kindergarten" <?php if(in_array("Kindergarten", $sctns)) { echo "selected"; } ?> ><?= $lbl1815 ?></option>
                                            <option value="Primary" <?php if(in_array("Primary", $sctns)) { echo "selected"; } ?> ><?= $lbl1577 ?></option>
                                            <option value="Senior" <?php if(in_array("Senior", $sctns)) { echo "selected"; } ?> ><?= $lbl2189 ?></option>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-12 col-md-3 col-lg-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <?php
                                        if(!empty($enddate1)) {
                                            $enddate1 = date("Y-m-d", strtotime($enddate1));
                                        }
                                        else
                                        {
                                            $enddate1 = '';
                                        }?>
                                        <input type="date" class="form-control" id="enddate" value="<?= $enddate1 ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="End Date *">
                                        <input type="hidden" id="seldate" name="seldate">
                                    </div>
        	                    </div>
        	                    <div class="col-12 col-md-3 col-lg-2">
        	                        <label><?= $lbl2222 ?>*</label>
                                    <div class="form-group">
                                        <select class="form-control timings" id="timings" name="timings" required >
                                            <?php  if($time == "") { ?>
                                            <option value="">Choose Timings</option>
                                            <?php } else { ?>
                                               <option value="">Select Timings</option>
                                               <?php foreach($time_opt as $to) { ?>
                                               <option value="<?= $to['timings'] ?>" <?php if($to['timings'] == $time) { echo "selected" ; } ?> ><?= $to['timings'] ?></option>
                                            <?php } } ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-12 col-md-3 col-lg-2">
        	                        <label><?= $lbl2392 ?>*</label>
                                    <div class="form-group">
                                        <select class="form-control closed_booking" id="deliver_un" name="deliver_un[]" multiple required>
                                            <option value="">Choose Option</option>
                                            <?php $delivrs = explode(",", $deliv); ?>
                                            <option value="0" <?php if(in_array("0", $delivrs)) { echo "selected"; } ?> ><?= $lbl2393 ?></option>
                                            <option value="1" <?php if(in_array("1", $delivrs)) { echo "selected"; } ?> ><?= $lbl2394 ?></option>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-12 col-md-9 col-lg-1">
        	                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?= $lbl243 ?></button>
        	                    </div>
        	                    </div>
        	                    <?php echo $this->Form->end();?>
        	               </div>
        	               
    	                </div>
    	                
    	                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="deliverfood_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th>
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
                                            </label>
                                        </th>
                                        <th><?= $lbl2342 ?></th>
                                        <th><?= $lbl147 ?></th> 
                                        <th><?= $lbl9 ?></th>   
                                        
                                        <th><?= $lbl2208 ?></th>       
                                        <th><?= $lbl2226 ?></th>                             
                                        <th><?= $lbl2223 ?></th>
                                        <th><?= $lbl2224 ?></th>
                                        <th><?= $lbl2225 ?></th>
                                        <th>Date</th>
                                        <th><?= $lbl2222 ?></th>
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($cso_details)) {
                                        //print_r($cso_details);
                                    foreach($cso_details as $value)
                                    { ?>
                                        <tr>
                                            <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value['id'] ?>">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td><?= $value->order_no ?></td>
                                            <td><?= ucfirst($value['student']['l_name']." ".$value['student']['f_name']."-".$value['student']['adm_no']) ?></td>
                                            <td><?= ucfirst($value['class']['c_name']."-".$value['class']['c_section']." (".$value['class']['school_sections'].")") ?></td>
                                            
                                            <td><?= ucfirst($value['food_item']['food_name']) ?></td>
                                            <td><?= $value->quantity ?></td>
                                            <td>$<?= $value->food_amount ?></td>
                                            <td>$<?= $value->total_amount ?></td>
                                            <!--<td><b><?= $value->order_status == 0 ? "Undeliver":"Delivered"; ?></b></td>-->
                                            <td>
                                                <?php if($value['order_status'] == 0) { ?>
                                                    <a href="javascript:void()" data-url="orderfood/ostatus" data-id="<?=$value['id']?>" data-status="<?=$value['order_status']?>" data-str="Student Food Ordered" class="btn btn-sm btn-outline-danger js-sweetalert" title="Status" data-type="status_change"><?=str_replace("0","Undeliver",str_replace("1","Delivered",$value['order_status']))?> </a>
                                                <?php } else { ?>
                                                    <a href="javascript:void()" data-url="orderfood/ostatus" data-id="<?=$value['id']?>" data-status="<?=$value['order_status']?>" data-str="Student Food Ordered" class="btn btn-sm btn-outline-success js-sweetalert" title="Status" data-type="status_change"><?=str_replace("0","Undeliver",str_replace("1","Delivered",$value['order_status']))?> </a>
                                                <?php } ?>
                                            </td>
                                            <td><?= $value->date ?></td>
                                            <td><?= $value->timings ?></td>
                                        </tr>
                                        <?php
                                    } }
                                    ?>
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php
if(!empty($error))
{
    ?>
    <script>
        $("#summryerror").fadeIn().delay('5000').fadeOut('slow');
    </script>
    <?php
}
?>
<script>
$(document).ready(function() {
    $('input[type="date"]').change(function()
    {
        var seldate = this.value;
        $("#seldate").val(seldate);
        $("#timings").html("")
        var sctn = $("#chosesctn").val()
        var sclid = $("#listschool").val()
        var refscrf = $("input[name='_csrfToken']").val();
        $.ajax({
            url: baseurl +"/orderfood/gettime", 
            data: {"seldate":seldate, _csrfToken:refscrf, "sctn":sctn, "scl_id":sclid}, 
			type: 'post',
            success: function(result) {
                if (result) 
                {
                    $("#timings").html(result)
                }
                else
                {
                    swal("Something went wrong. Please try again",  "error");
                }
            }
        })
    });
    $('#deliverfoodall').click(function() {
        //alert("Cds");
        var str =  $(this).data("str") ;
        var url = baseurl+"/"+$(this).data("url") ;
        var post_arr = [];
        // Get checked checkboxes
        $('#deliverfood_table input[type=checkbox]').each(function() {
          if (jQuery(this).is(":checked")) {
            var id = this.id;
            //var splitid = id.split('_');
            var postid = id;
            post_arr.push(postid);
          }
        });
        var refscrf = $("input[name='_csrfToken']").val();
        console.log(post_arr);
        var lbl2395 = "<?php echo $lbl2395 ?>"
        if(post_arr.length > 0){
            swal({
            title: areyou,
            text: chngselsts,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            confirmButtonText: yeschng,
            closeOnConfirm: false,
            cancelButtonText: cncl,  
            showLoaderOnConfirm: true
        }, function () {
            $.ajax({
                data : {
                    val : post_arr ,
                   _csrfToken : refscrf,
                   str : str
                },
                type : "post",
                url : url,
                success: function(response){
                    if(response.result == "success"){
                        swal(statuschng, str+" "+ haschng, "success");
                        setTimeout(function(){ location.reload() ;  }, 1000);
                    }
                    else{
                        swal(errorpop, response.result, "error");
                    }
                }
            })
        });
        } 
        else
        {
            swal(errorpop, lbl2395, "error");
        }
    });
});
</script>




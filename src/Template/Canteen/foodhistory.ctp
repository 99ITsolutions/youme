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
    #rmrk
    {
        font-size:17px;
        font-weight:bold;
    }
    @media screen and (max-width: 767px) {
        .card .header .header-dropdown {
    top: 115px !important;
    right: 100px !important;
}
    }

    @media screen and (max-width: 391px) {
        .col-sm-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 24%;
            max-width: 24%;
        }
        .card .header .header-dropdown {
    top: 115px !important;
    right: 60px !important;
}
    }
    
 
</style>
<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '9') { $lbl9 = $langlbl['title'] ; }
    if($langlbl['id'] == '2213') { $lbl2213 = $langlbl['title'] ; }
    if($langlbl['id'] == '2222') { $lbl2222 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2223') { $lbl2223 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2224') { $lbl2224 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2225') { $lbl2225 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2226') { $lbl2226 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2208') { $lbl2208 = $langlbl['title'] ; }
    if($langlbl['id'] == '2228') { $lbl2228 = $langlbl['title'] ; }
    if($langlbl['id'] == '566') { $lbl566 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2254') { $lbl2254 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2264') { $lbl2264 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2265') { $lbl2265 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2266') { $lbl2266 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2267') { $lbl2267 = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '2268') { $lbl2268 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2269') { $lbl2269 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2270') { $lbl2270 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2271') { $lbl2271 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2272') { $lbl2272 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2273') { $lbl2273 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2274') { $lbl2274 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2275') { $lbl2275 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2276') { $lbl2276 = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '2277') { $lbl2277 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2278') { $lbl2278 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2279') { $lbl2279 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2280') { $lbl2280 = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '2281') { $lbl2281 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2282') { $lbl2282 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2283') { $lbl2283 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2284') { $lbl2284 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2285') { $lbl2285 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2286') { $lbl2286 = $langlbl['title'] ; } 
} 
$del = explode(",", $deliv); 
$delvs = implode("~", $del);
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-3 heading"><?= $lbl2254 ?></h2>
                            
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0);" title="Order no." class="btn btn-sm btn-success" data-toggle="modal" data-target="#invoice"><?= $lbl2264 ?></a></li>
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                            </ul>
                        </div>
                        <div class="row mt-2">
                            <p class="col-md-3">
                                <?= $lbl2265 ?> - $<?= $amtadded ?> <br>
                                <?= $lbl2266 ?> - $<?= $amtspent ?> <br>
                                <?= $lbl2267 ?> - $<?= $balance ?>
                            </p>
                        </div>
                        
                    </div>
                    
                    <div class="body" id="gen_pdf">
                        <div class="row clearfix">
                            <div class="col-md-12 row"  style="max-width:100%">
    	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'foodhistory'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
    	                    <input type="hidden" name="searchby" value="1">
    	                    <div class="row col-md-12">
        	                    <div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <?php
                                        if(!empty($strtdate1)) {
                                            $strtdate1 = date("Y-m-d", strtotime($strtdate1));
                                        }
                                        else
                                        {
                                            $strtdate1 = '';
                                        }?>
                                        <input type="date" class="form-control" id="startdate" value="<?= $strtdate1 ?>" data-date-format="dd-mm-yyyy" name="startdate"  required placeholder="Start Date *">
                                    </div>
        	                    </div>
        	                    <div class="col-sm-3">
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
                                    </div>
        	                    </div>
        	                    <div class="col-sm-1">
        	                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
        	                    </div>
        	                    </div>
        	                    <?php echo $this->Form->end();?>
        	               </div>
        	               
    	                </div>
    	                <div class="row  clearfix">
                            <div class="col-sm-12">
                                <div class="table-responsive"><br><br>
                                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="foodhistory_table" data-page-length='50'>
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="display:none">id</th>
                                                <th><?= $lbl2268 ?></th>
                                                <th><?= $lbl2213 ?></th> 
                                               
                                                <th><?= $lbl2208 ?></th>       
                                                <th><?= $lbl2226 ?></th>                             
                                                <th><?= $lbl2223 ?></th>
                                                <th><?= $lbl2224 ?></th>
                                                <th><?= $lbl2225 ?></th>
                                                <th><?= $lbl2269 ?></th>
                                                <th><?= $lbl2270 ?></th>
                                                <th><?= $lbl2271 ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="meetinglink_body" class="modalrecdel"> 
                                            <?php if(!empty($cso_details)) {
                                            foreach($cso_details as $value)
                                            { 
                                                if($value->order_status == 0)
                                                {
                                                    $osts = $lbl2276;
                                                }
                                                elseif($value->order_status == 1)
                                                {
                                                    $osts = $lbl2273;
                                                }
                                                elseif($value->order_status == 2)
                                                {
                                                    $osts = $lbl2274;
                                                }
                                                elseif($value->order_status == 3)
                                                {
                                                    $osts = $lbl2272;
                                                }  
                                                elseif($value->order_status == 4)
                                                {
                                                    $osts = $lbl2275;
                                                }  
                                                $timings = explode("-", $value->timings);
                                                if(!empty($timings))
                                                {
                                                    $seldate = strtotime($value->date." ".$timings[0]);
                                                }
                                                else
                                                {
                                                    $seldate = strtotime($value->date." ".$value->timings);
                                                }
                                                
                                                
                                                $currdate = time();
                                                //echo date("d-m-Y h:i A");
                                                $diff = $seldate-$currdate;
                                                $cancl = '';
                                                if($value['order_status'] != 2 && $value['order_status'] != 1) {
                                                    
                                                    if($diff >= 18000)
                                                    {
                                                        $cancl = '<a href="javascript:void()" data-url="cancel" data-id="'.$value['id'].'" data-status="'.$value['order_status'].'" data-str="Student Food Cancelled" class="btn btn-sm btn-secondary canclfood" title="Status" data-type="status_change">Cancel</a>';
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td style="display:none"><?= $value->id ?></td>
                                                    <td><?= $value->order_no ?></td>
                                                    <td><?= ucfirst($value['canteen_vendor']['vendor_company']."(".$value['canteen_vendor']['l_name']." ".$value['canteen_vendor']['f_name'].")") ?></td>
                                                    
                                                    <td><?= ucfirst($value['food_item']['food_name']) ?></td>
                                                    <td><?= $value->quantity ?></td>
                                                    <td>$<?= $value->food_amount ?></td>
                                                    <td>$<?= $value->total_amount ?></td>
                                                    <!--<td><b><?= $value->order_status == 0 ? "Undelivered":"Delivered"; ?></b></td>-->
                                                    <td>
                                                        <?php if($value['order_status'] == 0) { ?>
                                                            <a href="javascript:void()" data-str="Student Food Ordered" class="btn btn-sm btn-pending" title="Status" data-type="status_change"><?=  $osts ?> </a>
                                                        <?php }
                                                        elseif($value['order_status'] == 4) { ?>
                                                            <a href="javascript:void()" data-str="Student Food Ordered" class="btn btn-sm btn-pending" title="Status" data-type="status_change"><?=  $osts ?> </a>
                                                        <?php }
                                                        elseif($value['order_status'] == 1) { ?>
                                                            <a href="javascript:void()" data-str="Student Food Ordered" class="btn btn-sm btn-deliver" title="Status" data-type="status_change"><?= $osts ?> </a>
                                                        <?php } else { ?>
                                                            <a href="javascript:void()" data-str="Student Food Ordered" class="btn btn-sm btn-danger" title="Status" data-type="status_change"><?=  $osts ?> </a>
                                                        <?php }
                                                        echo $cancl;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?= $value->remark ?>
                                                    </td>
                                                    <td><?= $value->date." (".$value->timings.")" ?></td>
                                                    <td><?= date("d-m-Y (h:i)", $value->created_date) ?></td>
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
<div class="modal classmodal animated zoomIn" id="invoice" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl2277 ?></h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'orderinfo'] , 'id' => "orderinfo" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label><?= $lbl2278 ?></label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control" required name="order_no" placeholder="<?= $lbl2278 ?>*">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="ordererror"></div>
                        <div class="success" id="ordersuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addorderbtn" id="addorderbtn"><?= $lbl2279 ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '108') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>  

<div class="modal classmodal animated zoomIn" id="orderdinfo" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl2280 ?></h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-11" id="studinfo">
                        
                    </div>
                    <div class="col-md-1" id="downloadinvoice"></div>
                </div>
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'orderdinfo'] , 'id' => "orderdinfo" , 'method' => "post"  ]); ?>
                <input type="hidden" name="orderid" id="orderid" >
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem viewdtlorder_table" id="viewdtlorder_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?= $lbl2281 ?></th>
                                        <th><?= $lbl2282 ?></th>
                                        <th><?= $lbl2283 ?></th>
                                        <th><?= $lbl2284 ?></th>
                                        <th><?= $lbl2285 ?></th>
                                        <th><?= $lbl2265 ?></th>
                                        <th><?= $lbl2286 ?></th>
                                    </tr>
                                </thead>
                                <tbody id="viewdtlordrbody" class="modalrecdel"> 
                                
                                </tbody>
                            </table>
                        </div>
                        
                        
                    </div>
                    <div class="col-md-12" id="rmrk"></div>
                    <div class="col-md-12">
                        <div class="error" id="orderderror"></div>
                        <div class="success" id="orderdsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <!--<a href="#" class="btn btn-primary addorderbtn" id="addorderbtn">Deliver All</a>-->
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '108') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                </div>    
                <?php echo $this->Form->end(); ?>
            </div>
             
        </div>
    </div>
</div>   

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
            swal(errorpop, "Please select delivered food items", "error");
        }
    });
});
</script>




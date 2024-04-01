<style>
    .col-sm-2 {
        -ms-flex: 0 0 21%;
        flex: 0 0 21%;
        max-width: 21%;
    }
</style>
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '2230') { $lbl2230 = $langlbl['title'] ; }
    if($langlbl['id'] == '2231') { $lbl2231 = $langlbl['title'] ; }
} 
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-4 heading">Vendor Food Status (Not Marked)</h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0)" title="Delivered All" data-str= "All food items status" data-url="foodstatus/vndrfoodsts" id="deliverfoodall" data-status="1" class="btn btn-sm btn-success"><?= $lbl2231 ?></a></li>
                                <li><a href="javascript:void(0)" title="Undelivered All" data-str= "All food items status" data-url="foodstatus/vndrfoodsts" id="undeliverfoodall" data-status="3" class="btn btn-sm btn-success"><?= $lbl2230 ?></a></li>
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="body" id="gen_pdf">
                        <div class="row clearfix col-md-12 ">
                            <div class="error" id="summryerror"><?= $error ?></div>
                        </div>
                        
	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
	                    <div class="row">
	                        <div class="col-sm-2">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <select class="form-control listschool" id="listschool" name="schoolid" required onchange="getvendorsfoodsts(this.value)">
                                        <option value="">Choose School</option>
                                        <?php
                                        foreach($school_details as $key => $val){
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
    	                    <div class="col-sm-2">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2216') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <select class="form-control request_opt" id="vendors" name="vendors" required>
                                        <option value="">Choose Teachers</option>
                                        <?php if(!empty($vndrid))
                                        {
                                            echo $vendordata;
                                        }
                                        ?>
                                    </select> 
                                </div>
    	                    </div>
    	                    <div class="col-sm-2">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <?php  if(!empty($startdate1)) {
                                        $startdate1 = date("d-m-Y", $startdate1);
                                    }
                                    else
                                    {
                                        $startdate1 = '';
                                    }?>
                                    <input type="text" class="form-control dobirthdatepicker" id="startdate" value="<?= $startdate1 ?>" data-date-format="dd-mm-yyyy" name="startdate"  required placeholder="Start Date*">
                                </div>
    	                    </div>
    	                    <div class="col-sm-2">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <?php  if(!empty($enddate1)) {
                                        $enddate1 = date("d-m-Y", $enddate1);
                                    }
                                    else
                                    {
                                        $enddate1 = '';
                                    }?>
                                    <input type="text" class="form-control dobirthdatepicker" id="enddate" value="<?= $enddate1 ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="End Date *">
                                </div>
    	                    </div>
    	                    <div class="col-sm-1">
    	                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
    	                    </div>
    	                    </div>
	                    <?php echo $this->Form->end();?>
    	                
    	                <div class="row  clearfix">
                            <div class="col-sm-12">
                                <div class="table-responsive"><br><br>
                                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem foodsts_table" id="foodsts_table" data-page-length='50'>
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>
                                                    <label class="fancy-checkbox">
                                                        <input class="select-all" type="checkbox" name="checkbox">
                                                        <span></span>
                                                    </label>
                                                </th>
                                                <th>Order No.</th> 
                                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '637') { echo $langlbl['title'] ; } } ?></th> 
                                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2213') { echo $langlbl['title'] ; } } ?></th> 
                                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '147') { echo $langlbl['title'] ; } } ?></th> 
                                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2208') { echo $langlbl['title'] ; } } ?></th>   
                                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2223') { echo $langlbl['title'] ; } } ?></th>
                                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2226') { echo $langlbl['title'] ; } } ?></th>
                                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2224') { echo $langlbl['title'] ; } } ?></th>                             
                                                <th>Date/<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2222') { echo $langlbl['title'] ; } } ?></th>
                                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '110') { echo $langlbl['title'] ; } } ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="foodsts_body" class="modalrecdel"> 
                                            <?php if(!empty($csodetails)) {
                                                
                                            foreach($csodetails as $value)
                                            { ?>
                                                <tr>
                                                    <td class="width45">
                                                        <label class="fancy-checkbox">
                                                            <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value->id ?>">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td><?= $value->order_no ?></td>
                                                    <td><?= ucfirst($value['company']['comp_name']) ?></td>
                                                    <td><?= ucfirst($value['canteen_vendor']['vendor_company']) ?></td>
                                                    <td><?= ucfirst($value['student']['l_name']." ". $value['student']['f_name']. "(". $value['student']['adm_no'] .")" ) ?></td>
                                                    <td><?= ucfirst($value['food_item']['food_name']) ?></td>
                                                    <td>$<?= $value->food_amount ?></td>
                                                    <td><?= $value->quantity ?></td>
                                                    <td>$<?= $value->total_amount ?></td>
                                                    <td><?= $value->date." / ".$value->timings ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn btn-outline-success foodmarked" title="Status" data-url="foodstatus/ostatus" data-id="<?=$value['id']?>" data-status="1" data-str="Food Mark Delivered">Mark Delivered</a>
                                                        <a href="javascript:void(0);" class="btn btn-outline-danger ufoodmarked" title="Status" data-url="foodstatus/ostatus" data-id="<?=$value['id']?>" data-status="3" data-str="Food Mark Undelivered" >Mark Undelivered</a>
                                                    </td>
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
    $('.foodmarked').click(function() {
        //alert("Cds");
        var str =  $(this).data("str") ;
        var id =  $(this).data("id") ;
        var sts =  $(this).data("status") ;
        var url = baseurl+"/"+$(this).data("url") ;
        var refscrf = $("input[name='_csrfToken']").val();
      
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
                    sts : sts ,
                   _csrfToken : refscrf,
                   id : id
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
       
    });
    
    $('.ufoodmarked').click(function() {
        //alert("Cds");
        var str =  $(this).data("str") ;
        var id =  $(this).data("id") ;
        var sts =  $(this).data("status") ;
        var url = baseurl+"/"+$(this).data("url") ;
        var refscrf = $("input[name='_csrfToken']").val();
      
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
                    sts : sts ,
                   _csrfToken : refscrf,
                   id : id
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
       
    });
    
    $('#deliverfoodall').click(function() {
        var str =  $(this).data("str") ;
        var sts =  $(this).data("status") ;
        var url = baseurl+"/"+$(this).data("url") ;
        var post_arr = [];
        // Get checked checkboxes
        $('#foodsts_table input[type=checkbox]').each(function() {
            if (jQuery(this).is(":checked")) {
                var id = this.id;
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
                data : { val : post_arr, _csrfToken : refscrf, sts:sts },
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
    
    $('#undeliverfoodall').click(function() {
        var str =  $(this).data("str") ;
        var sts =  $(this).data("status") ;
        var url = baseurl+"/"+$(this).data("url") ;
        var post_arr = [];
        // Get checked checkboxes
        $('#foodsts_table input[type=checkbox]').each(function() {
            if (jQuery(this).is(":checked")) {
                var id = this.id;
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
                data : { val : post_arr, _csrfToken : refscrf, sts:sts },
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


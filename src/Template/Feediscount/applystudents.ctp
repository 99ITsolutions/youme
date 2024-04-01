<?php
foreach($lang_label as $langlbl) {
    if($langlbl['id'] == '2168') { $lbl2168 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '2169') { $lbl2169 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '2170') { $lbl2170 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '136') { $lbl136 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '1032') { $lbl1032 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '532') { $lbl532 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '341') { $lbl341 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '241') { $lbl241 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '240') { $lbl240 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '534') { $lbl534 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '261') { $lbl261 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '2124') { $lbl2124 = $langlbl['title'] ; }
    if($langlbl['id'] == '2171') { $lbl2171 = $langlbl['title'] ; }
    if($langlbl['id'] == '2172') { $lbl2172 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2173') { $lbl2173 = $langlbl['title'] ; }
}
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?= $lbl2168 ?></h2>
                <ul class="header-dropdown">
                    <?php /*if(!empty($sclsub_details[0]))
                    { 
                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                        if(in_array("34", $roles)) { ?>
                            <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addfeehead"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '327') { echo $langlbl['title'] ; } } ?> </a></li>
                        <?php }
                    } else {*/ ?>
                        <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addfeehead"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '327') { echo $langlbl['title'] ; } } ?> </a></li>
                    <?php //} ?>
                    
                    <li><a href="<?=$baseurl?>feediscount"  class="btn btn-info" title="Back"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '328') { echo $langlbl['title'] ; } } ?> </a></li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
    					<label><?= $lbl241 ?>*</label>
    					<div class="form-group">                                    
    						<select class="form-control js-states session" id="session" required placeholder="Select Session">
    						    <option value=""><?= $lbl341 ?></option>
    						    <?php foreach($session_details as $val) { ?>
    					            <option value="<?= $val['id'] ?>"><?= $val['startyear']."-". $val['endyear'] ?></option>
    					        <?php } ?>
    				        </select>
    					</div>
    				</div>
                    <div class="col-md-3">
    				    <label><?= $lbl136 ?>*</label>
    					<div class="form-group">                                    
    						<select class="form-control js-states class_s" id="selcls" placeholder="Select Class" onchange="getsesscoupndata(this.value)">
    						    <option value=""><?= $lbl1032 ?></option>
    					        <?php foreach($class_details as $cls) { ?>
    					            <option value="<?= $cls['id'] ?>"><?= $cls['c_name']."-". $cls['c_section']." (".$cls['school_sections'].")"?></option>
    					        <?php } ?>
    				        </select>
    					</div>
    				</div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem feedis_table" id="feedis_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th><?= $lbl241 ?></th>
                                <th><?= $lbl534 ?></th>
                                <th><?= $lbl261 ?></th>
                                <th><?= $lbl136 ?></th>
                                <th><?= $lbl2124 ?></th>
                                <th><?= $lbl2171 ?> </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '333') { echo $langlbl['title'] ; } } ?> </th>
                            </tr>
                        </thead>
                        <tbody id="feedisbody" class="modalrecdel">
                            <?php foreach($dis_details as $dis) {
                            if($dis['feediscount']['percentage_amount'] == "amount") {
                            $couponamt = "$".$dis['feediscount']['amount']; 
                            } else { 
                            $couponamt = $dis['feediscount']['amount']."%";
                            }?>
                            <tr>
                                <td><?= $dis['session']['startyear']."-".$dis['session']['endyear'] ?></td>
                                <td><?= $dis['student']['l_name']." ".$dis['student']['f_name'] ?></td>
                                <td><?= $dis['student']['adm_no'] ?></td>
                                <td><?= $dis['class']['c_name']."-".$dis['class']['c_section']." (".$dis['class']['school_sections'].")" ?></td>
                                <td><?= $dis['feediscount']['discount_name'] ?></td>
                                <td><?= $couponamt ?></td>
                                <td>
                                    <!--<button type="button" data-id=" <?= $dis['id'] ?>" class="btn btn-sm btn-outline-secondary editstudentcoupn" data-toggle="modal"  data-target="#editstudentcoupn" title="Edit"><i class="fa fa-edit"></i></button>-->
                                    <?php if($dis['coupontaken'] == 0) { ?>
                                        <button type="button" data-id="<?=$dis['id']?>" data-url="coupondelete" class="btn btn-sm btn-outline-danger js-sweetalert " title="<?= $lbl2172 ?>" data-str="Student Coupon " data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!------------------ Add Fee Head  --------------------->

<div class="modal animated zoomIn" id="addfeehead"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl2169 ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addstuddiscount'] , 'id' => "addstuddiscountform" , 'method' => "post"  ]); ?>

			<div class="row clearfix">
				
				<div class="col-md-12">
				    <label><?= $lbl136 ?>*</label>
					<div class="form-group">                                    
						<select class="form-control js-states class_s" required name="class" placeholder="Discount Option" onchange="seldisstudent(this.value)">
						    <option value=""><?= $lbl1032 ?></option>
					        <?php foreach($class_details as $cls) { ?>
					            <option value="<?= $cls['id'] ?>"><?= $cls['c_name']."-". $cls['c_section']." (".$cls['school_sections'].")"?></option>
					        <?php } ?>
				        </select>
					</div>
				</div>
				
				<div class="col-md-12">
					<label><?= $lbl240 ?>*</label>
					<div class="form-group">                                    
						<select class="form-control js-states liststudent" id="liststudent" required name="student[]" placeholder="Select Student" multiple>
						    <option value=""><?= $lbl532 ?></option>
				        </select>
					</div>
				</div>
				
				<div class="col-md-12">
				    <label><?= $lbl2173 ?>*</label>
					<div class="form-group">                                     
						<select class="form-control js-states request_opt" required name="discount_coupon" placeholder="Discount Coupon">
						    <option value="">Choose Option</option>
					        <?php foreach($fee_dis as $val) { ?>
					            <option value="<?= $val['id'] ?>"><?= $val['discount_name']." (".$val['percentage_amount']."-". $val['amount'].")"?></option>
					        <?php } ?>
				        </select>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="error" id="feeerror"></div>
					<div class="success" id="feesuccess"></div>
				</div>
				<div class="button_row" >
					<hr>
					<button type="submit" class="btn btn-primary addstudisbtn" id="addstudisbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '396') { echo $langlbl['title'] ; } } ?></button>
					<button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
				</div>
						
					   <?php echo $this->Form->end(); ?>
					   
					</div>
			</div>
             
        </div>
    </div>
</div>

<!------------------ Edit Fee Head --------------------->

    
<div class="modal animated zoomIn" id="editstudentcoupn" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl2170 ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editdiscstudent'] , 'id' => "editdiscstudentform" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
				<input type="hidden" name="id" id="coupnid">
				<div class="col-md-12">
				    <label><?= $lbl136 ?>*</label>
					<div class="form-group">                                    
						<select class="form-control js-states class_s" id="eclass" required name="class" placeholder="Select Class" onchange="seldisstudent(this.value)">
						    <option value=""><?= $lbl1032 ?></option>
				        </select>
					</div>
				</div>
				
				<div class="col-md-12">
					<label><?= $lbl240 ?>*</label>
					<div class="form-group">                                    
						<select class="form-control js-states studentchose" id="eliststudent" required name="student" placeholder="Select Student">
						    <option value=""><?= $lbl532 ?></option>
				        </select>
					</div>
				</div>
				
				<div class="col-md-12">
				    <label><?= $lbl2173 ?>*</label>
					<div class="form-group">                                     
						<select class="form-control js-states request_opt" id="ecoupon" required name="discount_coupon" placeholder="Discount Coupon">
						    <option value="">Choose Option</option>
				        </select>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="error" id="efeeerror"></div>
					<div class="success" id="efeesuccess"></div>
				</div>
				<div class="button_row" >
					<hr>
					<button type="submit" class="btn btn-primary editstudisbtn" id="editstudisbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
					<button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
				</div>
						
			    <?php echo $this->Form->end(); ?>
				</div>
            </div>
             
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function seldisstudent(val)
    {
        $("#liststudent").html("");
        $("#eliststudent").html("");
        var refscrf = $("input[name='_csrfToken']").val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax
        ({
            data : {_csrfToken:refscrf, 'cls':val },
            type : "post",
    		url: baseurl + '/Feediscount/getstudnt',
            success: function(response){
                console.log(response);
                $("#liststudent").html(response);
                $("#eliststudent").html(response);
            }
        })  
    }
    
    
</script>
<?php
    $time = '00:00';	
    $statusarray = array('Inactive','Active' );
    $smsarray = array('Yes','No' );
    $emailarray = array('Yes','No' );
    $school_no = $school_details['id'];
    $school_no++;
    
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
        if($langlbl['id'] == '669') { $lbl669 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2216') { $lbl2216 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2217') { $lbl2217 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2218') { $lbl2218 = $langlbl['title'] ; }
    } 
?>
<style>
.fieldpaddings {
    padding: 0px 8px !important;
}
h5
{
	color: #007bff !important;
}
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Assign Vendor to School & Food Items</h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>Canteenvendors/assignfood" title="Back" class="btn btn-sm btn-success"><?= $lbl41 ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'addfoodassigned'] , 'id' => "addassignfoodform" , 'class' => "addassignfoodform", 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <div class="col-sm-12 mb-2">
                                <h5>Assign Schools to Vendor</h5>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $lbl2216 ?>*</label>
                                <div class="form-group">                                                
                                    <select class="form-control request_opt" id="vendor" name="vendor" required>
                                        <option selected="selected">Choose vendor</option>
                                        <?php
                                        foreach($cv_details as $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" ><?php echo $val['vendor_company']." (".$val['l_name']." ".$val['f_name'].")";?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>       
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <label><?= $lbl2217 ?>*</label>
                                <div class="form-group">                                                
                                    <select class="form-control" id="schoollist" name="school[]" multiple required>
                                        <option value="">Choose School</option>
                                        <?php
                                        $all = [];
                                        foreach($scl_details as $vals)
                                        {
                                            $all[] = $vals['id'];
                                        }
                                        $allids = implode(",", $all);
                                        ?>
                                        <option value="<?= $allids ?>">All</option>
                                        <?php
                                        foreach($scl_details as $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" ><?php echo $val['comp_name'] ;?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>       
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <h5>Assign food to vendor</h5>
                            </div>
                            
                            <div class="col-sm-8">
                                <label>Select Food & enter food price (In Dollars)*</label>
                                <div class="wrapper">
                                    <div class="input-box row container mb-2">
                                        <select class="col-sm-4 form-control" name="foodlist[]" style="margin-right:15px !important;">
                                            <option value=""><?= $lbl2218 ?></option>
                                            <?php foreach($food_details as $key => $val) { ?>
                                                <option  value="<?=$val['id']?>" ><?php echo $val['food_name'];?> </option>
                                            <?php } ?>
                                        </select>
                                        <input type="text" class="col-sm-4 form-control" id="foodprice1" name="foodprice[]" placeholder="Enter Selected Food Price">
                                        
                                        <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="error" id="assignerror">
                                </div>
                                <div class="success" id="assignsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="addassignbtn" class="btn btn-primary addassignbtn"><?= $lbl669 ?></button>
                                    </div>
                                </div>
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
    var max_input1 = 50;
    var y = 1;
    $('.add-btn').click(function (e) {
        e.preventDefault();
        if (y < max_input1) {
            y++;
            $('.wrapper').append(`
            
            <div class="input-box row container mb-2">
                <select class="col-sm-4 form-control select_food"  name="foodlist[]" style="margin-right:15px !important;">
                    <option value=""><?php echo $lbl2218 ?></option>
                    <?php foreach($food_details as $key => $val) { ?>
                        <option  value="<?php echo $val['id']?>" ><?php echo $val['food_name'];?> </option>
                    <?php } ?>
                </select>
                <input type="text" class="col-sm-4 form-control" id="foodprice1" name="foodprice[]"  placeholder="Enter Selected Food Price">
                
                <a href="#" class="col-sm-2 remove-lnk"><i class="fa fa-minus"></i></a>
            </div>
            
            `); // add input field
        }
    });
    
    // handle click event of the remove link
    $('.wrapper').on("click", ".remove-lnk", function (e) {
    e.preventDefault();
    
    $(this).parent('div.input-box').remove();  // remove input field
    x--; // decrement the counter
    });
});

</script>
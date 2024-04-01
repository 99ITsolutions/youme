<?php
    $time = '00:00';	
    $statusarray = array('Inactive','Active' );
    $smsarray = array('Yes','No' );
    $emailarray = array('Yes','No' );
    $school_no = $school_details['id'];
    $school_no++;
    
    foreach($lang_label as $langlbl) { 
        
        if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
        if($langlbl['id'] == '641') { $lbl641 = $langlbl['title'] ; } 
        if($langlbl['id'] == '642') { $lbl642 = $langlbl['title'] ; } 
        if($langlbl['id'] == '643') { $lbl643 = $langlbl['title'] ; } 
        if($langlbl['id'] == '644') { $lbl644 = $langlbl['title'] ; } 
        if($langlbl['id'] == '645') { $lbl645 = $langlbl['title'] ; } 
        if($langlbl['id'] == '646') { $lbl646 = $langlbl['title'] ; } 
        if($langlbl['id'] == '648') { $lbl648 = $langlbl['title'] ; } 
        if($langlbl['id'] == '650') { $lbl650 = $langlbl['title'] ; } 
        if($langlbl['id'] == '652') { $lbl652 = $langlbl['title'] ; } 
        if($langlbl['id'] == '653') { $lbl653 = $langlbl['title'] ; } 
        if($langlbl['id'] == '654') { $lbl654 = $langlbl['title'] ; } 
        if($langlbl['id'] == '655') { $lbl655 = $langlbl['title'] ; } 
        if($langlbl['id'] == '656') { $lbl656 = $langlbl['title'] ; } 
        if($langlbl['id'] == '657') { $lbl657 = $langlbl['title'] ; } 
        if($langlbl['id'] == '660') { $lbl660 = $langlbl['title'] ; } 
        if($langlbl['id'] == '661') { $lbl661 = $langlbl['title'] ; } 
        if($langlbl['id'] == '662') { $lbl662 = $langlbl['title'] ; } 
        if($langlbl['id'] == '663') { $lbl663 = $langlbl['title'] ; } 
        if($langlbl['id'] == '664') { $lbl664 = $langlbl['title'] ; } 
        if($langlbl['id'] == '665') { $lbl665 = $langlbl['title'] ; } 
        if($langlbl['id'] == '669') { $lbl669 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1408') { $lbl1408 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2200') { $lbl2200 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1425') { $lbl1425 = $langlbl['title'] ; } 
        
        if($langlbl['id'] == '1426') { $lbl1426 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1427') { $lbl1427 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1428') { $lbl1428 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1429') { $lbl1429 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1430') { $lbl1430 = $langlbl['title'] ; } 
        
        if($langlbl['id'] == '2073') { $lbl2073 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2074') { $lbl2074 = $langlbl['title'] ; } 
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
                <h2 class="heading"><?= $lbl2200 ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>Canteenvendors" title="Back" class="btn btn-sm btn-success"><?= $lbl41 ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'addvendor'] , 'id' => "addvendorform" , 'class' => "addvendorform", 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <div class="col-sm-12 mb-2">
                                <h5><?= $lbl641 ?></h5>
                            </div>
                            <div class="col-sm-3">
                                <label>Company Logo*</label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="logo"  >
                                    <small id="fileHelp" class="form-text text-muted">Company Logo</small>
                                </div>
                            </div>
                            <div class="col-sm-9"></div>
                            <div class="col-sm-4">
                                <label>First Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name"  required placeholder="Vendor First Name *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Last Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name"  required placeholder="Vendor Last Name *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Registered Company Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="comp_name"  required placeholder="Registered Company Name *">
                                </div>
                            </div>
                            
                             <div class="col-sm-4">
                                <label><?= $lbl643 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone"  required placeholder="<?= $lbl643 ?>*">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl644 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="add_1"  placeholder="<?= $lbl645 ?> ">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl646 ?>*</label>
                                <div class="form-group">
                                <select class="form-control country countries" id="country" name="country" required>
                                    <option selected="selected">Choose Country</option>
                                    <?php
                                    foreach($country_details as $val){
                                    ?>
                                      <option  value="<?=$val['id']?>" ><?php echo $val['name'];?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl648 ?></label>
                                <div class="form-group">
                                <select class="form-control states state" id="state" name="state" >
                                    <option selected="selected">Choose State</option>
                                    <?php
                                    foreach($state_details as $val){
                                    ?>
                                      <option  value="<?=$val['id']?>" ><?php echo $val['name'];?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl650 ?></label>
                                <div class="form-group" id="getcity">
                                    <input type="text" class="form-control city" id="city" name="city" placeholder="Enter City" >
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl652 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="zipcode"   placeholder="<?= $lbl652 ?>">
                                </div>
                            </div>
                           
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5><?= $lbl653 ?></h5>
                            </div>
                            <div class="col-sm-6">
                                <label><?= $lbl654 ?>*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  required placeholder="<?= $lbl655 ?> *">
                                </div>
                            </div>
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <label><?= $lbl663 ?>*</label>
                                <div class="form-group">
                                    <p>
                                       <?= $lbl1425 ?>: <input type="button" class="" name="nav_bar" id="nav_bar"  required style="width:15%; margin-bottom:5px;">
                                    </p>
                                    <input type="hidden" class="" name="navbar" id="navbar"  required >
                        				<input type="button" class="color_change_nav1" value="#d32191" style="color:#d32191; background:#d32191; max-width:12%;">
                        				<input type="button" class="color_change_nav2" value="#6b73d5" style="color:#6b73d5; background:#6b73d5; max-width:12%;">
                        				<input type="button" class="color_change_nav3" value="#fe9882" style="color:#fe9882; background:#fe9882; max-width:12%;">
                        				<input type="button" class="color_change_nav4" value="#9de498" style="color:#9de498; background:#9de498; max-width:12%;">
                        				<input type="button" class="color_change_nav5" value="#fae503" style="color:#fae503; background:#fae503; max-width:12%;">
                        				<input type="button" class="color_change_nav6" value="#438ccc" style="color:#438ccc; background:#438ccc; max-width:12%;">
                        				<input type="button" class="color_change_nav7" value="#00a65a" style="color:#00a65a; background:#00a65a; max-width:12%;">
                        				<input type="button" class="color_change_nav8" value="#01319d" style="color:#01319d; background:#01319d; max-width:12%;">
                        				<input type="button" class="color_change_nav9" value="#d1c8f5" style="color:#d1c8f5; background:#d1c8f5; max-width:12%;">
                        				
                        				<input type="button" class="color_change_nav10" value="#778899" style="color:#778899; background:#778899; max-width:12%;">
                        				<input type="button" class="color_change_nav11" value="#666699" style="color:#666699; background:#666699; max-width:12%;">
                        				<input type="button" class="color_change_nav12" value="#560319" style="color:#560319; background:#560319; max-width:12%;">
                        				<input type="button" class="color_change_nav13" value="#000036" style="color:#000036; background:#000036; max-width:12%;">
                        				<input type="button" class="color_change_nav14" value="#800080" style="color:#800080; background:#800080; max-width:12%;">
                                </div>
                            </div>
                            
                            
                            <div class="col-sm-6">
                                <label><?= $lbl664 ?>*</label>
                                <div class="form-group">
                                    <p>
                                       <?= $lbl1425 ?>: <input type="button" class="" name="button_color" id="button_color" value=""  required style="width:15%; margin-bottom:5px;">
                                    </p>
                                    <input type="hidden" class="" name="buttoncolor" id="buttoncolor"  required >
                                       
                        				<input type="button" class="color_change_btn1" value="#d32191" style="color:#d32191; background:#d32191; max-width:12%;">
                        				<input type="button" class="color_change_btn2" value="#6b73d5" style="color:#6b73d5; background:#6b73d5; max-width:12%;">
                        				<input type="button" class="color_change_btn3" value="#fe9882" style="color:#fe9882; background:#fe9882; max-width:12%;">
                        				<input type="button" class="color_change_btn4" value="#9de498" style="color:#9de498; background:#9de498; max-width:12%;">
                        				<input type="button" class="color_change_btn5" value="#fae503" style="color:#fae503; background:#fae503; max-width:12%;">
                        				<input type="button" class="color_change_btn6" value="#438ccc" style="color:#438ccc; background:#438ccc; max-width:12%;">
                        				<input type="button" class="color_change_btn7" value="#00a65a" style="color:#00a65a; background:#00a65a; max-width:12%;">
                        				<input type="button" class="color_change_btn8" value="#01319d" style="color:#01319d; background:#01319d; max-width:12%;">
                        				<input type="button" class="color_change_btn9" value="#d1c8f5" style="color:#d1c8f5; background:#d1c8f5; max-width:12%;">
                        				
                        				<input type="button" class="color_change_btn10" value="#778899" style="color:#778899; background:#778899; max-width:12%;">
                        				<input type="button" class="color_change_btn11" value="#666699" style="color:#666699; background:#666699; max-width:12%;">
                        				<input type="button" class="color_change_btn12" value="#560319" style="color:#560319; background:#560319; max-width:12%;">
                        				<input type="button" class="color_change_btn13" value="#000036" style="color:#000036; background:#000036; max-width:12%;">
                        				<input type="button" class="color_change_btn14" value="#800080" style="color:#800080; background:#800080; max-width:12%;">
                        				
                                    
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5>Timings</h5>
                            </div>
                            <div class="row container">
                                <div class="col-sm-4 fieldpaddings">
                                    <label>Open At*</label>
                                    <div class="form-group">
                                        <input class="form-control timepicker" id="ktimepicker" type="text" name="open_time" placeholder="Open At*" required/>
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label>Closed At*</label>
                                    <div class="form-group">
                                        <input class="form-control timepicker2" id="ktimepicker2"  type="text" name="close_time" placeholder="Closed At*" required/>
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label>Order Booking closed before*</label>
                                    <div class="form-group">
                                        <select class="form-control status" id="booking_closed" name="booking_closed" required>
                                            <option value="">Choose Timings</option>
                                            <option value="30 Minutes">30 Minutes</option>
                                            <option value="60 Minutes">60 Minutes</option>
                                            <option value="90 Minutes">90 Minutes</option>
                                            <option value="120 Minutes">120 Minutes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label><?= $lbl665 ?>*</label>
                                <div class="form-group">
                                <select class="form-control status" id="status" name="status" required>
                                    <option selected="selected">Choose Status</option>
                                    <?php
                                    foreach($statusarray as $key => $val){
                                    ?>
                                      <option  value="<?=$key?>" ><?php echo $val;?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="error" id="vendrerror">
                                </div>
                                <div class="success" id="vendrsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="addvendrbtn" class="btn btn-primary addvendrbtn"><?= $lbl669 ?></button>
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

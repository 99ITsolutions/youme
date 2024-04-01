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
        if($langlbl['id'] == '1424') { $lbl1424 = $langlbl['title'] ; } 
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
                <h2 class="heading"><?= $lbl1424 ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>schools" title="Back" class="btn btn-sm btn-success"><?= $lbl41 ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'addscl'] , 'id' => "addsclform" , 'class' => "addsclform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <div class="col-sm-12 mb-2">
                                <h5><?= $lbl641 ?></h5>
                            </div>
                            <input type="hidden" class="form-control" readonly required value="<?=$school_no?>">
                           
                            <div class="col-sm-4">
                                <label><?= $lbl642 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="comp_name"  required placeholder="<?= $lbl642 ?> *">
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
                            <div class="col-sm-3">
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
                            <div class="col-sm-3">
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
                            <div class="col-sm-3">
                                <label><?= $lbl650 ?></label>
                                <div class="form-group" id="getcity">
                                    <input type="text" class="form-control city" id="city" name="city" placeholder="Enter City" >
                                </div>
                            </div>
                            <div class="col-sm-3">
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
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5><?= $lbl656 ?></h5>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $lbl657 ?>*</label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="logo"  >
                                    <small id="fileHelp" class="form-text text-muted"><?= $lbl660 ?></small>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $lbl661 ?>  *</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="working_days"  required placeholder="<?= $lbl662 ?> *">
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
                            <div class="col-sm-12">
                                <label><?= $lbl1408 ?> *</label>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-2"><input type="checkbox" name="scl_privilages[]" value="KinderGarten" onclick="chckpriv(this.value)" > KinderGarten</div>
                                        <div class="col-sm-2"><input type="checkbox" name="scl_privilages[]" value="Primary" onclick="chckpriv(this.value)" > Primary</div>
                                        <div class="col-sm-2"><input type="checkbox" name="scl_privilages[]" value="Senior" onclick="chckpriv(this.value)" > Secondary</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="kinderpriv" class="col-sm-12 row container " style="display:none;">
                            <h6>Kindergarten School Timings</h6>
                            <div class="row container">
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1426 ?></label>
                                <div class="form-group">
                                    <input class="form-control timepicker" id="ktimepicker" type="text" name="kevent_start_time" placeholder="<?= $lbl1426 ?>" />
                                </div>
                            </div>
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1427 ?> </label>
                                <div class="form-group">
                                    <input class="form-control timepicker2" id="ktimepicker2"  type="text" name="kevent_end_time" placeholder="<?= $lbl1427 ?>"  />
                                </div>
                            </div>
                            <!--<div class="col-sm-2 fieldpaddings">
                                <label><?= $lbl1428 ?></label>
                                <div class="form-group">
                                    <select class="form-control slott" name="ktimeslot" id="ktimeslot" placeholder="Choose Slot" >
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes">5 Minutes</option>
                                        <option value="10 minutes">10 Minutes</option>
                                        <option value="15 minutes">15 Minutes</option>
                                        <option value="20 minutes">20 Minutes</option>
                                        <option value="25 minutes">25 Minutes</option>
                                        <option value="30 minutes">30 Minutes</option>
                                        <option value="35 minutes">35 Minutes</option>
                                        <option value="40 minutes">40 Minutes</option>
                                        <option value="45 minutes">45 Minutes</option>
                                        <option value="50 minutes">50 Minutes</option>
                                        <option value="55 minutes">55 Minutes</option>
                                        <option value="60 minutes">60 Minutes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 fieldpaddings"><div class="wrapper1"><div class="input-box row container mb-2">
                                <div class="col-sm-3 fieldpaddings">
                                    <label><?= $lbl1429 ?> </label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="kbreakname[]" id="kbreakname" placeholder="<?php echo $lbl1429 ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2073 ?> </label>
                                    <div class="form-group">
                                        <input class="form-control timepicker3" id="ktimepicker3" type="text" name="kbreak_start_time[]" placeholder="<?= $lbl2073 ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2074 ?> </label>
                                    <select class="form-control slott" name="kbreak_end_time[]" id="ktimepicker4" placeholder="Choose Slot" >
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes">5 Minutes</option>
                                        <option value="10 minutes">10 Minutes</option>
                                        <option value="15 minutes">15 Minutes</option>
                                        <option value="20 minutes">20 Minutes</option>
                                        <option value="25 minutes">25 Minutes</option>
                                        <option value="30 minutes">30 Minutes</option>
                                        <option value="35 minutes">35 Minutes</option>
                                        <option value="40 minutes">40 Minutes</option>
                                        <option value="45 minutes">45 Minutes</option>
                                        <option value="50 minutes">50 Minutes</option>
                                        <option value="55 minutes">55 Minutes</option>
                                        <option value="60 minutes">60 Minutes</option>
                                    </select>
                                </div>
                                <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>
                             </div></div></div>-->
                            </div>
                            </div>
                            
                            
                            <div id="primarypriv" class="col-sm-12 row container" style="display:none;">
                            <h6>Primary School Timings</h6>
                            <div class="row container">
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1426 ?></label>
                                <div class="form-group">
                                    <input class="form-control timepicker" id="ptimepicker" type="text" name="pevent_start_time" placeholder="<?= $lbl1426 ?>"  />
                                </div>
                            </div>
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1427 ?> </label>
                                <div class="form-group">
                                    <input class="form-control timepicker2" id="ptimepicker2"  type="text" name="pevent_end_time" placeholder="<?= $lbl1427 ?>"  />
                                </div>
                            </div>
                            
                            <!--<div class="col-sm-2 fieldpaddings">
                                <label><?= $lbl1428 ?></label>
                                <div class="form-group">
                                    <select class="form-control slott" name="ptimeslot" id="ptimeslot" placeholder="Choose Slot" onchange="slotbreak(this.value)">
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes">5 Minutes</option>
                                        <option value="10 minutes">10 Minutes</option>
                                        <option value="15 minutes">15 Minutes</option>
                                        <option value="20 minutes">20 Minutes</option>
                                        <option value="25 minutes">25 Minutes</option>
                                        <option value="30 minutes">30 Minutes</option>
                                        <option value="35 minutes">35 Minutes</option>
                                        <option value="40 minutes">40 Minutes</option>
                                        <option value="45 minutes">45 Minutes</option>
                                        <option value="50 minutes">50 Minutes</option>
                                        <option value="55 minutes">55 Minutes</option>
                                        <option value="60 minutes">60 Minutes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 fieldpaddings"><div class="wrapper2"><div class="input-box row container mb-2">
                                <div class="col-sm-3 fieldpaddings">
                                    <label><?= $lbl1429 ?> </label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="pbreakname[]" id="pbreakname" placeholder="<?php echo $lbl1429 ?>">
                                    </div>
                                </div>
                               
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2073 ?> </label>
                                    <div class="form-group">
                                        <input class="form-control timepicker3" id="ptimepicker3" type="text" name="pbreak_start_time[]" placeholder="<?= $lbl2073 ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2074 ?> </label>
                                    <select class="form-control slott" name="pbreak_end_time[]" id="ptimepicker4" placeholder="Choose Slot" >
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes">5 Minutes</option>
                                        <option value="10 minutes">10 Minutes</option>
                                        <option value="15 minutes">15 Minutes</option>
                                        <option value="20 minutes">20 Minutes</option>
                                        <option value="25 minutes">25 Minutes</option>
                                        <option value="30 minutes">30 Minutes</option>
                                        <option value="35 minutes">35 Minutes</option>
                                        <option value="40 minutes">40 Minutes</option>
                                        <option value="45 minutes">45 Minutes</option>
                                        <option value="50 minutes">50 Minutes</option>
                                        <option value="55 minutes">55 Minutes</option>
                                        <option value="60 minutes">60 Minutes</option>
                                    </select>
                                </div>
                                <button class="col-sm-1 btn add-btn1"><i class="fa fa-plus"></i></button>
                             </div></div></div>-->
                            </div></div>
                            
                            <div id="secondarypriv" class="col-sm-12 row container" style="display:none;">
                            <h6>Secondary School Timings</h6>
                            <div class="row container">
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1426 ?></label>
                                <div class="form-group">
                                    <input class="form-control timepicker" id="stimepicker" type="text" name="sevent_start_time" placeholder="<?= $lbl1426 ?>" />
                                </div>
                            </div>
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1427 ?> </label>
                                <div class="form-group">
                                    <input class="form-control timepicker2" id="stimepicker2"  type="text" name="sevent_end_time" placeholder="<?= $lbl1427 ?>"  />
                                </div>
                            </div>
                            <!--<div class="col-sm-2 fieldpaddings">
                                <label><?= $lbl1428 ?></label>
                                <div class="form-group">
                                    <select class="form-control slott" name="stimeslot" id="stimeslot" placeholder="Choose Slot" onchange="slotbreak(this.value)">
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes">5 Minutes</option>
                                        <option value="10 minutes">10 Minutes</option>
                                        <option value="15 minutes">15 Minutes</option>
                                        <option value="20 minutes">20 Minutes</option>
                                        <option value="25 minutes">25 Minutes</option>
                                        <option value="30 minutes">30 Minutes</option>
                                        <option value="35 minutes">35 Minutes</option>
                                        <option value="40 minutes">40 Minutes</option>
                                        <option value="45 minutes">45 Minutes</option>
                                        <option value="50 minutes">50 Minutes</option>
                                        <option value="55 minutes">55 Minutes</option>
                                        <option value="60 minutes">60 Minutes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 fieldpaddings"><div class="wrapper3"><div class="input-box row container mb-2">
                                <div class="col-sm-3 fieldpaddings">
                                    <label><?= $lbl1429 ?> </label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="sbreakname[]" id="sbreakname" placeholder="<?php echo $lbl1429 ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2073 ?> </label>
                                    <div class="form-group">
                                        <input class="form-control timepicker3" id="stimepicker3" type="text" name="sbreak_start_time[]" placeholder="<?= $lbl2073 ?>"  />
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2074 ?> </label>
                                    <select class="form-control slott" name="sbreak_end_time[]" id="stimepicker4" placeholder="Choose Slot" >
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes">5 Minutes</option>
                                        <option value="10 minutes">10 Minutes</option>
                                        <option value="15 minutes">15 Minutes</option>
                                        <option value="20 minutes">20 Minutes</option>
                                        <option value="25 minutes">25 Minutes</option>
                                        <option value="30 minutes">30 Minutes</option>
                                        <option value="35 minutes">35 Minutes</option>
                                        <option value="40 minutes">40 Minutes</option>
                                        <option value="45 minutes">45 Minutes</option>
                                        <option value="50 minutes">50 Minutes</option>
                                        <option value="55 minutes">55 Minutes</option>
                                        <option value="60 minutes">60 Minutes</option>
                                    </select>
                                </div>
                                <button class="col-sm-1 btn add-btn2"><i class="fa fa-plus"></i></button>
                             </div></div></div>-->
                            </div></div>
                            
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
                                <div class="error" id="sclerror">
                                </div>
                                <div class="success" id="sclsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="addsclbtn" class="btn btn-primary addsclbtn"><?= $lbl669 ?></button>
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
function chckpriv(val)
{
    $("#kinderpriv").css("display", "none");
    $("#primarypriv").css("display", "none");
    $("#secondarypriv").css("display", "none");
    $("#ktimepicker").removeAttr("required");
    $("#ktimepicker2").removeAttr("required");
    /*$("#ktimeslot").removeAttr("required");
    $("#kbreakname").removeAttr("required");
    $("#ktimepicker3").removeAttr("required");
    $("#ktimepicker4").removeAttr("required");*/
    
    $("#ptimepicker").removeAttr("required");
    $("#ptimepicker2").removeAttr("required");
    /*$("#ptimeslot").removeAttr("required");
    $("#pbreakname").removeAttr("required");
    $("#ptimepicker3").removeAttr("required");
    $("#ptimepicker4").removeAttr("required");*/
    
    $("#stimepicker").removeAttr("required");
    $("#stimepicker2").removeAttr("required");
    /*$("#stimeslot").removeAttr("required");
    $("#sbreakname").removeAttr("required");
    $("#stimepicker3").removeAttr("required");
    $("#stimepicker4").removeAttr("required");*/
    $("input[name='scl_privilages[]']:checked").each(function ()
    {
        var priv = $(this).val();
        if(priv == "KinderGarten")
        {
            $("#kinderpriv").css("display", "block");
            $("#ktimepicker").prop("required", true);
            $("#ktimepicker2").prop("required", true);
            /*$("#ktimeslot").prop("required", true);
            $("#kbreakname").prop("required", true);
            $("#ktimepicker3").prop("required", true);
            $("#ktimepicker4").prop("required", true);*/
        }
        if(priv == "Primary")
        {
            $("#primarypriv").css("display", "block");
            $("#ptimepicker").prop("required", true);
            $("#ptimepicker2").prop("required", true);
           /* $("#ptimeslot").prop("required", true);
            $("#pbreakname").prop("required", true);
            $("#ptimepicker3").prop("required", true);
            $("#ptimepicker4").prop("required", true);*/
        }
        if(priv == "Senior")
        {
            $("#secondarypriv").css("display", "block");
            $("#stimepicker").prop("required", true);
            $("#stimepicker2").prop("required", true);
            /*$("#stimeslot").prop("required", true);
            $("#sbreakname").prop("required", true);
            $("#stimepicker3").prop("required", true);
            $("#stimepicker4").prop("required", true);*/
        }
    });
}


$(document).ready(function () {
      var max_input1 = 3;
      var y = 1;
      var x = 1;
      var z = 1;
      $('.add-btn').click(function (e) {
        e.preventDefault();
        
        if (y < max_input1) {
            y++;
          $('.wrapper1').append(`
               <div class="input-box row container mb-2">
                                <div class="col-sm-3 fieldpaddings">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="kbreakname[]" id="kbreakname"  placeholder="<?php echo $lbl1429 ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <div class="form-group">
                                        <input class="form-control timepicker3" id="ktimepicker3" type="text" name="kbreak_start_time[]" placeholder="<?php echo $lbl2073 ?>"  />
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <select class="form-control slott" name="kbreak_end_time[]" id="ktimepicker4" placeholder="Choose Slot" >
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes">5 Minutes</option>
                                        <option value="10 minutes">10 Minutes</option>
                                        <option value="15 minutes">15 Minutes</option>
                                        <option value="20 minutes">20 Minutes</option>
                                        <option value="25 minutes">25 Minutes</option>
                                        <option value="30 minutes">30 Minutes</option>
                                        <option value="35 minutes">35 Minutes</option>
                                        <option value="40 minutes">40 Minutes</option>
                                        <option value="45 minutes">45 Minutes</option>
                                        <option value="50 minutes">50 Minutes</option>
                                        <option value="55 minutes">55 Minutes</option>
                                        <option value="60 minutes">60 Minutes</option>
                                    </select>
                                        
                                </div>
                                <a href="#" class="col-sm-1 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                            </div>
                        
                </div>
         `); // add input field
        }
      });
      
      $('.add-btn1').click(function (e) {
        e.preventDefault();
        
        if (x < max_input1) {
            x++;
          $('.wrapper2').append(`
               <div class="input-box row container mb-2">
                                <div class="col-sm-3 fieldpaddings">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="pbreakname[]" id="pbreakname" placeholder="<?php echo $lbl1429 ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <div class="form-group">
                                        <input class="form-control timepicker3" id="ptimepicker3" type="text" name="pbreak_start_time[]" placeholder="<?php echo $lbl2073 ?>"  />
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <select class="form-control slott" name="pbreak_end_time[]" id="ptimepicker4" placeholder="Choose Slot" >
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes">5 Minutes</option>
                                        <option value="10 minutes">10 Minutes</option>
                                        <option value="15 minutes">15 Minutes</option>
                                        <option value="20 minutes">20 Minutes</option>
                                        <option value="25 minutes">25 Minutes</option>
                                        <option value="30 minutes">30 Minutes</option>
                                        <option value="35 minutes">35 Minutes</option>
                                        <option value="40 minutes">40 Minutes</option>
                                        <option value="45 minutes">45 Minutes</option>
                                        <option value="50 minutes">50 Minutes</option>
                                        <option value="55 minutes">55 Minutes</option>
                                        <option value="60 minutes">60 Minutes</option>
                                    </select>
                                        
                                </div>
                                <a href="#" class="col-sm-1 remove-lnk1 form-control"><i class="fa fa-minus"></i></a>
                            </div>
                        
                </div>
         `); // add input field
        }
      });
      
      $('.add-btn2').click(function (e) {
        e.preventDefault();
        
        if (z < max_input1) {
            z++;
          $('.wrapper3').append(`
               <div class="input-box row container mb-2">
                                <div class="col-sm-3 fieldpaddings">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="sbreakname[]" id="sbreakname"  placeholder="<?php echo $lbl1429 ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <div class="form-group">
                                        <input class="form-control timepicker3" id="stimepicker3" type="text" name="sbreak_start_time[]" placeholder="<?php echo $lbl2073 ?>"  />
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <select class="form-control slott" name="sbreak_end_time[]" id="stimepicker4" placeholder="Choose Slot" >
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes">5 Minutes</option>
                                        <option value="10 minutes">10 Minutes</option>
                                        <option value="15 minutes">15 Minutes</option>
                                        <option value="20 minutes">20 Minutes</option>
                                        <option value="25 minutes">25 Minutes</option>
                                        <option value="30 minutes">30 Minutes</option>
                                        <option value="35 minutes">35 Minutes</option>
                                        <option value="40 minutes">40 Minutes</option>
                                        <option value="45 minutes">45 Minutes</option>
                                        <option value="50 minutes">50 Minutes</option>
                                        <option value="55 minutes">55 Minutes</option>
                                        <option value="60 minutes">60 Minutes</option>
                                    </select>
                                        
                                </div>
                                <a href="#" class="col-sm-1 remove-lnk2 form-control"><i class="fa fa-minus"></i></a>
                            </div>
                        
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper1').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        y--; // decrement the counter
      });
      $('.wrapper2').on("click", ".remove-lnk1", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      });
      $('.wrapper3').on("click", ".remove-lnk2", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        z--; // decrement the counter
      });
    });
</script>

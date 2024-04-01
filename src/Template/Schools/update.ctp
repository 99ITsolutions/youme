<?php 

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
    
    $id = $school_details[0]['id'];
    $principal_name = $school_details[0]['principal_name'];
    $comp_no = $school_details[0]['comp_no'];
    $user_name = $school_details[0]['user_name'];
    $comp_name = $school_details[0]['comp_name'];
    $country = $school_details[0]['country'];
    $state = $school_details[0]['state'];
    $city = $school_details[0]['city'];
    $zipcode = $school_details[0]['zipcode'];
    $add_1 = $school_details[0]['add_1'];
	$primary = $school_details[0]['primary_color'];
    $phone = $school_details[0]['ph_no'];
    $pan_no = $school_details[0]['pan_no'];
    $tin_no = $school_details[0]['tin_no'];
    $email = $school_details[0]['email'];
    $password = $school_details[0]['password'];
    $url = $school_details[0]['www'];
    $module = $school_details[0]['module'];
    $status = $school_details[0]['status'];
    $comp_logo = $school_details[0]['comp_logo'];
    $comp_logo1 = $school_details[0]['comp_logo1'];
    $site_name = $school_details[0]['site_name'];
    $site_title = $school_details[0]['site_title'];
    $logout_url = $school_details[0]['logout_url'];
    $favicon = $school_details[0]['favicon'];
    $expiry_date = $school_details[0]['expiry_date'];
    $work_key = $school_details[0]['work_key'];
    $sender = $school_details[0]['sender'];
    $send_sms = $school_details[0]['send_sms'];
    $sms_temp = $school_details[0]['sms_temp'];
    $send_time = $school_details[0]['send_time'];
    $mail_host = $school_details[0]['mail_host'];
    $email_host = $school_details[0]['email_host'];
    $mail_password = $school_details[0]['mail_password'];
    $port = $school_details[0]['port'];
    $send_email = $school_details[0]['send_email'];
    $primary = $school_details[0]['primary_color'];
    $button = $school_details[0]['button_color'];
    $presentdays = $school_details[0]['present_days'];
    
    $statusarray = array('Inactive','Active' );
    $smsarray = array('Yes','No' );
    $emailarray = array('Yes','No' );

?>

<style>

h5
{
    color: #007bff !important;
}


</style>
<?php
$styles = 'style="border:2px solid #000; border-radius:0.25rem !important; color:'.$primary.'; background:'.$primary.'; max-width:12%;" ';
$stylesbtn = 'style="border:2px solid #000; border-radius:0.25rem !important; color:'.$button.'; background:'.$button.'; max-width:12%;" ';
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1431') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>schools" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'updatescl'] , 'id' => "updatesclform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <div class="col-sm-12 mb-2">
                                <h5><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '641') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
                            <input type="hidden" class="form-control" name="" readonly  placeholder="School Number *" value="<?=$comp_no ?>">
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '642') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="comp_name" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '642') { echo $langlbl['title'] ; } } ?> *" required value="<?=$comp_name ?>"   >
                                </div>
                            </div>
                             <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '643') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone" value="<?=$phone?>"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '643') { echo $langlbl['title'] ; } } ?>*">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '644') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="add_1" value="<?=$add_1?>"   placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '645') { echo $langlbl['title'] ; } } ?> ">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '646') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group"> 
                                <select class="form-control countries country" id="country" name="country" required>
                                    <option selected="selected">Choose Country</option>
                                    <?php 
                                    foreach($country_details as $val){
                                        	$sel = $country == $val['id'] ? "selected" : "";
                                    ?>
                                      <option  value="<?=$val['id']?>"  <?php if($country == $val['id']){ ?>selected="true" <?php } ?>><?php echo $val['name'];?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '648') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group" >
                                <select class="form-control state states" id="state" name="state" >
                                    <option selected="selected">Choose State</option>
                                    <?php
                                    foreach($state_details as $val){
										$sel = $state == $val['id'] ? "selected" : "";
                                    ?>
                                        
                                        <option  value="<?=$val['id']?>" <?php if($state == $val['id']){ ?>selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '650') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group" >
                                    <input type="text" class="form-control city" id="city" name="city" placeholder="Enter City"  value="<?php echo $city?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '652') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="zipcode" value="<?=$zipcode?>"   placeholder="Zipcode ">
                                </div>
                            </div>
                           
                            
                        
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '653') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '654') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" value="<?=$email?>"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '655') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '631') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?=$password?>" name="password"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '631') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '656') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '657') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">   
									<img src="../../img/<?=$comp_logo?>" width="70px" height="45px" style="margin-bottom:15px;">
                                    <input type="file" class="form-control" name="logo"  >
                                    <input type="hidden" class="form-control" name="lpicture" value="<?=$comp_logo?>" >
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '660') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
							<div class="col-sm-8"></div>
                          
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '661') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="working_days"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '662') { echo $langlbl['title'] ; } } ?> *" value="<?= $presentdays?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '665') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                <select class="form-control status" id="status" name="status" required>
                                    <option selected="selected">Choose Status</option>
                                    <?php
                                    foreach($statusarray as $key => $val){
										$sel = $status == $key ? "selected" : "";
                                    ?>
                                    <option  value="<?=$key?>" <?php if($status == $key){ ?>selected="true" <?php } ?> ><?php echo $val;?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            
                            
                            <div class="col-sm-6">
                            </div>
                             <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '663') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <p>
                                       <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1425') { echo $langlbl['title'] ; } } ?>: <input type="button" class="" name="nav_bar" value="<?=$primary?>" id="nav_bar"  required  style="color:<?=$primary?>; background:<?=$primary?>; max-width:12%; margin-bottom:5px;"></p>
                                    <input type="hidden" class="form-control" name="navbar" value="<?=$primary?>" id="navbar"  required >
                                    
                                    
                                    <input type="button" class="color_change_nav1" value="#d32191" <?php if($primary == "#d32191") { echo $styles; }?> style="color:#d32191; background:#d32191; max-width:12%;">
                                    <input type="button" class="color_change_nav2" value="#6b73d5" <?php if($primary == "#6b73d5") { echo $styles; }?> style="color:#6b73d5; background:#6b73d5; max-width:12%;">
                                    <input type="button" class="color_change_nav3" value="#fe9882" <?php if($primary == "#fe9882") { echo $styles; }?> style="color:#fe9882; background:#fe9882; max-width:12%;">
                                    <input type="button" class="color_change_nav4" value="#9de498" <?php if($primary == "#9de498") { echo $styles; }?> style="color:#9de498; background:#9de498; max-width:12%;">
                                    <input type="button" class="color_change_nav5" value="#fae503" <?php if($primary == "#fae503") { echo $styles; }?> style="color:#fae503; background:#fae503; max-width:12%;">
                                    <input type="button" class="color_change_nav6" value="#438ccc" <?php if($primary == "#438ccc") { echo $styles; }?> style="color:#438ccc; background:#438ccc; max-width:12%;">
                                    <input type="button" class="color_change_nav7" value="#00a65a" <?php if($primary == "#00a65a") { echo $styles; }?> style="color:#00a65a; background:#00a65a; max-width:12%;">
                                    <input type="button" class="color_change_nav8" value="#01319d" <?php if($primary == "#01319d") { echo $styles; }?> style="color:#01319d; background:#01319d; max-width:12%;">
                                    <input type="button" class="color_change_nav9" value="#d1c8f5" <?php if($primary == "#d1c8f5") { echo $styles; }?> style="color:#d1c8f5; background:#d1c8f5; max-width:12%;">
                                    
                    				<input type="button" class="color_change_nav10" value="#778899" <?php if($primary == "#778899") { echo $styles; }?> style="color:#778899; background:#778899; max-width:12%;">
                    				<input type="button" class="color_change_nav11" value="#666699" <?php if($primary == "#666699") { echo $styles; }?> style="color:#666699; background:#666699; max-width:12%;">
                    				<input type="button" class="color_change_nav12" value="#560319" <?php if($primary == "#560319") { echo $styles; }?> style="color:#560319; background:#560319; max-width:12%;">
                    				<input type="button" class="color_change_nav13" value="#000036" <?php if($primary == "#000036") { echo $styles; }?> style="color:#000036; background:#000036; max-width:12%;">
                    				<input type="button" class="color_change_nav14" value="#800080" <?php if($primary == "#800080") { echo $styles; }?> style="color:#800080; background:#800080; max-width:12%;">
                    				
                    				
                                </div>
                            </div>
                            
                            
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '664') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <p>
                                       <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1425') { echo $langlbl['title'] ; } } ?>: <input type="button" class="" name="button_color" value="<?=$button?>" id="button_color" required  style="color:<?=$button?>; background:<?=$button?>; max-width:15%; margin-bottom:5px;">
                                    </p> <input type="hidden" class="form-control" name="buttoncolor" value="<?=$primary?>" id="buttoncolor"  required >
                                    
                                    <input type="button" class="color_change_btn1" value="#d32191" <?php if($button == "#d32191") { echo $stylesbtn; }?> style="color:#d32191; background:#d32191; max-width:12%;">
                                    <input type="button" class="color_change_btn2" value="#6b73d5" <?php if($button == "#6b73d5") { echo $stylesbtn; }?> style="color:#6b73d5; background:#6b73d5; max-width:12%;">
                                    <input type="button" class="color_change_btn3" value="#fe9882" <?php if($button == "#fe9882") { echo $stylesbtn; }?> style="color:#fe9882; background:#fe9882; max-width:12%;">
                                    <input type="button" class="color_change_btn4" value="#9de498" <?php if($button == "#9de498") { echo $stylesbtn; }?> style="color:#9de498; background:#9de498; max-width:12%;">
                                    <input type="button" class="color_change_btn5" value="#fae503" <?php if($button == "#fae503") { echo $stylesbtn; }?> style="color:#fae503; background:#fae503; max-width:12%;">
                                    <input type="button" class="color_change_btn6" value="#438ccc" <?php if($button == "#438ccc") { echo $stylesbtn; }?> style="color:#438ccc; background:#438ccc; max-width:12%;">
                                    <input type="button" class="color_change_btn7" value="#00a65a" <?php if($button == "#00a65a") { echo $stylesbtn; }?> style="color:#00a65a; background:#00a65a; max-width:12%;">
                                    <input type="button" class="color_change_btn8" value="#01319d" <?php if($button == "#01319d") { echo $stylesbtn; }?> style="color:#01319d; background:#01319d; max-width:12%;">
                                    <input type="button" class="color_change_btn9" value="#d1c8f5" <?php if($button == "#d1c8f5") { echo $stylesbtn; }?> style="color:#d1c8f5; background:#d1c8f5; max-width:12%;">
                    				
                    				<input type="button" class="color_change_btn10" value="#778899" <?php if($button == "#778899") { echo $stylesbtn; }?> style="color:#778899; background:#778899; max-width:12%;">
                    				<input type="button" class="color_change_btn11" value="#666699" <?php if($button == "#666699") { echo $stylesbtn; }?> style="color:#666699; background:#666699; max-width:12%;">
                    				<input type="button" class="color_change_btn12" value="#560319" <?php if($button == "#560319") { echo $stylesbtn; }?> style="color:#560319; background:#560319; max-width:12%;">
                                    <input type="button" class="color_change_btn13" value="#000036" <?php if($button == "#000036") { echo $stylesbtn; }?> style="color:#000036; background:#000036; max-width:12%;">
                        			<input type="button" class="color_change_btn14" value="#800080" <?php if($button == "#800080") { echo $stylesbtn; }?> style="color:#800080; background:#800080; max-width:12%;">
                        			
                    				  
                                </div>
                            </div>
                            <?php $exsclpriv =  explode(",", $school_details[0]['scl_privilages']); 
                            $checked = "";
                            $kinderpriv = 'style="display:none;"';
                            $checked1 =  ""; 
                            $primarypriv = 'style="display:none;"';
                            $checked2 = ""; 
                            $seniorpriv = 'style="display:none;"';
                            if(in_array("KinderGarten", $exsclpriv)) { 
                                $checked = "checked";
                                $kinderpriv = 'style="display:block;"';
                            }
                            if(in_array("Primary", $exsclpriv)) { 
                                $checked1 =  "checked"; 
                                $primarypriv = 'style="display:block;"';
                            }
                            if(in_array("Senior", $exsclpriv)) { 
                                $checked2 = "checked"; 
                                $seniorpriv = 'style="display:block;"';
                            } 
                            ?>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1408') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-2"><input type="checkbox" name="scl_privilages[]" value="KinderGarten" <?php echo $checked;  ?> onclick="chckpriv1(this.value)" > KinderGarten</div>
                                        <div class="col-sm-2"><input type="checkbox" name="scl_privilages[]" value="Primary" <?php echo $checked1;  ?> onclick="chckpriv1(this.value)" > Primary</div>
                                        <div class="col-sm-2"><input type="checkbox" name="scl_privilages[]" value="Senior" <?php echo $checked2; ?> onclick="chckpriv1(this.value)" > Secondary</div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <div id="kinderpriv" class="col-sm-12 row container" <?php echo $kinderpriv ?> >
                            <h6>Kindergarten School Timings</h6>
                            <div class="row container">
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1426 ?></label>
                                <div class="form-group">
                                    <input class="form-control timepicker" id="ktimepicker" type="text" value="<?= $school_details[0]['kinderscl_strttimings'] ?>" name="kevent_start_time" placeholder="<?= $lbl1426 ?>" />
                                </div>
                            </div>
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1427 ?> </label>
                                <div class="form-group">
                                    <input class="form-control timepicker2" id="ktimepicker2" value="<?= $school_details[0]['kinderscl_endtimings'] ?>"  type="text" name="kevent_end_time" placeholder="<?= $lbl1427 ?>"  />
                                </div>
                            </div>
                            <!--<div class="col-sm-2 fieldpaddings">
                                <label><?= $lbl1428 ?></label>
                                <div class="form-group">
                                    <select class="form-control slott" name="ktimeslot" id="ktimeslot" placeholder="Choose Slot" onchange="slotbreak(this.value)">
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes" <?php if($school_details[0]['kinder_periodslot'] == "5 minutes") { echo "selected"; } ?> >5 Minutes</option>
                                        <option value="10 minutes" <?php if($school_details[0]['kinder_periodslot'] == "10 minutes") { echo "selected"; } ?>>10 Minutes</option>
                                        <option value="15 minutes" <?php if($school_details[0]['kinder_periodslot'] == "15 minutes") { echo "selected"; } ?>>15 Minutes</option>
                                        <option value="20 minutes" <?php if($school_details[0]['kinder_periodslot'] == "20 minutes") { echo "selected"; } ?>>20 Minutes</option>
                                        <option value="25 minutes" <?php if($school_details[0]['kinder_periodslot'] == "25 minutes") { echo "selected"; } ?>>25 Minutes</option>
                                        <option value="30 minutes" <?php if($school_details[0]['kinder_periodslot'] == "30 minutes") { echo "selected"; } ?>>30 Minutes</option>
                                        <option value="35 minutes" <?php if($school_details[0]['kinder_periodslot'] == "35 minutes") { echo "selected"; } ?>>35 Minutes</option>
                                        <option value="40 minutes" <?php if($school_details[0]['kinder_periodslot'] == "40 minutes") { echo "selected"; } ?>>40 Minutes</option>
                                        <option value="45 minutes" <?php if($school_details[0]['kinder_periodslot'] == "45 minutes") { echo "selected"; } ?>>45 Minutes</option>
                                        <option value="50 minutes" <?php if($school_details[0]['kinder_periodslot'] == "50 minutes") { echo "selected"; } ?>>50 Minutes</option>
                                        <option value="55 minutes" <?php if($school_details[0]['kinder_periodslot'] == "55 minutes") { echo "selected"; } ?>>55 Minutes</option>
                                        <option value="60 minutes" <?php if($school_details[0]['kinder_periodslot'] == "60 minutes") { echo "selected"; } ?>>60 Minutes</option>
                                    </select>
                                </div>
                            </div>-->
                            <!--<div class="col-sm-6 fieldpaddings"><div class="wrapper1">
                                <?php
                                $kinderbreak =  explode(",", $school_details[0]['kinder_breakname']); 
                                $kinderbreakst =  explode(",", $school_details[0]['kinder_breakstrt']); 
                                $kinderbreaked =  explode(",", $school_details[0]['kinder_breakend']); 
                                
                                $countkb = count($kinderbreak);
                                $countkbs = count($kinderbreakst);
                                $countkbe = count($kinderbreaked);
                                foreach($kinderbreak as $key => $kb) {
                                ?>
                                <div class="input-box row container mb-2">
                                    <div class="col-sm-3 fieldpaddings">
                                        <label><?= $lbl1429 ?> </label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="kbreakname[]" value="<?= $kb ?>" id="kbreakname" placeholder="<?php echo $lbl1429 ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <label><?= $lbl2073 ?> </label>
                                        <div class="form-group">
                                            <input class="form-control timepicker3" id="ktimepicker3" type="text" value="<?= $kinderbreakst[$key] ?>" name="kbreak_start_time[]" placeholder="<?= $lbl2073 ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <label><?= $lbl2074 ?> </label>
                                        <select class="form-control slott" name="kbreak_end_time[]" id="ktimepicker4" placeholder="Choose Slot" >
                                            <option value="">Choose Slot</option>
                                            <option value="5 minutes" <?php if($kinderbreaked[$key] == "5 minutes") { echo "selected"; } ?> >5 Minutes</option>
                                            <option value="10 minutes" <?php if($kinderbreaked[$key] == "10 minutes") { echo "selected"; } ?>>10 Minutes</option>
                                            <option value="15 minutes" <?php if($kinderbreaked[$key] == "15 minutes") { echo "selected"; } ?>>15 Minutes</option>
                                            <option value="20 minutes" <?php if($kinderbreaked[$key] == "20 minutes") { echo "selected"; } ?>>20 Minutes</option>
                                            <option value="25 minutes" <?php if($kinderbreaked[$key] == "25 minutes") { echo "selected"; } ?>>25 Minutes</option>
                                            <option value="30 minutes" <?php if($kinderbreaked[$key] == "30 minutes") { echo "selected"; } ?>>30 Minutes</option>
                                            <option value="35 minutes" <?php if($kinderbreaked[$key] == "35 minutes") { echo "selected"; } ?>>35 Minutes</option>
                                            <option value="40 minutes" <?php if($kinderbreaked[$key] == "40 minutes") { echo "selected"; } ?>>40 Minutes</option>
                                            <option value="45 minutes" <?php if($kinderbreaked[$key] == "45 minutes") { echo "selected"; } ?>>45 Minutes</option>
                                            <option value="50 minutes" <?php if($kinderbreaked[$key] == "50 minutes") { echo "selected"; } ?>>50 Minutes</option>
                                            <option value="55 minutes" <?php if($kinderbreaked[$key] == "55 minutes") { echo "selected"; } ?>>55 Minutes</option>
                                            <option value="60 minutes" <?php if($kinderbreaked[$key] == "60 minutes") { echo "selected"; } ?>>60 Minutes</option>
                                        </select>
                                    </div>
                                    <?php if($key == 0) { ?>
                                    <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>
                                    <?php } else { ?>
                                    <a href="#" class="col-sm-1 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div></div>-->
                            </div>
                            </div>
                            
                            <div id="primarypriv" class="col-sm-12 row container"<?php echo $primarypriv ?> >
                            <h6>Primary School Timings</h6>
                            <div class="row container">
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1426 ?></label>
                                <div class="form-group">
                                    <input class="form-control timepicker" id="ptimepicker" value="<?= $school_details[0]['primaryscl_strttimings'] ?>" type="text" name="pevent_start_time" placeholder="<?= $lbl1426 ?>"  />
                                </div>
                            </div>
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1427 ?> </label>
                                <div class="form-group">
                                    <input class="form-control timepicker2" id="ptimepicker2" value="<?= $school_details[0]['primaryscl_endtimings'] ?>" type="text" name="pevent_end_time" placeholder="<?= $lbl1427 ?>"  />
                                </div>
                            </div>
                            <!--<div class="col-sm-2 fieldpaddings">
                                <label><?= $lbl1428 ?></label>
                                <div class="form-group">
                                    <select class="form-control slott" name="ptimeslot" id="ptimeslot" placeholder="Choose Slot" onchange="slotbreak(this.value)">
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes" <?php if($school_details[0]['primary_periodslot'] == "5 minutes") { echo "selected"; } ?> >5 Minutes</option>
                                        <option value="10 minutes" <?php if($school_details[0]['primary_periodslot'] == "10 minutes") { echo "selected"; } ?>>10 Minutes</option>
                                        <option value="15 minutes" <?php if($school_details[0]['primary_periodslot'] == "15 minutes") { echo "selected"; } ?>>15 Minutes</option>
                                        <option value="20 minutes" <?php if($school_details[0]['primary_periodslot'] == "20 minutes") { echo "selected"; } ?>>20 Minutes</option>
                                        <option value="25 minutes" <?php if($school_details[0]['primary_periodslot'] == "25 minutes") { echo "selected"; } ?>>25 Minutes</option>
                                        <option value="30 minutes" <?php if($school_details[0]['primary_periodslot'] == "30 minutes") { echo "selected"; } ?>>30 Minutes</option>
                                        <option value="35 minutes" <?php if($school_details[0]['primary_periodslot'] == "35 minutes") { echo "selected"; } ?>>35 Minutes</option>
                                        <option value="40 minutes" <?php if($school_details[0]['primary_periodslot'] == "40 minutes") { echo "selected"; } ?>>40 Minutes</option>
                                        <option value="45 minutes" <?php if($school_details[0]['primary_periodslot'] == "45 minutes") { echo "selected"; } ?>>45 Minutes</option>
                                        <option value="50 minutes" <?php if($school_details[0]['primary_periodslot'] == "50 minutes") { echo "selected"; } ?>>50 Minutes</option>
                                        <option value="55 minutes" <?php if($school_details[0]['primary_periodslot'] == "55 minutes") { echo "selected"; } ?>>55 Minutes</option>
                                        <option value="60 minutes" <?php if($school_details[0]['primary_periodslot'] == "60 minutes") { echo "selected"; } ?>>60 Minutes</option>
                                    
                                    </select>
                                </div>
                            </div>-->
                            <!--<div class="col-sm-6 fieldpaddings"><div class="wrapper2">
                                <?php
                                $primarybreak =  explode(",", $school_details[0]['primary_breakname']); 
                                $primarybreakst =  explode(",", $school_details[0]['primary_breakstrt']); 
                                $primarybreaked =  explode(",", $school_details[0]['primary_breakend']); 
                                
                                $countpb = count($primarybreak);
                                $countpbs = count($primarybreakst);
                                $countpbe = count($primarybreaked);
                                foreach($primarybreak as $key => $pb) {
                                ?>
                                <div class="input-box row container mb-2">
                                <div class="col-sm-3 fieldpaddings">
                                    <label><?= $lbl1429 ?> </label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="pbreakname[]" value="<?= $pb ?>" id="pbreakname" placeholder="<?php echo $lbl1429 ?>">
                                    </div>
                                </div>
                               
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2073 ?> </label>
                                    <div class="form-group">
                                        <input class="form-control timepicker3" id="ptimepicker3" value="<?= $primarybreakst[$key] ?>" type="text" name="pbreak_start_time[]" placeholder="<?= $lbl2073 ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2074 ?> </label>
                                    <select class="form-control slott" name="pbreak_end_time[]" id="ptimepicker4" placeholder="Choose Slot" >
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes" <?php if($primarybreaked[$key] == "5 minutes") { echo "selected"; } ?> >5 Minutes</option>
                                        <option value="10 minutes" <?php if($primarybreaked[$key] == "10 minutes") { echo "selected"; } ?>>10 Minutes</option>
                                        <option value="15 minutes" <?php if($primarybreaked[$key] == "15 minutes") { echo "selected"; } ?>>15 Minutes</option>
                                        <option value="20 minutes" <?php if($primarybreaked[$key] == "20 minutes") { echo "selected"; } ?>>20 Minutes</option>
                                        <option value="25 minutes" <?php if($primarybreaked[$key] == "25 minutes") { echo "selected"; } ?>>25 Minutes</option>
                                        <option value="30 minutes" <?php if($primarybreaked[$key] == "30 minutes") { echo "selected"; } ?>>30 Minutes</option>
                                        <option value="35 minutes" <?php if($primarybreaked[$key] == "35 minutes") { echo "selected"; } ?>>35 Minutes</option>
                                        <option value="40 minutes" <?php if($primarybreaked[$key] == "40 minutes") { echo "selected"; } ?>>40 Minutes</option>
                                        <option value="45 minutes" <?php if($primarybreaked[$key] == "45 minutes") { echo "selected"; } ?>>45 Minutes</option>
                                        <option value="50 minutes" <?php if($primarybreaked[$key] == "50 minutes") { echo "selected"; } ?>>50 Minutes</option>
                                        <option value="55 minutes" <?php if($primarybreaked[$key] == "55 minutes") { echo "selected"; } ?>>55 Minutes</option>
                                        <option value="60 minutes" <?php if($primarybreaked[$key] == "60 minutes") { echo "selected"; } ?>>60 Minutes</option>
                                    </select>
                                </div>
                                <?php if($key == 0) { ?>
                                <button class="col-sm-1 btn add-btn1"><i class="fa fa-plus"></i></button>
                                <?php } else { ?>
                                <a href="#" class="col-sm-1 remove-lnk1 form-control"><i class="fa fa-minus"></i></a>
                                <?php } ?>
                                </div>
                                <?php } ?>
                            </div></div>-->
                            </div></div>
                            
                            <div id="secondarypriv" class="col-sm-12 row container" <?php echo $seniorpriv ?> >
                            <h6>Secondary School Timings</h6>
                            <div class="row container">
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1426 ?></label>
                                <div class="form-group">
                                    <input class="form-control timepicker" id="stimepicker" value="<?= $school_details[0]['seniorscl_strttimings'] ?>" type="text" name="sevent_start_time" placeholder="<?= $lbl1426 ?>" />
                                </div>
                            </div>
                            <div class="col-sm-4 fieldpaddings">
                                <label><?= $lbl1427 ?> </label>
                                <div class="form-group">
                                    <input class="form-control timepicker2" id="stimepicker2" value="<?= $school_details[0]['seniorscl_endtimings'] ?>" type="text" name="sevent_end_time" placeholder="<?= $lbl1427 ?>"  />
                                </div>
                            </div>
                            <!--<div class="col-sm-2 fieldpaddings">
                                <label><?= $lbl1428 ?></label>
                                <div class="form-group">
                                    <select class="form-control slott" name="stimeslot" id="stimeslot" placeholder="Choose Slot" onchange="slotbreak(this.value)">
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes" <?php if($school_details[0]['senior_periodslot'] == "5 minutes") { echo "selected"; } ?> >5 Minutes</option>
                                        <option value="10 minutes" <?php if($school_details[0]['senior_periodslot'] == "10 minutes") { echo "selected"; } ?>>10 Minutes</option>
                                        <option value="15 minutes" <?php if($school_details[0]['senior_periodslot'] == "15 minutes") { echo "selected"; } ?>>15 Minutes</option>
                                        <option value="20 minutes" <?php if($school_details[0]['senior_periodslot'] == "20 minutes") { echo "selected"; } ?>>20 Minutes</option>
                                        <option value="25 minutes" <?php if($school_details[0]['senior_periodslot'] == "25 minutes") { echo "selected"; } ?>>25 Minutes</option>
                                        <option value="30 minutes" <?php if($school_details[0]['senior_periodslot'] == "30 minutes") { echo "selected"; } ?>>30 Minutes</option>
                                        <option value="35 minutes" <?php if($school_details[0]['senior_periodslot'] == "35 minutes") { echo "selected"; } ?>>35 Minutes</option>
                                        <option value="40 minutes" <?php if($school_details[0]['senior_periodslot'] == "40 minutes") { echo "selected"; } ?>>40 Minutes</option>
                                        <option value="45 minutes" <?php if($school_details[0]['senior_periodslot'] == "45 minutes") { echo "selected"; } ?>>45 Minutes</option>
                                        <option value="50 minutes" <?php if($school_details[0]['senior_periodslot'] == "50 minutes") { echo "selected"; } ?>>50 Minutes</option>
                                        <option value="55 minutes" <?php if($school_details[0]['senior_periodslot'] == "55 minutes") { echo "selected"; } ?>>55 Minutes</option>
                                        <option value="60 minutes" <?php if($school_details[0]['senior_periodslot'] == "60 minutes") { echo "selected"; } ?>>60 Minutes</option>
                                    
                                    </select>
                                </div>
                            </div>-->
                            <!--<div class="col-sm-6 fieldpaddings"><div class="wrapper3">
                                <?php
                                $seniorbreak =  explode(",", $school_details[0]['senior_breakname']); 
                                $seniorbreakst =  explode(",", $school_details[0]['senior_breakstrt']); 
                                $seniorbreaked =  explode(",", $school_details[0]['senior_breakend']); 
                                
                                $countsb = count($seniorbreak);
                                $countsbs = count($seniorbreakst);
                                $countsbe = count($seniorbreaked);
                                foreach($seniorbreak as $key => $sb) {
                                ?>
                                <div class="input-box row container mb-2">
                                <div class="col-sm-3 fieldpaddings">
                                    <label><?= $lbl1429 ?> </label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="<?= $sb ?>" name="sbreakname[]" id="sbreakname" placeholder="<?php echo $lbl1429 ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2073 ?> </label>
                                    <div class="form-group">
                                        <input class="form-control timepicker3" id="stimepicker3" value="<?= $seniorbreakst[$key] ?>" type="text" name="sbreak_start_time[]" placeholder="<?= $lbl2073 ?>"  />
                                    </div>
                                </div>
                                <div class="col-sm-4 fieldpaddings">
                                    <label><?= $lbl2074 ?> </label>
                                    <select class="form-control slott" name="sbreak_end_time[]" id="stimepicker4" placeholder="Choose Slot" >
                                        <option value="">Choose Slot</option>
                                        <option value="5 minutes" <?php if($seniorbreaked[$key] == "5 minutes") { echo "selected"; } ?> >5 Minutes</option>
                                        <option value="10 minutes" <?php if($seniorbreaked[$key] == "10 minutes") { echo "selected"; } ?>>10 Minutes</option>
                                        <option value="15 minutes" <?php if($seniorbreaked[$key] == "15 minutes") { echo "selected"; } ?>>15 Minutes</option>
                                        <option value="20 minutes" <?php if($seniorbreaked[$key] == "20 minutes") { echo "selected"; } ?>>20 Minutes</option>
                                        <option value="25 minutes" <?php if($seniorbreaked[$key] == "25 minutes") { echo "selected"; } ?>>25 Minutes</option>
                                        <option value="30 minutes" <?php if($seniorbreaked[$key] == "30 minutes") { echo "selected"; } ?>>30 Minutes</option>
                                        <option value="35 minutes" <?php if($seniorbreaked[$key] == "35 minutes") { echo "selected"; } ?>>35 Minutes</option>
                                        <option value="40 minutes" <?php if($seniorbreaked[$key] == "40 minutes") { echo "selected"; } ?>>40 Minutes</option>
                                        <option value="45 minutes" <?php if($seniorbreaked[$key] == "45 minutes") { echo "selected"; } ?>>45 Minutes</option>
                                        <option value="50 minutes" <?php if($seniorbreaked[$key] == "50 minutes") { echo "selected"; } ?>>50 Minutes</option>
                                        <option value="55 minutes" <?php if($seniorbreaked[$key] == "55 minutes") { echo "selected"; } ?>>55 Minutes</option>
                                        <option value="60 minutes" <?php if($seniorbreaked[$key] == "60 minutes") { echo "selected"; } ?>>60 Minutes</option>
                                    </select>
                                </div>
                                <?php if($key == 0) { ?>
                                <button class="col-sm-1 btn add-btn2"><i class="fa fa-plus"></i></button>
                                <?php } else { ?>
                                <a href="#" class="col-sm-1 remove-lnk2 form-control"><i class="fa fa-minus"></i></a>
                                <?php } ?>
                             </div>
                             <?php } ?>
                             </div></div>-->
                            </div></div>
                            
                            <div class="col-sm-12">
                                <div class="error" id="sclerror">
                                </div>
                                <div class="success" id="sclsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <input type="hidden" name="sclid" value="<?= $id?>">   
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="updatesclbtn" class="btn btn-primary updatesclbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                   
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

function chckpriv1(val)
{
    //alert(val);
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
            /*$("#ptimeslot").prop("required", true);
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
      var y = "<?php echo $countkb ?>";
      var x = "<?php echo $countpb ?>";
      var z = "<?php echo $countsb ?>";
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
    
    function slotbreak(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var start =  $('#timepicker').val();
    var end =  $('#timepicker2').val();
    var slot = val.split(' ');
    var duration = slot[0];
     $("#breaktime").html("");
    $.ajax({
        type:'POST',
        url: baseurl + '/Schools/time_slots_prepare',
        data:{'start':start, 'end':end, 'duration':duration},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
           
            
            $.each(result, function(i, item) {
                 console.log(item);
                var abc = "<option value='"+item+"'>"+item+"</option>";
               
                $("#breaktime").append(abc);
            });
        }
    });
    
}

</script>

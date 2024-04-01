<?php
    $school_id =$this->request->session()->read('company_id');
    $gender = array('Male','Female');
    $bloodgrp = array('O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-');
    
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '189') { $labl189 = $langlbl['title'] ; } 
        if($langlbl['id'] == '41') { $labl41 = $langlbl['title'] ; } 
        if($langlbl['id'] == '190') { $lbl190 = $langlbl['title'] ; }
        if($langlbl['id'] == '191') { $lbl191 = $langlbl['title'] ; }
        if($langlbl['id'] == '193') { $lbl193 = $langlbl['title'] ; }
        if($langlbl['id'] == '209') { $lbl209 = $langlbl['title'] ; }
        if($langlbl['id'] == '197') { $lbl197 = $langlbl['title'] ; }
        if($langlbl['id'] == '196') { $lbl196 = $langlbl['title'] ; }
        if($langlbl['id'] == '198') { $lbl198 = $langlbl['title'] ; }
        if($langlbl['id'] == '199') { $lbl199 = $langlbl['title'] ; }
        if($langlbl['id'] == '200') { $lbl200 = $langlbl['title'] ; }
        if($langlbl['id'] == '201') { $lbl201 = $langlbl['title'] ; }
        if($langlbl['id'] == '10') { $lbl10 = $langlbl['title'] ; }
        if($langlbl['id'] == '202') { $lbl202 = $langlbl['title'] ; }
        if($langlbl['id'] == '203') { $lbl203 = $langlbl['title'] ; }
        if($langlbl['id'] == '204') { $lbl204 = $langlbl['title'] ; }    
        if($langlbl['id'] == '205') { $lbl205 = $langlbl['title'] ; }    
        if($langlbl['id'] == '206') { $lbl206 = $langlbl['title'] ; }    
        if($langlbl['id'] == '207') { $lbl207 = $langlbl['title'] ; }    
        if($langlbl['id'] == '208') { $lbl208 = $langlbl['title'] ; }  
        if($langlbl['id'] == '209') { $lbl209 = $langlbl['title'] ; }
        if($langlbl['id'] == '211') { $lbl211 = $langlbl['title'] ; }
        if($langlbl['id'] == '212') { $lbl212 = $langlbl['title'] ; }
        if($langlbl['id'] == '213') { $lbl213 = $langlbl['title'] ; }
        if($langlbl['id'] == '214') { $lbl214 = $langlbl['title'] ; }
        if($langlbl['id'] == '215') { $lbl215 = $langlbl['title'] ; }
        if($langlbl['id'] == '216') { $lbl216 = $langlbl['title'] ; }
        if($langlbl['id'] == '217') { $lbl217 = $langlbl['title'] ; }
        if($langlbl['id'] == '218') { $lbl218 = $langlbl['title'] ; }
        if($langlbl['id'] == '1783') { $lbl1783 = $langlbl['title'] ; }
        if($langlbl['id'] == '1784') { $lbl1784 = $langlbl['title'] ; }
        if($langlbl['id'] == '1785') { $lbl1785 = $langlbl['title'] ; }
        if($langlbl['id'] == '219') { $lbl219 = $langlbl['title'] ; }
        if($langlbl['id'] == '232') { $lbl232 = $langlbl['title'] ; }
        if($langlbl['id'] == '175') { $lbl175 = $langlbl['title'] ; }
        if($langlbl['id'] == '231') { $lbl231 = $langlbl['title'] ; }
        if($langlbl['id'] == '224') { $lbl224 = $langlbl['title'] ; }
        if($langlbl['id'] == '228') { $lbl228 = $langlbl['title'] ; }
        if($langlbl['id'] == '229') { $lbl229 = $langlbl['title'] ; }
        if($langlbl['id'] == '230') { $lbl230 = $langlbl['title'] ; }
        if($langlbl['id'] == '178') { $lbl178 = $langlbl['title'] ; }
        if($langlbl['id'] == '222') { $lbl222 = $langlbl['title'] ; }
        if($langlbl['id'] == '223') { $lbl223 = $langlbl['title'] ; }
        if($langlbl['id'] == '2011') { $male = $langlbl['title'] ; } 
        if($langlbl['id'] == '2012') { $female = $langlbl['title'] ; }
        
        if($langlbl['id'] == '2062') { $youmeph = $langlbl['title'] ; }
        if($langlbl['id'] == '2060') { $fphnum = $langlbl['title'] ; }
        if($langlbl['id'] == '2061') { $mphnum = $langlbl['title'] ; }
        if($langlbl['id'] == '2156') { $savprnt = $langlbl['title'] ; }
    }
?>
<style>
.left-date{
    display: none;
}
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?= $labl189 ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $labl41 ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'addadm'] , 'id' => "addadmform", 'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <input type="hidden" name="school_id" value="<?=$school_id?>">
                            <div class="col-sm-12 mb-2">
                                <h5 class="iconsss"><?= $lbl190 ?></h5>
                            </div>
							<div class="col-sm-4">
                                <label><?= $lbl191 ?></label>
                                <div class="form-group">                                                
                                    <select class="form-control left" name="sessionid" id="sessionid" required>
                                        <option value=""><?= $lbl190 ?></option>
                                        <?php
                                        foreach($session_details as $session)
                                        {
                                            ?>
                                            <option value="<?= $session['id'] ?>"><?= $session['startyear']."-".$session['endyear'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label><?= $lbl193 ?></label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control"  name="picture"  >
                                    <small id="fileHelp" class="form-text text-muted"><?= $lbl209 ?></small>
                                </div>
                            </div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-3">
                                <label><?= $lbl197 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name"  required placeholder="<?= $lbl197 ?> *">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $lbl196 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name"  required placeholder="<?= $lbl196 ?> *">
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label><?= $lbl198 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control dobirthdatepicker" id="dobdatepicker" data-date-format="dd-mm-yyyy" name="dob"  required placeholder="<?= $lbl198 ?> *">
                                </div>
                            </div>
			                <div class="col-sm-3">
                                <label><?= $lbl199 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="s_age" name="s_age"  placeholder="<?= $lbl199 ?> " readonly>
                                </div>
                            </div>
			                <div class="col-sm-3">
                                <label><?= $lbl200 ?>*</label>
                                <div class="form-group">
                                    <select class="form-control gender" id="gender" name="gender" required>
                                        <option value="">Choose One</option>
                                        <option value="Male"><?= $male ?></option>
                                        <option value="Female"><?= $female ?></option>
                                    </select>
                                </div>
                            </div>
							<div class="col-sm-3">
                                <label><?= $lbl201 ?> *</label>
                                <div class="form-group">
                                    <select class="form-control class" id="class" name="class" required onchange="grades(this.value)">
                                        <option value="">Choose Class</option>
                                        <?php
                                        foreach($class_details as $key => $val){
                                            if(!empty($sclsub_details[0]))
                                            { 
                                                //echo "subadmin";
                                                if(strtolower($val['school_sections']) == "creche" || strtolower($val['school_sections']) == "maternelle") {
                                                    $clsmsg = "kindergarten";
                                                }
                                                elseif(strtolower($val['school_sections']) == "primaire") {
                                                    $clsmsg = "primaire";
                                                }
                                                else
                                                {
                                                    $clsmsg = "secondaire";
                                                }
                                                $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                                //print_r($subpriv);
                                                $clsmsg = trim($clsmsg);
                                                if(in_array($clsmsg, $subpriv)) { 
                                                    $show = 1;
                                                }
                                                else
                                                {
                                                    $show = 0;
                                                }
                                            } else { 
                                                $show = 1;
                                            }
                                            if($show == 1) {
                                            ?>
                                              <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']. " (". $val['school_sections']. ")";?> </option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select> 
                                </div>
                            </div>
                            <div class="col-sm-3 subject_field" style="display:none">
                                <label><?= $lbl10 ?>*</label>
                                <div class="form-group">
                                    <select class="form-control js-example-basic-multiple subj_s" multiple="multiple" name="subjects[]" id="subjects" placeholder="Choose Subjects">
                                        <option value="">Choose Subjects</option>
                                        <?php
                                        foreach($subject_details as $key => $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" ><?php echo $val['subject_name'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $lbl202 ?></label>
                                <div class="form-group">
                                    <!--<input type="text" class="form-control" name="bloodgroup"  placeholder="Blood Group">-->
                                     <select class="form-control bloodgroup" id="bloodgroup" name="bloodgroup">
                                        <option value="">Choose One</option>
                                        <?php
                                        foreach($bloodgrp as $val){
                                        ?>
                                          <option  value="<?=$val?>" ><?php echo $val;?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label><?= $lbl203 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control"  maxlength="15" name="mobile_for_sms" id="mobile_for_sms"  required placeholder="<?= $lbl203 ?>*">
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label><?= $lbl204 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="national" placeholder="<?= $lbl204 ?> ">
                                </div>
                            </div>
							
							<div class="col-sm-3">
                                <label><?= $youmeph ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="contactyoume" placeholder="<?= $youmeph ?>">
                                </div>
                            </div>
							<div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?= $lbl208 ?></h5>
                            </div>
							
							<div class="col-sm-4">
                                <label><?= $lbl205 ?></label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="gr1_path"  >
                                    <small id="fileHelp" class="form-text text-muted"><?= $lbl209 ?></small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl206 ?></label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="gr2_path"  >
                                    <small id="fileHelp" class="form-text text-muted"><?= $lbl209 ?></small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl207 ?></label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="gr3_path"  >
                                    <small id="fileHelp" class="form-text text-muted"><?= $lbl209 ?></small>
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label><?= $lbl211 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="s_f_name" name="s_f_name"  required placeholder="<?= $lbl211 ?> *">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $fphnum ?></label>
                                <div class="wrapper1">
                                    <div class="input-box row container mb-2">
                                        <input type="text" class="col-sm-10 form-control"  name="fatherphone[]"  placeholder="<?= $fphnum ?>">
                                        <button class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $lbl212 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="s_m_name" name="s_m_name"  required placeholder="<?= $lbl212 ?>*">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $mphnum ?></label>
                                <div class="wrapper">
                                    <div class="input-box row container mb-2">
                                        <input type="text" class="col-sm-10 form-control"  name="motherphone[]"  placeholder="<?= $mphnum ?>">
                                        <button class="col-sm-2 btn add-btn"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl213 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="guardian_name"  placeholder="<?= $lbl213 ?> ">
                                </div>
                            </div>	
							<div class="col-sm-4">
                                <label><?= $lbl214 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_occ"  placeholder="<?= $lbl214 ?>">
                                </div>
                            </div>	
                            <div class="col-sm-4">
                                <label><?= $lbl215 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="emergency_contact"  placeholder="<?= $lbl215 ?>*" required>
                                </div>
                            </div>	
                            
                            <div class="col-sm-4">
                                <label><?= $lbl216 ?>*</label>
                                <div class="form-group">
                                    <input type="text"  id="emergency_name" name="emergency_name" required class="form-control" placeholder="<?= $lbl216 ?>*">
                                    <!--<select class="form-control js-states" id="emergency_name" name="emergency_name">
                                        <option value="Father" selected><?= $lbl1783 ?></option>
                                        <option value="Mother"><?= $lbl1784 ?></option>
                                        <option value="Guardian"><?= $lbl1785 ?></option>
                                    </select>-->
                                </div>
                            </div>	
							<div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?= $lbl217 ?></h5>
                            </div>
							
							<div class="col-sm-6">
                                <label><?= $lbl218 ?> *</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="resi_add1"  placeholder="<?= $lbl218 ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?= $lbl219 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="resi_add2"  placeholder="<?= $lbl219 ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl222 ?> *</label>
                                <div class="form-group">
                                <select class="form-control countries country" id="country" name="admcountry" required>
                                    <option value="">Choose Country</option>
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
                                <label><?= $lbl223 ?> *</label>
                                <div class="form-group">
                                <select class="form-control state" id="state" name="admstate" required>
                                    <option value="">Choose State</option>
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
                                <label><?= $lbl224 ?> *</label>
                                <div class="form-group" >
                                    <input type="text" class="form-control" name="admcity" placeholder="<?= $lbl178 ?> "  required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl228 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone_resi" placeholder="<?= $lbl228 ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?= $lbl229 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone_off"  placeholder="<?= $lbl229 ?>">
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?= $lbl230 ?></h5>
                            </div>
                            <div class="col-sm-6">
                                <label><?= $lbl231 ?>*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  id="email" required placeholder="<?= $lbl231 ?>*">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?= $lbl175 ?>*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="pemail"  id="pemail" required placeholder="<?= $lbl175 ?>*">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="error" id="stdnterror">
                                </div>
                                <div class="success" id="stdntsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="addstdntbtn" class="btn btn-primary addstdntbtn"><?= $savprnt ?></button>
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
    var max_input = 3;
    var x = 1;
    $('.add-btn').click(function (e) {
        e.preventDefault();
        if (x < max_input) {
            x++;
            $('.wrapper').append(`
                <div class="input-box row container mb-2">
                    <input type="text" class="col-sm-10 form-control"  name="motherphone[]"  placeholder="<?php echo $mphnum ?>">
                    <a href="#" class="col-sm-2 remove-lnk form-control"><i class="fa fa-minus"></i></a>
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
    
    var y = 1;
    $('.add-btn1').click(function (e) {
        e.preventDefault();
        
        if (y < max_input) {
        y++;
        $('.wrapper1').append(`
            <div class="input-box row container mb-2">
                <input type="text" class="col-sm-10 form-control"  name="fatherphone[]"  placeholder="<?php echo $fphnum ?>">
                <a href="#" class="col-sm-2 remove-lnk form-control"><i class="fa fa-minus"></i></a>
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
});
</script>


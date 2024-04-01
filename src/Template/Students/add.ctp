<?php
    $school_id =$this->request->session()->read('company_id');
    $gender = array('Male','Female');
    $bloodgrp = array('O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-');
?>
<style>



.left-date{
    display: none;
}

</style>

<?php
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '2104') { $youmeph = $langlbl['title'] ; }
        if($langlbl['id'] == '2060') { $fphnum = $langlbl['title'] ; }
        if($langlbl['id'] == '2061') { $mphnum = $langlbl['title'] ; }
    }
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '139') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>students" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                 </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'addstdnt'] , 'id' => "addstdntform", 'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <input type="hidden" name="school_id" value="<?=$school_id?>">
                            <div class="col-sm-12 mb-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '140') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
							
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '141') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control"  name="picture"  >
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-8"></div>
                            
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '144') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '144') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '143') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '143') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '145') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control dobirthdatepicker" id="dobdatepicker" data-date-format="dd-mm-yyyy" name="dob"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '145') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
							
			                <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '146') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="s_age" name="s_age" readonly  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '148') { echo $langlbl['title'] ; } } ?> ">
                                </div>
                            </div>
			                <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '149') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <select class="form-control gender" id="gender" name="gender">
                                        <option value="">Choose One</option>
                                        <option value="Male"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2011') { echo $langlbl['title'] ; } } ?></option>
                                        <option value="Female"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2012') { echo $langlbl['title'] ; } } ?></option>
                                        
                                    </select>
                                </div>
                            </div>
							
							
							<div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '150') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <select class="form-control class" id="class" name="class" required onchange="grades(this.value)">
                                        <option value="">Choose Class</option>
                                        <?php
                                        foreach($class_details as $key => $val){
                                            if(!empty($sclsub_details[0]))
                                            { 
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
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '119') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <select class="form-control js-example-basic-multiple subj_s" multiple="multiple" name="subjects[]" id="subjects" placeholder="Choose Subjects" disabled>
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
                            <input type="hidden" name="subjec" id="subjec">
                            
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '151') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group"><!--
                                    <input type="text" class="form-control" name="bloodgroup"  placeholder="Blood Group">-->
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
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '152') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control"  maxlength="15" name="mobile_for_sms" id="mobile_for_sms"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '152') { echo $langlbl['title'] ; } } ?>*">
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '153') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="national" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '153') { echo $langlbl['title'] ; } } ?> ">
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label><?= $youmeph ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="contactyoume" placeholder="<?= $youmeph ?>">
                                </div>
                            </div>
                        
							
							
							<div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '155') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
							
							<div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '156') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="gr1_path"  >
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '157') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="gr2_path"  >
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '158') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="gr3_path"  >
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '160') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="s_f_name" name="s_f_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '160') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php echo $fphnum ?></label>
                                <div class="wrapper1">
                                    <div class="input-box row container mb-2">
                                        <input type="text" class="col-sm-10 form-control"  name="fatherphone[]"  placeholder="<?php echo $fphnum ?>">
                                        <button class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '161') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="s_m_name" name="s_m_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '161') { echo $langlbl['title'] ; } } ?> *">
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
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '162') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="guardian_name"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '162') { echo $langlbl['title'] ; } } ?> ">
                                </div>
                            </div>	
							<div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '163') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_occ"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '163') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>	
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '164') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="emergency_contact"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '164') { echo $langlbl['title'] ; } } ?>*" required>
                                </div>
                            </div>	
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '165') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text"  id="emergency_name" name="emergency_name" required class="form-control" placeholder="<?= $lbl216 ?>">
                                    <!--<select class="form-control js-states" id="emergency_name" name="emergency_name">
                                        <option value="Father" selected><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1783') { echo $langlbl['title'] ; } } ?></option>
                                        <option value="Mother"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1784') { echo $langlbl['title'] ; } } ?></option>
                                        <option value="Guardian"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1785') { echo $langlbl['title'] ; } } ?></option>
                                    </select>-->
                                </div>
                            </div>	
							
							
							
							<div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '166') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
							
							<div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '167') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="resi_add1"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '167') { echo $langlbl['title'] ; } } ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '168') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="resi_add2"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '168') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '169') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                <select class="form-control countries country" id="country" name="country" required>
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
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '170') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                <select class="form-control state" id="state" name="state" required>
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
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '171') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group" >
                                    <input type="text" class="form-control" name="city" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '178') { echo $langlbl['title'] ; } } ?>"  required>
                                                           
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '172') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone_resi" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '172') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '173') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone_off"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '173') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1580') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="checkbox" name="lib_access"  id="lib_access" value="1"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1579') { echo $langlbl['title'] ; } } ?>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '230') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '233') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  id="email" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '233') { echo $langlbl['title'] ; } } ?>*">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '175') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="pemail"  id="pemail" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '175') { echo $langlbl['title'] ; } } ?>*">
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
                                        <button type="submit" id="addstdntbtn" class="btn btn-primary addstdntbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '179') { echo $langlbl['title'] ; } } ?></button>
                   <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
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


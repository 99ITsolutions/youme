<?php
    
    $leftstdnt = array('Yes','No' );
    $boarder = array('Yes','No' );	
    $catarry = array('General','S.C.','B.C.','OBC','EWS' );
    $newcatarry = array('Regular','Staff.','Teacher','Free','EWS' );
    $gender = array('Male','Female');
    $bloodgrp = array('O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-');

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
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1534') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>students" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php   
                    echo $this->Form->create(false , ['url' => ['action' => 'editstdnt'] , 'id' => "editstdntform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <input type="hidden" name="id" value="<?=$studentdetails[0]['id']?>" >
                            <input type="hidden" name="school_id" value="<?=$studentdetails[0]['school_id']?>" >
                            
                            <div class="col-sm-12 mb-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '140') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
							
                            
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '141') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <?php 
                                    if(!empty($studentdetails[0]['pic']))
                                    { ?>
                                        <img src="../../img/<?=$studentdetails[0]['pic']?>" width="70px" height="45px" style="margin-bottom:15px;">
                                        <?php 
                                    }else
                                    {
                                        ?>
                                            <img src="../../img/notimg.png" width="70px" height="45px" style="margin-bottom:15px;">
                                        <?php
                                    } ?>
                                    <input type="file" class="form-control" name="picture" >
                                    <input type="hidden" name="hpicture" value="<?=$studentdetails[0]['pic']?>">
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-8"></div>
                          
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '144') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name" value="<?=$studentdetails[0]['l_name']?>"  required placeholder="Last Student Name *">
                                </div>
                            </div>

                              
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '143') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name" value="<?=$studentdetails[0]['f_name']?>"  required placeholder="First Student Name *">
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '145') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control dobirthdatepicker" id="dobdatepicker" data-date-format="dd-mm-yyyy" name="dob" value="<?= date('d-m-Y',strtotime($studentdetails[0]['dob']))?>" required placeholder="Date Of Birth *">
                                </div>
                            </div>
							<div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '146') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="s_age" name="s_age" readonly value="<?=$studentdetails[0]['s_age']?>"  placeholder="Student Age ">
                                </div>
                            </div>
							
							 <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '149') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <select class="form-control gender" id="gender" name="gender">
                                        <option value="">Choose One</option>
                                        <option value="Male" <?php if($studentdetails[0]['gender']== "Male") {?> selected="true" <?php } ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2011') { echo $langlbl['title'] ; } } ?></option>
                                        <option value="Female" <?php if($studentdetails[0]['gender']== "Female" ) {?> selected="true" <?php } ?>  ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2012') { echo $langlbl['title'] ; } } ?></option>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            
                            
							<div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '150') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <select class="form-control class" id="class" name="class" onchange="grades(this.value)">
                                        <option value="">Choose Grade</option>
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
                                              <option  value="<?=$val['id']?>" <?php if($studentdetails[0]['class']==$val['id']) { ?> selected="true" <?php } ?> ><?php echo $val['c_name'] ."-" . $val['c_section']. " (". $val['school_sections']. ")";?> </option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select> 
                                </div>
                            </div>	
                            <div class="col-sm-3 subject_field">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '119') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <?php $subjects = explode(",", $studentdetails[0]['subjects']) ;
                                    ?>
                                    <select class="form-control js-example-basic-multiple subj_s" multiple="multiple" name="subjects[]" id="subjects" placeholder="Choose Subjects" disabled>
                                        <option value="">Choose Subjects</option>
                                        <?php
                                        
                                        foreach($subject_details as $key => $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" <?php if(in_array($val['id'], $subjects)) { echo "selected" ; } ?>><?php echo $val['subject_name'];?> </option>
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
                                    <input type="text" class="form-control" name="bloodgroup" value="<?=$studentdetails[0]['bloodgroup']?>" placeholder="Blood Group">-->
                                    <select class="form-control bloodgroup" id="bloodgroup" name="bloodgroup">
                                        <option value="">Choose One</option>
                                        <?php
                                        foreach($bloodgrp as $val){
                                        ?>
                                          <option  value="<?=$val?>" <?php if($studentdetails[0]['bloodgroup']==$val) {?> selected="true" <?php } ?> ><?php echo $val;?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '152') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" maxlength="15" name="mobile_for_sms" value="<?=$studentdetails[0]['mobile_for_sms']?>" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '152') { echo $langlbl['title'] ; } } ?>*" id="mobile_for_sms">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '153') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="national" value="<?=$studentdetails[0]['national']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '153') { echo $langlbl['title'] ; } } ?> ">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $youmeph ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="contactyoume" placeholder="<?= $youmeph ?>" value="<?=$studentdetails[0]['contactyoume']?>" >
                                </div>
                            </div>
                            
                            
							<div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '155') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
							
							<div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '156') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <?php 
                                    if(!empty($studentdetails[0]['gr1_path']))
                                    { ?>
                                        <img src="../../img/<?=$studentdetails[0]['gr1_path']?>" width="70px" height="45px" style="margin-bottom:15px;">
                                        <?php 
                                    }else
                                    {
                                        ?>
                                            <img src="../../img/notimg.png" width="70px" height="45px" style="margin-bottom:15px;">
                                        <?php
                                    } ?>
                                    <input type="file" class="form-control" name="gr1_path"  >
                                    <input type="hidden" name="hgr1_path" value="<?=$studentdetails[0]['gr1_path']?>">
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '157') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <?php 
                                    if(!empty($studentdetails[0]['gr2_path']))
                                    { ?>
                                        <img src="../../img/<?=$studentdetails[0]['gr2_path']?>" width="70px" height="45px" style="margin-bottom:15px;">
                                        <?php 
                                    }else
                                    {
                                        ?>
                                            <img src="../../img/notimg.png" width="70px" height="45px" style="margin-bottom:15px;">
                                        <?php
                                    } ?>  
                                    
                                    <input type="file" class="form-control" name="gr2_path"  >
                                    <input type="hidden" name="hgr2_path" value="<?=$studentdetails[0]['gr2_path']?>">
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '158') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <?php 
                                    if(!empty($studentdetails[0]['gr3_path']))
                                    { ?>
                                        <img src="../../img/<?=$studentdetails[0]['gr3_path']?>" width="70px" height="45px" style="margin-bottom:15px;">
                                        <?php 
                                    }else
                                    {
                                        ?>
                                            <img src="../../img/notimg.png" width="70px" height="45px" style="margin-bottom:15px;">
                                        <?php
                                    } ?>   
                                    <input type="file" class="form-control" name="gr3_path" >
                                    <input type="hidden" name="hgr3_path" value="<?=$studentdetails[0]['gr3_path']?>">
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '160') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="s_f_name" value="<?=$studentdetails[0]['s_f_name']?>"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '160') { echo $langlbl['title'] ; } } ?>*">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $fphnum ?></label>
                                <div class="wrapper1">
                                    <?php $phnum = explode(",", $studentdetails[0]['fatherphn']);
                                    $countfph = count($phnum); 
                                    for($i=0; $i < count($phnum); $i++)
                                    {?>
                                        <div class="input-box row container mb-2">
                                            <?php if($i == 0) { ?>
                                            <input type="text" class="col-sm-10 form-control"  name="fatherphone[]"  placeholder="<?= $fphnum ?>" value="<?= $phnum[$i] ?>">
                                            <button class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></button>
                                            <?php } else { ?>
                                            <input type="text" class="col-sm-10 form-control"  name="fatherphone[]"  placeholder="<?= $fphnum ?>" value="<?= $phnum[$i] ?>">
                                            <a href="#" class="col-sm-2 remove-lnk"><i class="fa fa-minus"></i></a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <!--<div class="input-box row container mb-2">
                                        <input type="text" class="col-sm-10 form-control"  name="fatherphone[]"  placeholder="Father Phone Number">
                                        <button class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></button>
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '161') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="s_m_name" value="<?=$studentdetails[0]['s_m_name']?>" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '161') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $mphnum ?></label>
                                <div class="wrapper">
                                    <?php $phnum1 = explode(",", $studentdetails[0]['motherphn']);
                                    $countmph = count($phnum1); 
                                    for($j=0; $j < count($phnum1); $j++)
                                    {?>
                                        <div class="input-box row container mb-2">
                                            <?php if($j == 0) { ?>
                                            <input type="text" class="col-sm-10 form-control"  name="motherphone[]"  placeholder="<?= $mphnum ?>" value="<?= $phnum1[$j] ?>">
                                            <button class="col-sm-2 btn add-btn"><i class="fa fa-plus"></i></button>
                                            <?php } else { ?>
                                            <input type="text" class="col-sm-10 form-control"  name="motherphone[]"  placeholder="<?= $mphnum ?>" value="<?= $phnum1[$j] ?>">
                                            <a href="#" class="col-sm-2 remove-lnk"><i class="fa fa-minus"></i></a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <!--<div class="input-box row container mb-2">
                                        <input type="text" class="col-sm-10 form-control"  name="motherphone[]"  placeholder="Mother Phone Number">
                                        <button class="col-sm-2 btn add-btn"><i class="fa fa-plus"></i></button>
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '162') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="guardian_name" value="<?=$studentdetails[0]['guardian_name']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '162') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>
							<div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '163') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_occ" value="<?=$studentdetails[0]['f_occ']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '163') { echo $langlbl['title'] ; } } ?> ">
                                </div>
                            </div>
                             <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '164') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="emergency_contact" value="<?=$studentdetails[0]['emergency_number']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '164') { echo $langlbl['title'] ; } } ?>*" required>
                                </div>
                            </div>	
                            <div class="col-sm-3 ">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '165') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="emergency_name" id="emergency_name" value="<?=$studentdetails[0]['emergency_name']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '165') { echo $langlbl['title'] ; } } ?>*" required>
                                    <!--<select class="form-control js-states" id="emergency_name" name="emergency_name">
                                        <option value="Father" <?php echo $studentdetails[0]['emergency_name'] == "Father" ? "selected" : "" ?>><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1783') { echo $langlbl['title'] ; } } ?></option>
                                        <option value="Mother" <?php echo $studentdetails[0]['emergency_name'] == "Mother" ? "selected" : "" ?>><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1784') { echo $langlbl['title'] ; } } ?></option>
                                        <option value="Guardian" <?php echo $studentdetails[0]['emergency_name'] == "Guardian" ? "selected" : "" ?>><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1785') { echo $langlbl['title'] ; } } ?></option>
                                    </select>-->
                                </div>
                            </div>	
                            
                            
							<div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '166') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
							
							<div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '167') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="resi_add1" value="<?=$studentdetails[0]['resi_add1']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '167') { echo $langlbl['title'] ; } } ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '168') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="resi_add2" value="<?=$studentdetails[0]['resi_add2']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '168') { echo $langlbl['title'] ; } } ?>">
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
                                      <option  value="<?=$val['id']?>" <?php if($studentdetails[0]['country']==$val['id']) { ?> selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
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
                                      <option  value="<?=$val['id']?>" <?php if($studentdetails[0]['state']==$val['id']) { ?> selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '171') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="city" value="<?=$studentdetails[0]['city']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '178') { echo $langlbl['title'] ; } } ?>" required>
                                                        
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '172') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone_resi" value="<?=$studentdetails[0]['phone_resi']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '172') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '173') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone_off" value="<?=$studentdetails[0]['phone_off']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '173') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>
							
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1580') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?php $checked = '';
                                    if($studentdetails[0]['library_access'] == 1) { $checked = "checked"; }
                                    ?>
                                    <input type="checkbox" name="lib_access"  id="lib_access" value="1" <?= $checked ?> > <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1579') { echo $langlbl['title'] ; } } ?>
                                </div>
                            </div>
                            
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '230') { echo $langlbl['title'] ; } } ?></h5>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '233') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                     <?php if(!empty($user_details[0])) { ?>
                                    <input type="email" class="form-control"  id= "email" name="email" value="<?=$studentdetails[0]['email']?>"  required placeholder="Student Email *">
                                    <?php } else { ?>
                                    <input type="email" class="form-control" id= "email" name="email" value="<?=$studentdetails[0]['email']?>"  required placeholder="Student Email *">
                                    
                                    <?php } ?>
                                
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?>.</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly name="adm_no" value="<?=$studentdetails[0]['adm_no']?>" required placeholder="Student Number *">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1536') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password" value="<?=$studentdetails[0]['password']?>" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1536') { echo $langlbl['title'] ; } } ?> *" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '175') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" id= "pemail" name="pemail" value="<?=$studentdetails[0]['parentemail']?>"  required placeholder="Student Email *">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1537') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="parentpassword" value="<?=$studentdetails[0]['parentpass']?>" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1537') { echo $langlbl['title'] ; } } ?> *" >
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
                                        <button type="submit" id="editstdntbtn" class="btn btn-primary editstdntbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                   
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
    var x = "<?php echo $countmph ?>";
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
    
    var y = "<?php echo $countfph ?>";
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


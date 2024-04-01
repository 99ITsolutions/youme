<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
</style>
<?php
    $gender = array('Male','Female');
?>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2011') { $male = $langlbl['title'] ; } 
    if($langlbl['id'] == '2012') { $female = $langlbl['title'] ; } 
} ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class=" row">
                    <h2 class="col-md-8 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '236') { echo $langlbl['title'] ; } } ?>
                    <?php if(!empty($student_details)) { ?>
                    <span>(<?= $student_details['f_name']." ".$student_details['l_name'] ?> - <?= $classname ?> (<?= $sessionyr ?>)) </span>
                    <?php } ?>
                    </h2>
                    <h2 class="col-md-1 text-right" style="padding-right:0px;" >
                        <span  id="closesearch" <?= $closeicon ?> onclick="closesearch();" aria-hidden="true">X</span>
                        <a href="javascript:void(0)" title="Back" class=" btn btn-sm btn-success" id="searchstudent"  <?= $searchicon ?> onclick="studentsearch();" ><i class="fa fa-search"></i></a>
                    </h2> 
                    <?php if(!empty($student_details)) { ?>
                        <h2 class="col-md-2 text-right"  style="max-width:14% !important;" <?= $downloadreport ?> ><a href="<?= $baseurl ?>readmissions/report/<?= $student_details['id']?>" class="btn btn-sm btn-success" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1535') { echo $langlbl['title'] ; } } ?></a></h2>
                    <?php } ?>
                    <h2 class="col-md-1"><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
            </div>
            <div class="body" id="gen_pdf">
                <div  id="studentsearch" <?= $style ?> >
                    <div class="col-md-12">
                        <div class="error" id="summryerror"><?= $error ?></div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-5 row" style="max-width:100%; border-right:1px solid #cccccc; margin-right:5px">
                            <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "asummaryreportform" ,  'method' => "post"  ]);  ?>
                            <div class="row col-md-12">
        	                    <div class="col-sm-5">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '237') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="student_no" id="student_no">                       
                                    </div>
        	                    </div>
        	                    <div class="col-sm-5">
        	                        <label> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '238') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control left currntsesssion"  name="start_year"  required>
                                        <option value="">Choose One</option>
                                        <?php
                                        foreach($session_details as $key => $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
        	                    </div>
        	                    <input type="hidden" name="searchform" value="1">
        	                    <div class="col-sm-2">
        	                        <button type="submit" class="btn btn-primary summary_report" id="summary_report" style="margin-top: 1.6rem!important;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
        	                    </div>
    	                    </div>
        	                <?php echo $this->Form->end();?>
        	           </div>
	                    <div class="col-md-7 row"  style="max-width:100%">
    	                   <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
	                        <div class="row col-md-12">
	                            <div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '241') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control currntsesssion" id="select_year" name="start_year" required>
                                        <option value="">Choose One</option>
                                        <?php
                                        foreach($session_details as $key => $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
        	                    </div>
        	                    <div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '239') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control class" id="class" name="class" required onchange="getstudentsessionscl(this.value)">
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
        	                    <div class="col-sm-4">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '240') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control studentchose" name="student" id="student" placeholder="Choose Student" required>
                                            <option value="">Choose Student</option>
                                        </select>
                                    </div>
        	                    </div>
        	                    
        	                    <input type="hidden" name="searchform" value="2">
        	                    <div class="col-sm-2">
        	                        <button type="submit" class="btn btn-primary mt-4 clsummary_report" id="clsummary_report"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
        	                    </div>
    	                    </div>
    	                    <?php echo $this->Form->end();?>
    	               </div>
                    </div>
                </div>
                <div class="row clearfix" id= "reportdata" <?= $viewpage ?> >
                    <?php echo $this->Form->create(false , ['url' => ['action' => 'addadm'] , 'id' => "updateadmform", 'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>
                    <div class=" container row ">
                    <input type="hidden" name="id" value="<?=$student_details['id']?>" >
                    <input type="hidden" name="school_id" value="<?=$student_details['school_id']?>" >
                    
                    <div class="col-sm-12 mb-2">
                        <!--<h5 class="iconsss">Basic Information</h5>-->
                    </div>
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?>.</label>
                        <div class="form-group">
                            <input type="text" class="form-control" readonly name="adm_no" value="<?=$student_details['adm_no']?>" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '238') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                                
                            <select class="form-control currntsesssion" name="sessionid" id="sessionid" required>
                                <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '191') { echo $langlbl['title'] ; } } ?></option>
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
                	<div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '150') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control class_s" id="class_s" name="class_s" required onchange="grades(this.value)">
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
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '10') { echo $langlbl['title'] ; } } ?>*</label>
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
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '141') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <?php 
                            if(!empty($student_details['pic']))
                            { ?>
                                <img src="<?= $baseurl ?>webroot/img/<?=$student_details['pic']?>" width="70px" height="45px" style="margin-bottom:15px;">
                                <?php 
                            }else
                            {
                                ?>
                                    <img src="<?= $baseurl ?>webroot/img/notimg.png" width="70px" height="45px" style="margin-bottom:15px;">
                                <?php
                            } ?>
                            <input type="file" class="form-control" name="picture" >
                            <input type="hidden" name="hpicture" value="<?=$student_details['pic']?>">
                            <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                        </div>
                    </div>
                    <div class="col-sm-6"></div>
                            
                    
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '144') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="l_name" value="<?=$student_details['l_name']?>"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '144') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '143') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="f_name" value="<?=$student_details['f_name']?>"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '143') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '145') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control backdatepicker" id="dobdatepicker" data-date-format="dd-mm-yyyy" name="dob" value="<?= date('d-m-Y',strtotime($student_details['dob']))?>" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '145') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
					<div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '146') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="s_age" name="s_age" value="<?=$student_details['s_age']?>"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '148') { echo $langlbl['title'] ; } } ?> ">
                        </div>
                    </div>		
					<div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '149') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <select class="form-control gender" id="gender" name="gender">
                                <option value="">Choose One</option>
                                <option value="Male" <?php if($student_details['gender']== "Male") {?> selected="true" <?php } ?>><?= $male ?></option>
                                <option value="Female" <?php if($student_details['gender']== "Female") {?> selected="true" <?php } ?>><?= $female ?></option>
                                
                            </select>
                        </div>
                    </div>
					
					<div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '151') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="bloodgroup" value="<?=$student_details['bloodgroup']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '151') { echo $langlbl['title'] ; } } ?>">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '152') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" maxlength="15" name="mobile_for_sms" value="<?=$student_details['mobile_for_sms']?>" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '152') { echo $langlbl['title'] ; } } ?>*" id="mobile_for_sms">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '153') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="national" value="<?=$student_details['national']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '153') { echo $langlbl['title'] ; } } ?> ">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label>You-Me Phone Number</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="contactyoume" placeholder="You-Me Phone Number" value="<?=$student_details['contactyoume']?>" >
                        </div>
                    </div>
					<div class="col-sm-12 mb-2 mt-2">
                        <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '155') { echo $langlbl['title'] ; } } ?></h5>
                    </div>
					
					<div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '156') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <?php 
                            if(!empty($student_details['gr1_path']))
                            { ?>
                                <img src="<?= $baseurl ?>webroot/img/<?=$student_details['gr1_path']?>" width="70px" height="45px" style="margin-bottom:15px;">
                                <?php 
                            }else
                            {
                                ?>
                                    <img src="<?= $baseurl ?>webroot/img/notimg.png" width="70px" height="45px" style="margin-bottom:15px;">
                                <?php
                            } ?>
                            <input type="file" class="form-control" name="gr1_path"  >
                            <input type="hidden" name="hgr1_path" value="<?=$student_details['gr1_path']?>">
                            <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '157') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <?php 
                            if(!empty($student_details['gr2_path']))
                            { ?>
                                <img src="<?= $baseurl ?>webroot/img/<?=$student_details['gr2_path']?>" width="70px" height="45px" style="margin-bottom:15px;">
                                <?php 
                            }else
                            {
                                ?>
                                    <img src="<?= $baseurl ?>webroot/img/notimg.png" width="70px" height="45px" style="margin-bottom:15px;">
                                <?php
                            } ?>  
                            
                            <input type="file" class="form-control" name="gr2_path"  >
                            <input type="hidden" name="hgr2_path" value="<?=$student_details['gr2_path']?>">
                            <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '158') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <?php 
                            if(!empty($student_details['gr3_path']))
                            { ?>
                                <img src="<?= $baseurl ?>webroot/img/<?=$student_details['gr3_path']?>" width="70px" height="45px" style="margin-bottom:15px;">
                                <?php 
                            }else
                            {
                                ?>
                                    <img src="<?= $baseurl ?>webroot/img/notimg.png" width="70px" height="45px" style="margin-bottom:15px;">
                                <?php
                            } ?>   
                            <input type="file" class="form-control" name="gr3_path" >
                            <input type="hidden" name="hgr3_path" value="<?=$student_details['gr3_path']?>">
                            <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '160') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="s_f_name" value="<?=$student_details['s_f_name']?>"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '160') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label>Father Phone Number</label>
                        <div class="wrapper1">
                                    <?php $phnum = explode(",", $student_details['fatherphn']);
                                    //print_r($phnum);
                                    $countfph = count($phnum); 
                                    for($i=0; $i < count($phnum); $i++)
                                    {?>
                                        <div class="input-box row container mb-2">
                                            <?php if($i == 0) { ?>
                                            <input type="text" class="col-sm-10 form-control"  name="fatherphone[]"  placeholder="Father Phone Number" value="<?= $phnum[$i] ?>">
                                            <button class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></button>
                                            <?php } else { ?>
                                            <input type="text" class="col-sm-10 form-control"  name="fatherphone[]"  placeholder="Father Phone Number" value="<?= $phnum[$i] ?>">
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
                            <input type="text" class="form-control" name="s_m_name" value="<?=$student_details['s_m_name']?>" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '161') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label>Mother Phone Number</label>
                        <!--<div class="wrapper">
                            <div class="input-box row container mb-2">
                                <input type="text" class="col-sm-10 form-control"  name="motherphone[]"  placeholder="Mother Phone Number">
                                <button class="col-sm-2 btn add-btn"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>-->
                        <div class="wrapper">
                            <?php $phnum1 = explode(",", $student_details['motherphn']);
                             //print_r($phnum1);
                            $countmph = count($phnum1); 
                            for($j=0; $j < count($phnum1); $j++)
                            {?>
                                <div class="input-box row container mb-2">
                                    <?php if($j == 0) { ?>
                                    <input type="text" class="col-sm-10 form-control"  name="motherphone[]"  placeholder="Mother Phone Number" value="<?= $phnum1[$j] ?>">
                                    <button class="col-sm-2 btn add-btn"><i class="fa fa-plus"></i></button>
                                    <?php } else { ?>
                                    <input type="text" class="col-sm-10 form-control"  name="motherphone[]"  placeholder="Mother Phone Number" value="<?= $phnum1[$j] ?>">
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
                            <input type="text" class="form-control" name="guardian_name" value="<?=$student_details['guardian_name']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '162') { echo $langlbl['title'] ; } } ?> ">
                        </div>
                    </div>
					<div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '163') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="f_occ" value="<?=$student_details['f_occ']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '163') { echo $langlbl['title'] ; } } ?> ">
                        </div>
                    </div>
                     <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '164') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="emergency_contact" value="<?=$student_details['emergency_number']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '164') { echo $langlbl['title'] ; } } ?>*" required>
                        </div>
                    </div>	
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '165') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="emergency_name" value="<?=$student_details['emergency_name']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '165') { echo $langlbl['title'] ; } } ?>*" required>
                            <!--<select class="form-control js-states" id="emergency_name" name="emergency_name">
                                <option value="Father" <?php //echo $student_details['emergency_name'] == "Father" ? "selected" : "" ?>>Father</option>
                                <option value="Mother" <?php //echo $student_details['emergency_name'] == "Mother" ? "selected" : "" ?>>Mother</option>
                                <option value="Guardian" <?php //echo $student_details['emergency_name'] == "Guardian" ? "selected" : "" ?>>Guardian</option>
                            </select>-->
                        </div>
                    </div>	
                            
                            
					<div class="col-sm-12 mb-2 mt-2">
                        <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '166') { echo $langlbl['title'] ; } } ?></h5>
                    </div>
					
					<div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '167') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="resi_add1" value="<?=$student_details['resi_add1']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '167') { echo $langlbl['title'] ; } } ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '168') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="resi_add2" value="<?=$student_details['resi_add2']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '168') { echo $langlbl['title'] ; } } ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '169') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                        <select class="form-control countries country" id="country" name="admcountry" required>
                            <option value="">Choose Country</option>
                            <?php
                            foreach($country_details as $val){
                            ?>
                              <option  value="<?=$val['id']?>" <?php if($student_details['country']==$val['id']) { ?> selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
                            <?php
                            }
                            ?>
                        </select>                                    
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '170') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                        <select class="form-control state" id="state" name="admstate" required>
                            <option value="">Choose State</option>
                            <?php
                            foreach($state_details as $val){
                            ?>
                              <option  value="<?=$val['id']?>" <?php if($student_details['state']==$val['id']) { ?> selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
                            <?php
                            }
                            ?>
                        </select>                                    
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '171') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="admcity" value="<?=$student_details['city']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '178') { echo $langlbl['title'] ; } } ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '172') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone_resi" value="<?=$student_details['phone_resi']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '172') { echo $langlbl['title'] ; } } ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '173') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone_off" value="<?=$student_details['phone_off']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '173') { echo $langlbl['title'] ; } } ?>">
                        </div>
                    </div>
                            
                    <div class="col-sm-12 mb-2 mt-2">
                        <h5 class="iconsss"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '174') { echo $langlbl['title'] ; } } ?></h5>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '233') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="email" class="form-control" readonly id= "email" name="email" value="<?=$student_details['email']?>"  required placeholder="Student Email *">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1536') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" readonly name="password" value="<?=$student_details['password']?>" required placeholder="Password *">
                        </div>
                    </div>
                    <!--<div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1537') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="parentpassword" value="<?=$student_details['parent_password']?>" required placeholder="Password *">
                        </div>
                    </div>-->
                    
                    <?php /***********/ ?>
                    
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '175') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="email" class="form-control" readonly id= "pemail" name="pemail" value="<?=$student_details['parentemail']?>"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '175') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1537') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" readonly name="parentpassword" value="<?=$student_details['parentpass']?>" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1537') { echo $langlbl['title'] ; } } ?> *" >
                        </div>
                    </div>
                    <input type="hidden" name="parentid" value="<?= $student_details['parent_id'] ?>" >
                            
                    <div class="col-sm-12">
                        <div class="error" id="stdnterror">
                        </div>
                        <div class="success" id="stdntsuccess">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            
                            <div class="mt-4 ml-4">
                                <button type="submit" id="addstdntbtn" class="btn btn-primary addstdntbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2156') { echo $langlbl['title'] ; } } ?></button>
                            </div>
                        </div>
                    </div>
                </div>   
                <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>


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
                    <input type="text" class="col-sm-10 form-control"  name="motherphone[]"  placeholder="Mother Phone Number">
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
                <input type="text" class="col-sm-10 form-control"  name="fatherphone[]"  placeholder="Father Phone Number">
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
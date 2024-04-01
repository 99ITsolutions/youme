<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
    foreach($lang_label as $langlbl) { if($langlbl['id'] == '296') { $lbl296 = $langlbl['title'] ; } }
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">View Teacher</h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>teachers" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '294') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group"> 
                            <img src="../../img/<?=$teacher_details[0]['pict']?>" width="70px" height="45px" style="margin-bottom:15px;">                                               
                        </div>
                    </div>
                    <div class="col-sm-8"></div>
                    
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '285') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" readonly class="form-control" name="l_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '285') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['l_name']?>"> 
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '284') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" readonly class="form-control" name="f_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '284') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['f_name']?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1538') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" readonly class="form-control" name="fathers_name"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1538') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['fathers_name']?>">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" readonly class="form-control" name="qualification"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['quali']?>">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '297') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" readonly class="form-control dobirthdatepicker" data-date-format="dd-mm-yyyy" name="dob" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '297') { echo $langlbl['title'] ; } } ?> d-m-Y*" value="<?= date('d-m-Y', strtotime( $teacher_details[0]['dob']))?>">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '288') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" readonly class="form-control backdatepicker" data-date-format="dd-mm-yyyy" name="doj"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '288') { echo $langlbl['title'] ; } } ?> d-m-Y*" value="<?= date('d-m-Y', strtotime($teacher_details[0]['doj']))?>">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label>You-Me Phone Number</label>
                        <div class="form-group">
                            <input type="text" readonly class="form-control" name="contactyoume" placeholder="You-Me Phone Number" value="<?=$teacher_details[0]['contact_youme']?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '295') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="wrapper1">
                        <?php $phnum = explode(",", $teacher_details[0]['mobile_no']);
                        $countph = count($phnum); 
                        for($i=0; $i < count($phnum); $i++)
                        {?>
                            <div class="input-box row container mb-2">
                                <?php if($i == 0) { ?>
                                 <input type="text" readonly class="col-sm-10 form-control"  name="phone[]" required  placeholder="<?= $lbl296 ?>*" value="<?= $phnum[$i] ?>">
                                <?php } else { ?>
                                <input type="text" readonly class="col-sm-10 form-control"  name="phone[]"  placeholder="<?= $lbl296 ?>*" value="<?= $phnum[$i] ?>">
                                <?php } ?>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '298') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="wrapper">
                        <?php $grades = explode(",", $teacher_details[0]['grades']) ;
                        $wrapperdiv = count($grades);
                        for($i=0; $i<count($grades); $i++)
                        { 
                            $j = $i+1;
                            $tchrid = $teacher_details[0]['id'];
                            $hostname = "localhost";
                                $username = "youmeglo_globaluser";
                                $password = "DFmp)9_p%Kql";
                                $database = "youmeglo_globalweb";
                            $con = mysqli_connect($hostname, $username, $password, $database); 
                            if(mysqli_connect_error($con)){ echo "Connection Error."; die();}
                             mysqli_set_charset( $conn, 'utf8');
                            $getsubjects = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `class_subjects` WHERE `class_id` = '$grades[$i]' "));
                            
                            $gettchrsubjcts = mysqli_query($con, $a = "SELECT * FROM `employee_class_subjects` WHERE `class_id` = '$grades[$i]' AND `emp_id` = '$tchrid' ");
                            $tsub = [];
                            while($tchrsub = mysqli_fetch_assoc($gettchrsubjcts))
                            {
                                //print_r($tchrsub);
                                $tsub[] = $tchrsub['subject_id'];
                            }
                            $subjects = explode(",", $getsubjects['subject_id']);
                            $subid = implode("','", $subjects);
                            $getclssubjects = mysqli_query($con, $q= "SELECT * FROM `subjects` WHERE `id` IN ('".$subid."') ");
                            
                            $getcls = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `class` WHERE `id` = '$grades[$i]' "));
                            $sectn = $getcls['school_sections'];
                            ?>
                        
                            <div class="input-box row container mb-2">
                                <select class="col-sm-4 form-control clsgrade" disabled id="clsgrade<?= $j ?>" name="grades[]" style="margin-right:15px">
                                        <option value="">Choose Grade</option>
                                        <?php
                                        foreach($class_details as $key => $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" <?php if($val['id'] == $grades[$i]) { ?> selected="true" <?php } ?> ><?php echo $val['c_name'] ."-" . $val['c_section']. " (". $val['school_sections']. ")";?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php $subjects = explode(",", $teacher_details[0]['subjects']) ?>
                                <select class="col-sm-4  form-control subgrade" disabled id="subgrade<?= $j ?>" name="subjects<?= $j ?>[]" multiple >
                                    <option value="">Choose Subjects</option>
                                    <?php if($sectn == "Maternelle" || $sectn == "Primaire" || $sectn == "Creche" ) { ?>
                                    <option value="all">All</option>
                                    <?php } ?>
                                    <?php
                                    while($val = mysqli_fetch_assoc($getclssubjects))
                                    { ?>
                                      <option  value="<?=$val['id']?>" <?php if(in_array($val['id'], $tsub)) { ?> selected="true" <?php } ?> ><?php echo utf8_encode($val['subject_name']);?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '301') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" readonly class="form-control" name="address"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '301') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['address']?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '302') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group"> 
                        <select class="form-control countries country" disabled id="country" name="country" required>
                            <option value="">Choose Country</option>
                            <?php 
                            foreach($country_details as $val){
                                	$sel = $teacher_details[0]['country'] == $val['id'] ? "selected" : "";
                            ?>
                              <option  value="<?=$val['id']?>"  <?php if($teacher_details[0]['country'] == $val['id']){ ?>selected="true" <?php } ?>><?php echo $val['name'];?> </option>
                            <?php
                            }
                            ?>
                        </select>                                    
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '303') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group" >
                        <select class="form-control state states" disabled id="state" name="state" required>
                            <option selected="selected">Choose State</option>
                            <?php
                            foreach($state_details as $val){
								$sel = $teacher_details[0]['state'] == $val['id'] ? "selected" : "";
                            ?>
                                
                                <option  value="<?=$val['id']?>" <?php if($teacher_details[0]['state'] == $val['id']){ ?>selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
                            <?php
                            }
                            ?>
                        </select>                                    
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '304') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group" >
                            <input type="text" readonly class="form-control city" id="city" name="city" placeholder="Enter City"  value="<?php echo $teacher_details[0]['city']?>">                       
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '305') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" readonly  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '305') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['email']?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '306') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" readonly class="form-control" name="password"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '306') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['password']?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
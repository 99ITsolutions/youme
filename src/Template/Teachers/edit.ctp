<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
    foreach($lang_label as $langlbl) { if($langlbl['id'] == '296') { $lbl296 = $langlbl['title'] ; }
    if($langlbl['id'] == '2062') { $lbl2062 = $langlbl['title'] ; } 
        
    }
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1539') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>teachers" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'edittchr'] , 'id' => "edittchrform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <input type="hidden" name="id" value="<?=$teacher_details[0]['id']?>">
                            
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '294') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group"> 
                                    <img src="../../img/<?=$teacher_details[0]['pict']?>" width="70px" height="45px" style="margin-bottom:15px;">                                               
                                    <input type="file" class="form-control" name="picture"  >
                                    <input type="hidden" name="apicture" value="<?=$teacher_details[0]['pict']?>">
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-8">    
                            </div>
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '285') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '285') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['l_name']?>"> 
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '284') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '284') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['f_name']?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1538') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fathers_name"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1538') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['fathers_name']?>">
                                </div>
                            </div>
                            
                            
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="qualification"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['quali']?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '297') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control dobirthdatepicker" data-date-format="dd-mm-yyyy" name="dob" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '297') { echo $langlbl['title'] ; } } ?> d-m-Y*" value="<?= date('d-m-Y', strtotime( $teacher_details[0]['dob']))?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '288') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control backdatepicker" data-date-format="dd-mm-yyyy" name="doj"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '288') { echo $langlbl['title'] ; } } ?> d-m-Y*" value="<?= date('d-m-Y', strtotime($teacher_details[0]['doj']))?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $lbl2062 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="contactyoume" placeholder="<?= $lbl2062 ?>" value="<?=$teacher_details[0]['contact_youme']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '295') { echo $langlbl['title'] ; } } ?>*</label>
                                <!--<div class="form-group">
                                    <input type="text" class="form-control" name="phone"  required placeholder="<?= $lbl296 ?>*" value="<?=$teacher_details[0]['mobile_no']?>">
                                </div>-->
                                <div class="wrapper1">
                                <?php $phnum = explode(",", $teacher_details[0]['mobile_no']);
                                $countph = count($phnum); 
                                for($i=0; $i < count($phnum); $i++)
                                {?>
                                    <div class="input-box row container mb-2">
                                        
                                         <?php if($i == 0) { ?>
                                         <input type="text" class="col-sm-10 form-control"  name="phone[]" required  placeholder="<?= $lbl296 ?>*" value="<?= $phnum[$i] ?>">
                                        <button class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></button>
                                        <?php } else { ?>
                                        <input type="text" class="col-sm-10 form-control"  name="phone[]"  placeholder="<?= $lbl296 ?>*" value="<?= $phnum[$i] ?>">
                                        <a href="#" class="col-sm-2 remove-lnk"><i class="fa fa-minus"></i></a>
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
                                        <select class="col-sm-4 form-control clsgrade" id="clsgrade<?= $j ?>" name="grades[]" style="margin-right:15px">
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
                                                      <option  value="<?=$val['id']?>" <?php if($val['id'] == $grades[$i]) { ?> selected="true" <?php } ?> ><?php echo $val['c_name'] ."-" . $val['c_section']. " (". $val['school_sections']. ")";?> </option>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        <?php $subjects = explode(",", $teacher_details[0]['subjects']) ?>
                                        <select class="col-sm-4  form-control subgrade" id="subgrade<?= $j ?>" name="subjects<?= $j ?>[]" multiple >
                                            <option value="">Choose Subjects</option>
                                            <?php if($sectn == "Maternelle" || $sectn == "Primaire" || $sectn == "Creche" ) { ?>
                                            <option value="all">All</option>
                                            <?php } ?>
                                            <?php
                                            while($val = mysqli_fetch_assoc($getclssubjects))
                                            {
                                               
                                            ?>
                                              <option  value="<?=$val['id']?>" <?php if(in_array($val['id'], $tsub)) { ?> selected="true" <?php } ?> ><?php echo utf8_encode($val['subject_name']);?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <?php if($i == 0) { ?>
                                        <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>
                                        <?php } else { ?>
                                        <a href="#" class="col-sm-1 remove-lnk"><i class="fa fa-minus"></i></a>
                                        <?php } ?>
                                       
                                    </div>
                                
                                <?php
                                } ?>
                                </div>
                            </div>
                            
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '301') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '301') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['address']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '302') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group"> 
                                <select class="form-control countries country" id="country" name="country" required>
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
                                <select class="form-control state states" id="state" name="state" required>
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
                                    <input type="text" class="form-control city" id="city" name="city" placeholder="Enter City"  value="<?php echo $teacher_details[0]['city']?>">                       
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '305') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <?php if(!empty($user_details[0])) { ?>
                                    <input type="email" class="form-control" name="email"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '305') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['email']?>">
                                    <?php } else { ?>
                                    <input type="email" class="form-control" name="email"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '305') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['email']?>">
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '306') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '306') { echo $langlbl['title'] ; } } ?> *" value="<?=$teacher_details[0]['password']?>">
                                </div>
                            </div>
                           
                            <div class="col-sm-12">
                                <div class="error" id="tchrerror">
                                </div>
                                <div class="success" id="tchrsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="edittchrbtn" class="btn btn-primary"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                   <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>    

<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '299') { $gradessch =  $langlbl['title'] ; } 
if($langlbl['id'] == '300') { $subjectssch =  $langlbl['title'] ; }
} ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
$(document).ready(function () {
      var max_input1 = 3;
      var y = "<?php echo $countph ?>";
      $('.add-btn1').click(function (e) {
        e.preventDefault();
        
        if (y < max_input1) {
            y++;
          $('.wrapper1').append(`
                <div class="input-box row container mb-2">
                        <input type="text" class="col-sm-10 form-control" name="phone[]"  placeholder="<?php echo $lbl296 ?>*">
                        <a href="#" class="col-sm-2 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper1').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      });
    });
$(document).ready(function () {
 
      // allowed maximum input fields
      var max_input = 45;
 
      // initialize the counter for textbox
      //var x = 1;
      var x =  <?php echo $wrapperdiv ?>;
      // handle click event on Add More button
      $('.add-btn').click(function (e) {
        e.preventDefault();
        if (x < max_input) { // validate the condition
          x++; // increment the counter
          $('.wrapper').append(`
                <div class="input-box row container">
                    <select class="col-sm-4 form-control  clsgrade" id="clsgrade`+x+`" name="grades[]" style="margin-right:15px">
                        <option value=""><?php echo $gradessch ?></option>
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
                    <select class="col-sm-4 form-control subgrade" id="subgrade`+x+`"  name="subjects`+x+`[]" multiple>
                        <option value=""><?php echo $subjectssch ?></option>
                    </select>
                    <a href="#" class="col-sm-1 remove-lnk"><i class="fa fa-minus"></i></a>
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper').on("click", ".remove-lnk", function (e) {
          //alert(this);
        e.preventDefault();
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      })
      
      
      $('.wrapper').on("change", ".clsgrade", function (e) {
        
        var gradeid = $(this).val()+"_teacher";
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var id = this.id;
        var splitid = id.split("clsgrade");
        //alert(splitid[1]);
        $("#subgrade"+splitid[1]).html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/SchoolLibrary/getsubjects',
            data:{'classId':gradeid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html)
                {    
                    $("#subgrade"+splitid[1]).html(html);
                }
          
            }

        });
    });
 
    });
     
</script>

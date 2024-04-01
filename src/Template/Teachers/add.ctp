<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
?>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '296') { $lbl296 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2062') { $lbl2062 = $langlbl['title'] ; } 
} ?>
<style>
    .hide
    {
        display:none;
    }
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '293') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>teachers" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'addtchr'] , 'id' => "addtchrform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '294') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="picture"  >
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-8"></div>
                               <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '285') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '285') { echo $langlbl['title'] ; } } ?>*">
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '284') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '284') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                         
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1538') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="father_name"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1538') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="qualification"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '297') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control dobirthdatepicker" data-date-format="dd-mm-yyyy" name="dob"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '297') { echo $langlbl['title'] ; } } ?> d-m-Y*">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '288') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control backdatepicker" data-date-format="dd-mm-yyyy" name="doj"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '288') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label><?= $lbl2062 ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="contactyoume" placeholder="<?= $lbl2062 ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '295') { echo $langlbl['title'] ; } } ?>*</label>
                                <!--<div class="form-group">
                                    <input type="text" class="form-control" name="phone"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '296') { echo $langlbl['title'] ; } } ?>*">
                                </div>-->
                                <div class="wrapper1">
                                    <div class="input-box row container mb-2">
                                        <input type="text" class="col-sm-9 form-control"  name="phone[]" required  placeholder="<?= $lbl296 ?>*">
                                        <button class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '298') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="wrapper">
                                    <div class="input-box row container mb-2">
                                        <!--<div class="col-sm-5">-->
                                        <select class="col-sm-4 form-control clsgrade" id="clsgrade1" name="grades[]" style="margin-right:15px !important;">
                                            <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '299') { echo $langlbl['title'] ; } } ?></option>
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
                                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'] . " (". $val['school_sections']. ")";?> </option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                       <!-- </div>
                                       <div class="">-->
                                        <select class="col-sm-4 form-control subgrade" id="subgrade1" name="subjects1[]" multiple >
                                            <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '300') { echo $langlbl['title'] ; } } ?></option>
                                            
                                        </select>
                                         <!--</div>
                                         <div class="col-sm-2">-->
                                        <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>
                                         <!--</div>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '301') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address"   placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '301') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>
                            <div class="container row ">
                                <div class="col-sm-4">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '302') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">
                                    <select class="form-control countries country" id="country" name="country" >
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
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '303') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">
                                    <select class="form-control state" id="state" name="state" >
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
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '304') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group" >
                                        <input type="text" class="form-control city" id="city" name="city" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '304') { echo $langlbl['title'] ; } } ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="container row ">
                                <div class="col-sm-6">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '305') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '305') { echo $langlbl['title'] ; } } ?> *">
                                    </div>
                                </div>
                            </div>
                         
                            <input type="hidden" name="meeting_link" id="meeting_link">
                            <div class="col-sm-12">
                                <div class="error" id="tchrerror">
                                </div>
                                <div class="success" id="tchrsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="addtchrbtn" class="btn btn-primary"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '307') { echo $langlbl['title'] ; } } ?></button>
                   
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->Form->end(); ?>
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
      var y = 1;
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
      var x = 1;
 
      // handle click event on Add More button
      $('.add-btn').click(function (e) {
        e.preventDefault();
        if (x < max_input) { // validate the condition
          x++; // increment the counter
          
          $('.wrapper').append(`
                <div class="input-box row container mb-2">
                    
                        <select class="col-sm-4 form-control clsgrade" id="clsgrade`+x+`" name="grades[]" style="margin-right:15px">
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
                    
                        <a href="#" class="col-sm-1 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                   
                    
                    
                    
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper').on("click", ".remove-lnk", function (e) {
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
  
      })
 
    });
</script>
<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Edit Teacher</h2>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'edittchr'] , 'id' => "edittchrform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <input type="hidden" name="id" value="<?=$teacher_details[0]['id']?>">
                            
                            
                            <div class="col-sm-4">
                                <label>Picture*</label>
                                <div class="form-group"> 
                                    <img src="../../img/<?=$teacher_details[0]['pict']?>" width="70px" height="45px" style="margin-bottom:15px;">                                               
                                    <input type="file" class="form-control" name="picture"  >
                                    <input type="hidden" name="apicture" value="<?=$teacher_details[0]['pict']?>">
                                    <small id="fileHelp" class="form-text text-muted">Only jpg, png format files are allowed</small>
                                </div>
                            </div>
                            <div class="col-sm-8">    
                            </div>
                            <div class="col-sm-4">
                                <label>First Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name"  required placeholder="First Name *" value="<?=$teacher_details[0]['f_name']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Last Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name"  required placeholder="Last Name *" value="<?=$teacher_details[0]['l_name']?>"> 
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Father Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fathers_name"  placeholder="Father Name *" value="<?=$teacher_details[0]['fathers_name']?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label>Phone*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone"  required placeholder="Phone Number*" value="<?=$teacher_details[0]['mobile_no']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Qualification*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="qualification"  required placeholder="Qualification *" value="<?=$teacher_details[0]['quali']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Date of Birth</label>
                                <div class="form-group">
                                    <input type="text" class="form-control backdatepicker" data-date-format="dd-mm-yyyy" name="dob" placeholder="Date Of Birth d-m-Y*" value="<?= date('d-m-Y', strtotime( $teacher_details[0]['dob']))?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Date of Joining*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control backdatepicker" data-date-format="dd-mm-yyyy" name="doj"  required placeholder="Date Of Joining d-m-Y*" value="<?= date('d-m-Y', strtotime($teacher_details[0]['doj']))?>">
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>Grades and Subjects*</label>
                                <div class="wrapper">
                                <?php $grades = explode(",", $teacher_details[0]['grades']) ;
                                for($i=0; $i<count($grades); $i++)
                                { ?>
                                
                                    <div class="input-box row container">
                                        <select class="col-sm-4 form-group form-control " name="grades[]" style="margin-right:15px">
                                            <option value="">Choose Grade</option>
                                            <?php
                                            foreach($class_details as $key => $val){
                                            ?>
                                              <option  value="<?=$val['id']?>" <?php if($val['id'] == $grades[$i]) { ?> selected="true" <?php } ?> ><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <?php $subjects = explode(",", $teacher_details[0]['subjects']) ?>
                                        <select class="col-sm-4 form-group form-control " name="subjects[]"  >
                                            <option value="">Choose Subjects</option>
                                            <?php
                                            foreach($subject_details as $key => $val){
                                            ?>
                                              <option  value="<?=$val['id']?>" <?php if($val['id'] == $subjects[$i]) { ?> selected="true" <?php } ?> ><?php echo $val['subject_name'];?> </option>
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
                            <!--<div class="col-sm-4">
                                <label>Grades*</label>
                                <div class="form-group">
                                    <?php $grades = explode(",", $teacher_details[0]['grades']) ?>
                                    <select class="form-control js-example-basic-multiple" multiple="multiple" name="grades[]" id="grades" >
                                        <option value="">Choose Grade</option>
                                        <?php
                                        foreach($class_details as $key => $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" <?php if(in_array($val['id'], $grades)) { ?> selected="true" <?php } ?> ><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label>Subjects*</label>
                                <div class="form-group">
                                    <?php $subjects = explode(",", $teacher_details[0]['subjects']) ?>
                                    <select class="form-control js-example-basic-multiple" multiple="multiple" name="subjects[]" id="subjects" >
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
                            </div>-->
                            <div class="col-sm-12">
                                <label>Address*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address"  required placeholder="Teacher Address *" value="<?=$teacher_details[0]['address']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Country*</label>
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
                                <label>State</label>
                                <div class="form-group" >
                                <select class="form-control state states" id="state" name="state" >
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
                                <label>City</label>
                                <div class="form-group" >
                                    <input type="text" class="form-control city" id="city" name="city" placeholder="Enter City"  value="<?php echo $teacher_details[0]['city']?>">                       
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Email*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  required placeholder="Email *" value="<?=$teacher_details[0]['email']?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Password*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password"  required placeholder="Password *" value="<?=$teacher_details[0]['password']?>">
                                </div>
                            </div>
                            <!--<div class="col-sm-4">
                                <label>Status*</label>
                                <div class="form-group">
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">Choose Status</option>
                                        <option value="1" <?php echo $teacher_details[0]['status'] == '1' ? "Selected" : "" ?>>Active</option>
                                        <option value="0" <?php echo $teacher_details[0]['status'] == '0' ? "Selected" : "" ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>-->
                            
                            <div class="col-sm-12">
                                <div class="error" id="tchrerror">
                                </div>
                                <div class="success" id="tchrsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="edittchrbtn" class="btn btn-primary">Update</button>
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
 
      // allowed maximum input fields
      var max_input = 15;
 
      // initialize the counter for textbox
      var x = 1;
 
      // handle click event on Add More button
      $('.add-btn').click(function (e) {
        e.preventDefault();
        if (x < max_input) { // validate the condition
          x++; // increment the counter
          $('.wrapper').append(`
                <div class="input-box row container">
                    <select class="col-sm-4 form-group form-control" name="grades[]" style="margin-right:15px">
                        <option value="">Choose Grade</option>
                        <?php
                        foreach($class_details as $key => $val){
                        ?>
                          <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
                        <?php
                        }
                        ?>
                    </select>
                    <select class="col-sm-4 form-group form-control" name="subjects[]" >
                        <option value="">Choose Subjects</option>
                        <?php
                        foreach($subject_details as $key => $val){
                        ?>
                          <option  value="<?=$val['id']?>" ><?php echo $val['subject_name'];?> </option>
                        <?php
                        }
                        ?>
                    </select>
                    <a href="#" class="col-sm-1 remove-lnk"><i class="fa fa-minus"></i></a>
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
 
    });
</script>

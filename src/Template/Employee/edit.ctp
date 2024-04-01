<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Edit Employee</h2>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'editemp'] , 'id' => "editempform", 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <input type="hidden" name="id" value="<?=$emp_details[0]['id']?>">
                            
                            
                            <div class="col-sm-4">
                                <label>Picture</label>
                                <div class="form-group"> 
				<?php 
                                    if(!empty($emp_details[0]['pict']))
                                    { ?> 	
                                    <img src="../../img/<?=$emp_details[0]['pict']?>" width="70px" height="45px" style="margin-bottom:15px;"> 
				<?php 
                                    } 
				   else
                                    {
                                        ?> 	
                                            <img src="../../img/notimg.png" width="70px" height="45px" style="margin-bottom:15px;">
                                        <?php
                                    } ?>                                              
                                    <input type="file" class="form-control" name="picture"  >
                                    <input type="hidden" name="apicture" value="<?=$emp_details[0]['pict']?>">
                                    <small id="fileHelp" class="form-text text-muted">Only jpg, png format files are allowed</small>
                                </div>
                            </div>
                            <div class="col-sm-8">    
                            </div>
                            <div class="col-sm-4">
                                <label>Code*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="e_code"  required placeholder="Employee Code *" value="<?=$emp_details[0]['e_code']?>">
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="e_name"  required placeholder="Employee Name *" value="<?=$emp_details[0]['e_name']?>"> 
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Father Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name"   placeholder="Father Name " value="<?=$emp_details[0]['f_name']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Address</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address"   placeholder="Employee Address " value="<?=$emp_details[0]['address']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Phone *</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone"   placeholder="Phone Number" value="<?=$emp_details[0]['mobile_no']?>" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Qualification</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="qualification"  placeholder="Qualification " value="<?=$emp_details[0]['quali']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Department Name*</label>
                                <div class="form-group">
                                    <select class="form-control department" id="department_name" name="department_name" required>
                                        <option selected="selected">Choose One</option>
                                        <?php
                                        foreach($dprtmnt_details as $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" <?php if ($emp_details[0]['department_name']==$val['id']){ ?> selected="true" <?php }?> ><?php echo $val['dprt_name'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <label>Date of Birth</label>
                                <div class="form-group">
                                    <input type="text" class="form-control backdatepicker" data-date-format="dd-mm-yyyy" name="dob"   placeholder="Date Of Birth d-m-Y" value="<?= date('d-m-Y', strtotime( $emp_details[0]['dob']))?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Date of Joining</label>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" name="doj"   placeholder="Date Of Joining d-m-Y" value="<?= date('d-m-Y', strtotime($emp_details[0]['doj']))?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Email*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  required placeholder="Employee Email *" value="<?=$emp_details[0]['email']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Password*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password"  required placeholder="Password *" value="<?=$emp_details[0]['password']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Teaching Staff</label>
                                <div class="form-group">
                                    <select class="form-control teacher" id="teacher" name="teacher" >
                                        <option selected="selected">Choose One</option>
                                        <?php
                                        foreach($tchr as $key => $val){
                                        ?>
                                          <option  value="<?=$key?>" <?php if ($emp_details[0]['teachers']==$key){ ?> selected="true" <?php }?> ><?php echo $val;?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Employee Left</label>
                                <div class="form-group">
                                    <select class="form-control left" id="left" name="left" >
										<!--<option value="No">No</option>
										<option value="Yes">Yes</option> -->
                                        <option selected="selected">Choose One</option>
                                        <?php
                                        foreach($emp as $key => $val){
                                        ?>
                                          <option  value="<?=$key?>" <?php if ($emp_details[0]['lefts']==$key){ ?> selected="true" <?php }?> ><?php echo $val;?> </option>
                                        <?php
                                        }
                                        ?> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Salary</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="amount"   placeholder="Salary " value="<?=$emp_details[0]['amount']?>">
                                </div>
                            </div>
                            <?php
                                $role =  explode(',', $emp_details[0]['role']);
                            ?>
                            <div class="col-sm-4">
                                <label>Role*</label>
                                <div class="form-group">
                                    <select  class="form-control js-states" class="emprolename" id="emprolename" multiple  placeholder="" required name="role[]">
                                        <?php
                                        foreach($role_details as $val)
                                        {
                                            $sel = in_array($val['id'], $role) ? "selected" : "" ;
                                            
                                            ?>  
                                                <option  value="<?=$val['name']?>"  <?php echo $sel;?>  > <?php echo $val['name'];?> </option>
                                            <?php
                                        }                                
                                        ?>                                        
                                    </select>
                                   
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Date of Start Contract Period</label>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" name="date_c_start"   placeholder="Date Of Start Contract Period d-m-Y" value="<?= date('d-m-Y', strtotime( $emp_details[0]['c_prd_fr']))?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Date of End Contract Period</label>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" name="date_c_end"   placeholder="Date Of End Contract Period d-m-Y" value="<?= date('d-m-Y', strtotime( $emp_details[0]['c_prd_t']))?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="error" id="emperror">
                                </div>
                                <div class="success" id="empsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="editempbtn" class="btn btn-primary editempbtn">Update</button>
                   <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>    

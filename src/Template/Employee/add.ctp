<?php
    $tchr = array('No','Yes' );
    $empno = 0;	
    $emp = array('No','Yes' );
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Add Employee</h2>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'addemp'] , 'id' => "addempform", 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <div class="col-sm-4">
                                <label>Picture</label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control"  name="picture"  >
                                    <small id="fileHelp" class="form-text text-muted">Only jpg, png format files are allowed</small>
                                </div>
                            </div>
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4">
                                <label>Code*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="code"  required placeholder="Employee Code *">
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name"  required placeholder="Employee Name *">
                                </div>
                            </div>
                            
                            <div class="col-sm-12">
                                <label>Address</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address"   placeholder="Employee Address">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <label>Father Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name"   placeholder="Father Name">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Phone*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone" id="phone"   placeholder="Phone Number" maxlength="15" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Qualification</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="qualification"  placeholder="Qualification ">
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
                                          <option  value="<?=$val['id']?>" ><?php echo $val['dprt_name'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <label>Date of Birth</label>
                                <div class="form-group">
                                    <input type="text" class="form-control backdatepicker" data-date-format="dd-mm-yyyy" name="dob"   placeholder="Date Of Birth d-m-Y">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Date of Joining</label>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" name="doj"   placeholder="Date Of Joining ">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Email*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  required placeholder="Employee Email *">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Password*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password"  required placeholder="Password *">
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
                                          <option  value="<?=$key?>" ><?php echo $val;?> </option>
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
                                        <option selected="selected">Choose One</option>
                                        <?php
                                        foreach($emp as $key => $val){
                                        ?>
                                          <option  value="<?=$key?>" <?php if($key == $empno ){ ?> selected="true" <?php } ?>><?php echo $val;?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Salary</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="amount"   placeholder="Salary ">
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label>Role*</label>
                                <div class="form-group">
                                    <select  class="form-control js-states emprolename" id="emprolename" multiple placeholder="" required name="role[]">
                                    <option value="" >Choose Role</option>
                                    <?php
                                    foreach($role_details as $role)
                                    {
                                        echo '<option value="'.$role['name'].'" >'.$role['name'].'</option>' ;
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Date of Start Contract Period</label>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" name="date_c_start"   placeholder="Date Of Start Contract Period d-m-Y">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Date of End Contract Period</label>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" name="date_c_end"   placeholder="Date Of End Contract Period d-m-Y">
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
                                        <button type="submit" id="addempbtn" class="btn btn-primary addempbtn">Save</button>
                   <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>    



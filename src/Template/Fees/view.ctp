<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">View Teacher</h2>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false ); ?>
                        <div class="row clearfix">                            
                            
                            <div class="col-sm-4">
                                <label>Picture*</label>
                                <div class="form-group"> 
                                    <img src="../../img/<?=$teacher_details[0]['pict']?>" width="70px" height="45px" style="margin-bottom:15px;">                                               
                                    <input type="file" class="form-control" readonly >
                                    <input type="hidden" readonly value="<?=$teacher_details[0]['pict']?>">
                                    <small id="fileHelp" class="form-text text-muted">Only jpg, png format files are allowed</small>
                                </div>
                            </div>
                            <div class="col-sm-8">    
                            </div>
                            <div class="col-sm-4">
                                <label>Code*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly required placeholder="Teacher Code *" value="<?=$teacher_details[0]['e_code']?>">
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  required placeholder="Teacher Name *" value="<?=$teacher_details[0]['e_name']?>"> 
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Father Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  required placeholder="Father Name *" value="<?=$teacher_details[0]['f_name']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Address*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly required placeholder="Teacher Address *" value="<?=$teacher_details[0]['address']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Phone*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly required placeholder="Phone Number*" value="<?=$teacher_details[0]['mobile_no']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Qualification*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly required placeholder="Qualification *" value="<?=$teacher_details[0]['quali']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Department Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly required placeholder="Department Name *" value="<?=$teacher_details[0]['department_name']?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <label>Date of Birth*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control backdatepicker" data-date-format="dd-mm-yyyy" readonly required placeholder="Date Of Birth d-m-Y*" value="<?= date('d-m-Y', strtotime( $teacher_details[0]['dob']))?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Date of Joining*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" readonly  required placeholder="Date Of Joining d-m-Y*" value="<?= date('d-m-Y', strtotime($teacher_details[0]['doj']))?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Email*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" readonly  required placeholder="Teacher Email *" value="<?=$teacher_details[0]['email']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Password*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly required placeholder="Password *" value="<?=$teacher_details[0]['password']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Teaching Staff*</label>
                                <div class="form-group">
                                    <select class="form-control" id="teacher" disabled required>
                                        <option selected="selected">Choose One</option>
                                        <?php
                                        foreach($tchr as $key => $val){
                                        ?>
                                          <option  value="<?=$key?>" <?php if ($teacher_details[0]['teachers']==$key){ ?> selected="true" <?php }?> ><?php echo $val;?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Employee Left*</label>
                                <div class="form-group">
                                    <select class="form-control" id="left" disabled required>
                                        <option selected="selected">Choose One</option>
                                        <?php
                                        foreach($emp as $key => $val){
                                        ?>
                                          <option  value="<?=$key?>" <?php if ($teacher_details[0]['lefts']==$key){ ?> selected="true" <?php }?> ><?php echo $val;?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Salary*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  required placeholder="Salary *" value="<?=$teacher_details[0]['amount']?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label>Role*</label>
                                <div class="form-group">
                                    <select  class="form-control" id="role" disabled>
                                    <option value="" >Choose Role</option>
                                    <?php
                                    foreach($role_details as $role)
                                    {      
                                        ?>
                                            <option value="<?=$role['id']?>" <? if($teacher_details[0]['role'] == $role['id']){ ?>selected="true" <? } ?> ><?=$role['name']?> </option>
                                            <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Date of Start Contract Period*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" readonly required placeholder="Date Of Start Contract Period d-m-Y*" value="<?= date('d-m-Y', strtotime( $teacher_details[0]['c_prd_fr']))?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Date of End Contract Period*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" readonly  required placeholder="Date Of End Contract Period d-m-Y*" value="<?= date('d-m-Y', strtotime( $teacher_details[0]['c_prd_t']))?>">
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                   <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>    



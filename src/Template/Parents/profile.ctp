<?php //print_r($parent_details); ?>
        <div class="card">
                                <div class="body header">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12">
                                        <?php   echo $this->Form->create(false , ['url' => ['action' => 'editstdntprofile'] , 'id' => "editstdntprofileform" , 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>

                                        <h6 class="mb-4">Student Details</h6>
                                            
                                            <input type="hidden" name="school_id" value="<?=$parent_details[0]['school_id']?>">

                                            <input type="hidden" name="id" value="<?=$parent_details[0]['id']?>">

                                            <div class="form-group"> 
                                                <label>First Name</label>
                                                <input type="text" class="form-control" name="f_name" value="<?=$parent_details[0]['f_name']?>" placeholder="First Name">
                                            </div>
                                            <div class="form-group">         
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" name="l_name" value="<?=$parent_details[0]['l_name']?>" placeholder="Last Name">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="email" value="<?=$parent_details[0]['email']?>" placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile No.</label>
                                                <input type="text" class="form-control" name="mobile_no" value="<?=$parent_details[0]['mobile_for_sms']?>" placeholder="Phone Number">
                                            </div>
                                            <div class="form-group">     
                                                <label>Student Profile</label>
                                                <input type="file" class="form-control" name="picture" >
                                                <input type="hidden" class="form-control" name="apicture" value="<?=$parent_details[0]['pic']?>" >
                                            </div>

                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <h6 class="mb-4 mt-2">Change Password</h6>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="opassword" placeholder="Current Password">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="New Password">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="cpassword" placeholder="Confirm New Password">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="error" id="stdnterror"></div>
                                          <div class="success" id="stdntsuccess"></div>
                                        </div>
                                    </div>
                                    <button type="submit" id="editstdntprfbtn" class="btn btn-primary">Update</button> &nbsp;&nbsp;
<?php echo $this->Form->end(); ?>
                                    
                                </div>
                            </div>
                     

                            
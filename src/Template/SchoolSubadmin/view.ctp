
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">View User</h2>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false); ?>
                        <div class="row clearfix">
                             
                            <div class="col-sm-11">
                                <label>Profile Image*</label>
                                <div class="form-group">                                                
                                    <input type="text" class="form-control" readonly value="<?=$users_details[0]['picture']?>" >
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <a href="<?=$baseurl."img/".$users_details[0]['picture']?>" target="_blank">
                                    <img src='<?=$baseurl."img/".$users_details[0]['picture']?>' height="50" width="50" alt="Profile image">
                                </a>
                            </div>
                            <div class="col-sm-12">
                                <label>First Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  required placeholder="First Name *" value="<?=$users_details[0]['fname']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Last Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  required placeholder="Last Name *" value="<?=$users_details[0]['lname']?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-12">
                                <label>Phone*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  required placeholder="Phone Number*" value="<?=$users_details[0]['phone']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Email*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" readonly  required placeholder="Email Address *" value="<?=$users_details[0]['email']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Password*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  required placeholder="Password *" value="<?=$users_details[0]['password']?>">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <label>Role*</label>
                                <div class="form-group">
                                    <select  class="form-control role" id="role" placeholder="" required disabled>
                                    <option value="" >Choose Role</option>
                                    <?php
                                    foreach($role_details as $role)
                                    {   
                                        ?>
                                            <option  value="<?=$role['id']?>" <?php if($users_details[0]['role'] == $role['id']){ ?>selected="true" <?php } ?> ><?=$role['name']?> </option>
                                        <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    
                                    <div class="mt-4 ml-4">
                                       
                   <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>    



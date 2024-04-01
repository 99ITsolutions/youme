<?php 
    
    $id = $school_details[0]['id'];
    $principal_name = $school_details[0]['principal_name'];
    $comp_no = $school_details[0]['comp_no'];
    $user_name = $school_details[0]['user_name'];
    $comp_name = $school_details[0]['comp_name'];
    $state = $school_details[0]['state'];
    $city = $school_details[0]['city'];
    $zipcode = $school_details[0]['zipcode'];
    $add_1 = $school_details[0]['add_1'];
    $primary = $school_details[0]['primary_color'];
    $phone = $school_details[0]['ph_no'];
    $pan_no = $school_details[0]['pan_no'];
    $tin_no = $school_details[0]['tin_no'];
    $email = $school_details[0]['email'];
    $password = $school_details[0]['password'];
    $url = $school_details[0]['www'];
    $module = $school_details[0]['module'];
    $status = $school_details[0]['status'];
    $comp_logo = $school_details[0]['comp_logo'];
    $comp_logo1 = $school_details[0]['comp_logo1'];
    $site_name = $school_details[0]['site_name'];
    $site_title = $school_details[0]['site_title'];
    $logout_url = $school_details[0]['logout_url'];
    $favicon = $school_details[0]['favicon'];
    $expiry_date = $school_details[0]['expiry_date'];
    $work_key = $school_details[0]['work_key'];
    $sender = $school_details[0]['sender'];
    $send_sms = $school_details[0]['send_sms'];
    $sms_temp = $school_details[0]['sms_temp'];
    $send_time = $school_details[0]['send_time'];
    $mail_host = $school_details[0]['mail_host'];
    $email_host = $school_details[0]['email_host'];
    $mail_password = $school_details[0]['mail_password'];
    $port = $school_details[0]['port'];
    $send_email = $school_details[0]['send_email'];
    
    $statusarray = array('Inactive','Active' );
    $smsarray = array('Yes','No' );
    $emailarray = array('Yes','No' );

?>
<style>

h5
{
    color: #007bff !important;
}

</style>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">View School</h2>
            </div>
            <div class="body">
                <?php   
                    echo $this->Form->create(false ); ?>
                        <div class="row clearfix">
                            
                            <div class="col-sm-12 mb-2">
                                <h5>Basic Information</h5>
                            </div>
                            <div class="col-sm-4">
                                <label>School Number*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  placeholder="School Number *" value="<?=$comp_no ?>">
                                </div>
                            </div>
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4">
                                <label>School Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly placeholder="School Name *" required value="<?=$comp_name ?>"   >
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Principal Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  placeholder="Principal Name " value="<?=$principal_name ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Address</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly value="<?=$add_1?>"   placeholder="School Address ">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>State*</label>
                                <div class="form-group">
                                <select class="form-control states module" id="state" disabled required>
                                    <option selected="selected">Choose State</option>
                                    <?php
                                    foreach($state_details as $val){
                                        $sel = $state == $val['id'] ? "selected" : "";
                                    ?>
                                        
                                        <option  value="<?=$val['id']?>" <?php if($state == $val['id']){ ?>selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>City*</label>
                                <div class="form-group" id="getcity">
                                <select class="form-control city" id="city" disabled required>
                                    <option selected="selected">Choose City</option>
                                    <?php
                                    foreach($city_details as $val){
                                        $sel = $city == $val['id'] ? "selected" : "";
                                    ?>
                                        
                                        <option  value="<?=$val['id']?>" <?php if($city == $val['id']){ ?>selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Zipcode</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly value="<?=$zipcode?>"   placeholder="Zipcode ">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Contact Number*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly value="<?=$phone?>"  required placeholder="Contact Number*">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>PAN Number</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly   placeholder="PAN Number*" value="<?=$pan_no?>">
                                </div>
                            </div>
                           <!-- <div class="col-sm-12">
                                <label>Tin number*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="tin_no"  required placeholder="Tin Number*" value="<?=$tin_no?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>User name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="user_name" value="<?=$user_name?>"  required placeholder="User Name *">
                                </div>
                            </div> -->
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5>Login Information</h5>
                            </div>
                            <div class="col-sm-12">
                                <label>Email*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" readonly value="<?=$email?>"  required placeholder="Email Address *">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Password*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?=$password?>" readonly  required placeholder="Password *">
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5>White-label Information</h5>
                            </div>
                            <div class="col-sm-4">
                                <label>School Logo*</label>
                                <div class="form-group">   
                                    <img src="../../img/<?=$comp_logo?>" width="70px" height="45px" style="margin-bottom:15px;">
                                    <input type="text" class="form-control" readonly value="<?=$comp_logo?>" >
                                    <small id="fileHelp" class="form-text text-muted">Only jpg, png format files are allowed</small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Background Image*</label>
                                <div class="form-group">   
                                    <img src="../../img/<?=$comp_logo1?>" width="70px" height="45px" style="margin-bottom:15px;">
                                    <input type="text" class="form-control" readonly value="<?=$comp_logo1?>" >
                                    <small id="fileHelp" class="form-text text-muted">Only jpg, png format files are allowed</small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Favicon*</label>
                                <div class="form-group">
                                    <img src="../../img/<?=$favicon?>" width="70px" height="45px" style="margin-bottom:15px;">
                                    <input type="text" class="form-control" readonly value="<?=$favicon?>" >
                                    <small id="fileHelp" class="form-text text-muted">Only jpg, png format files are allowed</small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Primary Color*</label>
                                <div class="form-group">                            
                                    <input type="color" class="form-control" readonly value="<?= $primary?>" required >                                    
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label>Domain*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  value="<?=$url?>" required placeholder="Domain (www.abc.com/school/login?slug= )  *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Logout Url ?*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly value="<?=$logout_url?>" required placeholder="Logout Url (Kindly enter Domain url last values after slug= ) *">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Site Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?=$site_name?>" readonly  required placeholder="Site Name *">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Site Title*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?=$site_title?>" readonly  required placeholder="Site Title *">
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5>SMS Information</h5>
                            </div>
                            <div class="col-sm-6">
                                <label>Working Key</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  placeholder="Working Key" value="<?=$work_key?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Sender</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly   placeholder="Sender" value="<?=$sender?>">
                                </div>
                            </div>
                            <!--<div class="col-sm-12">
                                <label>Send username and password using sms*</label>
                                <div class="form-group">
                                <select class="form-control" id="send_sms" name="send_sms" required>
                                    <option selected="selected">Choose One</option>
                                    <?php
                                    foreach($smsarray as $key => $val){
                                    ?>
                                      <option  value="<?=$val?>" <?php if($send_sms == $val){ ?>selected="true" <?php } ?> ><?php echo $val;?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>-->
                            <div class="col-sm-6">
                                <label>Birthday SMS Template</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly   placeholder="please use ***School*** for school & ***Student*** for student in your template " value="<?=$sms_temp?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Sending Time</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly   placeholder="Sending Time" value="<?=$send_time?>">
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5>Mail Information</h5>
                            </div>
                            <div class="col-sm-6">
                                <label>Host Email Address</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" readonly  placeholder="Host Email Address" value="<?=$email_host?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Host</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly   placeholder="Mail Host" value="<?=$mail_host?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Mail Password</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  placeholder="Mail Password" value="<?=$mail_password?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Port</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  placeholder="Port" value="<?=$port?>">
                                </div>
                            </div>
                            <!--<div class="col-sm-12">
                                <label>Send username and password using email*</label>
                                <div class="form-group">
                                <select class="form-control" id="send_email" name="send_email" required>
                                    <option selected="selected">Choose One</option>
                                    <?php
                                    foreach($emailarray as $key => $val){
                                    ?>
                                      <option  value="<?=$val?>" <?php if($send_email == $val){ ?>selected="true" <?php } ?> ><?php echo $val;?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div> -->
                            
                            <div class="col-sm-12 mb-2 mt-2">
                                <h5>Membership Information</h5>
                            </div>
                            <div class="col-sm-4">
                                <label>Module*</label>
                                <div class="form-group">
                                <select class="form-control module" id="module" disabled required>
                                    <option selected="selected">Choose Module</option>
                                    <?php
                                    foreach($module_details as $val){
                                        $sel = $module == $val['id'] ? "selected" : "";
                                    ?>
                                      
                                      <option  value="<?=$val['id']?>" <?php if($module == $val['id']){ ?>selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Expiry Date*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly  placeholder="Expiry Date d-m-Y *" value="<?= date('d-m-Y', strtotime($expiry_date))?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Status*</label>
                                <div class="form-group">
                                <select class="form-control status" id="status" disabled required>
                                    <option selected="selected">Choose Status</option>
                                    <?php
                                    foreach($statusarray as $key => $val){
                                        $sel = $status == $key ? "selected" : "";
                                    ?>
                                      <option  value="<?=$key?>" <?php if($status == $key){ ?>selected="true" <?php } ?> ><?php echo $val;?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="error" id="sclerror">
                                </div>
                                <div class="success" id="sclsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <input type="hidden" name="sclid" value="<?= $id?>">   
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



<?php

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '816') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>dealers" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'adddealer'] , 'id' => "adddealersform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '817') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="company_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '817') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '818') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '818') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '819') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="lname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '819') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '820') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '820') { echo $langlbl['title'] ; } } ?>*">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '821') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '821') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '823') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="dealeraddress"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '823') { echo $langlbl['title'] ; } } ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '824') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                <select class="form-control countries country" id="country" name="dealercountry" required>
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
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '826') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group" id="getstate">
                                <select class="form-control state" id="state" name="dealerstate" required>
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
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '828') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group" >
                                    <input type="text" class="form-control" name="dealercity" placeholder="Enter City"  required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '830') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <div class="row">
                                        <?php
                                        foreach($categories_details as $scl)
                                        {
                                            echo '<div class="col-md-3"><input type="checkbox" name="categories[]" value="'.$scl['id'].'" > '. $scl['name'].'</div>' ;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="error" id="dealererror">
                                </div>
                                <div class="success" id="dealersuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="adddealerbtn" class="btn btn-primary adddealerbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '839') { echo $langlbl['title'] ; } } ?></button>
                   <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>    



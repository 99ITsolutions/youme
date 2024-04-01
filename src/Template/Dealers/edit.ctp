
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1415') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>dealers" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'editdealer'] , 'id' => "editdealerform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '817') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '817') { echo $langlbl['title'] ; } } ?> *" value="<?=$dealer_details[0]['name']?>">
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?=$dealer_details[0]['id']?>">
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '818') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '818') { echo $langlbl['title'] ; } } ?> *" value="<?=$dealer_details[0]['fname']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '819') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="lname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '819') { echo $langlbl['title'] ; } } ?> *" value="<?=$dealer_details[0]['lname']?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '820') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone"   placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '820') { echo $langlbl['title'] ; } } ?>*" value="<?=$dealer_details[0]['phone_no']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '821') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '821') { echo $langlbl['title'] ; } } ?> *" value="<?=$dealer_details[0]['email']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '823') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="dealeraddress"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '823') { echo $langlbl['title'] ; } } ?>" value="<?=$dealer_details[0]['address']?>">
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
                                      <option  value="<?=$val['id']?>" <?php if($dealer_details[0]['country']==$val['id']) { ?> selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
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
                                      <option  value="<?=$val['id']?>" <?php if($dealer_details[0]['state']==$val['id']) { ?> selected="true" <?php } ?> ><?php echo $val['name'];?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>                                    
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '828') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group" >
                                    <input type="text" class="form-control" name="dealercity" placeholder="Enter City"  required value="<?=$dealer_details[0]['city']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '830') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <div class="row">
                                        <?php
                                        $priv = explode(",", $dealer_details[0]['categories']);
                                        
                                        foreach($category_details as $scl)
                                        {
                                            if(in_array($scl['id'], $priv))
                                            {
                                                $chck = "checked";
                                            }
                                            else
                                            {
                                                $chck = "";
                                            }
                                            echo '<div class="col-md-3"><input type="checkbox" name="categories[]" value="'.$scl['id'].'" '.$chck.'> '. $scl['name'].'</div>' ;
                                        
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="error" id="dealererror"></div>
                                <div class="success" id="dealersuccess"></div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="editdealerbtn" class="btn btn-primary editdealerbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                     <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    



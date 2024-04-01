<?php

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '620') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>subadmin" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'adduser'] , 'id' => "adduserform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '621') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="picture" >
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '624') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '625') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '625') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '626') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="lname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '626') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '627') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '628') { echo $langlbl['title'] ; } } ?>*">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Email*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  required placeholder="Email Address *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '631') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '631') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="role" id="role" required value="3">
                           
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1408') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <div class="row">
                                        <?php
                                        foreach($school_details as $scl)
                                        {
                                            echo '<div class="col-md-4"><input type="checkbox" name="subadmin_privilages[]" value="'.$scl['id'].'" > '. $scl['comp_name'].'</div>' ;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1409') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <div class="row">
                                        <?php
                                        foreach($menus_details as $menu)
                                        {
                                            echo '<div class="col-md-4"><input type="checkbox" name="menus_privilages[]" value="'.$menu['id'].'" > '. $menu['name'].'</div>' ;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="error" id="usererror">
                                </div>
                                <div class="success" id="usersuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="adduserbtn" class="btn btn-primary adduserbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '633') { echo $langlbl['title'] ; } } ?></button>
                   <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>    



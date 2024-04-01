
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1410') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>subadmin" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'edituser'] , 'id' => "edituserform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '621') { echo $langlbl['title'] ; } } ?>*</label>
                                <br>
                                <img src="<?= $baseurl ?>/img/<?=$users_details[0]['picture']?>" width="50px">
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="picture" >
                                    <input type="hidden" name="apicture" value="<?=$users_details[0]['picture']?>">
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '624') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?=$users_details[0]['id']?>">
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '625') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '625') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['fname']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '626') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="lname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '626') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['lname']?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '627') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone"   placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '628') { echo $langlbl['title'] ; } } ?>*" value="<?=$users_details[0]['phone']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Email*</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email"  required placeholder="Email Address *" value="<?=$users_details[0]['email']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '631') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '631') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['password']?>">
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="role" id="role" required value="<?= $users_details[0]['role'] ?>">
                            
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1408') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <div class="row">
                                        <?php
                                        $priv = explode(",", $users_details[0]['privilages']);
                                        
                                        foreach($school_details as $scl)
                                        {
                                            if(in_array($scl['id'], $priv))
                                            {
                                                $chck = "checked";
                                            }
                                            else
                                            {
                                                $chck = "";
                                            }
                                            echo '<div class="col-md-4"><input type="checkbox" name="subadmin_privilages[]" value="'.$scl['id'].'" '.$chck.'> '. $scl['comp_name'].'</div>' ;
                                        
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
                                        $mpriv = explode(",", $users_details[0]['menus_privilages']);
                                        
                                        foreach($menus_details as $menu)
                                        {
                                            if(in_array($menu['id'], $mpriv))
                                            {
                                                $chck = "checked";
                                            }
                                            else
                                            {
                                                $chck = "";
                                            }
                                            echo '<div class="col-md-4"><input type="checkbox" name="menus_privilages[]" value="'.$menu['id'].'" '.$chck.' > '. $menu['name'].'</div>' ;
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
                                        <button type="submit" id="edituserbtn" class="btn btn-primary edituserbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                   <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>    



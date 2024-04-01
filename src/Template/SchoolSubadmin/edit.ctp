<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '612') { $lbl612 = $langlbl['title'] ; } } ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1528') { echo $langlbl['title'] ; } } ?></h2>
                 <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'editsubadmin'] , 'id' => "editsubadminform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '89') { echo $langlbl['title'] ; } } ?>*</label>
                                <br>
                                <img src="<?= $baseurl ?>/img/<?=$users_details[0]['picture']?>" width="50px">
                                <div class="form-group">                                                
                                    <input type="file" class="form-control" name="picture" >
                                    <input type="hidden" name="apicture" value="<?=$users_details[0]['picture']?>">
                                    <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '209') { echo $langlbl['title'] ; } } ?></small>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?=$users_details[0]['id']?>">
                          
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '144') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="lname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '144') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['lname']?>">
                                </div>
                            </div>

                              <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '143') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fname"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '143') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['fname']?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '612') { echo $langlbl['title'] ; } } ?></label>
                               
                                <div class="wrapper">
                                <?php $phnum = explode(",", $users_details[0]['phone']);
                                $countph = count($phnum); 
                                for($i=0; $i < count($phnum); $i++)
                                {?>
                                    <div class="input-box row container mb-2">
                                        
                                         <?php if($i == 0) { ?>
                                         <input type="text" class="col-sm-10 form-control"  name="phone[]" required  placeholder="<?= $lbl612 ?>*" value="<?= $phnum[$i] ?>">
                                        <button class="col-sm-2 btn add-btn"><i class="fa fa-plus"></i></button>
                                        <?php } else { ?>
                                        <input type="text" class="col-sm-10 form-control"  name="phone[]"  placeholder="<?= $lbl612 ?>*" value="<?= $phnum[$i] ?>">
                                        <a href="#" class="col-sm-2 remove-lnk"><i class="fa fa-minus"></i></a>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '90') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <?php //if(!empty($user_details[0])) { ?>
                                    <input type="email" class="form-control" name="email"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '90') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['email']?>">
                                    <?php /* } else { ?>
                                    <input type="email" class="form-control" readonly name="email"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '90') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['email']?>">
                                    
                                    <?php } */ ?>
                                    
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '91') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '91') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['password']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '92') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="cpassword"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '92') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['password']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1754') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="jobtitle"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1754') { echo $langlbl['title'] ; } } ?> *" value="<?=$users_details[0]['jobtitle']?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>You-Me Phone Number</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="contactyoume" placeholder="You-Me Phone Number" value="<?=$users_details[0]['contact_youme']?>">
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="role" id="role" required value="3">
                            <div class="col-sm-4">
                                <label>School Class Privileges *</label>
                                <div class="form-group">
                                    <?php
                                    $subpriv = explode(",", $users_details[0]['scl_sub_priv']);
                                    //print_r($subpriv);
                                    ?>
                                    <select class="form-control js-states community_filter" name="subadpriv[]" required multiple>
                                        <option value="">Choose Privileges</option>
                                        <option value="kindergarten" <?php if(in_array("kindergarten", $subpriv)) { echo "selected"; } ?> >Maternelle</option>
                                        <option value="primaire" <?php if(in_array("primaire", $subpriv)) { echo "selected"; } ?> >Primaire</option>
                                        <option value="secondaire" <?php if(in_array("secondaire", $subpriv)) { echo "selected"; } ?> >Secondaire</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '93') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <div class="row" id="sclpriv">
                                        <?php
                                        $priv = explode(",", $users_details[0]['privilages']);
                                        $subpriv = explode(",", $users_details[0]['sub_privileges']);
                                        //print_r($subpriv);
                                        foreach($schoolmnu_details as $scl)
                                        {
                                            /*if($languagesel == 1)
                                            {
                                                echo '<div class="col-md-3"><input type="checkbox" name="subadmin_privilages[]" value="'.$scl['id'].'" '.$chck.'> '. $scl['name'].'</div>' ;
                                            }
                                            else
                                            {
                                                echo '<div class="col-md-3"><input type="checkbox" name="subadmin_privilages[]" value="'.$scl['id'].'" '.$chck.'> '. $scl['french_name'].'</div>' ;
                                            }*/
                                            if(in_array($scl['id'], $priv))
                                            {
                                                $chck = "checked";
                                            }
                                            else
                                            {
                                                $chck = "";
                                            }
                                            $roles = '';
                                            if($chck == "checked")
                                            {
                                                foreach($submnu_details as $role) {
                                                    if($role['sclmenu_id'] == $scl['id'])
                                                    {
                                                        
                                                        if(in_array($role['id'], $subpriv))
                                                        {
                                                            $subchck = "checked";
                                                        }
                                                        else
                                                        {
                                                            $subchck = "";
                                                        }
                                                        
                                                        //print_r($subpriv);
                                                        $roles .= '<div class="col-md-12 ml-1"><input type="checkbox" name="subroles_privilages[]" value="'.$role['id'].'" '.$subchck.'> '. $role['name'].'</div>';
                                                    }
                                                }
                                            }
                                            echo '<div class="col-md-6 row" id="rowsepid'.$scl['id'].'" style="margin:0 auto !important; padding:10px !important; border-bottom:1px solid #ccc !important;">';
                                            if($languagesel == 1)
                                            {
                                                echo '<div class="col-md-5"><input class="schoolmenus" type="checkbox" id="subpriv'.$scl['id'].'" name="subadmin_privilages[]" value="'.$scl['id'].'" '.$chck.' > '. $scl['name'].'</div>
                                                <div class="col-md-7 row" id="subrolespriv'.$scl['id'].'">'.$roles.'</div>' ;
                                            }
                                            else
                                            {
                                                echo '<div class="col-md-5"><input class="schoolmenus" type="checkbox" id="subpriv'.$scl['id'].'" name="subadmin_privilages[]" value="'.$scl['id'].'" '.$chck.' > '. $scl['french_name'].'</div>
                                                <div class="col-md-7 row" id="subrolespriv'.$scl['id'].'">'.$roles.'</div>' ;
                                                //echo '<div class="col-md-6"><input class="schoolmenus" type="checkbox" name="subadmin_privilages[]" value="'.$scl['id'].'" > '. $scl['french_name'].'</div>' ;
                                            }
                                            echo '</div>';
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
$(document).ready(function () {
      var max_input = 3;
      var x = "<?php echo $countph ?>";
      $('.add-btn').click(function (e) {
        e.preventDefault();
        
        if (x < max_input) {
            x++;
          $('.wrapper').append(`
                <div class="input-box row container mb-2">
                        <input type="text" class="col-sm-10 form-control" name="phone[]"  placeholder="<?php echo $lbl612 ?>*">
                        <a href="#" class="col-sm-2 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      });
    });
</script>


<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2063') { $lbl2063 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2065') { $lbl2065 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2066') { $lbl2066 = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '2067') { $lbl2067 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2068') { $lbl2068 = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '305') { $lbl305 = $langlbl['title'] ; } 
    if($langlbl['id'] == '49') { $lbl49 = $langlbl['title'] ; } 
    if($langlbl['id'] == '89') { $lbl89 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1820') { $lbl1820 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1821') { $lbl1821 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1822') { $lbl1822 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1823') { $lbl1823 = $langlbl['title'] ; }
    if($langlbl['id'] == '1412') { $lbl1412 = $langlbl['title'] ; } 
}
    if(!empty($company_details[0]))
    { ?>
        <div class="card">
                                <div class="body header">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12">
                                        <?php   echo $this->Form->create(false , ['url' => ['action' => 'edituserprofile'] , 'id' => "edituserprofileform" , 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>

                                        <h6 class="mb-4"><?= $lbl2063 ?></h6>
                                            
                                            <div class="form-group">        
                                                <label><?= $lbl2065 ?></label>
                                                <input type="text" class="form-control" name="fname" value="<?=$company_details[0]['comp_name']?>" placeholder="<?= $lbl2066 ?>">
                                            </div>
                                            <div class="form-group">
                                                 <label>E-mail</label>
                                                <?php if(!empty($user_details[0])) { ?>
                                                <input type="email"  class="form-control" name="email" value="<?=$company_details[0]['email']?>" placeholder="<?= $lbl2067 ?>">
                                                <?php } else { ?>
                                                <input type="email" readonly class="form-control" name="email" value="<?=$company_details[0]['email']?>" placeholder="<?= $lbl2067 ?>">
                                                
                                                <?php } ?>
                                            
                                            </div>
                                            <div class="form-group">
                                                 <label><?= $lbl49 ?></label>
                                                <input type="text" class="form-control" name="phone" value="<?=$company_details[0]['ph_no']?>" placeholder="<?= $lbl2068 ?>">
                                            </div>
                                            <div class="form-group">       
                                                 <label><?= $lbl89 ?></label>
                                                <img src="<?= $baseurl ?>img/<?=$company_details[0]['comp_logo']?>" width="50px" height="50px">
                                                <input type="file" class="form-control" name="picture" value="" >
                                                <input type="hidden" class="form-control" name="apicture" value="<?=$company_details[0]['comp_logo']?>" >
                                            </div>

                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <h6 class="mb-4 mt-2"><?= $lbl1820 ?></h6>
                                            <div class="form-group">
                                                <label><?= $lbl1821 ?></label>
                                                <input type="password" class="form-control" name="opassword" placeholder="<?= $lbl1821 ?>">
                                            </div>
                                            <div class="form-group">
                                                <label><?= $lbl1822 ?></label>
                                                <input type="password" class="form-control" name="password" placeholder="<?= $lbl1822 ?>">
                                            </div>
                                            <div class="form-group">
                                                <label><?= $lbl1823 ?></label>
                                                <input type="password" class="form-control" name="cpassword" placeholder="<?= $lbl1823 ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="error" id="userserror"></div>
                                          <div class="success" id="userssuccess"></div>
                                        </div>
                                    </div>
                                    <button type="submit" id="userseditbtn" class="btn btn-primary"><?= $lbl1412 ?></button> &nbsp;&nbsp;
                                <?php echo $this->Form->end(); ?>
                                    
                                </div>
                            </div>
 <?php   }


?>                        

                            
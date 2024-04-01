<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '144') { $lbl144 = $langlbl['title'] ; } 
    if($langlbl['id'] == '130') { $lbl130 = $langlbl['title'] ; } 
    if($langlbl['id'] == '143') { $lbl143 = $langlbl['title'] ; } 
    if($langlbl['id'] == '305') { $lbl305 = $langlbl['title'] ; } 
    if($langlbl['id'] == '49') { $lbl49 = $langlbl['title'] ; } 
    if($langlbl['id'] == '89') { $lbl89 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1820') { $lbl1820 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1821') { $lbl1821 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1822') { $lbl1822 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1823') { $lbl1823 = $langlbl['title'] ; }
    if($langlbl['id'] == '1412') { $lbl1412 = $langlbl['title'] ; }
    if($langlbl['id'] == '2104') { $lbl2104 = $langlbl['title'] ; }
}
?>
<div class="card">
    <div class="body header">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'editstdntprofile'] , 'id' => "editstdntprofileform" , 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>

                <h6 class="mb-4">Student Details</h6>
                <input type="hidden" name="school_id" value="<?=$student_details[0]['school_id']?>">
                <input type="hidden" name="id" value="<?=$student_details[0]['id']?>">
                <div class="form-group">         
                    <label><?= $lbl144 ?></label>
                    <input type="text" class="form-control" name="l_name" value="<?=$student_details[0]['l_name']?>" readonly placeholder="<?= $lbl144 ?>">
                </div>
                <div class="form-group"> 
                    <label><?= $lbl143 ?></label>
                    <input type="text" class="form-control" name="f_name" value="<?=$student_details[0]['f_name']?>" readonly placeholder="<?= $lbl143 ?>">
                </div>
                
                <div class="form-group">
                    <label><?= $lbl130 ?></label>
                    <input type="text" readonly class="form-control" value="<?=$student_details[0]['adm_no']?>" placeholder="<?= $lbl130 ?>">
                </div>
                <div class="form-group">
                    <label><?= $lbl305 ?></label>
                    <input type="email" readonly class="form-control" name="email" value="<?=$student_details[0]['email']?>" placeholder="<?= $lbl305 ?>">
                </div>
                <div class="form-group">
                    <label><?= $lbl49 ?></label>
                    <input type="text" class="form-control" name="mobile_no" value="<?=$student_details[0]['mobile_for_sms']?>" placeholder="<?= $lbl49 ?>">
                </div>
                <div class="form-group">
                    <label><?= $lbl2104 ?></label>
                    <input type="text" class="form-control" name="contactyoume" value="<?=$student_details[0]['contactyoume']?>" placeholder="<?= $lbl2104 ?>">
                </div>
                <div class="form-group">     
                    <label><?= $lbl89 ?></label>
                    <img src="<?= $baseurl ?>img/<?=$student_details[0]['pic']?>" width="50px" height="50px">
                    <input type="file" class="form-control" name="picture" >
                    <input type="hidden" class="form-control" name="apicture" value="<?=$student_details[0]['pic']?>" >
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
                <div class="error" id="stdnterror"></div>
                <div class="success" id="stdntsuccess"></div>
            </div>
        </div>
        <button type="submit" id="editstdntprfbtn" class="btn btn-primary"><?= $lbl1412 ?></button> 
        <?php echo $this->Form->end(); ?>
    </div>
</div>
                     

                            
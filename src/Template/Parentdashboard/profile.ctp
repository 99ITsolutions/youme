<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '144') { $lbl144 = $langlbl['title'] ; } 
    if($langlbl['id'] == '143') { $lbl143 = $langlbl['title'] ; } 
    if($langlbl['id'] == '305') { $lbl305 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1984') { $lbl1984 = $langlbl['title'] ; } 
    if($langlbl['id'] == '135') { $lbl135 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1820') { $lbl1820 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1821') { $lbl1821 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1822') { $lbl1822 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1823') { $lbl1823 = $langlbl['title'] ; }
    if($langlbl['id'] == '1412') { $lbl1412 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1983') { $lbl1983 = $langlbl['title'] ; }
}
?>
<div class="card">
    <div class="body header">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editparntprofile'] , 'id' => "editstdntprofileform" , 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>

            <h6 class="mb-4"><?= $lbl1983 ?></h6>
                <input type="hidden" name="id" value="<?=$parent_dtl[0]['id']?>">
                <div class="form-group">         
                    <label><?= $lbl144 ?></label>
                    <input type="text" class="form-control" name="l_name" value="<?=$parent_dtl[0]['l_name']?>" placeholder="<?= $lbl144 ?>">
                </div>
                <div class="form-group"> 
                    <label><?= $lbl143 ?></label>
                    <input type="text" class="form-control" name="f_name" value="<?=$parent_dtl[0]['f_name']?>" placeholder="<?= $lbl143 ?>">
                </div>
                
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" class="form-control" readonly name="email" value="<?=$parent_dtl[0]['parent_email']?>" placeholder="Email">
                </div>
                <div class="form-group">
                    <label><?= $lbl135 ?></label>
                    <input type="text" class="form-control" name="mobile_no" value="<?=$parent_dtl[0]['mobile']?>" placeholder="<?= $lbl135 ?>">
                </div>
                <div class="form-group">     
                    <label><?= $lbl1984 ?></label>
                    <img src="<?= $baseurl ?>img/<?=$parent_dtl[0]['image']?>" width="50px" height="50px">
                    <input type="file" class="form-control" name="picture" >
                    <input type="hidden" class="form-control" name="apicture" value="<?=$parent_dtl[0]['image']?>" >
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
        <button type="submit" id="editstdntprfbtn" class="btn btn-primary"><?= $lbl1412 ?></button> &nbsp;&nbsp;
        <?php echo $this->Form->end(); ?>
    </div>
</div>
                     

                            
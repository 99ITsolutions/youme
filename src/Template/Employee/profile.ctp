<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '144') { $lbl144 = $langlbl['title'] ; } 
    if($langlbl['id'] == '143') { $lbl143 = $langlbl['title'] ; } 
    if($langlbl['id'] == '305') { $lbl305 = $langlbl['title'] ; } 
    if($langlbl['id'] == '49') { $lbl49 = $langlbl['title'] ; } 
    if($langlbl['id'] == '89') { $lbl89 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1820') { $lbl1820 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1821') { $lbl1821 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1822') { $lbl1822 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1823') { $lbl1823 = $langlbl['title'] ; }
    if($langlbl['id'] == '1921') { $lbl1921 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1412') { $lbl1412 = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '296') { $lbl296 = $langlbl['title'] ; } 
}
?>
<div class="card">
    <div class="body header">
        <?php   echo $this->Form->create(false , ['url' => ['action' => 'editempprofile'] , 'id' => "editempprofileform" , 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <h6 class="mb-4"><?= $lbl1921 ?></h6>
                <input type="hidden" name="school_id" value="<?=$emp_details[0]['school_id']?>">
                <input type="hidden" name="id" value="<?=$emp_details[0]['id']?>">
                <div class="row">
                    <div class="form-group col-md-6">       
                        <label><?= $lbl144 ?></label>
                        <input type="text" class="form-control" name="l_name" value="<?=$emp_details[0]['l_name']?>" placeholder="<?= $lbl144 ?>">
                    </div>
                    <div class="form-group col-md-6">       
                        <label><?= $lbl143 ?></label>
                        <input type="text" class="form-control" name="f_name" value="<?=$emp_details[0]['f_name']?>" placeholder="<?= $lbl143 ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">       
                        <label><?= $lbl305 ?></label>
                        <input type="email" readonly class="form-control" name="email" value="<?=$emp_details[0]['email']?>" placeholder="<?= $lbl305 ?>">
                    </div>
                    <div class="form-group col-md-6">       
                        <label><?= $lbl49 ?></label>
                        <!--<input type="text" class="form-control" name="mobile_no" value="<?=$emp_details[0]['mobile_no']?>" placeholder="<?= $lbl49 ?>">-->
                        <div class="wrapper1">
                        <?php $phnum = explode(",", $emp_details[0]['mobile_no']);
                        $countph = count($phnum); 
                        for($i=0; $i < count($phnum); $i++)
                        {?>
                            <div class="input-box row container mb-2">
                                
                                <?php if($i == 0) { ?>
                                <input type="text" class="col-sm-10 form-control"  name="phone[]" required  placeholder="<?= $lbl296 ?>*" value="<?= $phnum[$i] ?>">
                                <button class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></button>
                                <?php } else { ?>
                                <input type="text" class="col-sm-10 form-control"  name="phone[]"  placeholder="<?= $lbl296 ?>*" value="<?= $phnum[$i] ?>">
                                <a href="#" class="col-sm-2 remove-lnk"><i class="fa fa-minus"></i></a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">       
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?></label>
                        <input type="text" class="form-control" name="qualifcation" value="<?=$emp_details[0]['quali']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">        
                        <label><?= $lbl89 ?></label>
                        <img src="../../webroot/img/<?=$emp_details[0]['pict']?>" width="50px" height="50px" style="display:block">
                        <input type="file" class="form-control" name="picture" value="" >
                        <input type="hidden" class="form-control" name="apicture" value="<?=$emp_details[0]['pict']?>" >
                    </div>
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
                <div class="error" id="emperror"></div>
              <div class="success" id="empsuccess"></div>
            </div>
        </div>
        <button type="submit" id="editempprfbtn" class="btn btn-primary"><?= $lbl1412 ?></button> &nbsp;&nbsp;
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
$(document).ready(function () {
      var max_input1 = 3;
      var y = "<?php echo $countph ?>";
      $('.add-btn1').click(function (e) {
        e.preventDefault();
        
        if (y < max_input1) {
            y++;
          $('.wrapper1').append(`
                <div class="input-box row container mb-2">
                        <input type="text" class="col-sm-10 form-control" name="phone[]"  placeholder="<?php echo $lbl296 ?>*">
                        <a href="#" class="col-sm-2 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper1').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      });
    });
</script>

                            
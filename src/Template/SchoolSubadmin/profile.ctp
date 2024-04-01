
<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '612') { $lbl612 = $langlbl['title'] ; } } ?>
<div class="card">
    <div class="body header">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editprofile'] , 'id' => "editprofileform" , 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>

            <h6 class="mb-4">Subadmin Profile Details</h6>
                <input type="hidden" name="id" value="<?=$sclsub_details[0]['id']?>">
                <div class="form-group">      
                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '143') { echo $langlbl['title'] ; } } ?></label>
                    <input type="text" class="form-control" name="fname" value="<?=$sclsub_details[0]['fname']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '143') { echo $langlbl['title'] ; } } ?>">
                </div>
                <div class="form-group">    
                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '144') { echo $langlbl['title'] ; } } ?></label>
                    <input type="text" class="form-control" name="lname" value="<?=$sclsub_details[0]['lname']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '144') { echo $langlbl['title'] ; } } ?>">
                </div>
                <div class="form-group">
                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1754') { echo $langlbl['title'] ; } } ?></label>
                    <input type="text" class="form-control"  name="jobtitle" value="<?=$sclsub_details[0]['jobtitle']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1754') { echo $langlbl['title'] ; } } ?>">
                </div>
                <div class="form-group">
                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '82') { echo $langlbl['title'] ; } } ?></label>
                    <input type="email" class="form-control" readonly name="email" value="<?=$sclsub_details[0]['email']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '82') { echo $langlbl['title'] ; } } ?>">
                </div>
                <div class="form-group">
                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '296') { echo $langlbl['title'] ; } } ?></label>
                    <!--<input type="text" class="form-control" name="phone" value="<?=$sclsub_details[0]['phone']?>" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '296') { echo $langlbl['title'] ; } } ?>">
                    -->
                    <div class="wrapper">
                    <?php $phnum = explode(",", $sclsub_details[0]['phone']);
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
                <div class="form-group">          
                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '89') { echo $langlbl['title'] ; } } ?></label>
                    <img src="<?= $baseurl ?>img/<?=$sclsub_details[0]['picture']?>" width="50px" height="50px">
                    <input type="file" class="form-control" name="picture" value="" >
                    <input type="hidden" class="form-control" name="apicture" value="<?=$sclsub_details[0]['picture']?>" >
                </div>

            </div>

            <div class="col-lg-12 col-md-12">
                <h6 class="mb-4 mt-2"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1820') { echo $langlbl['title'] ; } } ?></h6>
                <div class="form-group">
                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1821') { echo $langlbl['title'] ; } } ?></label>
                    <input type="password" class="form-control" name="opassword" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1821') { echo $langlbl['title'] ; } } ?>">
                </div>
                <div class="form-group">
                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1822') { echo $langlbl['title'] ; } } ?></label>
                    <input type="password" class="form-control" name="password" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1822') { echo $langlbl['title'] ; } } ?>">
                </div>
                <div class="form-group">
                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1823') { echo $langlbl['title'] ; } } ?></label>
                    <input type="password" class="form-control" name="cpassword" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1823') { echo $langlbl['title'] ; } } ?>">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="error" id="usererror"></div>
                <div class="success" id="usersuccess"></div>
            </div>
        </div>
        <button type="submit" id="editbtn" class="btn btn-primary"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button> &nbsp;&nbsp;
        <?php echo $this->Form->end(); ?>
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

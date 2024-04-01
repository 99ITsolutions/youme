<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2203') { $lbl2203 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2065') { $lbl2065 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2204') { $lbl2204 = $langlbl['title'] ; } 
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
    if($langlbl['id'] == '143') { $lbl143 = $langlbl['title'] ; } 
    if($langlbl['id'] == '144') { $lbl144 = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '2377') { $lbl2377 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2378') { $lbl2378 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2379') { $lbl2379 = $langlbl['title'] ; } 
}
    if(!empty($cvndr_details[0]))
    { ?>
        <div class="card">
            <div class="body header">
                <?php   echo $this->Form->create(false , ['url' => ['action' => 'edituserprofile'] , 'id' => "edituserprofileform" , 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>
                <h6 class="mb-4"><?= $lbl2203 ?></h6>
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">        
                            <label><?= $lbl143 ?></label>
                            <input type="text" class="form-control" name="fname" value="<?=$cvndr_details[0]['f_name']?>" placeholder="<?= $lbl143 ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                            <label><?= $lbl144 ?></label>
                            <input type="text"  class="form-control" name="lname" value="<?=$cvndr_details[0]['l_name']?>" placeholder="<?= $lbl144 ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">        
                            <label><?= $lbl2204 ?></label>
                            <input type="text" class="form-control" name="compname" value="<?=$cvndr_details[0]['vendor_company']?>" placeholder="<?= $lbl2204 ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email"  class="form-control" name="email" value="<?=$cvndr_details[0]['email']?>" placeholder="<?= $lbl2067 ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                             <label><?= $lbl49 ?></label>
                            <input type="text" class="form-control" name="phone" value="<?=$cvndr_details[0]['contact_no']?>" placeholder="<?= $lbl2068 ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label><?= $lbl2379 ?>*</label>
                        <div class="form-group">
                            <select class="form-control status" id="booking_closed" name="booking_closed" required>
                                <option value="">Choose Timings</option>
                                <option value="30 Minutes" <?php if("30 Minutes" == $cvndr_details[0]['slot_close']) { echo "selected"; } ?> >30 Minutes</option>
                                <option value="60 Minutes" <?php if("60 Minutes" == $cvndr_details[0]['slot_close']) { echo "selected"; } ?> >60 Minutes</option>
                                <option value="90 Minutes" <?php if("90 Minutes" == $cvndr_details[0]['slot_close']) { echo "selected"; } ?> >90 Minutes</option>
                                <option value="120 Minutes" <?php if("120 Minutes" == $cvndr_details[0]['slot_close']) { echo "selected"; } ?> >120 Minutes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label><?= $lbl2377 ?>*</label>
                        <div class="form-group">
                            <input class="form-control timepicker" id="ktimepicker" value="<?= $cvndr_details[0]['deliver_starttime'] ?>" type="text" name="open_time" placeholder="<?= $lbl2377 ?>*" required/>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label><?= $lbl2378 ?>*</label>
                        <div class="form-group">
                            <input class="form-control timepicker2" id="ktimepicker2" value="<?= $cvndr_details[0]['deliver_endtime'] ?>" type="text" name="close_time" placeholder="<?= $lbl2378 ?>*" required/>
                        </div>
                    </div>
                    
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">       
                             <label><?= $lbl89 ?></label>
                            <img src="<?= $baseurl ?>img/<?=$cvndr_details[0]['logo']?>" width="50px" height="50px">
                            <input type="file" class="form-control" name="picture" value="" >
                            <input type="hidden" class="form-control" name="apicture" value="<?=$cvndr_details[0]['logo']?>" >
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
<?php } ?>                        

                            
<?php
    $school_id =$this->request->session()->read('company_id');
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1464') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?= $baseurl ?>knowledgeCenter/localuniversity" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'editlocaluniversity'] , 'id' => "editlocaluniversityform", 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1458') { echo $langlbl['title'] ; } } ?>*</label>
                                <p><img src="<?= $baseurl ?>/univ_logos/<?= $univ_details[0]['logo'] ?>" width="50px"></p>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="logo"  placeholder="Upload File *">
                                    <input type="hidden" class="form-control" name="elogo"  required value="<?= $univ_details[0]['logo'] ?>">
                                </div>
                            </div>
                            <div class="col-sm-8"></div>
                            <input type="hidden" class="form-control" name="uid"  required value="<?= $univ_details[0]['id'] ?>">
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1459') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="uni_name"  required placeholder="Enter Name *" value="<?= $univ_details[0]['univ_name'] ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '48') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '48') { echo $langlbl['title'] ; } } ?>*" value="<?= $univ_details[0]['email'] ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '50') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="website"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '50') { echo $langlbl['title'] ; } } ?> *" value="<?= $univ_details[0]['website_link'] ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '49') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="contact"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '49') { echo $langlbl['title'] ; } } ?> *" value="<?= $univ_details[0]['contact_number'] ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '170') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <select class="form-control state" name="state" id="state">
                                        <option value=""></option>
                                        <?php foreach($states_details as $val) { ?>
                                            <option value="<?= $val['id'] ?>"  <?php if($val['id'] == $univ_details[0]['state_id']) { echo "selected"; } ?> ><?= $val['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '171') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="city"  placeholder="Enter City*" value="<?= $univ_details[0]['city'] ?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '51') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <textarea class="form-control" id="courses" name="courses" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '51') { echo $langlbl['title'] ; } } ?> *" ><?= $univ_details[0]['academics'] ?></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '52') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <textarea class="form-control" id="desc" name="desc" rows="5" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '52') { echo $langlbl['title'] ; } } ?> *" ><?= $univ_details[0]['about_univ'] ?></textarea>
                                </div>
                            </div>
					
                           
                            <div class="col-sm-12">
                                <div class="error" id="univerror">
                                </div>
                                <div class="success" id="univsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="editunivbtn" class="btn btn-primary editunivbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                   
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    




<?php
    $school_id =$this->request->session()->read('company_id');
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '750') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?= $baseurl ?>knowledgeCenter/studyabroad" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '752') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>

            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'adduniversity'] , 'id' => "adduniversityform", 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1458') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="logo"  required placeholder="Upload File *">
                                </div>
                            </div>
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1459') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="uni_name"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1459') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '48') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email"  placeholder="Enter Email *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '50') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="website"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '50') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '49') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="contact"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '49') { echo $langlbl['title'] ; } } ?> *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '47') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <select class="form-control country" name="country" id="country">
                                        <option value=""></option>
                                        <?php foreach($countries_details as $country) { ?>
                                            <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '51') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">
                                    <textarea class="form-control" id="courses" name="courses" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '51') { echo $langlbl['title'] ; } } ?> *" ></textarea>
                                </div>
                            </div>
                           
                            
                            
                            
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '52') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <textarea class="form-control" id="desc" name="desc" rows="5" required placeholder=" <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '52') { echo $langlbl['title'] ; } } ?> *" ></textarea>
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
                                        <button type="submit" id="addunivbtn" class="btn btn-primary addunivbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '95') { echo $langlbl['title'] ; } } ?></button>
                   
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    




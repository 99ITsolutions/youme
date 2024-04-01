<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2143') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                 </ul>
            </div>
            <div class="body"> 
                <?php echo $this->Form->create(false , ['url' => ['action' => 'codeconductadd'] , 'id' => "codeconductform", 'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>
                    <div class="row clearfix">
                        
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '704') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="title" value="<?= $code_details['title'] ?>"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '705') { echo $langlbl['title'] ; } } ?> *">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '716') { echo $langlbl['title'] ; } } ?></label>
                            <div class="form-group">
                                <textarea id="contentnotify" name="contentnotify"><?= $code_details['content'] ?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="error" id="codeerror"></div>
                            <div class="success" id="codesuccess"></div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="mt-4 ml-4">
                                    <button type="submit" id="codeconductbtn" class="btn btn-primary codeconductbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '802') { echo $langlbl['title'] ; } } ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    



<?php
	
    $school_id =$this->request->session()->read('company_id');
    $gender = array('Male','Female');
?>
<style>



.left-date{
    display: none;
}

</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '701') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>notification" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                 </ul>
            </div>
            <div class="body"> 
                <?php echo $this->Form->create(false , ['url' => ['action' => 'addnotify'] , 'id' => "addnotifyform", 'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '702') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="notify_to" id="notify_to" required onchange="notify(this.value)">
                                    <option value="all">All</option>
                                    <option value="schools">Schools</option>
                                    <option value="teachers">Teachers</option>
                                    <option value="students">Students</option>
                                   <!-- <option value="parents">Parents</option>-->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="scl_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="schools[]" id="schoollist" multiple>
                                    <?php 
                                    $sclids = [];
                                    foreach($scl_details as $val) { 
                                        $sclids[] = $val['id'];
                                    } 
                                    $sclidss = implode(",",$sclids);
                                    ?>
                                    <option value="<?= $sclidss ?>">All</option>
                                    <?php foreach($scl_details as $val) { ?>
                                        <option value="<?= $val['id'] ?>" > <?= $val['comp_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-3" id="schl_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="schoollist" id="listschool"  onchange="schoolchange(this.value)" placeholder="Choose School">
                                    <option value="">Choose School</option>
                                    <?php foreach($scl_details as $val) { ?>
                                        <option value="<?= $val['id'] ?>" > <?= $val['comp_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-6" id="tchr_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '15') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="teacherlist[]" id="listteacher" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3" id="cls_opt" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1420') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="classoptn" id="classoptn" onchange = "classchnge(this.value)" >
                                    <option value=""> Choose Class Option</option>
                                    <option value="single">Single Class</option>
                                    <option value="multiple">Multiple Classes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3" id="multiple_cls_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '726') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="m_classlist[]" id="m_listclass" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3" id="single_cls_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '726') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="s_classlist" id="s_listclass" onchange = "getstudent(this.value)">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="studnt_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '725') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="studentlist[]" id="liststudent" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="parnt_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '449') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="parentlist[]" id="listparent" multiple>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '704') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '705') { echo $langlbl['title'] ; } } ?> *">
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '706') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                  <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"  name="schedule_date" id="schedule_date" required/>
                                  <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '707') { echo $langlbl['title'] ; } } ?></label>
                            <div class="form-group">
                                <input type="file" class="form-control" name="attachmnt" accept="jpg, .jpeg, .png, .doc, .pdf, .docx, .xls, .xlsx">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '716') { echo $langlbl['title'] ; } } ?></label>
                            <div class="form-group">
                                <textarea id="descnotify" name="descnotify"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="error" id="notifyerror"></div>
                            <div class="success" id="notifysuccess"></div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="mt-4 ml-4">
                                    <button type="submit" id="addnotifybtn" class="btn btn-primary addnotifybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '802') { echo $langlbl['title'] ; } } ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    



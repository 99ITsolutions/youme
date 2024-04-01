<?php
    $school_id =$this->request->session()->read('company_id');
    $gender = array('Male','Female');
    
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '2092') { $lbl2092 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2093') { $lbl2093 = $langlbl['title'] ; } 
        
    } 
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
                <div class="row">
                    <h2 class="heading col-md-6 align-left"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1097') { echo $langlbl['title'] ; } } ?></h2>
                    <div class="col-md-6 align-right" >
                        <a href="<?=$baseurl?>teacherNotifications" title="Back" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1077') { echo $langlbl['title'] ; } } ?></a>
                    </div>
                </div>
            </div>
            <div class="body"> 
                <?php echo $this->Form->create(false , ['url' => ['action' => 'addnotify'] , 'id' => "taddnotifyform", 'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1098') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="notify_to" id="notify_to" required onchange="schoolnotify(this.value)">
                                    <option value="all"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1099') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="students"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1100') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="parents"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1101') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3" id="cls_opt" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1420') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="classoptn" id="classoptn" onchange = "teacherclasschnge(this.value)" >
                                    <option value=""> Choose Class Option</option>
                                    <option value="single"><?= $lbl2092 ?></option>
                                    <option value="multiple"><?= $lbl2093 ?></option>
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
                                <select class="form-control js-states" name="s_classlist" id="s_listclass" onchange = "gettchrstudent(this.value)">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="studnt_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1100') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="studentlist[]" id="liststudent" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="parnt_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1101') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="parentlist[]" id="listparent" multiple>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1102') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1103') { echo $langlbl['title'] ; } } ?> *">
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1205') { echo $langlbl['title'] ; } } ?>*</label>
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
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1105') { echo $langlbl['title'] ; } } ?></label>
                            <div class="form-group">
                                <input type="file" class="form-control" name="attachmnt" >
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1108') { echo $langlbl['title'] ; } } ?>*</label>
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
                                    <button type="submit" id="addnotifybtn" class="btn btn-primary addnotifybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1118') { echo $langlbl['title'] ; } } ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    



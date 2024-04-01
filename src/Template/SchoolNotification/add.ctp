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
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '444') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body"> 
                <?php echo $this->Form->create(false , ['url' => ['action' => 'addnotify'] , 'id' => "saddnotifyform", 'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '445') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="notify_to" id="notify_to" required onchange="schoolnotify(this.value)">
                                    <option value="all"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '446') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="teachers"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '447') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="students"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '448') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="parents"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '449') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="tchr_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '447') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="teacherlist[]" id="listteacher" multiple>
                                    <?php $tchrids =[];
                                    foreach($empdetails as $val) { 
                                        $tchrids[] = $val['id'];
                                    }
                                    $tchridss = implode(",", $tchrids); ?>
                                    <option value="<?= $tchridss ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2096') { echo $langlbl['title'] ; } } ?></option>
                                    <?php 
                                    foreach($empdetails as $val) { ?>
                                        <option value="<?= $val['id'] ?>" > <?= $val['f_name']." ". $val['l_name'] ."(".  $val['email'] .") "?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3" id="cls_opt" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1420') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="classoptn" id="classoptn" onchange = "schoolclasschnge(this.value)" >
                                    <option value=""> Choose Class Option</option>
                                    <option value="single"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2092') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="multiple"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2093') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3" id="multiple_cls_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '239') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="m_classlist[]" id="m_listclass" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3" id="single_cls_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '239') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="s_classlist" id="s_listclass" onchange = "getschoolstudent(this.value)">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="studnt_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '448') { echo $langlbl['title'] ; } } ?>*</label>
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
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '435') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?> *">
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '436') { echo $langlbl['title'] ; } } ?>*</label>
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
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '450') { echo $langlbl['title'] ; } } ?></label>
                            <div class="form-group">
                                <input type="file" class="form-control" name="attachmnt" accept="jpg, .jpeg, .png, .doc, .pdf, .docx, .xls, .xlsx">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '453') { echo $langlbl['title'] ; } } ?>*</label>
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
                                    <button type="submit" id="addnotifybtn" class="btn btn-primary addnotifybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '463') { echo $langlbl['title'] ; } } ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    



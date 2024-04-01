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
                <h2 class="heading col-md-6 align-left"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1421') { echo $langlbl['title'] ; } } ?></h2>
                <div class="col-md-6 align-right" >
                    <a href="<?=$baseurl?>teacherNotifications" title="Back" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1077') { echo $langlbl['title'] ; } } ?></a>
                </div>
                </div>
            </div>
            <div class="body"> <?php //print_r($notify_details); ?>
                <?php echo $this->Form->create(false , ['url' => ['action' => 'editnotify'] , 'id' => "teditnotifyform", 'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>
                    <div class="row clearfix">
                        <input type="hidden" name="notify_id" value="<?= $notify_details[0]['id'] ?>" >
                        <div class="col-sm-3">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1098') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="notify_to" id="notify_to" required onchange="notify(this.value)">
                                    <option value="all" <?php if($notify_details[0]['notify_to'] == "all") { echo  "Selected"; }  ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1099') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="students" <?php if($notify_details[0]['notify_to'] == "students" ) { echo "Selected" ; } ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1100') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="parents" <?php if($notify_details[0]['notify_to'] == "parents" ) { echo "Selected" ; } ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1101') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                     
                        
                        <?php if($notify_details[0]['notify_to'] == "students") { ?>
                        <div class="col-sm-3" id="cls_opt">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1420') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="classoptn" id="classoptn" onchange = "teacherclasschnge(this.value)" >
                                    <option value=""> Choose Class Option</option>
                                    <option value="single"  <?php if( "single" ==  $notify_details[0]['class_opt'] ) { echo "Selected" ; } ?> ><?= $lbl2092 ?> </option>
                                    <option value="multiple"  <?php if( "multiple" ==  $notify_details[0]['class_opt'] ) { echo "Selected" ; } ?> ><?= $lbl2093 ?></option>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-3" id="cls_opt" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1420') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="classoptn" id="classoptn" onchange = "teacherclasschnge(this.value)" >
                                    <option value=""> Choose Class Option</option>
                                    <option value="single" ><?= $lbl2092 ?></option>
                                    <option value="multiple" ><?= $lbl2093 ?></option>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if($notify_details[0]['notify_to'] == "students" && $notify_details[0]['class_opt'] == "multiple" ) { ?>
                        <div class="col-sm-3" id="multiple_cls_list">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '726') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="m_classlist[]" id="m_listclass" multiple>
                                    <option value="all">All</option>
                                    <?php 
                                    $clses = explode(",", $notify_details[0]['class_ids']);
                                    foreach($cls_details as $val) { ?>
                                        <option value="<?= $val['id'] ?>" <?php if(in_array($val['id'], $clses )) { echo "Selected" ; } ?> > <?= $val['c_name']." ". $val['c_section'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-3" id="multiple_cls_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '726') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="m_classlist[]" id="m_listclass" multiple>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if(($notify_details[0]['notify_to'] == "students" && $notify_details[0]['class_opt'] == "single" ) || ($notify_details[0]['notify_to'] == "parents")) { ?>
                            <div class="col-sm-3" id="single_cls_list">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '726') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <select class="form-control js-states" name="s_classlist" id="s_listclass" onchange = "gettchrstudent(this.value)">
                                        <?php 
                                        foreach($cls_details as $val) { ?>
                                            <option value="<?= $val['id'] ?>" <?php if($val['id'] ==  $notify_details[0]['class_ids']) { echo "Selected" ; } ?> > <?= $val['c_name']." ". $val['c_section'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-3" id="single_cls_list" style="display:none">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '726') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <select class="form-control js-states" name="s_classlist" id="s_listclass" onchange = "gettchrstudent(this.value)">
                                    </select>
                                </div>
                            </div> 
                        <?php } ?>
                        <?php if($notify_details[0]['notify_to'] == "students" && $notify_details[0]['class_opt'] == "single" ) { ?>
                        <div class="col-sm-6" id="studnt_list">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1100') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="studentlist[]" id="liststudent" multiple>
                                    <option value="all">All</option>
                                    <?php 
                                    $studnts = explode(",", $notify_details[0]['student_ids']);
                                    foreach($std_details as $val) { ?>
                                        <option value="<?= $val['id'] ?>" <?php if(in_array($val['id'], $studnts )) { echo "Selected" ; } ?> > <?= $val['f_name']." ". $val['l_name']. " (". $val['email'].")" ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-6" id="studnt_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1100') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="studentlist[]" id="liststudent" multiple>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if($notify_details[0]['notify_to'] == "parents") { ?>
                        <div class="col-sm-6" id="parnt_list">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1101') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="parentlist[]" id="listparent" multiple>
                                    <option value="all">All</option>
                                    <?php 
                                    $parnts = explode(",", $notify_details[0]['parent_ids']);
                                    foreach($std_details as $val) { ?>
                                        <option value="<?= $val['id'] ?>" <?php if(in_array($val['id'], $parnts )) { echo "Selected" ; } ?> > <?= $val['s_f_name']. " (". $val['emergency_number'].")" ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-6" id="parnt_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1101') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="parentlist[]" id="listparent" multiple>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1102') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1103') { echo $langlbl['title'] ; } } ?> *" value="<?= $notify_details[0]['title']?>">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1205') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                  <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"  name="schedule_date" id="schedule_date" value="<?= $notify_details[0]['schedule_date']?>" required/>
                                  <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1105') { echo $langlbl['title'] ; } } ?></label>
                            <div class="form-group">
                                <?php 
                                if(!empty($notify_details[0]['attachment'])) {
                                $ex_file = explode(".", $notify_details[0]['attachment']);
                                //print_r($ex_file);
                                if($ex_file[1] == "pdf")
                                {
                                    ?>
                                    <p><a href="../../webroot/notifyattachmnt/<?= $notify_details[0]['attachment']?>" target="_blank"><?= $notify_details[0]['attachment']?></a></p>  
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <img src="../../webroot/notifyattachmnt/<?= $notify_details[0]['attachment']?>"  width="50px" height="50px" />
                                    <?php
                                }
                                }
                                ?>
                                <input type="hidden" class="form-control" name="preattachmnt" value="<?= $notify_details[0]['attachment']?>">
                                <input type="file" class="form-control" name="attachmnt" >
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1106') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <textarea id="descnotify" name="descnotify"><?= $notify_details[0]['description']?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="error" id="enotifyerror"></div>
                            <div class="success" id="enotifysuccess"></div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="mt-4 ml-4">
                                    <button type="submit" id="editnotifybtn" class="btn btn-primary editnotifybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    



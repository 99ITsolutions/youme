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
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1421') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body"> <?php //print_r($notify_details); ?>
                <?php echo $this->Form->create(false , ['url' => ['action' => 'editnotify'] , 'id' => "seditnotifyform", 'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>
                    <div class="row clearfix">
                        <input type="hidden" name="notify_id" value="<?= $notify_details[0]['id'] ?>" >
                        <div class="col-sm-3">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '445') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="notify_to" id="notify_to" required onchange="schoolnotify(this.value)">
                                    <option value="all" <?php if($notify_details[0]['notify_to'] == "all") { echo  "Selected"; }  ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '446') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="teachers" <?php if( $notify_details[0]['notify_to'] == "teachers" ) { echo "Selected" ;} ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '447') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="students" <?php if($notify_details[0]['notify_to'] == "students" ) { echo "Selected" ; } ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '448') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="parents" <?php if($notify_details[0]['notify_to'] == "parents" ) { echo "Selected" ; } ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '449') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                     
                        <?php if($notify_details[0]['notify_to'] == "teachers") { ?>
                        <div class="col-sm-6" id="tchr_list">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '447') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="teacherlist[]" id="listteacher" multiple>
                                    <?php $tchrids =[];
                                    foreach($empdetails as $val) { 
                                        $tchrids[] = $val['id'];
                                    }
                                    $tchridss = implode(",", $tchrids); ?>
                                    <option value="<?= $tchridss ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2095') { echo $langlbl['title'] ; } } ?></option>
                                    <?php 
                                    $tchrs = explode(",", $notify_details[0]['teacher_ids']);
                                    foreach($empdetails as $val) { ?>
                                        <option value="<?= $val['id'] ?>" <?php if(in_array($val['id'], $tchrs )) { echo "Selected" ; } ?> > <?= $val['f_name']." ". $val['l_name'] ."(".  $val['email'] .") "?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-6" id="tchr_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '447') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="teacherlist[]" id="listteacher" multiple>
                                    <?php $empids =[];
                                    foreach($empdetails as $val) { 
                                        $empids[] = $val['id'];
                                    }
                                    $empidss = implode(",", $empids); ?>
                                    <option value="<?= $empidss ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2095') { echo $langlbl['title'] ; } } ?></option>
                                    <?php 
                                    
                                    foreach($empdetails as $val) { ?>
                                        <option value="<?= $val['id'] ?>"  > <?= $val['f_name']." ". $val['l_name'] ."(".  $val['email'] .") "?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if($notify_details[0]['notify_to'] == "students") { ?>
                        <div class="col-sm-3" id="cls_opt">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1420') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="classoptn" id="classoptn" onchange = "schoolclasschnge(this.value)" >
                                    <option value=""> Choose Class Option</option>
                                    <option value="single"  <?php if( "single" ==  $notify_details[0]['class_opt'] ) { echo "Selected" ; } ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2092') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="multiple"  <?php if( "multiple" ==  $notify_details[0]['class_opt'] ) { echo "Selected" ; } ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2093') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-3" id="cls_opt" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1420') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-states" name="classoptn" id="classoptn" onchange = "schoolclasschnge(this.value)" >
                                    <option value=""> Choose Class Option</option>
                                    <option value="single" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2092') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="multiple" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2093') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if($notify_details[0]['notify_to'] == "students" && $notify_details[0]['class_opt'] == "multiple" ) { ?>
                        <div class="col-sm-3" id="multiple_cls_list">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '239') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="m_classlist[]" id="m_listclass" multiple>
                                    <option value="all"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2095') { echo $langlbl['title'] ; } } ?></option>
                                    <?php 
                                    $clses = explode(",", $notify_details[0]['class_ids']);
                                    foreach($cls_details as $val) { ?>
                                        <option value="<?= $val['id'] ?>" <?php if(in_array($val['id'], $clses )) { echo "Selected" ; } ?> > <?= $val['c_name']."-". $val['c_section'] ."(". $val['school_sections'] .")"?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-3" id="multiple_cls_list" style="display:none">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '239') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="m_classlist[]" id="m_listclass" multiple>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if(($notify_details[0]['notify_to'] == "students" && $notify_details[0]['class_opt'] == "single" ) || ($notify_details[0]['notify_to'] == "parents")) { ?>
                            <div class="col-sm-3" id="single_cls_list">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '239') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <select class="form-control js-states" name="s_classlist" id="s_listclass" onchange = "getschoolstudent(this.value)">
                                        <?php 
                                        foreach($cls_details as $val) { ?>
                                            <option value="<?= $val['id'] ?>" <?php if($val['id'] ==  $notify_details[0]['class_ids']) { echo "Selected" ; } ?> > <?= $val['c_name']." ". $val['c_section'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-3" id="single_cls_list" style="display:none">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '239') { echo $langlbl['title'] ; } } ?>*</label>
                                <div class="form-group">
                                    <select class="form-control js-states" name="s_classlist" id="s_listclass" onchange = "getschoolstudent(this.value)">
                                    </select>
                                </div>
                            </div> 
                        <?php } ?>
                        <?php if($notify_details[0]['notify_to'] == "students" && $notify_details[0]['class_opt'] == "single" ) { ?>
                        <div class="col-sm-6" id="studnt_list">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '448') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="studentlist[]" id="liststudent" multiple>
                                    <option value="all"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2095') { echo $langlbl['title'] ; } } ?></option>
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
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '448') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="studentlist[]" id="liststudent" multiple>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if($notify_details[0]['notify_to'] == "parents") { ?>
                        <div class="col-sm-6" id="parnt_list">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '449') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="parentlist[]" id="listparent" multiple>
                                    <option value="all"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2095') { echo $langlbl['title'] ; } } ?></option>
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
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '449') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-multiple" name="parentlist[]" id="listparent" multiple>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '435') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="title"  required placeholder="Enter Title *" value="<?= $notify_details[0]['title']?>">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '436') { echo $langlbl['title'] ; } } ?>*</label>
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
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '450') { echo $langlbl['title'] ; } } ?></label>
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
                                <input type="file" class="form-control" name="attachmnt" accept="jpg, .jpeg, .png, .doc, .pdf, .docx, .xls, .xlsx">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '453') { echo $langlbl['title'] ; } } ?>*</label>
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



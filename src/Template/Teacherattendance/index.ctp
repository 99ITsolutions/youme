<style>
/*.bg-dash
{
    background-color:#242E3B !important;
}*/

@media screen and (max-width: 444px) and (min-width: 200px) 
{
    #tchrattnmod>.buttons
    {
        display:block !important;
        text-align:left !important;
    
    }
}
</style>
<?php //print_r($emp_details);
if(!empty($emp_details))
{ ?>
    <div class="row clearfix ">
        <div class="card ">
            <div class="header row" id = "tchrattnmod">
                <h2 class="heading col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1135') { echo $langlbl['title'] ; } } ?></h2>
                <h2 class="col-md-6 text-right buttons">
                    <!--<a href="javascript:void(0);" class="btn btn-sm btn-success mt-1" data-toggle="modal" data-target="#importattendance"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1136') { echo $langlbl['title'] ; } } ?></a>
	                <a href="javascript:void(0);" class="btn btn-sm btn-success mt-1" data-toggle="modal" data-target="#addstdntattndnc"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1137') { echo $langlbl['title'] ; } } ?></a>-->
                    <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
            
                </h2>
                            
            </div>
            <div class="body">
                <div class="row clearfix">
                    <?php foreach($employees_details as $empdtl)
                    { $classname =    $empdtl['class']['c_name']."-". $empdtl['class']['c_section']." (". $empdtl['class']['school_sections'] .")";
                        /*$grades = explode(",", $empdtl['gradesName']);
                        $subjects = explode(",", $empdtl['subjectName']);
                        $gradeids = explode(",", $empdtl['grades']);
                        $subjectids = explode(",", $empdtl['subjects']);
                        
                        foreach($grades as $key=>$val):*/
	                    ?>
	                    <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash ">
                                <div class="body" style="height:90px !important;">
                                    <div class="p-15 text-light teachersubdtlss"><!--
                                        <span><b><a style="color:#FFFFFF !important" href="<?=$baseurl?>teacherSubject/<?= $subjectids[$key] ?>/<?= $gradeids[$key] ?>" class="teachersubdtldata" > <?= $grades[$key] ." (". $subjects[$key] . ")"?></a></b></span>-->
                                        <span><b><a  class="colorBtn" href="<?=$baseurl?>classAttendance?studentdetails=0&subid=<?= $empdtl['subjects']['id'] ?>&gradeid=<?= $empdtl['class']['id'] ?>" class="teachersubdtldata" > <?= $classname ." (". $empdtl['subjects']['subject_name'] . ")" ?></a></b></span>
                                        <!--<span><b><a style="color:#FFFFFF !important" href="<?=$baseurl?>teacherSubject?studentdetails=0&subid=<?= $empdtl['subjects']['id'] ?>&gradeid=<?= $empdtl['class']['id'] ?>" class="teachersubdtldata" > <?= $classname ." (". $empdtl['subjects']['subject_name'] . ")"?></a></b></span>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        //endforeach;
                    } ?>
                    
                </div>
            </div>
	    </div>
	</div>
	<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>
 <?php
}
?>

<!------------------ Import Attendance --------------------->

<div class="modal animated zoomIn" id="importattendance" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1136') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'import'] , 'id' => "addattndncecsvform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1142') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">              
                            <select name="class_sub" id="class_list" class="form-control class_list" >
                            <?php foreach($employees_details as $empdtl)
                            { 
                                $grades = explode(",", $empdtl['gradesName']);
                                $subjects = explode(",", $empdtl['subjectName']);
                                $gradeids = explode(",", $empdtl['grades']);
                                $subjectids = explode(",", $empdtl['subjects']);
                                
                                foreach($grades as $key=>$val):
        	                    ?>
        	                        <option value="<?= $gradeids[$key].",".$subjectids[$key] ?>"><?= $grades[$key] ." (". $subjects[$key] . ")"?></option>
                                <?php
                                endforeach;
                            } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1143') { echo $langlbl['title'] ; } } ?>*  <a href="webroot/Attendance.csv" download class="" style="color: #ffa812 !important;"><i class="fa fa-download"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1141') { echo $langlbl['title'] ; } } ?></i></a></label>
                        <div class="form-group">                                    
                            <input type="file" class="form-control" required  name="file" >
                            <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1139') { echo $langlbl['title'] ; } } ?></small>
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                        <div class="error" id="csverror"></div>
                        <div class="success" id="csvsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary csvbtn" id="csvbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1140') { echo $langlbl['title'] ; } } ?></button>
                    <a href="webroot/Attendance.csv" download class="btn btn-success mt-1"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1141') { echo $langlbl['title'] ; } } ?></a>
                    
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1144') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

<!------------------ Add Single Student Attendance --------------------->

<div class="modal animated zoomIn" id="addstdntattndnc" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1148') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'addattendnc'] , 'id' => "addstuattndncform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1142') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">              
                            <select name="classsub" id="class" class="form-control class" onchange="getstudentlist(this.value, this.id)" required>
                                <option value="">Choose Class</option>
                            <?php foreach($employees_details as $empdtl)
                            { 
                                $grades = explode(",", $empdtl['gradesName']);
                                $subjects = explode(",", $empdtl['subjectName']);
                                $gradeids = explode(",", $empdtl['grades']);
                                $subjectids = explode(",", $empdtl['subjects']);
                                
                                foreach($grades as $key=>$val):
        	                    ?>
        	                        <option value="<?= $gradeids[$key].",".$subjectids[$key] ?>"><?= $grades[$key] ." (". $subjects[$key] . ")"?></option>
                                <?php
                                endforeach;
                            } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12" id="studentlist">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1151') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select name="student_cls[]" id="student_cls" class="form-control class" multiple required>
                                <option value="">Choose Student</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1152') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control dobirthdatepicker" id="attdate" data-date-format="dd-mm-yyyy" name="attdate"  required placeholder="Select Date *">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1154') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select name="attendance" id="attdan"  class="form-control chngstatus">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Exception">Exception</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1158') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <textarea id="reason" name="reason" class="form-control"></textarea>
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                        <div class="error" id="addstudatterror"></div>
                        <div class="success" id="addstudattsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    
                    <button type="submit" class="btn btn-primary addstdntattndncbtn" id="addstdntattndncbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1156') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1157') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>


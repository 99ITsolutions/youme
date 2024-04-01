<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
?>
<style>
    .hide
    {
        display:none;
    }
    .input-group-text{
        font-size:0.8em;
    }
    div.dataTables_wrapper div.dataTables_paginate, #clsattndnc_table_length
    {
        display:none;
    }
    
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header row">
                <h2 class="heading col-md-6"><?= $class_details['c_name']."-".$class_details['c_section']."(".$class_details['school_sections']. ") (". $sub_details['subject_name'] .")"?> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1167') { echo $langlbl['title'] ; } } ?></h2>
                <h2 class="col-md-6 align-right"><a href="<?= $baseurl ?>teacherattendance" title="Back" class=" btn btn-primary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1162') { echo $langlbl['title'] ; } } ?> </a></h2>
            </div>
            <div class="body">
                
                <?php   echo $this->Form->create(false , ['url' => ['action' => 'index?studentdetails=0&subid='.$subjectid.'&gradeid='.$classid] , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="container row ">
                        <div class="col-sm-2" style="max-width:10.5%">
                            <div class="form-group">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1153') { echo $langlbl['title'] ; } } ?>*</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?php if(!empty($chosedate)) { ?>
                                <input type="text" class="form-control dobirthdatepicker"  data-date-format="dd-mm-yyyy" name="choosedate" value="<?= $chosedate?>"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1160') { echo $langlbl['title'] ; } } ?> *">                              
                                <?php } else { ?>
                                <input type="text" class="form-control dobirthdatepicker"  data-date-format="dd-mm-yyyy" name="choosedate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1160') { echo $langlbl['title'] ; } } ?> *">                              
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary choosedatebtn" id="choosedatebtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1161') { echo $langlbl['title'] ; } } ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <?php echo $this->Form->create(false , ['url' => ['action' => 'updateattandance?studentdetails=0&subid='.$subjectid.'&gradeid='.$classid] , 'id' => 'updateattendanceform', 'method' => "post"  ]); ?>
                        <div class="table-responsive">
                            <input type="hidden" name="selecteddate" value="<?= $chosedate?>" >
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="clsattndnc_table" data-page-length='-1'>
                                <thead class="thead-dark">
                                    <tr>
                                        <!--<th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '237') { echo $langlbl['title'] ; } } ?></th>-->
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1166') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1167') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1168') { echo $langlbl['title'] ; } } ?></th>
                                        <!--<th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1169') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1170') { echo $langlbl['title'] ; } } ?></th>-->
                                    </tr>
                                </thead>
                                <tbody id="clsattndnc_body" class="modalrecdel"> 
                                    <?php
                                    $holidy = [];
                                    $holitype = [];
                                    $holidesc = [];
                                        foreach($holiday_details as $holi)
                                        {
                                            $holidy[] = $holi['date'];
                                            $holitype[] = $holi['holi_type'];
                                            $holidesc[] = $holi['descs'];
                                        }
                                        $seldate = date("Y-m-d", strtotime($chosedate));
                                        //print_r($holidy);
                                        $i = 0;
                                        $today = date('d-m-Y', time());
                                        foreach($stud_details as $value)
                                        { 
                                            if(in_array($seldate, $holidy))
                                            {
                                                $keyhol = array_search($seldate, $holidy);
                                            ?>
                                                <tr>
                                                    <td><?= $value['l_name']." ".$value['f_name'] ?></td>
                                                    <td> Holiday : <?= $holitype[$keyhol] ?></td>
                                                    <td><?= $holidesc[$keyhol] ?></td>
                                                </tr>
                                            <?php }
                                            else
                                            {
                                            $disable = $value['added_by'] != "" ? "disabled" : "";
                                            //$disable = $value['attendance'] != "" ? "disabled" : "";
                                            
                                            if($chosedate == $today) {  ?>
                                            <tr>
                                            <td><?= $value['l_name']." ".$value['f_name'] ?></td>
                                            <td>
                                                <input type="hidden" name="attendcount[]" value="<?= $value['id'] ?>">
                                                <input type="hidden" name="studentid<?= $i ?>" value="<?= $value['id'] ?>">
                                                <span class="mr-2 ml-1 pr-1"><input type="radio" <?= $disable ?> name="attendance<?= $i ?>" id="attendance<?= $i ?>" <?php if($value['attendance'] == "Present") { echo "checked"; } ?> value="Present" required onclick="reasonbox(<?= $i ?>)"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '277') { echo $langlbl['title'] ; } } ?></span>
                                                <span class="mr-2 ml-1 pr-1"><input type="radio" <?= $disable ?>  name="attendance<?= $i ?>" id="attendance<?= $i ?>" <?php if($value['attendance'] == "Absent" || $value['attendance'] == "Exception") { echo "checked"; } ?> value="Absent" onclick="reasonbox(<?= $i ?>)"> Absent</span>
                                                <!--<span class="mr-2 ml-1 pr-1"><input type="radio" name="attendance<?= $i ?>" id="attendance<?= $i ?>" <?php if($value['attendance'] == "Exception") { echo "checked"; } ?> value="Exception" onclick="reasonbox(<?= $i ?>)" > Exception</span>-->
                                            </td>
                                            <td><input type="textbox" class="form-control" <?= $disable ?> name="reason<?= $i ?>" id="reason<?= $i ?>" value="<?= $value['reason'] ?>" ></td>
                                            </tr>
                                            <?php
                                            $i++;
                                            }
                                            else
                                            { 
                                                /*if($value['attendance'] == "" && $value['attdate'] == "")
                                                { ?>
                                                    <tr>
                                                    <td><?= $value['f_name']." ".$value['l_name'] ?></td>
                                                    <td>
                                                        <input type="hidden" name="attendcount[]" value="<?= $value['id'] ?>">
                                                        <input type="hidden" name="studentid<?= $i ?>" value="<?= $value['id'] ?>">
                                                        <span class="mr-2 ml-1 pr-1"><input type="radio" name="attendance<?= $i ?>" id="attendance<?= $i ?>" <?php if($value['attendance'] == "Present") { echo "checked"; } ?> value="Present" required onclick="reasonbox(<?= $i ?>)"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '277') { echo $langlbl['title'] ; } } ?></span>
                                                        <span class="mr-2 ml-1 pr-1"><input type="radio" name="attendance<?= $i ?>" id="attendance<?= $i ?>" <?php if($value['attendance'] == "Absent" || $value['attendance'] == "Exception") { echo "checked"; } ?> value="Absent" onclick="reasonbox(<?= $i ?>)"> Absent</span>
                                                        <!--<span class="mr-2 ml-1 pr-1"><input type="radio" name="attendance<?= $i ?>" id="attendance<?= $i ?>" <?php if($value['attendance'] == "Exception") { echo "checked"; } ?> value="Exception" onclick="reasonbox(<?= $i ?>)" > Exception</span>-->
                                                    </td>
                                                    <td><input type="textbox" class="form-control" name="reason<?= $i ?>" id="reason<?= $i ?>" value="<?= $value['reason'] ?>" ></td>
                                                    </tr>
                                                <?php 
                                                } else {*/?>
                                                <tr>
                                                <td><?= $value['f_name']." ".$value['l_name'] ?></td>
                                                <td><?= $value['attendance'] ?></td>
                                                <td><?= $value['reason'] ?></td>
                                                </tr>
                                            <?php //}
                                            }
                                            }
                                        }
                                    ?>
        	                    </tbody>
                            </table>
                        </div>
                        <div class="row col-md-12">
                            <div class="error" id="attendnerror"></div>
                            <div class="success" id="attendnsuccess"></div>
                        </div>
                        <?php 
                        $today = date('d-m-Y', time());
                        if($chosedate == $today) { ?>
                        <div class="col-sm-12">
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary submitattndnbtn" id="submitattndnbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1161') { echo $langlbl['title'] ; } } ?> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '14') { echo $langlbl['title'] ; } } ?></button>
                            </div>
                        </div>
                        <?php }
                        echo $this->Form->end(); ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>    
<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
    function reasonbox(val)
    {
        //alert("hi");
        var getatt = $("input[type='radio'][name='attendance"+val+"']:checked").val();
        if(getatt == "Present")
        {
            $("#reason"+val).prop("required", false);
        }
        else
        {
            $("#reason"+val).prop("required", true);
        }
    }
</script>


<!------------------ Add Single Student Attendance --------------------->

<div class="modal animated zoomIn" id="editattendance" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1505') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editattendnc'] , 'id' => "editstuattndncform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1142') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">              
                            
                            <select name="classsub" id="class" class="form-control class" onchange="getstudentlist(this.value, this.id)" required>
                            <?php foreach($employees_details as $empdtl)
                            { 
                                $grades = explode(",", $empdtl['gradesName']);
                                $subjects = explode(",", $empdtl['subjectName']);
                                $gradeids = explode(",", $empdtl['grades']);
                                $subjectids = explode(",", $empdtl['subjects']);
                                
                                foreach($grades as $key=>$val):
                                    $va = $gradeids[$key].",".$subjectids[$key];
        	                    ?>
        	                        <option value="<?= $gradeids[$key].",".$subjectids[$key] ?>" <?php if($va == $classSub) {echo "selected"; } ?>><?= $grades[$key] ." (". $subjects[$key] . ")"?></option>
                                <?php
                                endforeach;
                            } ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="atid">
                    <div class="col-md-12" id="studentlist">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1151') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select name="student_cls" id="student_cls" class="form-control class" required>
                                <?php foreach($stud_details as $stdnt)
                                { 
            	                      echo '<option value="'.$stdnt->id.'">'.$stdnt->f_name.' '.$stdnt->l_name.' ('.$stdnt->adm_no.')</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1151') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control dobirthdatepicker" id="eattdate" data-date-format="dd-mm-yyyy" name="eattdate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1152') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1153') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select name="attendance" id="attendance" class="form-control chngstatus">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Exception">Exception</option>
                            </select>
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                        <div class="error" id="editstudatterror"></div>
                        <div class="success" id="editstudattsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    
                    <button type="submit" class="btn btn-primary editstdntattndncbtn" id="editstdntattndncbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1157') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

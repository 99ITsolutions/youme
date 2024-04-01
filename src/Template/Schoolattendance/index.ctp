<style>
.bg-dash
{
    background-color:#242E3B !important;
}
@media screen and (max-width: 440px) and (min-width: 200px)
{
    .sclstuattmodule>.col-md-8
    {
       text-align:left !important;
    }
    .sclstuattmodule>.col-md-8>.btn
    {
       display:block !important;
       width:60% !important;
    }
    
}
 div.dataTables_wrapper div.dataTables_paginate
    {
        display:none;
    }   
</style>

    <div class="row clearfix ">
        <div class="card ">
            <div class="header">
                <div class="row sclstuattmodule">
                <h2 class="heading col-md-4"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '245') { echo $langlbl['title'] ; } } ?></h2>
                <div class="col-md-8 text-right">
                    <!--<a href="javascript:void(0);" class="btn btn-sm btn-success mt-1" data-toggle="modal" data-target="#importattendance"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '262') { echo $langlbl['title'] ; } } ?></a>
	                <a href="javascript:void(0);" class="btn btn-sm btn-success mt-1" data-toggle="modal" data-target="#addstdntattndnc"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '263') { echo $langlbl['title'] ; } } ?></a>-->
	                <?php if(!empty($sclsub_details[0]))
                    { 
                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                        if(in_array("25", $roles)) { ?>
                            <a href="<?=$baseurl?>holiday" class="btn btn-sm btn-success mt-1" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1953') { echo $langlbl['title'] ; } } ?></a>
                        <?php }
                    } else { ?>
                        <a href="<?=$baseurl?>holiday" class="btn btn-sm btn-success mt-1" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1953') { echo $langlbl['title'] ; } } ?></a>
                    <?php } ?>
	                
                    
                    <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                </div>
                  </div>          
            </div>
            <div class="body">
                <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="container row ">
                        <input type="hidden" value="<?= $sessionid ?>" id="select_year" name="start_year" >
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '570') { echo $langlbl['title'] ; } } ?>*</label>
                                <!--<select name="class_list" id="class_list" class="form-control class_list" required onchange="getsubjectattendance(this.value);">-->
                                <?php if(!empty($classid)) { ?>
                                    <select name="class_list" id="class_list" class="form-control class_list" required onchange="attendance_studentreport(this.value);">
                                        <option value="">Choose Class</option>
                                        <?php foreach($class_details as $clsdtl)
                                        {
                                            if(!empty($sclsub_details[0]))
                                            { 
                                                //echo "subadmin";
                                                if(strtolower($clsdtl['school_sections']) == "creche" || strtolower($clsdtl['school_sections']) == "maternelle") {
                                                    $clsmsg = "kindergarten";
                                                }
                                                elseif(strtolower($clsdtl['school_sections']) == "primaire") {
                                                    $clsmsg = "primaire";
                                                }
                                                else
                                                {
                                                    $clsmsg = "secondaire";
                                                }
                                                $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                                //print_r($subpriv);
                                                $clsmsg = trim($clsmsg);
                                                if(in_array($clsmsg, $subpriv)) { 
                                                    $show = 1;
                                                }
                                                else
                                                {
                                                    $show = 0;
                                                }
                                            } else { 
                                                $show = 1;
                                            }
                                            if($show == 1) {
                    	                    ?>
                    	                    <option value="<?= $clsdtl['id'] ?>" <?php if($clsdtl['id'] == $classid) { echo "selected"; } ?>><?= $clsdtl['c_name'] ."-". $clsdtl['c_section'] ." (". $clsdtl['school_sections'] ." )"?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                <?php } else { ?>
                                    <select name="class_list" id="class_list" class="form-control class_list" required onchange="attendance_studentreport(this.value);">
                                        <option value="">Choose Class</option>
                                        <?php foreach($class_details as $clsdtl)
                                        {
                                            if(!empty($sclsub_details[0]))
                                            { 
                                                //echo "subadmin";
                                                if(strtolower($clsdtl['school_sections']) == "creche" || strtolower($clsdtl['school_sections']) == "maternelle") {
                                                    $clsmsg = "kindergarten";
                                                }
                                                elseif(strtolower($clsdtl['school_sections']) == "primaire") {
                                                    $clsmsg = "primaire";
                                                }
                                                else
                                                {
                                                    $clsmsg = "secondaire";
                                                }
                                                $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                                //print_r($subpriv);
                                                $clsmsg = trim($clsmsg);
                                                if(in_array($clsmsg, $subpriv)) { 
                                                    $show = 1;
                                                }
                                                else
                                                {
                                                    $show = 0;
                                                }
                                            } else { 
                                                $show = 1;
                                            }
                                            if($show == 1) {
                    	                    ?>
                    	                    <option value="<?= $clsdtl['id'] ?>"><?= $clsdtl['c_name'] ."-". $clsdtl['c_section']." (". $clsdtl['school_sections'] ." )" ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                <?php } ?>
                                
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '571') { echo $langlbl['title'] ; } } ?>*</label>
                                
                                <?php if(empty($studid)) { ?>
                                    <select name="studentdef" id="studentdef" class="form-control studentchose" required >
                                        <option value="">Choose Student</option>
                                    </select>
                                <?php } else { ?>
                                    <select name="studentdef" id="studentdef" class="form-control studentchose" required >
                                        <option value="">Choose Student</option>
                                        <?php foreach($studlist_details as $studdtl)
                                        { ?>
                    	                    <option value="<?= $studdtl['id'] ?>" <?php if($studdtl['id'] == $studid) { echo "selected"; } ?>><?= $studdtl['l_name'] ."-". $studdtl['f_name']." (". $studdtl['adm_no'] ." )" ?></option>
                                            <?php
                                        } ?>
                                    </select>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '247') { echo $langlbl['title'] ; } } ?>*</label>
                                <?php if(!empty($chosedate)) { ?>
                                <input type="text" class="form-control fordatepicker" id="choosedate" data-date-format="dd-mm-yyyy" name="choosedate" value="<?= $chosedate?>"  required="" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '247') { echo $langlbl['title'] ; } } ?> *">
                                
                                <?php } else { ?>
                                <input type="text" class="form-control fordatepicker" id="choosedate" data-date-format="dd-mm-yyyy" name="choosedate" required="" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '247') { echo $langlbl['title'] ; } } ?> *">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary choosedatebtn" id="choosedatebtn" style="margin-top:1.6rem!important"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '250') { echo $langlbl['title'] ; } } ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                
                
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <?php echo $this->Form->create(false , ['url' => ['action' => 'updateattandance?studentdetails=0&subid='.$subjectid.'&gradeid='.$classid] , 'id' => 'updateattendanceform', 'method' => "post"  ]); ?>
                        <div class="table-responsive">
                            <input type="hidden" name="selecteddate" value="<?= $chosedate ?>" >
                            <input type="hidden" name="selectedclass" value="<?= $classid ?>" >
                            <input type="hidden" name="selectedstudent" value="<?= $studid ?>" >
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="sclattndnc_table" data-page-length='-1'>
                                <thead class="thead-dark">
                                    <tr>
                                        <!--<th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '261') { echo $langlbl['title'] ; } } ?></th>-->
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '210') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '244') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '253') { echo $langlbl['title'] ; } } ?></th><!--
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '259') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '72') { echo $langlbl['title'] ; } } ?></th>-->
                                    </tr>
                                </thead>
                                <tbody id="sclattndnc_body" class="modalrecdel"> 
                                    <?php
                                    $holidy = [];
                                    $holitype = [];
                                    $holidesc = [];
                                    //print_r($stud_details); die;
                                        foreach($holiday_details as $holi)
                                        {
                                            $holidy[] = $holi['date'];
                                            $holitype[] = $holi['holi_type'];
                                            $holidesc[] = $holi['descs'];
                                        }
                                        $seldate = date("Y-m-d", strtotime($chosedate));
                                        $i = 0;
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
                                                if($value['attendance'] == "") { ?>
                                                <tr>
                                               
                                                <td><?= $value['l_name']." ".$value['f_name'] ?></td>
                                                <td>
                                                    <input type="hidden" name="attendcount[]" value="<?= $value['id'] ?>">
                                                    <input type="hidden" name="studentid<?= $i ?>" value="<?= $value['id'] ?>">
                                                    <span class="mr-2 ml-1 pr-1"><input type="radio" name="attendance<?= $i ?>" id="attendance<?= $i ?>" value="Present" required onclick="reasonbox(<?= $i ?>)"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '277') { echo $langlbl['title'] ; } } ?></span>
                                                    <span class="mr-2 ml-1 pr-1"><input type="radio"  name="attendance<?= $i ?>" id="attendance<?= $i ?>" value="Absent" onclick="reasonbox(<?= $i ?>)"> Absent</span>
                                                    <span class="mr-2 ml-1 pr-1"><input type="radio" name="attendance<?= $i ?>" id="attendance<?= $i ?>" value="Exception" onclick="reasonbox(<?= $i ?>)" > Exception</span>
                                                </td>
                                                <td><input type="textbox" class="form-control" name="reason<?= $i ?>" id="reason<?= $i ?>"></td>
                                                </tr>
                                                <?php
                                                }
                                                else { ?>
                                                <tr>
                                                    <td><?= $value['l_name']." ".$value['f_name'] ?></td>
                                                    <td><?= $value['attendance'] ?></td>
                                                    <td><?= ucfirst($value['reason']) ?></td>
                                                </tr>
                                                <?php }
                                            }
                                            $i++;
                                        }
                                    //}
                                    ?>
        	                    </tbody>
                            </table>
                        </div>
                        <div class="row col-md-12">
                            <div class="error" id="attendnerror"></div>
                            <div class="success" id="attendnsuccess"></div>
                        </div>
                        <?php 
                        //echo $chosedate;
                        $today = date('d-m-Y', time());
                        if($stud_details[0]['attendance'] == "") { ?>
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
	<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>
 



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

<!------------------ Edit Single Student Attendance --------------------->

<!--<div class="modal animated zoomIn" id="editattendance" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1522') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editattendnc'] , 'id' => "editsclattndncform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '271') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">              
                            <select name="classsub" id="class" class="form-control class_s class_sub"  onchange="getstudentlistattendance(this.value, this.id)" required>
                                <option value="">Choose Class</option>
                                <?php foreach($class_details as $clsdtl)
                                {
                                    ?>
            	                    <option value="<?= $clsdtl['id'] ?>"><?= $clsdtl['c_name'] ."-". $clsdtl['c_section']." (". $clsdtl['school_sections'] ." )" ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="atid">
                    <div class="col-md-12" id="studentlist">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '273') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select name="student_cls" id="studentid" class="form-control studentlisting" required>
                                <?php foreach($stud_details as $stdnt)
                                { 
            	                      echo '<option value="'.$stdnt->id.'">'.$stdnt->f_name.' '.$stdnt->l_name.' ('.$stdnt->adm_no.')</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '276') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control dobirthdatepicker" id="eattdate" data-date-format="dd-mm-yyyy" name="eattdate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '276') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '244') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select name="attendance" id="attendance" class="form-control chngstatus">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Exception">Exception</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '278') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <textarea id="ereason" name="reason" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="editstudatterror"></div>
                        <div class="success" id="editstudattsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    
                    <button type="submit" class="btn btn-primary editstdntattndncbtn" id="editstdntattndncbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '314') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>
-->
<!------------------ Import Attendance --------------------->
<!--
<div class="modal animated zoomIn" id="importattendance" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '262') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'import'] , 'id' => "scladdattndncecsvform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '309') { echo $langlbl['title'] ; } } ?>*  <a href="webroot/SchoolAttendance.csv" download class="" style="color: #ffa812 !important;"><i class="fa fa-download"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '310') { echo $langlbl['title'] ; } } ?></i></a></label>
                        <div class="form-group">                                    
                            <input type="file" class="form-control" required  name="file" >
                            <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '315') { echo $langlbl['title'] ; } } ?></small>
                            <small id="fileHelp" class="form-text text-muted">Please Use the same date format as used in Sample File.</small>
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                        <div class="error" id="csverror"></div>
                        <div class="success" id="csvsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary csvbtn" id="csvbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '269') { echo $langlbl['title'] ; } } ?></button>
                    <a href="webroot/SchoolAttendance.csv" download class="btn btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '310') { echo $langlbl['title'] ; } } ?></a>
                    
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '314') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>-->

<!------------------ Add Single Student Attendance --------------------->

<!--<div class="modal animated zoomIn" id="addstdntattndnc" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '263') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'addattendnc'] , 'id' => "addsclattndncform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '271') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">    
                            <select name="classsingle" id="class" class="form-control eclass_s"  onchange="getstudentlistattendance(this.value, this.id)" required>
                                <option value="">Choose Class</option>
                                <?php foreach($class_details as $clsdtl)
                                {
                                    ?>
            	                    <option value="<?= $clsdtl['id'] ?>"><?= $clsdtl['c_name'] ."-". $clsdtl['c_section']." (". $clsdtl['school_sections'] ." )" ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12" id="studentlist">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '273') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select name="student_cls[]" id="student_cls" class="form-control class" multiple required>
                                <option value="">Choose student</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '276') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control dobirthdatepicker" id="attdate" data-date-format="dd-mm-yyyy" name="attdate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '276') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '244') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select name="attendance" id="attdan"  class="form-control chngstatus">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Exception">Exception</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '278') { echo $langlbl['title'] ; } } ?></label>
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
                    
                    <button type="submit" class="btn btn-primary addstdntattndncbtn" id="addstdntattndncbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '279') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '314') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>-->
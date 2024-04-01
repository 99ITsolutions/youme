<?php
    $statusarray = array('Inactive','Active' );
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1837') { $sundylbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '1830') { $mndylbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '1831') { $tuesdylbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '1832') { $wedlbl = $langlbl['title'] ; }
        if($langlbl['id'] == '1833') { $thurdylbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '1834') { $frilbl = $langlbl['title'] ; }
        if($langlbl['id'] == '1835') { $satlbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '910') { $lbl910 = $langlbl['title'] ; }
        if($langlbl['id'] == '1554') { $lbl1554 = $langlbl['title'] ; }
        if($langlbl['id'] == '1555') { $lbl1555 = $langlbl['title'] ; }
        if($langlbl['id'] == '1558') { $lbl1558 = $langlbl['title'] ; }
        if($langlbl['id'] == '1559') { $lbl1559 = $langlbl['title'] ; }
        if($langlbl['id'] == '1412') { $lbl1412 = $langlbl['title'] ; }
        if($langlbl['id'] == '355') { $lbl355 = $langlbl['title'] ; }
        if($langlbl['id'] == '365') { $lbl365 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1448') { $lbl1448 = $langlbl['title'] ; }
        if($langlbl['id'] == '1556') { $lbl1556 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1557') { $lbl1557 = $langlbl['title'] ; }
        if($langlbl['id'] == '72') { $lbl72 = $langlbl['title'] ; }
        if($langlbl['id'] == '396') { $lbl396 = $langlbl['title'] ; } 
        if($langlbl['id'] == '397') { $lbl397 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1825') { $lbl1825 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1826') { $lbl1826 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1827') { $lbl1827 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1828') { $lbl1828 = $langlbl['title'] ; } 
    }
?>
<style>
    .input-group-append
    {
        display:none !important;
    }
</style>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <h2 class="heading col-md-6 align-left"><?= $lbl1554 ?></h2>
                                <div class="col-md-6 align-right">
    					           <?php if(!empty($sclsub_details[0]))
                                    { 
                                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                        if(in_array("96", $roles)) { ?>
                                            <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addtable"><?= $lbl910 ?></a>
    					                <?php } if(in_array("98", $roles)) { ?>
    					                    <a href="<?= $baseurl?>timetable/view" title="View" class="btn btn-info"><?= $lbl1555 ?></a>
                                `       <?php }
                                    } else { ?>
                                        <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addtable"><?= $lbl910 ?></a>
    					                <a href="<?= $baseurl?>timetable/view" title="View" class="btn btn-info"><?= $lbl1555 ?></a>
                                `   <?php } ?>
    					           <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem timetbl_table" id="timetbl_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><?= $lbl355 ?></th>
                                            <th><?= $lbl365 ?></th>
                                            <th><?= $lbl1448 ?></th>
                                            <th><?= $lbl1556 ?></th>
                                            <th><?= $lbl1557 ?></th>
                                            <th><?= $lbl72 ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="timetblbody" class="modalrecdel"> 
                                        <?php foreach($timetbl_details as $val) 
                                        { 
                                        if($val['week_day'] == "Sunday")
                                        {
                                            $weekdaylbl = $sundylbl;
                                        }
                                        elseif($val['week_day'] == "Monday")
                                        {
                                            $weekdaylbl = $mndylbl;
                                        }
                                        elseif($val['week_day'] == "Tuesday")
                                        {
                                            $weekdaylbl = $tuesdylbl;
                                        }
                                        elseif($val['week_day'] == "Wednesday")
                                        {
                                            $weekdaylbl = $wedlbl;
                                        }
                                        elseif($val['week_day'] == "Thursday")
                                        {
                                            $weekdaylbl = $thurdylbl;
                                        }
                                        elseif($val['week_day'] == "Friday")
                                        {
                                            $weekdaylbl = $frilbl;
                                        }
                                        elseif($val['week_day'] == "Saturday")
                                        {
                                            $weekdaylbl = $satlbl;
                                        }
                                        if($val['show'] == 1) {
                                        ?>
                                            <tr>
                                                <td><?= $val['className']; ?></td>
                                                <td><?= $val['subjectName']; ?></td>
                                                <td><?= $val['tchr_name']; ?></td>
                                                <td><?= $weekdaylbl ?></td>
                                                <td><?= date("H:i", strtotime($val['start_time'])); ?></td>
                                                <!--<td><?= date("h:i A", strtotime($val['end_time'])); ?></td>-->
                                                <td>
                                                    <?php if(!empty($sclsub_details[0]))
                                                    { 
                                                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                                        if(in_array("97", $roles)) { ?>
                                                            <button type="button" data-id="<?= $val['id'] ?>" class="btn btn-sm btn-outline-secondary edittimetbl" data-toggle="modal"  data-target="#edittable" title="Edit"><i class="fa fa-edit"></i></button>
                    					                <?php } if(in_array("99", $roles)) { ?>
                    					                    <button type="button" data-id="<?=$val['id']?>" data-url="timetable/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Time Table" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                       <?php }
                                                    } else { ?>
                                                        <button type="button" data-id="<?= $val['id'] ?>" class="btn btn-sm btn-outline-secondary edittimetbl" data-toggle="modal"  data-target="#edittable" title="Edit"><i class="fa fa-edit"></i></button>
                                                        <button type="button" data-id="<?=$val['id']?>" data-url="timetable/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Time Table" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                  <?php } ?>
                                                    
                                                </td>
                                            </tr>
                                        <?php 
                                        } } ?>                                     
				                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
            </div>
            
            <div class="row clearfix">
            </div>


        </div>
    </div>

 <!------------------ Add Class Subject--------------------->

    
<div class="modal classmodal animated zoomIn" id="addtable" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl1558 ?> <span id="sclst" style="display:none;">(<?= $lbl1827 ?>: <span id="scltymgs"></span> )</span></h6>
		    <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addtimetbl'] , 'id' => "addtimetblform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <!--<input type="hidden" id="strt_tym">
                    <input type="hidden" id="end_tym">
                    <input type="hidden" id="slotss">-->
                    
                    <div class="col-md-6">
                        <div class="form-group">         
                            <label><?= $lbl355 ?></label>
                            <select class="form-control class_s" id="class" name="class" placeholder="Choose Class" onchange="getsub(this.value)">
                                <option value="">Choose Class</option>
                                <?php
                                foreach($class_details as $key => $val){
                                    if(!empty($sclsub_details[0]))
                                    { 
                                        //echo "subadmin";
                                        if(strtolower($val['school_sections']) == "creche" || strtolower($val['school_sections']) == "maternelle") {
                                            $clsmsg = "kindergarten";
                                        }
                                        elseif(strtolower($val['school_sections']) == "primaire") {
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
                                      <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']."(" . $val['school_sections'].")";?> </option>
                                    <?php
                                    }
                                }
                                ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">    
                            <label><?= $lbl1556 ?></label>
                            <select class="form-control weekday" name="weekday" id="weekday" placeholder="Choose Weekday" required>
                                <option value=""><?= $lbl1825 ?></option>
                                <!--<option value="Sunday"><?= $sundylbl ?></option>-->
                                <option value="Monday"><?= $mndylbl ?></option>
                                <option value="Tuesday"><?= $tuesdylbl ?></option>
                                <option value="Wednesday"><?= $wedlbl ?></option>
                                <option value="Thursday"><?= $thurdylbl ?></option>
                                <option value="Friday"><?= $frilbl ?></option>
                                <option value="Saturday"><?= $satlbl ?></option>
                            </select>
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"> 
                            <label><?= $lbl1557 ?></label>
                            <select class="form-control starttime" name="event_start_time" id="starttime" placeholder="Choose Time" required>
                                <option value=""><?= $lbl1826 ?></option></option>
                                
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label><?= $lbl365 ?></label>
                            <select class="form-control subj_s" name="subjects" id="subj_s" placeholder="Choose Subjects" onchange="gettchrinfo(this.value)">
                                <option value="">Choose Subjects</option>
                                <?php
                                foreach($subject_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['subject_name'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">    
                            <label><?= $lbl1448 ?></label>
                            <input type="text" class="form-control" name="teacher_name" id="tchr_name" readonly placeholder="<?= $lbl1448  ?>">
                            <input type="hidden" name="teacher_id" id="tchr_id">
                        </div>
                    </div>
                  
                    
                    
                    <input id="breaktime"  type="hidden" name="breaktime" value="<?php //echo  $scl_details['break_time']?>" />
    
                    <div class="col-md-12">
                        <div class="error" id="tymtblerror"></div>
                        <div class="success" id="tymtblsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addtimetblbtn" id="addtimetblbtn"><?= $lbl396 ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $lbl397 ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              



<!------------------ Edit Class Subjects--------------------->
<div class="modal classmodal animated zoomIn" id="edittable" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl1559 ?><span id="esclst" style="display:none;">(<?= $lbl1827 ?>: <span id="escltymgs"></span>)</span></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'edittimetbl'] , 'id' => "edittimetblform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-6">
                        <div class="form-group">         
                            <label><?= $lbl355 ?></label>
                            <select class="form-control eclass_s" id="eclass" name="class" placeholder="Choose Class" onchange="egetsub(this.value, this.id, 'test')">
                                <option value="">Choose Class</option>
                                <?php
                                foreach($class_details as $key => $val){
                                    if(!empty($sclsub_details[0]))
                                    { 
                                        //echo "subadmin";
                                        if(strtolower($val['school_sections']) == "creche" || strtolower($val['school_sections']) == "maternelle") {
                                            $clsmsg = "kindergarten";
                                        }
                                        elseif(strtolower($val['school_sections']) == "primaire") {
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
                                      <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']."(" . $val['school_sections'].")";?> </option>
                                    <?php
                                    }
                                }
                                ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">    
                            <label><?= $lbl1556 ?></label>
                            <select class="form-control weekday" name="weekday" id="eweekday" placeholder="Choose Weekday" required>
                                <option value=""><?= $lbl1825 ?></option>
                                <option value="Sunday"><?= $sundylbl ?></option>
                                <option value="Monday"><?= $mndylbl ?></option>
                                <option value="Tuesday"><?= $tuesdylbl ?></option>
                                <option value="Wednesday"><?= $wedlbl ?></option>
                                <option value="Thursday"><?= $thurdylbl ?></option>
                                <option value="Friday"><?= $frilbl ?></option>
                                <option value="Saturday"><?= $satlbl ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"> 
                            <label><?= $lbl1557 ?></label>
                            <select class="form-control starttime" name="event_start_time" id="etimepicker" placeholder="Choose Time" required>
                                <option value=""><?= $lbl1826 ?></option>
                                <?php
                                    /*$breaknames = $sclinfo['school_breakname'];
                                    $start = $sclinfo['strt_timings'];
                                    $end = $sclinfo['end_timings'];
                                    $slot = $sclinfo['slot'];
                                    $breaktyms = $sclinfo['school_breakstrt'];
                                    $beakslots = $sclinfo['school_breakend'];
                                    $bname = explode(",",$breaknames);
                                    $bt = explode(",",$breaktyms);
                                    $bty = [];
                                    foreach($bt as $tb)
                                    {
                                        $bty[] =  date ("G:i", $tb);
                                    }
                                   
                                    $dateTimes = SplitTime($start, $end, $slot, $breaktyms, $beakslots);
                                    
                                    print_r($dateTimes);
                                    
                                    foreach($dateTimes as $dt)
                                    {
                                        echo '<option value="'.$dt.'">'.$dt.'</option>';
                                    }*/
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label><?= $lbl365 ?></label>
                            <select class="form-control esubj_s" name="subjects" id="esubj" placeholder="Choose Subjects" onchange="egettchrinfo(this.value)">
                                <option value="">Choose Subjects</option>
                                <?php
                                foreach($subject_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['subject_name'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input id="breaktime"  type="hidden" name="breaktime" value="<?php //echo  $scl_details['break_time']?>" />
                    <div class="col-md-12">
                        <div class="form-group">    
                            <label><?= $lbl1448 ?></label>
                            <input type="text" class="form-control" name="teacher_name" id="etchr_name" readonly placeholder="<?= $lbl1448 ?>">
                            <input type="hidden" name="teacher_id" id="etchr_id">
                        </div>
                    </div>
                    
                    
                    <input type="hidden" id="editttid" name="edittid">
    
                    <div class="col-md-12">
                        <div class="error" id="etymtblerror"></div>
                        <div class="success" id="etymtblsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary edittimetblbtn" id="edittimetblbtn"><?= $lbl1412 ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $lbl397 ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php 
/*function SplitTime($StartTime, $EndTime, $Duration="60",  $breaktyms, $beakslots){
    $ReturnArray = array ();// Define output
    $StartTime    = strtotime ($StartTime); //Get Timestamp
    $EndTime      = strtotime ($EndTime); //Get Timestamp
    
    $AddMins  = $Duration * 60;
    
    $btimes = explode(",", $breaktyms);
    $bslot = explode(",", $beakslots);
    

    while ($StartTime < $EndTime) //Run loop
    {
        if(in_array($StartTime, $btimes))
        {
            $indx = array_search($StartTime, $btimes); 
            $brkslot = explode(" minutes", $bslot[$indx]);
            $slotbrk = $brkslot[0]*60;
            
            $stt = date ("H:i", $StartTime);
            $StartTime += $slotbrk; //Endtime check
        }
        else
        {
            $stt = date ("H:i", $StartTime);
            $StartTime += $AddMins; //Endtime check
            $ReturnArray[] = $stt;
        }
    }
    return $ReturnArray;
}*/
if(!empty($error)) { ?>
    <script>$("#gettterror").fadeIn().delay('5000').fadeOut('slow');</script>
<?php } ?>
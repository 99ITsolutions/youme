<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '522') { echo $langlbl['title'] ; } } ?> </h2>
                <ul class="header-dropdown">
                    
                    <?php if(!empty($sclsub_details[0])) { 
                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                        if(in_array("62", $roles)) { ?>
                            <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfeestruc"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '510') { echo $langlbl['title'] ; } } ?></a></li>
                    <?php }
                    } else { ?>
                        <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfeestruc"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '510') { echo $langlbl['title'] ; } } ?></a></li>
                    <?php } ?>
	                
	                <!--<li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#view_edit">View/edit student fee</a></li>-->
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
                            
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="tut_table_school" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '547') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '549') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '535') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '512') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '513') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '72') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="tutbody_school" class="modalrecdel"> 
                        <?php
                                    $n=1;
                                    foreach($tutorial_details as $value){
                                        if(!empty($sclsub_details[0]))
                                        { 
                                            //echo "subadmin";
                                            if(strtolower($value->class['school_sections']) == "creche" || strtolower($value->class['school_sections']) == "maternelle") {
                                                $clsmsg = "kindergarten";
                                            }
                                            elseif(strtolower($value->class['school_sections']) == "primaire") {
                                           
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
                                        <tr>
                                           <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value->class['c_name']."-".$value->class['c_section']." (".$value->class['school_sections'].")"?></span>
                                            </td>
                                            <td>
                                                <span><?= $value->subjects['subject_name']; ?></span>
                                            </td>
                                            <td>
                                                <?php if($value->frequency == "yearly")
                                                {
                                                     foreach($lang_label as $langlbl) { if($langlbl['id'] == '1804') { $frq = $langlbl['title'] ; } } 
                                                }
                                                elseif($value->frequency == "half yearly")
                                                {
                                                    foreach($lang_label as $langlbl) { if($langlbl['id'] == '1803') { $frq = $langlbl['title'] ; } } 
                                                }
                                                elseif($value->frequency == "quarterly")
                                                {
                                                    foreach($lang_label as $langlbl) { if($langlbl['id'] == '1802') { $frq = $langlbl['title'] ; } } 
                                                }
                                                elseif($value->frequency == "monthly")
                                                {
                                                    foreach($lang_label as $langlbl) { if($langlbl['id'] == '1801') { $frq = $langlbl['title'] ; } } 
                                                } ?>
                                                <span><?= ucwords($frq); ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value->session['startyear'].'-'.$value->session['endyear']; ?></span>
                                            </td>
                                            <td>
                                                <span>$<?= $value['fee']; ?></span>
                                            </td>
                                            
                                            <td>
                                                <?php if(!empty($sclsub_details[0])) { 
                                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                                    if(in_array("63", $roles)) { ?>
                                                        <button type="button" data-id='<?= $value['id'] ?>' class="btn btn-sm btn-outline-secondary editstruc_school" data-toggle="modal"  data-target="#editstruc_school" title="Edit"><i class="fa fa-edit"></i></button>
                                                    <?php } if(in_array("64", $roles)) { ?>
                                                    <button type="button" data-id="<?=$value['id']?>" data-url="SchoolTutorialfee/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Tutoring Fees" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                <?php }
                                                } else { ?>
                                                    <button type="button" data-id='<?= $value['id'] ?>' class="btn btn-sm btn-outline-secondary editstruc_school" data-toggle="modal"  data-target="#editstruc_school" title="Edit"><i class="fa fa-edit"></i></button>
                                                    <button type="button" data-id="<?=$value['id']?>" data-url="SchoolTutorialfee/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Tutoring Fees" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                <?php } ?>
                                                <!--<a href="<?=$baseurl?>fees/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>-->
                                                
                                            </td>
                                        </tr>
                                        <?php
                                        $n++;
                                        }
                                    }
                                    ?>
                                       
	                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!------------------ Add Class --------------------->

<div class="modal classmodal animated zoomIn" id="addfeestruc" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1011') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addtut'] , 'id' => "add_fee_tut_school" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '504') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control js-states" id="aclass" required name="class" data-id="asubjects" onchange="getsubjectstu(this.value, this.id)" >
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
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '549') { echo $langlbl['title'] ; } } ?></label>
                           <select class="form-control subj_s" name="subjects" id="asubjects"  data-id="ateacher" data-class="aclass" placeholder="Choose Subjects"  onchange="getteacherstu(this.value, this.id)" required>
                                <option value="">Choose Subjects</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '447') { echo $langlbl['title'] ; } } ?></label>
                           <select class="form-control listteacher" name="teacher_id" id="ateacher" placeholder="Choose Teacher" required>
                                <option value="">Choose Teacher</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '511') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control js-states " id="afrequency" required name="frequency" >
                                <option value="">Choose Frequency</option>
                                <option value="yearly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1804') { echo $langlbl['title'] ; } } ?></option>
                                <option value="half yearly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1803') { echo $langlbl['title'] ; } } ?></option>
                                <option value="quarterly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1802') { echo $langlbl['title'] ; } } ?></option>
                                <option value="monthly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1801') { echo $langlbl['title'] ; } } ?></option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '512') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control session" id="select_year" name="start_year" required>
                                <option value="">Choose Session</option>
                                <?php
                                foreach($session_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1024') { echo $langlbl['title'] ; } } ?></label>
                            <input type="number" class="form-control" name="amount"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '345') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="tuterror"></div>
                        <div class="success" id="tutsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addtutbtn" id="addtutbtn" style="margin-right: 10px;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '279') { echo $langlbl['title'] ; } } ?></button>
                    <!--<button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>-->
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              



    <!------------------ Edit Class --------------------->

    
<div class="modal classmodal  animated zoomIn" id="editstruc_school"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1540') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'edittut'] , 'id' => "edittutform_school" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="eid"  name="eid" >
                        </div>
                    </div>
		            <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '355') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control js-states" id="eclass" required name="class" data-id="esubjects" onchange="getsubjectstu(this.value, this.id)" >
                                <option value="">Choose Class</option>
                                <?php
                                foreach($class_details as $key => $val){
                                    if(!empty($sclsub_details[0]))
                                    { 
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
                                      <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']."(" . $val['school_sections'].")" ;?> </option>
                                    <?php
                                    }
                                }
                                ?>
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '365') { echo $langlbl['title'] ; } } ?></label>
                           <select class="form-control js-states" name="subjects" id="esubjects"  data-id="eteacher" data-class="eclass" placeholder="Choose Subjects"  onchange="getteacherstu(this.value, this.id)" required>
                                
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '447') { echo $langlbl['title'] ; } } ?></label>
                           <select class="form-control js-states" name="teacher_id" id="eteacher" placeholder="Choose Teacher" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '511') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control js-states " id="efrequency" required name="frequency" >
                                <option value="">Choose Frequency</option>
                                <option value="yearly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1804') { echo $langlbl['title'] ; } } ?></option>
                                <option value="half yearly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1803') { echo $langlbl['title'] ; } } ?></option>
                                <option value="quarterly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1802') { echo $langlbl['title'] ; } } ?></option>
                                <option value="monthly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1801') { echo $langlbl['title'] ; } } ?></option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '512') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control session" id="newselect_year" name="start_year" required>
                                <option value="">Choose Session</option>
                                <?php
                                foreach($session_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1024') { echo $langlbl['title'] ; } } ?></label>
                            <input type="number" class="form-control" id="eamount" name="amount"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '345') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="edittuterror"></div>
                        <div class="success" id="edittutsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary edittutbtn" id="edittutbtn" style="margin-right:15px"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
function getsubjectstu(val, id)
{
    if(val !=''){
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var get_id = $('#'+id).data('id');
        $.ajax({
            type:'POST',
            url: baseurl + '/SchoolTutorialfee/getsubjects',
            data:'classId='+val,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                if (result) {
                    $('#'+get_id).html('').trigger('change.select2');
                    $('#'+get_id).html(result).trigger('change.select2');
                
                }
            }
        });
    }
}

function getteacherstu(val,id)
{
    if(val !=''){
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var get_id = $('#'+id).data('id');
    //alert(get_id);
    var class_id_name = $('#'+id).data('class');
    var class_id = $('#'+class_id_name).val();
    $('#'+get_id).html('');
    $.ajax({
            type:'POST',
            url: baseurl + '/SchoolTutorialfee/getteachers',
            data:'classId='+class_id+'&subjectId='+val,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                if (result) {
                    console.log(result);
                    //$('#'+get_id).html('').trigger('change.select2');
                    //$('#'+get_id).html(result).trigger('change.select2');
                    $('#'+get_id).html(result);
                
                }
            }

        });
    }
}
</script>

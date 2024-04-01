<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '877') { $lbl877 = $langlbl['title'] ; } 
    if($langlbl['id'] == '643') { $lbl643 = $langlbl['title'] ; }
    if($langlbl['id'] == '287') { $lbl287 = $langlbl['title'] ; }
    if($langlbl['id'] == '943') { $lbl943 = $langlbl['title'] ; }
    if($langlbl['id'] == '944') { $lbl944 = $langlbl['title'] ; }
    if($langlbl['id'] == '939') { $lbl939 = $langlbl['title'] ; }
    if($langlbl['id'] == '940') { $lbl940 = $langlbl['title'] ; }
    if($langlbl['id'] == '941') { $lbl941 = $langlbl['title'] ; }
    if($langlbl['id'] == '1029') { $lbl1029 = $langlbl['title'] ; }
    if($langlbl['id'] == '1030') { $lbl1030 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '354') { $lbl354 = $langlbl['title'] ; }
    if($langlbl['id'] == '396') { $lbl396 =  $langlbl['title'] ; }
    if($langlbl['id'] == '397') { $lbl397 =  $langlbl['title'] ; }
    if($langlbl['id'] == '1805') { $lbl1805 = $langlbl['title'] ; }
    if($langlbl['id'] == '1806') { $lbl1806 = $langlbl['title'] ; }
    if($langlbl['id'] == '1807') { $lbl1807 = $langlbl['title'] ; }
    if($langlbl['id'] == '1465') { $lbl1465 = $langlbl['title'] ; }
} 
if(!empty($emp_details))
{ ?>
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="row header ">
	                <div class=" col-md-3 col-sm-4 mt-2 mb-2">
                        <img src="img/<?= $emp_details[0]['pict']?>" width="175px" height="150px" style="border:1px solid">
                    </div>
                    <div class=" col-md-6 col-sm-6 mt-4 mb-2" >
                        <p><b><?= $lbl877 ?>* : </b><?= $emp_details[0]['f_name'] . " ".$emp_details[0]['l_name'] ?></p>
                        <p><b>E-mail*: </b><?= $emp_details[0]['email']?></p>
                        <p><b><?= $lbl643 ?>* : </b><?= $emp_details[0]['mobile_no']?></p>
                        <p><b><?= $lbl287 ?>* : </b><?= ucfirst($emp_details[0]['quali']) ?></p>
                    </div>
                    <div class="col-md-3 align-right">
                        <a href="<?=$baseurl?>employee/profile/<?= md5($emp_details[0]['id']) ?>" title="Edit Teacher Profile" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> <?= $lbl943 ?></a>
                    </div>
	           </div>
	           <div class="body">
	               <div class="row clearfix">
	                    <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash ">
                                <div class="body" style="height:90px !important;">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF !important" href="<?=$baseurl?>teacherknowledge">  <?= $lbl944 ?></a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF" href="<?=$baseurl?>teacherLibrary"><?= $lbl939 ?></a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <?php if($tchrntfctn_count != 0) { ?>
                            <div class="card text-center bg-dash" style="background:#f3e83c !important" >
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b>
                                            <a style="color:#000000" href="<?=$baseurl?>teacherNotifications" ><?= $lbl940 ?> (<span id="tchrnotifycount"><?= $tchrntfctn_count ?></span>)</a>
                                        </b></span>
                                    </div>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b>
                                            <a style="color:#FFFFFF" href="<?=$baseurl?>teacherNotifications"><?= $lbl940 ?> (<span id="tchrnotifycount"><?= $tchrntfctn_count ?></span>)</a>
                                        </b></span>
                                    </div>
                                </div>
                            </div> 
                            <?php } ?>
                        </div>
                       
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF" href="javascript:void(0);" data-toggle="modal" data-target="#submitrequest"><?= $lbl941 ?></a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-7 text-light">
                                        <span><a class="colorBtn" href="http://learn.eltngl.com/" target="_blank"><img src="<?=$baseurl?>img/NationalGeoG1-logo.png" style="width: 100%; max-width: 280px;"  width="200px" height="68px"></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
	                <!--</div>
	                <div class="row clearfix">-->
	                    <?php foreach($empcls_details as $empdtl)
	                    {// print_r($empdtl); die;
	                        /*$grades = explode(",", $empdtl['gradesName']);
	                        $subjects = explode(",", $empdtl['subjectName']);
	                        $gradeids = explode(",", $empdtl['grades']);
	                        $subjectids = explode(",", $empdtl['subjects']);
	                        
	                        foreach($empdtl as $key=>$val):*/
	                         $classname =    $empdtl['class']['c_name']."-". $empdtl['class']['c_section']." (". $empdtl['class']['school_sections'] .")";
    	                    ?>
    	                    <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card text-center bg-dash ">
                                    <div class="body" style="height:90px !important;">
                                        <div class="p-7 text-light teachersubdtlss">
                                            <span><b><a style="color:#FFFFFF !important" href="<?=$baseurl?>teacherSubject?studentdetails=0&subid=<?= $empdtl['subjects']['id'] ?>&gradeid=<?= $empdtl['class']['id'] ?>" class="teachersubdtldata" > <?= $classname ." (". $empdtl['subjects']['subject_name'] . ")"?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                           // endforeach;
	                    } ?>
                        
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>
 <?php
}

?>
 <!------------------ Submit Request --------------------->

    
<div class="modal classmodal animated zoomIn" id="submitrequest" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl941 ?></h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'submitrequest'] , 'id' => "submitrequestform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="error" id="submitreqerror"></div>
                        <div class="success" id="submitreqsuccess"></div>
                    </div>
                </div>
                
                <div class="wrapper">
                    <div class="row clearfix" style-"margin-top:10px; margin-bottom:10px;">
                        <div class="col-md-3">
                            <div class="form-group">     
                                <label><?= $lbl1029 ?></label>
                                <!--<select class="form-control class_s" id="class" name="class" placeholder="Choose Class" required  onchange="getsubcls(this.value, this.id)">-->
                                <select class="form-control" id="s_listclass1" name="data[1][grade]" placeholder="Choose Class" required onchange="getgradeclsdata(this.value, 'add','1')">
                                    <option value="">Choose Class</option>
                                    <?php 
                                    foreach($getcls['id'] as $key => $cls)
            	                    { ?>
            	                        <!--<option value="<?= $cls ?>" ><?= $getcls['c_name'][$key] ?> </option>-->
            	                        <option value="<?= $getcls['sectns'][$key] ?>" ><?= $getcls['c_name'][$key] ?> </option>
                                    <?php } ?>
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">     
                                <label>Section(s)</label>
                                <!--<select class="form-control class_s" id="class" name="class[]" multiple placeholder="Choose Class" required  onchange="getsubcls(this.value, this.id)">-->
                                <select class="form-control class_s" id="examselclssctn1" name="data[1][class][]" multiple placeholder="Choose Class" onchange="getstdnt(this.value, 'add','1'); getsubclsdata(this.value, 'class','1');" required>
                                    <option value="">Choose sections</option>
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-3">
                                <div class="form-group">     
                                    <label>Student(s)</label>
                                    <select class="form-control stdnt_s" id="examselstdnt1" name="data[1][student][]" multiple placeholder="Choose Students" required>
                                        <option value="">Choose students</option>
                                    </select> 
                                </div>
                            </div>
                        <div class="col-md-2">
                            <div class="form-group">  
                                <label><?= $lbl1030 ?></label>
                                <select class="form-control" name="data[1][subjects]" id="all_subjects1" placeholder="Choose Subjects" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn add-btn" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '363') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control request_opt" name="request_for" id="request_for" placeholder="Choose Options" onchange="getexamtype(this.value)" required>
                                <option value="">Choose Option</option>
                                <option value="Assessment"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1796') { echo $langlbl['title'] ; } } ?></option>
                                <option value="Exams"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1244') { echo $langlbl['title'] ; } } ?></option>
                                <option value="Quiz"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1798') { echo $langlbl['title'] ; } } ?></option>
                                <option value="Study Guide"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1725') { echo $langlbl['title'] ; } } ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="examtypes" style="display:none;">
                        <div class="form-group">  
                            <label>Semestre/Trimestre</label>
                            <select class="form-control request_opt" name="exam_type" id="exam_type" placeholder="Choose Options"  onchange="getperiod(this.value)">
                                <option value="">Choose Option</option>
                                <!--<option value="Premier Trimestre">Premier Trimestre</option>
                                <option value="Deuxieme Trimestre">Deuxieme Trimestre</option>
                                <option value="Troisieme Trimestre">Troisieme Trimestre</option>-->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="examperiod" style="display:none;">
                        <div class="form-group">  
                            <label><?= $lbl2082 ?></label>
                            <select class="form-control request_opt" name="exam_period" id="exam_period" placeholder="Choose Options">
                                <option value="">Choose Option</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?></label>
                          <!--  <input type="date" class="form-control" name="start_date" id="start_date" required>-->
                            <!--<input type="text" class="form-control datetimepicker" id="datetimepicker" data-date-format="dd-mm-yyyy" name="doj"  required placeholder="Start Date & Time *">-->
                            <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"  name="start_date" id="start_date" required/>
                              <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?></label>
                           <!-- <input type="date" class="form-control" name="end_date" id="end_date" required>-->
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="end_date" id="end_date" required>
                              <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="maxmarks" >
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '371') { echo $langlbl['title'] ; } } ?></label>
                            <input type="number" name="max_marks" id="max_marks" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '372') { echo $langlbl['title'] ; } } ?>"  class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-12" style="display:block;">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></label>
                            <input type="text" name="title" id="title" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?>"  class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-12" id="guideinstr">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '373') { echo $langlbl['title'] ; } } ?></label>
                            <textarea name="instruction" id="instruction" placeholder="Enter Instruction"   class="form-control"  rows="3" required> </textarea>
                        </div>
                    </div>
                    <div class="col-md-12" id="contentuplod" >
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '374') { echo $langlbl['title'] ; } } ?> *</label>
                                <select class="form-control" required  name="contentupload" id="contentupload" onchange="contntupd(this.value)">
                                    <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '375') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '376') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="custom"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '377') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="pdfupload" style="display:none">
                        <div class="col-md-12">
                            <div class="form-group">  
                                <label><?= $lbl1465 ?></label>
                                <input type="file" name="fileupload" id="fileupload" placeholder="File Upload" class="form-control" >
                            </div>
                        </div>
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary submitreqbtn" id="submitreqbtn"><?= $lbl396 ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $lbl397 ?></button>
                        </div>
                    </div>
                    <div class="row clearfix" id="customize" style="display:none">
                        <div class="col-md-12" id="examformat" style="display:none">
                            <div class="form-group">   
                                <label><?= $lbl1805 ?></label>
                                <div class="form-group">      
                                    <div class="row container">
                                        <span class="mr-2"><input type="radio" name="exam_format" checked value="custom" class="mr-1"> <?= $lbl1806 ?></span>
                                        <span class="mr-2"><input type="radio" name="exam_format" value="pdf" class="mr-1"><?= $lbl1807 ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary submitreqbtn" id="submitreqbtn"><?= $lbl354 ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $lbl397 ?></button>
                        </div>
                    </div>
                   <?php echo $this->Form->end(); ?>
                </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
function getgradeclsdata(val, abc,g_id)
{
    if(abc == "add")
    {
        $("#examselclssctn").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var splitid = g_id;
        $("#examselclssctn"+splitid).html("");
        $("#examselstdnt"+splitid).html("");
        
        $.ajax({
            type:'POST',
            url: baseurl + '/Teacherdashboard/getsections',
            data:{'filter':val },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                $("#examselclssctn"+splitid).html(result['section']);
                $("#examselstdnt"+splitid).html(result['student']);
                //$("#examselclssctn").html(result['section']);
                //$("#examselstdnt").html(result['student']);
                
            }
        });
    }
    else if(abc == "editd")
    {
        $("#examselclssctn").html("");
        $("#eexamselclssctn").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/Teacherdashboard/getsections',
            data:{'filter':val },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                $("#examselclssctn").html(result);
                $("#eexamselclssctn").html(result);
            }
        });
    }
    else
    {
        var cls = abc.split(",");
        $("#examselclssctn").select2().val(cls).trigger('change.select2');
        $("#eexamselclssctn").select2().val(cls).trigger('change.select2');
        
        
    }
}

function getstdnt(val, abc, g_id)
{
    if(abc == "add")
    {
        $("#eguid_class").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        
        var splitid = g_id;
        $("#examselstdnt"+splitid).html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/Teacherdashboard/getstdnt',
            data:{'filter':val },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                $("#examselstdnt"+splitid).html(result['student']);
            }
        });
    }
    else if(abc == "editd")
    {
        $("#examselclssctn").html("");
        $("#eexamselclssctn").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/Teacherdashboard/getsections',
            data:{'filter':val },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                $("#examselclssctn").html(result);
                $("#eexamselclssctn").html(result);
            }
        });
    }
    else
    {
        var cls = abc.split(",");
        //console.log(cls);
        $("#examselclssctn").select2().val(cls).trigger('change.select2');
        $("#eexamselclssctn").select2().val(cls).trigger('change.select2');
    }
}

function getsubclsdata(val, ids, g_id)
{
        var splitid = g_id;
        $("#all_subjects"+splitid).html("");
        
        $('#request_for').val('').trigger('change') ;
        $('#erequest_for').val('').trigger('change') ;
        $("#examtypes").css("display","none");
        $("#examperiod").css("display","none");
        $("#eexamtypes").css("display","none");
        $("#eexamperiod").css("display","none");
        var subid = '';
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/teacherdashboard/getsubjecttchr',
            data:{'clsid':val, 'subid':subid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(data){
                $("#all_subjects"+splitid).html(data.subjectname);
            }
        });
}
    $(document).ready(function () {
 
      // allowed maximum input fields
      var max_input = 5;
 
      // initialize the counter for textbox
      var x = 1;
 
      // handle click event on Add More button
      $('.add-btn').click(function (e) {
        e.preventDefault();
        if (x < max_input) { // validate the condition
          x++; // increment the counter
          
          $('.wrapper').append(`
                <div class="row clearfix" style="margin-top:10px; margin-bottom:10px;">
                    <div class="col-md-3">
                        <div class="form-group">     
                            <label><?= $lbl1029 ?></label>
                            <select class="form-control" id="s_listclass`+x+`" name="data[`+x+`][grade]" placeholder="Choose Class" required onchange="getgradeclsdata(this.value, 'add', '`+x+`')">
                                <option value="">Choose Class</option>
                                    <?php 
                                        foreach($getcls['id'] as $key => $cls)
                	                    { ?>
                	                        <!--<option value="<?= $cls ?>" ><?= $getcls['c_name'][$key] ?> </option>-->
                	                        <option value="<?= $getcls['sectns'][$key] ?>" ><?= $getcls['c_name'][$key] ?> </option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group">     
                                <label>Section(s)</label>
                                <select class="form-control class_s" id="examselclssctn`+x+`" name="data[`+x+`][class][]" multiple placeholder="Choose Class" onchange="getstdnt(this.value, 'add', '`+x+`'); getsubclsdata(this.value, 'class', '`+x+`');" required>
                                    <option value="">Choose sections</option>
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-3">
                                <div class="form-group">     
                                    <label>Student(s)</label>
                                    <select class="form-control stdnt_s" id="examselstdnt`+x+`" name="data[`+x+`][student][]" multiple placeholder="Choose Students" required>
                                        <option value="">Choose students</option>
                                    </select> 
                                </div>
                            </div>
                        <div class="col-md-2">
                            <div class="form-group">  
                                <label><?= $lbl1030 ?></label>
                                <select class="form-control" name="data[`+x+`][subjects]" id="all_subjects`+x+`" placeholder="Choose Subjects" required>
                                </select>
                            </div>
                        </div>
                    <div class="col-md-1">
                       <a href="#" class="col-sm-2 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      })
      
      
    });
</script>
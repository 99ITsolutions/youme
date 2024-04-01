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
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '523') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <?php if(!empty($sclsub_details[0]))
                    { 
                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                        if(in_array("67", $roles)) { ?>
                            <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfee"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '528') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php }
                    } else { ?>
                        <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfee"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '528') { echo $langlbl['title'] ; } } ?></a></li>
                    <?php } ?>	
                    
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                
                        <div class="row clearfix">
                            <div class="container row ">
                                <div class="col-md-3">
                                    <div class="form-group"> 
                                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '191') { echo $langlbl['title'] ; } } ?></label>
                                        <select class="form-control session" id="select_year" name="start_year" onchange="getstudents_subject(this.value, this.id)" required>
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
                                <div class="col-sm-2">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '355') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">
                                        <select class="form-control class" id="class" name="class" required onchange="getstudents_subject(this.value, this.id)">
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
                                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']. "(".$val['school_sections'].")";?> </option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>                                    
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group"> 
                                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '365') { echo $langlbl['title'] ; } } ?></label>
                                        <select class="form-control subj_s" id="subject" name="subject" data-id="teacher" data-class="class" placeholder="Choose Subjects" onchange="getteachers_tut(this.value, this.id)" required>
                                            <option value="">Choose Subject</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">   
                                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '15') { echo $langlbl['title'] ; } } ?></label>
                                       <select class="form-control listteacher" name="teacher_id" id="teacher" placeholder="Choose Teacher" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '12') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">
                                    <select class="form-control studentchose" name="student" id="student" placeholder="Choose Student" onchange="getstudents_data(this.value, this.id)" required>
                                        <option value="">Choose Student</option>
                                    </select>  
                                    </div>
                                </div>
                            </div>
                    
                    </div>
                <div class="row clearfix">
                    <div class="col-sm-7">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="stdent_data_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '357') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '358') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '359') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '360') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '71') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                </thead>
                                <tbody id="stdent_data_body" class="modalrecdel"> 
                                               
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div id="chartContainer" style="height: 370px; width: 100%; margin-top:50px;"></div>
                       <!-- <canvas id="cvs" width="600" height="400">
                            [No canvas support]
                        </canvas>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!--<script src="https://www.rgraph.net/libraries/src/RGraph.common.core.js"></script>
<script src="https://www.rgraph.net/libraries/src/RGraph.common.key.js"></script>
<script src="https://www.rgraph.net/libraries/src/RGraph.bar.js"></script>-->

<!------------------ Add Class --------------------->

    
<div class="modal classmodal animated zoomIn" id="addfee" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '319') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
            <?php echo $this->Form->create(false , ['id' => "addstudenttutfees" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1552') { echo $langlbl['title'] ; } } ?></label>
                           <select class="form-control session" id="month" name="frequency" required>
                                <option value="">Choose Month</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '345') { echo $langlbl['title'] ; } } ?> <span id="session_amount"></span></label>
                            <input type="number" class="form-control" id="amount" name="amount"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '345') { echo $langlbl['title'] ; } } ?>  *">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="error" id="feeerror">
                        </div>
                        <div class="success" id="feesuccess">
                        </div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="addfeebtn" class="btn btn-primary"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '396') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              

<div class="modal classmodal  animated zoomIn" id="edittutfreqfee"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabelhead"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1553') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'edittutfreqfee'] , 'id' => "edittutfreqfeeform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div id="add_data" class="col-md-12">
                        
                    </div>
		            
                    <div class="col-md-12">
                        <div class="error" id="edittutfreqfeeerror"></div>
                        <div class="success" id="edittutfreqfeesuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary edittutfreqfeebtn" id="edittutfreqfeebtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1814') { $feeana =  $langlbl['title'] ; } }  ?>
<script>

$('#stdent_data_table tbody').on("click",".edittutfreqfee",function()
{
    var freq = $(this).data('freq');
    var sty = $(this).data('sty');
    var student = $(this).data('student');
    var classid = $(this).data('classid');
    var subjectid = $(this).data('subjectid');
    var teacherid = $(this).data('teacherid');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/SchoolTutorialfee/editfreq", 
        data: {"freq":freq,"sty":sty,"student":student,"classid":classid,"teacherid":teacherid,"subjectid":subjectid,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
            $('#add_data').html('');
            $('#add_data').html(result);
          }
        }
    });
});

$('#stdent_data_table tbody').on("click",".edittutfreqfee",function()
{
    var freq = $(this).data('freq');
    var sty = $(this).data('sty');
    var student = $(this).data('student');
    var classid = $(this).data('classid');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/fees/editfreq", 
        data: {"freq":freq,"sty":sty,"student":student,"classid":classid,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
            $('#add_data').html('');
            $('#add_data').html(result);
          }
        }
    });
});


$("#edittutfreqfeeform").submit(function(e){
  e.preventDefault();
  
$("#edittutfreqfeebtn").prop("disabled", true);
$("#edittutfreqfeebtn").text("Updating...");
var studentid = $("#student").val();
 $(this).ajaxSubmit({
  error: function(){
      $("#edittutfreqfeebtn").text("Update");
      $("#edittutfreqfeeerror").html("Some error occured. Please try again.") ;
      $("#edittutfreqfeeerror").fadeIn().delay('5000').fadeOut('slow');
      $("#edittutfreqfeebtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#edittutfreqfeebtn").text("Update");
      $("#edittutfreqfeebtn").prop("disabled", false);
      if(response.result === "success" ){ 
            $("#edittutfreqfeesuccess").html("Fee updated succesfully.") ;
            $("#edittutfreqfeesuccess").fadeIn();
            setTimeout(function(){ 
                getstudents_data(studentid, student);
                $('#edittutfreqfee').modal('hide'); }, 1000);
        }
        else if(response.result === "exist" ){
            $("#edittutfreqfeeerror").html("Fee Structure already exist.") ;
            $("#edittutfreqfeeerror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "empty" ){
        $("#edittutfreqfeeerror").html("Please fill in Details.") ;
        $("#edittutfreqfeeerror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#edittutfreqfeeerror").html("Some error occured. Error: "+response.result) ;
        $("#edittutfreqfeeerror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

function getstudents_data(get_val, id)
{
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
	var baseurl = "/" + baseurl;
	
    var select_year = $("#select_year").val();
    var new_val = $("#class").val();
    var subject = $("#subject").val();
    var teacher_id = $("#teacher").val();
    if(teacher_id !='' && subject !='' && new_val != '' && select_year !=''){
        $.ajax({
		type:'POST',
		url: baseurl + '/SchoolTutorialfee/getstudentsdata',
		data:'classId='+new_val+'&start_year='+select_year+'&subject='+subject+'&teacher_id='+teacher_id+'&student='+get_val,
		beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		},
		success:function(response){
		    console.log(response);
		    $( "#stdent_data_body" ).html('');
		    $( "#stdent_data_body" ).append( response.html );
		    var sessionyr = "<?php echo $feeana ?> ("+response.sessionyear+")";
		    console.log(response.graph); 
		    $( "#chartContainer" ).html('');
		    CanvasJS.addColorSet("greenShades",
                [
                "#5bd210",
                "#ec0202"               
                ]);
		    var chart = new CanvasJS.Chart("chartContainer", {
		        colorSet: "greenShades",
            	theme: "light2",
            	animationEnabled: true,
            	title: {
            		text: sessionyr
            	},
            	data: [{
            		type: "doughnut",
            		indexLabel: "{symbol} - {y}",
            		
            		showInLegend: true,
            		legendText: "{label} : {y}",
            		dataPoints: response.graph
            	}]
            });
            chart.render();
		}

	});
    }
    
}

function getteachers(val,id)
{
    if(val !=''){
       $('#student').val('').trigger('change.select2');
    $('#stdent_data_body').html(''); $('#stdent_data_body').html('<tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>'); 
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var get_id = $('#'+id).data('id');
    var select_year = $('#select_year').val();
    var class_id_name = $('#'+id).data('class');
    var class_id = $('#'+class_id_name).val();
    $.ajax({
            type:'POST',
            url: baseurl + '/SchoolTutorialfee/getteachers',
            data:'classId='+class_id+'&subjectId='+val+'&sessionId='+select_year,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                if (result) {
                    $('#'+get_id).html('').trigger('change.select2');
                    $('#'+get_id).html(result[0]).trigger('change.select2');
                    $( "#month" ).html('');
                    $( "#month" ).html(result[1]);
                    $( "#session_amount" ).html('');
                $( "#session_amount" ).html('($'+result[2]+')');
                }else{
                    $('#'+get_id).html('').trigger('change.select2');
                    $( "#month" ).html('');
                    $( "#session_amount" ).html('');
                }
            }

        });
    }
}

</script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '2030') { $cashlbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '2031') { $onpaylbl = $langlbl['title'] ; } 
    } 
?>
<style>
    .hide
    {
        display:none;
    }
    .input-group-text{
        font-size:0.8em;
    }
    table.dataTable>thead .sorting::before,
 table.dataTable>thead .sorting_asc::before,
 table.dataTable>thead .sorting_desc::before,
 table.dataTable>thead .sorting_asc_disabled::before,
 table.dataTable>thead .sorting_desc_disabled::before {
    right: 0;
    content: "";
}
 
 table.dataTable>thead .sorting::after,
 table.dataTable>thead .sorting_asc::after,
 table.dataTable>thead .sorting_desc::after,
 table.dataTable>thead .sorting_asc_disabled::after,
 table.dataTable>thead .sorting_desc_disabled::after {
    right: 0;
    content: "";
}
 
 table.dataTable>thead>tr>th:not(.sorting_disabled),
 table.dataTable>thead>tr>td:not(.sorting_disabled) {
    padding-right: 4px;
    padding-left: 4px;
}
 
 table.dataTable>thead>tr>th,
 table.dataTable>thead>tr>td {
    padding-right: 4px;
    padding-left: 4px;
}
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '346') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">	
                    <?php /*if(!empty($sclsub_details[0]))
                    { 
                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                        if(in_array("38", $roles)) { ?>
                            <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfee"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '319') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php }
                    } else { ?>
                        <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfee"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '319') { echo $langlbl['title'] ; } } ?></a></li>
                    <?php } */?>
                    
                    <!--<a href="http://you-me-globaleducation.org/school/fees/pdf/33/april" title="Add" class="btn btn-info" >pdf</a>-->
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                
                        <div class="row clearfix">
                            <div class="container row ">
                                <div class="col-sm-3">
                                    <div class="form-group">   
                                        <!--<label>Year</label>
                                       <div class="input-group date" id="datetimepicker_year3" data-target-input="nearest">
                                          <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker_year3"  required name="start_year" id="select_year2" required/>
                                          <div class="input-group-append" data-target="#datetimepicker_year3" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                          </div>
                                        </div>-->
                                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '341') { echo $langlbl['title'] ; } } ?></label>
                                        <select class="form-control session" id="select_year" name="start_year" onchange="getstudents(this.value, this.id)" required>
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
                                <div class="col-sm-3">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '337') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">
                                        <select class="form-control class" id="class" name="class" required onchange="getstudents(this.value, this.id)">
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
                                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']." (" . $val['school_sections'].")";?> </option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>                                    
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '240') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">
                                    <select class="form-control studentchose" name="student" id="student" placeholder="Choose Student" onchange="getstudents_data(this.value, this.id)" required>
                                        <option value="">Choose Student</option>
                                    </select>  
                                    </div>
                                </div>
                            </div>
                            <div class="container row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6 text-center">
                                    <div id="chartContainer"></div>
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                    
                    </div>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="fstdent_data_table" data-page-length='50'>
                                <thead class="thead-dark sorting sorting_desc sorting_asc">
                                    <tr>
                                        <th>Description</th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '358') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2160') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2161') { echo $langlbl['title'] ; } } ?></th>
                                        <th>Trans. ID</th>
                                        <th>Trans. Date</th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2162') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '359') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '360') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '72') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                </thead>
                                <tbody id="stdent_data_body" class="modalrecdel"> 
                                               
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

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
            <?php echo $this->Form->create(false , ['id' => "addstudentfees" , 'url' => ['action' => "addstudentfees"], 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1552') { echo $langlbl['title'] ; } } ?></label>
                           <select class="form-control months" id="month" name="frequency" onchange="getfrequency(this.value)">
                                <option value="">Choose Month</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1563') { echo $langlbl['title'] ; } } ?></label>
                           <select class="form-control paymode" id="paymode" name="paymode" required onchange="paymodetransid(this.value)">
                                <option value="">Choose Payment Mode</option>
                                <option value="cash"><?= $cashlbl ?></option>
                                <option value="cash express">Cash Express</option>
                                <option value="mobile money">Mobile Money</option>
                                <option value="tpe">TPE</option>
                                <option value="online payment"><?= $onpaylbl ?></option>
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '343') { echo $langlbl['title'] ; } } ?> <span id="session_amount"></span>
                            <br/><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1363') { echo $langlbl['title'] ; } } ?> <span id="amtdue"></span><br/>
                            <p></p>
                            </label>
                            <input type="number" class="form-control" id="amount" name="amount"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '345') { echo $langlbl['title'] ; } } ?>  *">
                        </div>
                    </div>
                    
                    <div class="col-md-12 row" id="hadcoupn" style="display:none">
                        <div class="col-md-6" style="padding:0px 0px 0px 15px;">
                            <div class="form-group">   
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2163') { echo $langlbl['title'] ; } } ?></label>
                                <select class="form-control" name="couponid"  id="couponid" onchange="getcouponamt(this.value, 'a')"></select>
                            </div>
                        </div>
    
                        <div class="col-md-6" style="padding:0px 0px 0px 15px;">
                            <div class="form-group">   
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2164') { echo $langlbl['title'] ; } } ?> </label>
                                <select class="form-control" name="couponamt" id="couponamt" onchange="getamtpaid(this.value)"></select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label>Trans. ID </label>
                            <input type="text" class="form-control" name="transid" id="transid" placeholder="Trans. Id">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label>Trans. Date </label>
                            <input type="date" class="form-control" name="transdate" id="transdate" placeholder="Trans. Date">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label>Memo</label>
                            <textarea class="form-control" name="cashmemo" id="cashmemo"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="dueamt" name="dueamt" value="0">
                    <input type="hidden" id="discountamt" name="discountamt" value="0">
                    <input type="hidden" id="feedetail" name="feedetail">
                    <input type="hidden" id="feehead" name="feehead">
                    <input type="hidden" id="feestructure" name="feestructure">
                    <input type="hidden" id="totalamt" name="totalamt">
                    <input type="hidden" id="studentid" name="studentid">
                    <input type="hidden" id="classid" name="classid">
                    <input type="hidden" id="sessionid" name="sessionid">
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

<!------------------ Edit Class --------------------->
<div class="modal classmodal animated zoomIn" id="editfee" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1553') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
            <?php echo $this->Form->create(false , ['id' => "editstudentfees" , 'url' => ['action' => "editstudentfees"], 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1552') { echo $langlbl['title'] ; } } ?></label>
                            <input type="text" class="form-control months" id="emonth" name="frequency" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1563') { echo $langlbl['title'] ; } } ?></label>
                           <select class="form-control paymode" id="epaymode" name="paymode" required onchange="paymodetransid(this.value)">
                                <option value="">Choose Payment Mode</option>
                                <option value="cash"><?= $cashlbl ?></option>
                                <option value="cash express">Cash Express</option>
                                <option value="mobile money">Mobile Money</option>
                                <option value="tpe">TPE</option>
                                <option value="online payment"><?= $onpaylbl ?></option>
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '343') { echo $langlbl['title'] ; } } ?> <span id="esession_amount"></span>
                            </label>
                            
                            <input type="number" class="form-control" id="eamount" name="amount"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '345') { echo $langlbl['title'] ; } } ?>  *">
                        </div>
                    </div>
                    
                    <div class="col-md-12 row" id="ehadcoupn" style="display:none">
                        <div class="col-md-6" style="padding:0px 0px 0px 15px;">
                            <div class="form-group">   
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2163') { echo $langlbl['title'] ; } } ?></label>
                                <select class="form-control" name="couponid"  id="ecouponid" onchange="getcouponamt(this.value, 'e')"></select>
                            </div>
                        </div>
    
                        <div class="col-md-6" style="padding:0px 0px 0px 15px;">
                            <div class="form-group">   
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2164') { echo $langlbl['title'] ; } } ?> </label>
                                <select class="form-control" name="couponamt" id="ecouponamt" onchange="getamtpaid(this.value)"></select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label>Trans. ID </label>
                            <input type="text" class="form-control" name="transid" id="etransid" placeholder="Trans. Id">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label>Trans. Date </label>
                            <input type="date" class="form-control" name="transdate" id="etransdate" placeholder="Trans. Date">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label>Memo</label>
                            <textarea class="form-control" name="cashmemo" id="ecashmemo"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="edueamt" name="dueamt" value="0">
                    <input type="hidden" id="ediscountamt" name="discountamt" value="0">
                    <input type="hidden" id="efeedetail" name="feedetail">
                    <input type="hidden" id="efeehead" name="feehead">
                    <input type="hidden" id="efeestructure" name="feestructure">
                    <input type="hidden" id="etotalamt" name="totalamt">
                    <input type="hidden" id="estudentid" name="studentid">
                    <input type="hidden" id="eclassid" name="classid">
                    <input type="hidden" id="esessionid" name="sessionid">
                    <input type="hidden" id="id" name="id">
                    <div class="col-sm-12">
                        <div class="error" id="efeeerror">
                        </div>
                        <div class="success" id="efeesuccess">
                        </div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="editfeebtn" class="btn btn-primary"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div> 

<?php
foreach($lang_label as $langlbl) 
{ 
    if($langlbl['id'] == '1814')  {  $feeanaysis = $langlbl['title'] ;  } 
}
?>

<script>


function getamtpaid(val)
{
    var due = $("#dueamt").val();
    var amtpaid = due-val;
    $("#amount").val(amtpaid);
}

function paymodetransid(val)
{
    $("#transid").removeAttr("required");
    if(val != "cash")
    {
        $("#transid").prop("required", "true");
    }
}

function getcouponamt(val,ae)
{
    if(ae == "a")
    {
        $("#couponamt").html("");
        $("#couponamt").removeAttr("required", "true");
        var refscrf = $("input[name='_csrfToken']").val();
        var amt = $("#totalamt").val();
        $.ajax({ 
            url: baseurl +"/fees/getcouponamt", 
            data: {"discid":val,'amt':amt, _csrfToken : refscrf}, 
            type: 'post',success: function (result) 
            {       
                if(result) {
                    console.log(result);
                    if(val != "")
                    {
                        $("#couponamt").prop("required", "true");
                    }
                    $("#couponamt").html(result.coupnamt);
                    $("#discountamt").val(result.disamt)
                    $
                    
                }
            }
        });
    }
    else
    {
        $("#ecouponamt").html("");
        $("#ecouponamt").removeAttr("required", "true");
        var refscrf = $("input[name='_csrfToken']").val();
        var amt = $("#etotalamt").val();
        $.ajax({ 
            url: baseurl +"/fees/getcouponamt", 
            data: {"discid":val,'amt':amt, _csrfToken : refscrf}, 
            type: 'post',success: function (result) 
            {       
                if(result) {
                    console.log(result);
                    if(val != "")
                    {
                        $("#ecouponamt").prop("required", "true");
                    }
                    $("#ecouponamt").html(result.coupnamt);
                    var ediscountamt =$("#ediscountamt").val();
                    if(ediscountamt != "" )
                    {
                        $("#ecouponamt").select2().val(ediscountamt).trigger('change.select2');
                    }
                    
                    $("#ediscountamt").val(result.disamt);
                    
                }
            }
        });
    }
}

function getstudents_data(get_val, id)
{
   
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
	var baseurl = "/" + baseurl;
	
    var select_year = $("#select_year").val();
    var new_val = $("#class").val();
	
    $.ajax({
		type:'POST',
		url: baseurl + '/fees/getstudents_data',
		data:'classId='+new_val+'&start_year='+select_year+'&student='+get_val,
		beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		},
		success:function(response){
		    console.log(response);
		   /* $( "#stdent_data_body" ).html('');
		    $( "#stdent_data_body" ).append( response.html );*/
		    
		    $('#fstdent_data_table').DataTable().destroy();
            $('#stdent_data_body').html(response.html); 
            $("#fstdent_data_table").DataTable({
                "aaSorting": [],
                "language": {
                    "lengthMenu": show+" _MENU_"+entries,
                    "search": search+":",
                    "info": show+" _START_ to _END_ "+ ofentries +" _TOTAL_ "+ entries,
                    "infoEmpty": show+" 0 to _END_ "+ ofentries +" _TOTAL_ "+ entries,
                    "paginate": {
                      next: next,
                      previous: prev,
                    }
                }
            });
            
		    
		    var sessionyr = "<?php echo $feeanaysis ?> ("+response.sessionyear+")";
		    console.log(response.graph); 
		    $( "#chartContainer" ).html('');
		    $( "#chartContainer" ).css('width', '100%');
		    $( "#chartContainer" ).css('height', '370px');
		    CanvasJS.addColorSet("greenShades",
                [
                "#1f7c29",
                "#ec0202",
                "#0F65DA",
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
            		//indexLabel: "{symbol} - {label}",
            		showInLegend: true,
            		toolTipContent: "<b>{label}</b>: ${y} ({percent})",
		            indexLabel: "{label} - {percent}",
            		legendText: "{label} : ${y}",
            		dataPoints: response.graph
            	}]
            });
            chart.render();
		}
	});
}

</script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
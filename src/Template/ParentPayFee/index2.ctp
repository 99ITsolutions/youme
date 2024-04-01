<style>
.bg-dash
{
	background-color: #242e3b !important;
}
.card .header {
    padding: 20px 20px 0px 20px !important;
}
</style>
 
<?php
    // Merchant key here as provided by Payu
    $MERCHANT_KEY = "HxRFGQ3S";
    // Merchant Salt as provided by Payu
    $SALT = "Svasp6HzCU";
    // Change to https://secure.payu.in for LIVE mode
    $PAYU_BASE_URL = "https://test.payu.in";
    $hash = '';
?>
        <div class="row clearfix container">
            <div class="card">
                <div class="header mb-4">
                    <div class=" row">
                        <h2 class="col-md-4 heading text-left"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1354') { echo $langlbl['title'] ; } } ?> </h2>
                        <h2 class="col-md-4 heading text-center"><?= "Class: ". $cls_sess ?></h2>
                        <ul class="header-dropdown">
                            <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfee">Add Online Fee</a></li>
                        </ul>
                    </div>
                </div>
                <div class="body">
                    <?php //print_r($freq); ?>
                    <div class="row clearfix">
                        <!--<div class="col-md-3">
                            <div class="form-group">   
                                    <label> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1355') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control class" id="select_year" name="start_year" onchange="getstudents_data(this.value)" required>
                                        <option value="">Choose Session</option>
                                        <?php
                                        foreach($session_details as $key => $val){
                                            
                                            /*$currentyr = date("Y", strtotime('now'));
                                            if($val['startyear'] == $currentyr)
                                            {
                                                $selsession = $val['id'];
                                            }*/
                                            
                                            $now = strtotime('now');
                                            $currentyear = date("Y", $now);
                                            $currentmonth = date("m", $now);
                                            $getmonthids_start = date("m", strtotime($val['startmonth']));
                                            $getmonthids_end = date("m", strtotime($val['endmonth']));
                                            $strtyr =  $val['startyear'];
                                            $endyr =  $val['endyear'];
                                            
                                            if( (($currentyear == $strtyr) && ($currentmonth <= 12 && $currentmonth >= $getmonthids_start)) || (($currentyear == $endyr) && ($currentmonth <= $getmonthids_end && $currentmonth >= 1)) )
                                            {
                                                echo $selsession = $val['id'];
                                            }
                                           
                                            
                                        ?>
                                          <option  value="<?=$val['id']?>" <?php if($val['id'] == $selsession) { echo "selected"; } ?> ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-9"></div>-->
                        <div class="col-lg-7">
                            <div class="table-responsive">
                                <table class="table table-hover" id="stdent_data_table" >
                                    <thead class="thead-dark">
                                        <tr>
                                            <th> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1356') { echo $langlbl['title'] ; } } ?></th>
                                            <th> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1357') { echo $langlbl['title'] ; } } ?></th>
                                            <th> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1358') { echo $langlbl['title'] ; } } ?></th>
                                            <th> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1359') { echo $langlbl['title'] ; } } ?></th>
                                            <th> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1360') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="stdent_data_body" class="modalrecdel"> 
                                        <?php foreach($student_fee as $s_sub){  ?>  
                                        <tr>
                                            <td><?= $s_sub['installment'] ?></td>
                                            <td><?= $s_sub['amount'] ?></td>
                                            <td><?= $s_sub['paid'] ?></td>
                                            <td><?= $s_sub['pending'] ?></td>
                                            <td><?php
                                            if($s_sub['pending']  == 0)
                                            {
                                               echo '<a href="studentFee/pdf/'.$student.'/'.$s_sub['installment'].'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-download"></i></a>';
                                            }
                                            else{ echo''; }
                                            ?></td>
                                        </tr>
                                        <?php } ?>
            	                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div id="chartContainer" style="height: 370px; width: 100%; margin-top:50px;"></div>
                        </div>
	                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
foreach($student_fee as $s_sub)
{
    $totl_amt[] = $s_sub['amount'];
    $paid_amt[] = $s_sub['paid'];
}

$total = array_sum($totl_amt);
$paid = array_sum($paid_amt);

$due = $total-$paid;

$grph[] = ['label' => "Paid Amount", 'symbol' => "Paid", 'y' => $paid ];
$grph[] = ['label' => "Due Amount", 'symbol' => "Due", 'y' => $due ];

$dataPoints = $grph;
?>

<!------------------ End --------------------->
<script>
var textfee = 
window.onload = function() {
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
		text: "Fee Analysis"
	},
	data: [{
		type: "doughnut",
		indexLabel: "{symbol} - {y}",
		yValueFormatString: "#####",
		showInLegend: true,
		legendText: "{label} : {y}",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>

<script>
    function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!--<script src="https://www.rgraph.net/libraries/src/RGraph.common.core.js"></script>
<script src="https://www.rgraph.net/libraries/src/RGraph.common.key.js"></script>
<script src="https://www.rgraph.net/libraries/src/RGraph.bar.js"></script>-->

<!------------------ Add Class --------------------->

    
<div class="modal classmodal animated zoomIn" id="addfee" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Pay Fee</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
            <?php // echo $this->Form->create(false , ['id' => "addstudentonlinfees", 'action' => addstudentonlinfees , 'method' => "post"  ]); ?>
            
            <?php echo $this->Form->create(false , ['id' => "payment_form", 'action'=>'paymoney', 'method' => "post"  ]); ?>
            
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label>Select Month</label>
                           <!--<select class="form-control class" id="month" name="frequency" onchange="getfrequency(this.value)">-->
                               <select class="form-control class" id="month" name="frequency">
                                <option value="">Choose Month</option>
                                <?php foreach($freq as $val) { ?>
                                <option value="<?= $val ?>"><?= $val ?></option>
                                <?php } ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">   
                            <label>Amount <span id="session_amount">$<?= $amt ?></span></label>
                            <input type="number" class="form-control" id="amount" name="amount"  required placeholder="Amount *">
                        </div>
                    </div>
<input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
<input type="hidden" name="hash" value="<?php echo $hash ?>"/>
<input type="hidden" name="txnid" value="1" />
                    <input type="hidden" name="firstname" id="firstname" value="<?= $studentdtl[0]['f_name'] ?>" />
                    <input type="hidden" name="email" id="email" value="<?= $studentdtl[0]['email'] ?>" /> 
                    <input type="hidden" id="phone" name="phone" value="<?= $studentdtl[0]['emergency_number'] ?>" />
                    
<input  type="hidden" name="surl" value="google.com" >
<input  type="hidden" name="furl" value="facebook.com" >
<input type="hidden" name="service_provider" value="payu_paisa" >

                    <!--<input type="hidden" id="hash" name="hash" placeholder="Hash" value="hash" />
                    <input type="hidden" id="txnid" name="txnid" placeholder="Transaction ID" value="<?php echo  "Txn" . rand(10000,99999999)?>" />
                    <input type="hidden" id="key" name="key" placeholder="Merchant Key" value="HxRFGQ3S" />
                    <input type="hidden" id="salt" name="salt" placeholder="Merchant Salt" value="Svasp6HzCU" /> -->
                    <input type="hidden" id="totalamt" name="totalamt" value="<?= $amt ?>">
                    <div class="col-sm-12">
                        <div class="error" id="feeerror">
                        </div>
                        <div class="success" id="feesuccess">
                        </div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="addfeebtn payment_form" onclick="launchBOLT(); return false;" class="btn btn-primary" >Save</button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php  echo $this->Form->end(); ?>
                   <!--</form>-->
                </div>
            </div>
             
        </div>
    </div>
</div>              




<script>
function getstudents_data(val)
{
   //alert(val);
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
	var baseurl = "/" + baseurl;
	
    var select_year = $("#select_year").val();
    var new_val = $("#class").val();
	
    $.ajax({
		type:'POST',
		url: baseurl + '/studentFee/getstudents_data',
		data: 'start_year='+val,
		beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		},
		success:function(response){
		    console.log(response);
		    $( "#stdent_data_body" ).html('');
		    $( "#stdent_data_body" ).append( response.html );
		    var sessionyr = "Fees Analysis ("+response.sessionyear+")";
		    $( "#month" ).html('');
		    $( "#month" ).html(response.frequency);
		    console.log(response.graph); 
		    $( "#chartContainer" ).html('');
		    CanvasJS.addColorSet("greenShades",
                [
                "green",
                "red"               
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

</script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
var hash = '<?php echo $hash ?>';
function submitForm() {
if(hash == '') {
return;
}
var payuForm = document.forms.payuForm;
payuForm.submit();
}
</script>
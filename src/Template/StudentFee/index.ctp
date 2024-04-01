<style>
.bg-dash
{
	background-color: #242e3b !important;
}
.card .header {
    padding: 20px 20px 0px 20px !important;
}
</style>
 
        <div class="row clearfix container">
            <div class="card">
                <div class="header mb-4">
                    <div class=" row">
                        <h2 class="col-md-4 heading text-left"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1354') { echo $langlbl['title'] ; } } ?> </h2>
                        <h2 class="col-md-4 heading text-center"><?= "Class: ". $cls_sess ?></h2>
                        <!--<ul class="header-dropdown">
                            <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfee">Add Online Fee</a></li>
                        </ul>-->
                        <h2 class="col-md-4 text-right"><a  href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><span class="notranslate"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1313') { echo $langlbl['title'] ; } } ?></span></a></h2>
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
                                        <?php 
                                        $i = 1; 
                                        foreach($student_fee as $s_sub){  ?>  
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
                                            else{ ?> 

                                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#myModal<?=$i;?>"><i class="fa fa-credit-card"></i> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1989') { echo $langlbl['title'] ; } } ?></a>

                                                <!-- Modal -->
                                                <div id="myModal<?=$i;?>" class="modal fade" role="dialog">
                                                  <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                      </div>
                                                      <div class="modal-body">
                                                       <form action="https://you-me-globaleducation.org/payment/createOrder.php" method="GET"> 
                                                           <table class="table table-hover" id="stdent_data_table" style="border: 1px solid #ccc;" >
                                                              <tbody id="stdent_data_body" class="modalrecdel"> 
                                                                <tr> 
                                                                 <td>Name: </td>
                                                                 <td>Devender Singh Rawat</td>
                                                                </tr> 
                                                                <tr> 
                                                                 <td>Email: </td>
                                                                 <td>devendersingh.rawat38@gmail.com</td>
                                                                </tr>
                                                                <tr> 
                                                                 <td>Phone: </td>
                                                                 <td>8368670138</td>
                                                                </tr> 
                                                                 <tr> 
                                                                 <td>Installment: </td>
                                                                 <td><?= $s_sub['installment'] ?></td>
                                                                </tr>  
                                                                 <tr> 
                                                                 <td>Amount: </td>
                                                                 <td><input type="number" name="fees" value="<?= $s_sub['pending'] ?>"></td>
                                                                </tr>
                                                                <tr> 
                                                                 <td colspan="2" align="text-center"><input class="btn btn-sm btn-outline-secondary" type="submit" name="paynow" value="Pay Now"> </td>
                                                                </tr>
                                                              </tbody>  
                                                           </table> 
                                                         </form>  
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>



                                               <?php  } ?>
                                           </td>
                                        </tr>
                                        <?php $i++; } ?>
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
foreach($lang_label as $langlbl) 
{ 
    if($langlbl['id'] == '1814') { $feeanaysis = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1812') { $paidamttt = $langlbl['title'] ; } 
    if($langlbl['id'] == '1813') { $dueamttt = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '1358') { $paidd = $langlbl['title'] ; } 
    if($langlbl['id'] == '1359') { $dueee = $langlbl['title'] ; } 
}
?>
<?php 
foreach($student_fee as $s_sub)
{
    $totl_amt[] = $s_sub['amount'];
    $paid_amt[] = $s_sub['paid'];
}

$total = array_sum($totl_amt);
$paid = array_sum($paid_amt);

$due = $total-$paid;

$grph[] = ['label' => $paidamttt, 'symbol' => $paidd, 'y' => $paid ];
$grph[] = ['label' => $dueamttt, 'symbol' => $dueee, 'y' => $due ];

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
		text: "<?php echo $feeanaysis ?>"
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
            <?php // echo $this->Form->create(false , ['id' => "addstudentonlinfees", 'action' => 'https://www.sandbox.paypal.com/cgi-bin/webscr' , 'method' => "post"  ]); ?>
            
            <?php //echo $this->Form->create(false , ['id' => "payment_form", 'action'=>'request', 'method' => "post"  ]); ?>
            <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
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

<!--<input type='hidden' name='business'  value='harry@biofeedbackinternational.com'> 
<input type='hidden' name='item_name' value='Camera'> 
<input type='hidden' name='item_number' value='CAM#N1'>
<input type='hidden' name='no_shipping' value='1'> 
<input type='hidden' name='currency_code' value='USD'> 
<input type='hidden' name='notify_url' value='https://you-me-globaleducation.org/notify.php'>
<input type='hidden' name='cancel_return' value='https://you-me-globaleducation.org/cancel.php'>
<input type='hidden' name='return' value='https://you-me-globaleducation.org/return.php'>
<input type="hidden" name="cmd" value="_xclick">-->

                    
                    <input type="hidden" id="totalamt" name="totalamt" value="<?= $amt ?>">
                    <div class="col-sm-12">
                        <div class="error" id="feeerror">
                        </div>
                        <div class="success" id="feesuccess">
                        </div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary" name="pay_now" id="pay_now"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1989') { echo $langlbl['title'] ; } } ?></button>
                        <!--<button type="submit" id="addfeebtn payment_form" onclick="launchBOLT(); return false;" class="btn btn-primary" >Save</button>-->
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php //  echo $this->Form->end(); ?>
                  
                </div>
                 </form>
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
		    var sessionyr = "<?php echo $feeanaysis ?> ("+response.sessionyear+")";
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

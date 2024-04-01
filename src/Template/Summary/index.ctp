<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
    .btn-sm {
    padding: .25rem .25rem;
    }
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '722') { echo $langlbl['title'] ; } } ?></h2>
                            <!--<h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>studentdashboard/studentprofile/" title="Back" class="btn btn-sm btn-success">Back</a></h2>-->
                            
                            <h2 class="col-md-1 text-right">
                                <span  id="closesearch" <?= $closeicon ?> onclick="closesearch();" aria-hidden="true">X</span>
                                <a href="javascript:void(0)" title="Back" class=" btn btn-sm btn-success" id="searchstudent"  <?= $searchicon ?> onclick="studentsearch();" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '740') { echo $langlbl['title'] ; } } ?></a>
                                <!--<span  id="searchstudent"  <?= $searchicon ?> onclick="studentsearch();" ><i class="fa fa-search"></i></span>-->
                            </h2>
                            <h2 class="col-md-2"  <?= $downloadreport ?> ><button onclick="generate()" id="sumdownloadreport"  class="btn btn-sm btn-success" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1422') { echo $langlbl['title'] ; } } ?></button></h2>
                        </div>
                        
                    </div>
                    <div class="body"  id="gen_pdf">
                        <div  id="studentsearch" <?= $style ?> >
                        <div class="row">
                            <div class="error" id="summryerror"><?= $error ?></div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-5 " style="max-width:100%; border-right:1px solid #cccccc;">
                            <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "asummaryreportform" ,  'method' => "post"  ]);  ?>
                                
                                <div class="row col-md-12">
        	                    <div class="col-sm-5" style="padding: 0px !important;">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '723') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="student_no" id="student_no" style="height: 2.2em;">                       
                                    </div>
        	                    </div>
        	                    <div class="col-sm-5" style="padding-right: 0px !important;">
        	                        <label> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '727') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control chngstatus" id="session_year" name="start_year"  required>
                                        <option value="">Choose One</option>
                                        <?php
                                        foreach($session_details as $key => $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
        	                    </div>
        	                    <input type="hidden" name="searchform" value="1">
        	                    <div class="col-sm-2">
        	                        <button type="submit" class="btn btn-primary summary_report" id="summary_report" style="margin-top: 1.6rem!important;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '728') { echo $langlbl['title'] ; } } ?></button>
        	                    </div>
        	                    </div>
        	                   <?php echo $this->Form->end();?>
        	               </div>

    	                
    	                <!--<div class="row clearfix">
    	                    <hr>
    	                    <div class="col-md-6 text-center"><b>OR</b></div>
    	                    <hr>
    	                </div>-->
    	                <div class="col-md-7 row"  style="max-width:100%;padding-right: 0px !important;">
    	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
    	                    
    	                        <div class="row col-md-12">
    	                        <div class="col-sm-6">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control class" id="schoolid" name="schoolid" required onchange="getclass(this.value)">
                                            <option value="">Choose School</option>
                                            <?php
                                            foreach($school_details as $key => $val){
                                            ?>
                                              <option  value="<?=$val['id']?>" ><?php echo $val['comp_name'];?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-6">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '727') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control class" id="select_year" name="start_year" required>
                                        <option value="">Choose One</option>
                                        <?php
                                        foreach($session_details as $key => $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
        	                    </div>
        	                    <div class="col-sm-4">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '726') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control class" id="class" name="class" required onchange="getstudentsession(this.value)">
                                            <option value="">Choose Class</option>
                                            <?php
                                            /*foreach($class_details as $key => $val){
                                            ?>
                                              <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
                                            <?php
                                            }*/
                                            ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-6">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '725') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control class" name="student" id="student" placeholder="Choose Student" required>
                                            <option value="">Choose Student</option>
                                        </select>
                                    </div>
        	                    </div>
        	                    
        	                    <input type="hidden" name="searchform" value="2">
        	                    <div class="col-sm-2">
        	                        <button type="submit" class="btn btn-primary mt-4 clsummary_report" id="clsummary_report"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '728') { echo $langlbl['title'] ; } } ?></button>
        	                    </div>
        	                    </div>
        	                    <?php echo $this->Form->end();?>
        	               </div>
    	                </div>
    	                </div>
    	                <div class="row clearfix" style="display:none;">
    	                    <h2 class="col-md-2"  <?= $downloadreport ?> ><span id="edownloadreport" class="btn btn-sm btn-success" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1422') { echo $langlbl['title'] ; } } ?></span></h2>
    	                </div>
    	                <div class="row clearfix" id= "reportdata">
    	                    <?php echo $viewpage; ?>
    	                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>

<!------------------ End --------------------->

<?php 

$grph[] = ['label' => "Paid Amount", 'symbol' => "Paid", 'y' => $paid ];
$grph[] = ['label' => "Due Amount", 'symbol' => "Due", 'y' => $due ];

$dataPoints = $grph;

$grphattendance[] = ['label' => "Presnt Attendance", 'symbol' => "Present", 'y' => $present ];
$grphattendance[] = ['label' => "Absent Attendance", 'symbol' => "Absent", 'y' => $absent ];

$dataPointsAtt = $grphattendance;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?php
if(!empty($error))
{
    ?>
    <script>
        $("#summryerror").fadeIn().delay('5000').fadeOut('slow');
    </script>
    <?php
}
?>
<!------------------ End --------------------->
<script>

window.onload = function() {
    CanvasJS.addColorSet("greenShades",
                [
                "#5bd210",
                "#ec0202"               
                ]);
    var chart = new CanvasJS.Chart("feesreport", {
        colorSet: "greenShades",
    	theme: "light2",
    	animationEnabled: true,
    	
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
    
  
    var chart1 = new CanvasJS.Chart("attendancereport", {
        colorSet: "greenShades",
    	theme: "light2",
    	animationEnabled: true,
    	
    	data: [{
    		type: "doughnut",
    		indexLabel: "{symbol} - {y}",
    		yValueFormatString: "#####",
    		showInLegend: true,
    		legendText: "{label} : {y}",
    		dataPoints: <?php echo json_encode($dataPointsAtt, JSON_NUMERIC_CHECK); ?>
    	}]
    });
    chart1.render();
 
}


</script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>


function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<style>
        #gen_pdf, body, p{
        background: #fff;
        height:200%;
    }
    .card{
        background:#fff;
    }
    .card .body {
        padding: 20px 20px 50px 20px;
    }
</style>
<script>
    function generate(){
        var doc = new jsPDF('p', 'pt', 'a4');
        doc.addHTML(document.getElementById('gen_pdf'), function() {
          doc.save('report.pdf');
        });
    }
</script>

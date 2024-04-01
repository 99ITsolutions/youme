<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-7 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '500') { echo $langlbl['title'] ; } } ?></h2>
                            <!--<h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>studentdashboard/studentprofile/" title="Back" class="btn btn-sm btn-success">Back</a></h2>-->
                            
                            <h2 class="col-md-1 text-right">
                                <span  id="closesearch" <?= $closeicon ?> onclick="closesearch();" aria-hidden="true">X</span>
                                <a href="javascript:void(0)" title="Back" class=" btn btn-sm btn-success" id="searchstudent"  <?= $searchicon ?> onclick="studentsearch();" ><i class="fa fa-search"></i></a>
                                <!--<span  id="searchstudent"  <?= $searchicon ?> onclick="studentsearch();" ><i class="fa fa-search"></i></span>-->
                            </h2> 
                            <h2 class="col-md-2"  <?= $downloadreport ?> ><button onclick="generate()" id="sumdownloadreport"  class="btn btn-sm btn-success" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1422') { echo $langlbl['title'] ; } } ?></button></h2>
                            <h2 class="col-md-1"><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                        </div>
                        
                    </div>
                    <div class="body" id="gen_pdf">
                        <div  id="studentsearch" <?= $style ?> >
                        <div class="col-md-12">
                                    <div class="error" id="summryerror"><?= $error ?></div>
                                </div>
                        <div class="row clearfix">
                            <div class="col-md-5 " style="max-width:100%; border-right:1px solid #cccccc;">
                            <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "asummaryreportform" ,  'method' => "post"  ]);  ?>
                                
                                <div class="row col-md-12">
        	                    <div class="col-sm-5">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '723') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="student_no" id="student_no">                       
                                    </div>
        	                    </div>
        	                    <div class="col-sm-5">
        	                        <label> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '727') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control session" id="session_year" name="start_year"  required>
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
    	                <div class="col-md-7 row"  style="max-width:100%">
    	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
    	                    
    	                        <div class="row col-md-12">
    	                        <div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '727') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control session" id="select_year" name="start_year" required>
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
        	                    <div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '355') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control class" id="class" name="class" required onchange="getstudentsessionscl(this.value)">
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
                                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']."(". $val['school_sections'] .")";?> </option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-4">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '725') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control studentchose" name="student" id="student" placeholder="Choose Student" required>
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
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '1358') { $paidlabel = $langlbl['title'] ; } 
    if($langlbl['id'] == '1362') { $paidamtlabel = $langlbl['title'] ; } 
    if($langlbl['id'] == '360') { $duelabel = $langlbl['title'] ; } 
    if($langlbl['id'] == '1813') { $dueamtlabel = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '277') { $psntlabel = $langlbl['title'] ; } 
    if($langlbl['id'] == '1971') { $psntattndlabel =  $langlbl['title'] ; } 
    if($langlbl['id'] == '1972') { $absntattndlabel =  $langlbl['title'] ; } 
} 


$grph[] = ['label' => $paidamtlabel, 'symbol' => $paidlabel, 'y' => $paid ];
$grph[] = ['label' => $dueamtlabel, 'symbol' => $duelabel, 'y' => $due ];

$dataPoints = $grph;

$grphattendance[] = ['label' => $psntattndlabel, 'symbol' => $psntlabel, 'y' => $present ];
$grphattendance[] = ['label' => $absntattndlabel, 'symbol' => "Absent", 'y' => $absent ];

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

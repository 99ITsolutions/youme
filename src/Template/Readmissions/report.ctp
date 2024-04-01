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
                            <h2 class="col-md-9 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1954') { echo $langlbl['title'] ; } } ?></h2>
                            <h2 class="col-md-2 text-right" style="padding-right:0px;"   <?= $downloadreport ?> ><button onclick="generate()" id="sumdownloadreport"  class="btn btn-sm btn-success" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1422') { echo $langlbl['title'] ; } } ?></button></h2>
                            
                            <h2 class="col-md-1 text-right"><a href="<?= $baseurl ?>readmissions/index?id=<?= $stuid ?>" title="Back" class=" btn btn-sm btn-success" id="searchstudent"  <?= $searchicon ?> onclick="studentsearch();" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2> 
                        </div>
                        
                    </div>
                    <div class="body" id="gen_pdf">
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
    if($langlbl['id'] == '1362') { $paidamt = $langlbl['title'] ; } 
    if($langlbl['id'] == '1363') { $dueamt = $langlbl['title'] ; }
    if($langlbl['id'] == '1358') { $pd = $langlbl['title'] ; }
    if($langlbl['id'] == '1967') { $du = $langlbl['title'] ; }
    if($langlbl['id'] == '1971') { $pattnd = $langlbl['title'] ; }
    if($langlbl['id'] == '1155') { $prsnt = $langlbl['title'] ; }
    
    if($langlbl['id'] == '1972') { $abatnnd = $langlbl['title'] ; }
    
}

$grph[] = ['label' => $paidamt, 'symbol' => $pd, 'y' => $paid ];
$grph[] = ['label' => $dueamt, 'symbol' => $du, 'y' => $due ];

$dataPoints = $grph;

$grphattendance[] = ['label' => $pattnd, 'symbol' => $prsnt, 'y' => $present ];
$grphattendance[] = ['label' => $abatnnd, 'symbol' => "Absent", 'y' => $absent ];

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

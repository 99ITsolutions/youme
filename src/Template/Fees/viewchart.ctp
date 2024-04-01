<?php
//print_r($feestructure_details); 
$frequency = $feestre_details[0]['frequency'];
$total_amount = $feestre_details[0]['amount'];

$datas = [];
$srtMonth = ucfirst($session_details[0]->startmonth); $endMonth = ucfirst($session_details[0]->endmonth);
                            
if($frequency == 'yearly')
{
    $monthslabel = $srtMonth."-".$endMonth;
    $amount = 8000;
    $datas[] = array("label"=> $monthslabel, "symbol" => $monthslabel,"y"=>$amount/$total_amount);
}

if($frequency == 'half yearly'){
    $effectiveDate = date('F', strtotime("+5 months", strtotime($srtMonth)));
    $effectiveDate2 = date('F', strtotime("+1 months", strtotime($effectiveDate)));
   
    $monthslabel = $srtMonth.'-'.$effectiveDate;
    $monthslabel1 = $effectiveDate2.'-'.$endMonth;
    $datas[] = array("label"=> $monthslabel, "symbol" => $monthslabel,"y"=>46.6);
    $datas[] = array("label"=> $monthslabel1, "symbol" => $monthslabel1,"y"=>46.6);
}

if($frequency == 'quarterly'){
    $session1 = date('F', strtotime("+2 months", strtotime($srtMonth)));
    $session2_strt = date('F', strtotime("+1 months", strtotime($session1)));
    $session2 = date('F', strtotime("+2 months", strtotime($session2_strt)));
    $session3_strt = date('F', strtotime("+1 months", strtotime($session2)));
    $session3 = date('F', strtotime("+2 months", strtotime($session3_strt)));
    $session4_strt = date('F', strtotime("+1 months", strtotime($session3)));
    $monthslabel = $srtMonth.'-'.$session1;
    $monthslabel1 = $session2_strt.'-'.$session2;
    $monthslabel2 = $session3_strt.'-'.$session3;
    $monthslabel3 = $session4_strt.'-'.$endMonth;
    $datas[] = array("label"=> $monthslabel, "symbol" => $monthslabel, "y"=>46.6);
    $datas[] = array("label"=> $monthslabel1, "symbol" => $monthslabel1, "y"=>23.4);
    $datas[] = array("label"=> $monthslabel2, "symbol" => $monthslabel2, "y"=>12.6);
    $datas[] = array("label"=> $monthslabel3, "symbol" => $monthslabel3, "y"=>17.4);
    
}



if($frequency == 'monthly'){
    
    $datas[] = array("label"=> $srtMonth, "symbol" => $srtMonth,"y"=>46.6);
    for($i=1; $i <= 10; $i++){
        $month = date('F', strtotime("+1 months", strtotime($srtMonth)));
        $srtMonth = $month;
        $datas[] = array("label"=> $month, "symbol" => $month,"y"=>46.6);
    }
    $datas[] = array("label"=> $endMonth, "symbol" => $endMonth,"y"=>46.6);
    
}

$dataPoints = $datas;
print_r($dataPoints);

?>

<script>
window.onload = function() 
{
    var chart = new CanvasJS.Chart("chartContainer", {
    	theme: "light2",
    	animationEnabled: true,
    	title: {
    		text: "Fee Analysis"
    	},
    	data: [{
    		type: "doughnut",
    		indexLabel: "{symbol} - {y}",
    		yValueFormatString: "#######/####",
    		showInLegend: true,
    		legendText: "{label} : {y}",
    		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    	}]
    });
    chart.render();
 
}
</script>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">View Chart</h2>
            </div>
            <div class="body">
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
            </div>
        </div>
    </div>
</div>    



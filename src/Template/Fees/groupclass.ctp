<style>
    #left-sidebar { left: -250px; }
    #main-content { width:100%;}
</style>
<?php 
foreach($lang_label as $langlbl) {
    if($langlbl['id'] == '2176') { $lbl2176 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '1422') { $lbl1422 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '136') { $lbl136 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '1032') { $lbl1032 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '341') { $lbl341 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '241') { $lbl241 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; } 
    if($langlbl['id'] == '192') { $lbl192 = $langlbl['title'] ; } 
    if($langlbl['id'] == '328') { $backlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2095') { $lbl2095 = $langlbl['title'] ; }
    if($langlbl['id'] == '1814') {  $feeanaysis = $langlbl['title'] ;  } 
    if($langlbl['id'] == '2188') {  $lbl2188 = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1519') {  $lbl1519 = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1577') {  $lbl1577 = $langlbl['title'] ;  } 
    if($langlbl['id'] == '2189') {  $lbl2189 = $langlbl['title'] ;  } 
}
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?= $lbl2176 ?></h2>
                <ul class="header-dropdown">
                    <li id="dwnlodrprt"></li>
                    <li><a href="<?=$baseurl?>fees/feereport" class="btn btn-info" title="Back"><?= $backlbl ?> </a></li>
                </ul>
            </div>
            <input type="hidden" id="selsctns">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
    					<label><?= $lbl241 ?>*</label>
    					<div class="form-group">                                    
    						<select class="form-control js-states session" id="session" required placeholder="Select Session" onchange="getsessclass(this.value)">
    						    <option value=""><?= $lbl341 ?></option>
    						    <?php foreach($session_details as $val) { ?>
    					            <option value="<?= $val['id'] ?>"><?= $val['startyear']."-". $val['endyear'] ?></option>
    					        <?php } ?>
    				        </select>
    					</div>
    				</div>
    				<div class="col-md-3">
    					<label><?= $lbl2188 ?>*</label>
    					<div class="form-group">                                    
    						<select class="form-control js-states attend_filter" multiple id="privileges" required placeholder="Select Section" onclick="getclasspriv(this.value)">
    						    <option value=""><?= $lbl192 ?></option>
    						    <?php if(!empty($sclsub_details[0])) { 
                                    $sclsubpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                    if(in_array("kindergarten", $sclsubpriv)) { ?>
                                        <option value="Maternelle"><?= $lbl1519 ?></option>
                                    <?php } if(in_array("primaire", $sclsubpriv)) { ?>
    					                <option value="Primaire"><?= $lbl1577 ?></option>
    					            <?php } if(in_array("secondaire", $sclsubpriv)) { ?>
    					                <option value="secondaire"><?= $lbl2189 ?></option>
                                    <?php } } else { ?>
    					            <option value="Maternelle"><?= $lbl1519 ?></option>
    					            <option value="Primaire"><?= $lbl1577 ?></option>
    					            <option value="secondaire"><?= $lbl2189 ?></option>
    					            <?php } ?>
    				        </select>
    					</div>
    				</div>
                    <div class="col-md-3">
    				    <label><?= $lbl136 ?>*</label>
    					<div class="form-group">                                    
    						<select class="form-control js-states class_s" id="selcls" placeholder="Select Class" onchange="getreportclass(this.value)">
    						    <!--<option value=""><?= $lbl1032 ?></option>
    						    <option value="all"><?= $lbl2095 ?></option>
    					        <?php //foreach($class_details as $cls) { ?>
    					            <option value="<?= $cls['id'] ?>"><?= $cls['c_name']."-". $cls['c_section']." (".$cls['school_sections'].")"?></option>
    					        <?php //} ?>-->
    				        </select>
    					</div>
    				</div>
                </div>
            </div>
            <div class="body">
                <div class="container row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 text-center">
                        <div id="chartContainer"></div>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem feereport_table" id="feereport_table" data-page-length='50'>
                        <thead class="thead-dark" id="reporthead">
                            
                        </thead>
                        <tbody id="feereportbody" class="modalrecdel">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php echo $this->Form->create(false , ['method' => "post"  ]); 
 echo $this->Form->end(); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function getsessclass(val)
    {
        //$("#selcls").val(null).trigger("change"); 
    }
    function getreportclass(val)
    {
        var session = $("#session").val();
        var refscrf = $("input[name='_csrfToken']").val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $('#feereport_table').css("text-align","center");
        $( "#chartContainer" ).css('height', '0px');
        $('#feereport_table').html('<img src="'+baseurl+'/img/loading-load.gif" width="100px" height="100px">');
        $("#dwnlodrprt").html('');
        $( "#chartContainer" ).html('');
        var sctns = $("#selsctns").val();
        $.ajax
        ({
            data : {_csrfToken:refscrf, 'cls':val, 'session':session, 'sctn':sctns },
            type : "post",
    		url: baseurl + '/Fees/getreport',
            success: function(response){
                console.log(response);
                
                if(response.feedtl != "")
                {
        		    $( "#chartContainer" ).css('width', '100%');
        		    $( "#chartContainer" ).css('height', '370px');
        		    var sessionyr = "<?php echo $feeanaysis ?> ("+response.sessionyear+")";
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
                    
                    $("#dwnlodrprt").html('<a href="'+baseurl+'/fees/downloadreport/'+session+'/'+sctns+'/'+val+'" class="btn btn-info" title="<?= $lbl1422 ?>"><?= $lbl1422 ?> </a>');
                }
                $("#feereport_table").html("");
                $("#feereport_table").html(response.tabledata);
                
            }
        })  
    }
   
    $(function() {
        $('#privileges').change(function(e) {
            var selected = $(e.target).val();
            console.dir(selected);
            //$("#selcls").html("");
            var baseurl = window.location.pathname.split('/')[1];
            var baseurl = "/" + baseurl;
            //alert(baseurl);
            $.ajax({ 
                url: baseurl +"/fees/getcls", 
                data: {'selclses':selected, 'func':'gc'}, 
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                type: 'post',success: function (result) 
                {       
                    console.log(result);
                    $("#selcls").html(result);
                    var sctns = selected.join(",");
                    $("#selsctns").val(sctns);
                }
            });
        }); 
    })
    
</script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
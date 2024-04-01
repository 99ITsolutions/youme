<style>
    #left-sidebar { left: -250px; }
    #main-content { width:100%;}
</style>
<?php
foreach($lang_label as $langlbl) {
    if($langlbl['id'] == '2190') { $lbl2190 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '1422') { $lbl1422 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '136') { $lbl136 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '1032') { $lbl1032 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '341') { $lbl341 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '317') { $lbl317 =  $langlbl['title'] ; } 
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
    if($langlbl['id'] == '2191') {  $lbl2191 = $langlbl['title'] ;  } 
    if($langlbl['id'] == '2192') {  $lbl2192 = $langlbl['title'] ;  } 
}
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?= $lbl2190 ?></h2>
                <ul class="header-dropdown">
                    <li id="dwnlodrprt"></li>
                    <li><a href="<?=$baseurl?>fees/feereport" class="btn btn-info" title="Back"><?= $backlbl ?> </a></li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
    					<label><?= $lbl241 ?>*</label>
    					<div class="form-group">                                    
    						<select class="form-control js-states session" id="session" required placeholder="Select Session">
    						    <option value=""><?= $lbl341 ?></option>
    						    <?php foreach($session_details as $val) { ?>
    					            <option value="<?= $val['id'] ?>"><?= $val['startyear']."-". $val['endyear'] ?></option>
    					        <?php } ?>
    				        </select>
    					</div>
    				</div>
    				<div class="col-md-2">
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
    						<select class="form-control js-states class_s" id="selcls" placeholder="Select Class" onchange="getinstdescname(this.value)">
    						    
    				        </select>
    					</div>
    				</div>
    				<div class="col-md-2">
    				    <label><?= $lbl2191."/". $lbl2192 ?></label>
    					<div class="form-group">                                    
    						<select class="form-control js-states request_opt" id="comp_due" placeholder="Select Option" onchange="getcd(this.value)">
    						    <option value=""><?= $lbl192 ?></option>
					            <option value="completed"><?= $lbl2191 ?></option>
					            <option value="dues"><?= $lbl2192 ?></option>
    				        </select>
    					</div>
    				</div>
    				<div class="col-md-2">
    				    <label><?= $lbl317 ?></label>
    					<div class="form-group">                                    
    						<select class="form-control js-states attend_filter" id="instlmnt" placeholder="Select Value" onchange="getcdreport(this.value)">
    						    
    				        </select>
    					</div>
    				</div>
                </div>
            </div>
            <div class="body">
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
    $(function() {
        $('#privileges').change(function(e) {
            var selected = $(e.target).val();
            //console.dir(selected);
            //$("#selcls").html("");
            $.ajax({ 
                url: baseurl +"/fees/getcls", 
                data: {'selclses':selected, 'func':'cd'}, 
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                type: 'post',success: function (result) 
                {       
                    console.log(result);
                    $("#selcls").html(result);
                }
            });
        }); 
    })
    
    function getinstdescname(val)
    {
        $("#instlmnt").html("");
        var sess = $("#session").val();
        $.ajax({ 
            url: baseurl +"/fees/getinstdesc", 
            data: {'clsid':val, 'sess':sess}, 
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            type: 'post',
            success: function (result) 
            {       
                console.log(result);
                $("#instlmnt").html(result);
            }
        });
    }
    
    function getcdreport(val)
    {
        if(val != "")
        {
            var sess = $("#session").val();
            var cls = $("#selcls").val();
            var cd = $("#comp_due").val();
            $("#dwnlodrprt").html('');
            $.ajax({ 
                url: baseurl +"/fees/getcdreport", 
                data: {'desc':val, 'sess':sess, 'cls':cls, 'cd':cd}, 
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                type: 'post',
                success: function (result) 
                {       
                    console.log(result);
                    $("#dwnlodrprt").html('<a href="'+baseurl+'/fees/downloadcdreport/'+sess+'/'+cls+'/'+cd+'/'+val+'" class="btn btn-info" title="<?= $lbl1422 ?>"><?= $lbl1422 ?> </a>');
                    $("#feereport_table").html("");
                    $("#feereport_table").html(result.tabledata);
                }
            });
        }
    }
    
</script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
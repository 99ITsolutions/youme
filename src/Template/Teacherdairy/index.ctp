<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
    .col-sm-3 {
        -ms-flex: 0 0 18%;
        flex: 0 0 18%;
        max-width: 18%;
        padding-right:2px;
    }
    
    
</style>
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '2150') { $lbl2150 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2148') { $lbl2148 = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '136') { $clslbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '365') { $sublbl = $langlbl['title'] ; }
    if($langlbl['id'] == '1448') { $tchrlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2025') { $stdttymlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2026') { $endtymlbl = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2193') { $lbl2193 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2194') { $lbl2194 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2195') { $lbl2195 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2196') { $lbl2196 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2197') { $lbl2197 = $langlbl['title'] ; } 
}
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading"><?= $lbl2148 ?></h2>
                            <ul class="header-dropdown">
                                <li><a target="_blank" href="<?= $baseurl ?>Teacherdairy/downloadreport/<?= $clsid.'/'.$subjctid.'/'.$startdate.'/'.$enddate ?> " title="Download Report" <?= $dwnldrprt ?> class="btn btn-sm btn-success" >Download Report</a></li>
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="body" id="gen_pdf">
                        <div class="row clearfix col-md-12 ">
                            <div class="error" id="summryerror"><?= $error ?></div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-12 row"  style="max-width:100%">
    	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
    	                    <input type="hidden" name="searchby" value="1">
    	                    <div class="row col-md-12">
        	                    <div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control class_s" id="teachercls" name="teachercls" required onchange = "getsubjcls(this.value)">
                                            <option value="">Choose Class</option>
                                            <?php foreach($empclas as $ec) { 
                                                if($ec['class_id'] == $clsid) { $sel = "selected"; } else { $sel = ""; } ?>
                                                    <option value="<?= $ec['class_id'] ?>" <?=  $sel ?>><?= $ec['classname'] ?></option>
                                            <?php } ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '10') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control subj_s" id="subjct" name="subjct" required>
                                            <option value="">Choose Subject</option>
                                            <?php if($subjctid != '') { 
                                                foreach($empclassub as $ecs) { 
                                                if($ecs['subject_id'] == $subjctid) { $sel = "selected"; } else { $sel = ""; } ?>
                                                    <option value="<?= $ecs['subject_id'] ?>" <?=  $sel ?> ><?= $ecs['subname'] ?></option>
                                            <?php } } ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-4" style="max-width:22%; flex:0 0 22%;">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2149') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control commondatepicker" data-date-format="dd-mm-yyyy" id="startdate" value="<?= $startdate ?>" name="startdate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2149') { echo $langlbl['title'] ; } } ?>*">
                                    </div>
        	                    </div>
        	                    <div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2152') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control commondatepicker" data-date-format="dd-mm-yyyy" id="enddate" value="<?= $enddate ?>" name="enddate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2152') { echo $langlbl['title'] ; } } ?>*">
                                    </div>
        	                    </div>
        	                    <div class="col-sm-1">
        	                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
        	                    </div>
        	                    </div>
        	                    <?php echo $this->Form->end();?>
        	                </div>
        	               
    	                </div>
    	                
    	                <div class="row  clearfix">
    	                    <?php if(!empty($subjname)) { ?>
                            <div class="col-sm-12 pt-2">
                                <div class="row container">
                                    <div class="col-md-3 text-left">
                                        <img src="<?= $baseurl ?>img/<?= $scllogo ?>" width="100px" height="100px">
                                    </div>
                                    <div class="col-md-5 text-center">
                                        <p><b><?= $clslbl ?>:  <?= $clsname ?></b></p>
                                        <p><b><?= $sublbl ?>:  <?= $subjname ?></b></p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <p><b><?= $tchrlbl ?>:  <?= $teachername ?></b></p>
                                        <p><b>School Year: <?= $session ?></b></p>
                                    </div>
                                    <br>
                                    <div class="col-md-12 row mt-2">
                                        
                                    </div>
                                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'updatetdairy'] , 'id' => "studentdairyform" , 'method' => "post"  ]);  ?>
                                    <div class="col-md-12 row" id="viewtdairy">
                                        <div class="col-md-3 text-center" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2193 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 11%; max-width: 11%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2194 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2195 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2196 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 22%; max-width: 22%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2197 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 22%; max-width: 22%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            Observations
                                        </div>
                                    <?php foreach($get_tdairy as $td)
                    		        {
                    		            if(($td['str_sd'] >= $sd) && ($td['str_ed'] <= $ed))
                    		            { ?>
                    		                    <input type="hidden" class="form-control" name="tdid[]" id="tdid" value="<?= $td['id'] ?>">
                    		                    
                                                <div class="col-md-3 text-center pt-3" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                   
                                                    <?= date("d-m-Y", strtotime($td['start_date']))." - ".date("d-m-Y", strtotime($td['end_date'])) ?>
                                                </div>
                                                <div class="col-md-3 text-center pt-3" style="flex: 0 0 11%; max-width: 11%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                     <?= $td['hours'] ?>                                           
                                                </div>
                                                
                                                <div class="col-md-3 text-center pt-3" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                <?php if((!empty($td['matieres'])) && (!empty($td['matieres_school']))) { ?>
                                                    <input type="text" class="form-control" name="matires_school[]" id="matires_school" readonly value="<?= $td['matieres_school'] ?>" style="display:inline-block; width:72%; " placeholder="For School...">
                                                    <a href="javascript:void(0);" data-title="Matieres Prevues" data-str="<?= ucfirst($td['matieres_school']) ?>" class="btn btn-sm btn-outline-secondary tdairydtl"><i class="fa fa-eye"></i></a>
                                                    <br>
                                                    <input type="text" class="form-control" name="matires[]" id="matires" value="<?= $td['matieres'] ?>" style="display:inline-block; width:72%; " placeholder="For Teacher...">
                                                    <a href="javascript:void(0);" data-title="Matieres Prevues" data-str="<?= ucfirst($td['matieres']) ?>" class="btn btn-sm btn-outline-secondary tdairydtl"><i class="fa fa-eye"></i></a>
                                                
                                                <?php }
                                                elseif((!empty($td['matieres'])) && (empty($td['matieres_school']))) { ?>
                                                    <input type="text" class="form-control" name="matires_school[]" id="matires_school" placeholder="For School..." readonly>
                                                    <br>
                                                    <input type="text" class="form-control" name="matires[]" id="matires" value="<?= $td['matieres'] ?>" style="display:inline-block; width:72%; " placeholder="For Teacher...">
                                                    <a href="javascript:void(0);" data-title="Matieres Prevues" data-str="<?= ucfirst($td['matieres']) ?>" class="btn btn-sm btn-outline-secondary tdairydtl"><i class="fa fa-eye"></i></a>
                                                
                                                <?php }
                                                elseif((empty($td['matieres'])) && (!empty($td['matieres_school']))) { echo $td['matieres']; ?>
                                                    <input type="text" class="form-control" name="matires_school[]" readonly id="matires_school" value="<?= $td['matieres_school'] ?>" style="display:inline-block; width:72%; " placeholder="For School...">
                                                    <a href="javascript:void(0);" data-title="Matieres Prevues" data-str="<?= ucfirst($td['matieres_school']) ?>" class="btn btn-sm btn-outline-secondary tdairydtl"><i class="fa fa-eye"></i></a>
                                                    <br>
                                                    <input type="text" class="form-control" name="matires[]" id="matires" placeholder="For Teacher...">
                                                <?php }
                                                else { ?>
                                                    <input type="text" class="form-control" name="matires_school[]" id="matires_school" placeholder="For School..." readonly>
                                                    <br>
                                                    <input type="text" class="form-control" name="matires[]" id="matires" placeholder="For Teacher...">
                                                <?php } ?>
                                                </div>
                                                
                                                <?php if(!empty($td['obectifs_operational'])) { ?>
                                                <div class="col-md-3 text-center pt-3" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                    <input type="text" class="form-control" name="obectifs[]" id="obectifs" value="<?= $td['obectifs_operational'] ?>"  style="display:inline-block; width:78%; "> <a href="javascript:void(0);" data-title="Objectifs Operationals" data-str="<?= ucfirst($td['obectifs_operational']) ?>" class="btn btn-sm btn-outline-secondary tdairydtl2"><i class="fa fa-eye"></i></a>
                                                    
                                                </div>
                                                <?php } else { ?>
                                                <div class="col-md-3 text-center pt-3" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                    <input type="text" class="form-control" name="obectifs[]" id="obectifs" >
                                                </div>
                                                <?php }
                                                if(!empty($td['matieres_v'])) { ?>
                                                <div class="col-md-3 text-center pt-3" style="flex: 0 0 22%; max-width: 22%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                    <input type="text" class="form-control" name="matiresv[]" id="matiresv" value="<?= $td['matieres_v'] ?>"  style="display:inline-block; width:78%; "> <a href="javascript:void(0);" data-title="Matieres Vues" data-str="<?= ucfirst($td['matieres_v']) ?>" class="btn btn-sm btn-outline-secondary tdairydtl4"><i class="fa fa-eye"></i></a>
                                                    
                                                </div>
                                                <?php } else { ?>
                                                <div class="col-md-3 text-center pt-3" style="flex: 0 0 22%; max-width: 22%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                    <input type="text" class="form-control" name="matiresv[]" id="matiresv" >
                                                </div>
                                                <?php }
                                                if(!empty($td['observation'])) { ?>
                                                <div class="col-md-3 text-center pt-3" style="flex: 0 0 22%; max-width: 22%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                    <input type="text" class="form-control" name="observation[]" id="observation" value="<?= $td['observation'] ?>"  style="display:inline-block; width:85%; "> <a href="javascript:void(0);" data-title="Observations" data-str="<?= ucfirst($td['observation']) ?>" class="btn btn-sm btn-outline-secondary tdairydtl3"><i class="fa fa-eye"></i></a>
                                                    
                                                </div>
                                                <?php } else { ?>
                                                <div class="col-md-3 text-center pt-3" style="flex: 0 0 22%; max-width: 22%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                    <input type="text" class="form-control" name="observation[]" id="observation" >
                                                </div>
                                                <?php } ?>
                    		            <?php }
                    		        } ?>
                    		        <div class="row container col-md-12">
                                        <div class="error" id="sderror"></div>
                                        <div class="success" id="sdsuccess"></div>
                                    </div>
                                    <div class="row clearfix m-1 text-right">
                                        <div class="col-sm-1 ">
                	                        <button type="submit" class="btn btn-primary mt-4 studdairybtn" id="studdairybtn">Submit</button>
                	                    </div>
                	                </div>
                                    </div>
                                    <?php echo $this->Form->end(); ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>

<!------------------ view detail --------------------->

<div class="modal classmodal animated zoomIn" id="viewtd" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><span id="title"></span></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                <div class="row clearfix container">
                    <div id="detailfull">
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>        

<!------------------ End --------------------->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?php
if(!empty($error))
{ ?>
    <script>
        $("#summryerror").fadeIn().delay('5000').fadeOut('slow');
    </script>
<?php } ?>

<script>
function getsubjcls(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $("#subjct").html("");
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherdairy/getsubjcls',
        data:{'clsid':val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            console.log(html);
            $("#subjct").html(html);
        }
    });
}



</script>   


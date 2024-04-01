<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
    @media (min-width: 576px)
    {
    .col-sm-3 {
        -ms-flex: 0 0 22%;
        flex: 0 0 22%;
        max-width: 22%;
    }
    }
    
</style>
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '2150') { $lbl2150 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2151') { $lbl2151 = $langlbl['title'] ; } 
    if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '136') { $clslbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '365') { $sublbl = $langlbl['title'] ; }
    if($langlbl['id'] == '1448') { $tchrlbl = $langlbl['title'] ; }
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
                            <h2 class="col-md-11 heading"><?= $lbl2150 ?></h2>
                            <ul class="header-dropdown">
                                <?php if(!empty($sclsub_details)) { if(in_array("113", $roles)) {?>
                                <li><a href="<?= $baseurl ?>schoolteacherdairy/view" title="<?= $lbl2151 ?>" class="btn btn-sm btn-success" ><?= $lbl2151 ?></a></li>
                                <?php } } else { ?>
                                <li><a href="<?= $baseurl ?>schoolteacherdairy/view" title="<?= $lbl2151 ?>" class="btn btn-sm btn-success" ><?= $lbl2151 ?></a></li>
                                <?php } ?>
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
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2109') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control listteacher" id="listteacher" name="listteacher" required onchange="getteacherclass(this.value)">
                                            <option value="">Choose Teachers</option>
                                            <?php
                                            foreach($tchrdetails as $key => $val){
                                                if($tchrid != '' && ($val['id'] == $tchrid))
                                                {
                                                    $sel = "selected";
                                                }
                                                else
                                                {
                                                    $sel = "";
                                                }
                                            ?>
                                              <option  value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['f_name'] ." ". $val['l_name'];?> </option>
                                            <?php
                                           }
                                            ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-3 mt-1">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control class_s" id="teachercls" name="teachercls" required onchange = "getsubjcls(this.value)">
                                            <option value="">Choose Class</option>
                                            <?php if($clsid != '') { 
                                                foreach($empclas as $ec) { 
                                                if($ec['class_id'] == $clsid) { $sel = "selected"; } else { $sel = ""; } ?>
                                                    <option value="<?= $ec['class_id'] ?>" <?=  $sel ?>><?= $ec['classname'] ?></option>
                                            <?php } } ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-3 mt-1">
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
        	                    <!--<div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2149') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control dobirthdatepicker" data-date-format="dd-mm-yyyy" id="startdate" value="<?= $startdate ?>" name="startdate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1761') { echo $langlbl['title'] ; } } ?>*">
                                    </div>
        	                    </div>-->
        	                   
        	                    <div class="col-sm-1">
        	                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
        	                    </div>
        	                    </div>
        	                    <?php echo $this->Form->end();?>
        	                </div>
        	               
    	                </div>
    	                
    	                <div class="row  clearfix">
    	                    <?php if(!empty($subjname)) { ?>
    	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'addtdairy'] , 'id' => "studentdairyform" , 'method' => "post"  ]);  ?>
    	                    <input type="hidden" name="tchrid" value="<?= $tchrid ?>">
    	                    <input type="hidden" name="clsid" value="<?= $clsid ?>">
    	                    <input type="hidden" name="subid" value="<?= $subjctid ?>">
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
                                        <div class="col-md-3 text-center" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2193 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 10%; max-width: 10%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2194 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 16%; max-width: 16%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2195 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 16%; max-width: 16%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2196 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 19%; max-width: 19%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            <?= $lbl2197 ?>
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 19%; max-width: 19%; padding-left:6px; padding-right:6px; border: 1px solid">
                                            Observations
                                        </div>
                                        <div class="col-md-3 text-center" style="flex: 0 0 5%; max-width: 5%; padding-left:5px; padding-right:5px; border: 1px solid">
                                            
                                        </div>
                                    </div>
                                    <div class="teacherdairy">
                                        <div class="tdairy col-md-12 row">
                                            <div class="col-md-3 text-center pt-3" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                <input type="date" style="width:80%; margin-bottom: 2px; padding: .375rem .15rem; display:initial !important" name="wksdate[]" id="wksdate" class="form-control" placeholder="Start Date">-
                                                <input type="date" style="width:80%; margin-bottom: 2px; padding: .375rem .15rem; display:initial !important" name="wkedate[]" id="wkedate" class="form-control" placeholder="End Date">
                                            </div>
                                            <div class="col-md-3 text-center pt-3" style="flex: 0 0 10%; max-width: 10%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                 <input type="text" class="form-control" name="noofhrs[]" id="noofhrs" >                                                    
                                            </div>
                                            <div class="col-md-3 text-center pt-3" style="flex: 0 0 16%; max-width: 16%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                <input type="text" class="form-control" name="matires[]" id="matires" >
                                            </div>
                                            <div class="col-md-3 text-center pt-3" style="flex: 0 0 16%; max-width: 16%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                <input type="text" class="form-control" name="obectifs[]" id="obectifs" >
                                            </div>
                                            <div class="col-md-3 text-center pt-3" style="flex: 0 0 19%; max-width: 19%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                <input type="text" class="form-control" name="matiresv[]" id="matiresv" >
                                            </div>
                                            <div class="col-md-3 text-center pt-3" style="flex: 0 0 19%; max-width: 19%; padding-left:6px; padding-right:6px; border: 1px solid">
                                                <input type="text" class="form-control" name="observation[]" id="observation" >
                                            </div>
                                            <div class="col-md-3 text-center" style="flex: 0 0 5%; max-width: 5%; padding-left:5px; padding-right:5px; border: 1px solid">
                                                <button class="btn add-btn"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row container col-md-12">
                                        <div class="error" id="sderror"></div>
                                        <div class="success" id="sdsuccess"></div>
                                    </div>
                                    <div class="row clearfix m-1">
                                        <div class="col-sm-1">
                	                        <button type="submit" class="btn btn-primary mt-4 studdairybtn" id="studdairybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
                	                    </div>
                	                </div>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>

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
function getteacherclass(val)
{
    //alert(val);
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $("#teachercls").html("");
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolteacherdairy/getclass',
        data:'tid='+val,
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            console.log(html);
            $("#teachercls").html(html.classes);
        }
    });
}

function getsubjcls(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $("#subjct").html("");
    var tid = $("#listteacher").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolteacherdairy/getsubjcls',
        data:{'clsid':val, 'tid':tid },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            console.log(html);
            $("#subjct").html(html);
        }
    });
}


$(document).ready(function () {
    var max_input1 = 20;
    var y = 1;
    $('.add-btn').click(function (e) {
        e.preventDefault();
        
        if (y < max_input1) {
            y++;
            $('.teacherdairy').append(`
                <div class="tdairy col-md-12 row">
                    <div class="col-md-3 text-center pt-3" style="flex: 0 0 15%; max-width: 15%; padding-left:6px; padding-right:6px; border: 1px solid">
                        <input type="date" style="width:80%; margin-bottom: 2px; padding: .375rem .15rem; display:initial !important" name="wksdate[]" id="wksdate" class="form-control" placeholder="Start Date">-
                        <input type="date" style="width:80%; margin-bottom: 2px; padding: .375rem .15rem; display:initial !important" name="wkedate[]" id="wkedate" class="form-control" placeholder="End Date">
                    </div>
                    <div class="col-md-3 text-center pt-3" style="flex: 0 0 10%; max-width: 10%; padding-left:6px; padding-right:6px; border: 1px solid">
                         <input type="text" class="form-control" name="noofhrs[]" id="noofhrs" >                                                    
                    </div>
                    <div class="col-md-3 text-center pt-3" style="flex: 0 0 16%; max-width: 16%; padding-left:6px; padding-right:6px; border: 1px solid">
                        <input type="text" class="form-control" name="matires[]" id="matires" >
                    </div>
                    <div class="col-md-3 text-center pt-3" style="flex: 0 0 16%; max-width: 16%; padding-left:6px; padding-right:6px; border: 1px solid">
                        <input type="text" class="form-control" name="obectifs[]" id="obectifs" >
                    </div>
                    <div class="col-md-3 text-center pt-3" style="flex: 0 0 19%; max-width: 19%; padding-left:6px; padding-right:6px; border: 1px solid">
                        <input type="text" class="form-control" name="matiresv[]" id="matiresv" >
                    </div>
                    <div class="col-md-3 text-center pt-3" style="flex: 0 0 19%; max-width: 19%; padding-left:6px; padding-right:6px; border: 1px solid">
                        <input type="text" class="form-control" name="observation[]" id="observation" >
                    </div>
                    <a href="#" class="col-md-3 text-center remove-lnk form-control" style="flex: 0 0 5%; max-width: 5%; margin-top:7px; padding:5px; border: none"><i class="fa fa-minus"></i></a>
                   
                </div>
            `); // add input field
        }
    });
 
    // handle click event of the remove link
    $('.teacherdairy').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        $(this).parent('div.tdairy').remove();  // remove input field
        y--; // decrement the counter
    });
});

</script>   


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
    if($langlbl['id'] == '41') { $bcklbl = $langlbl['title'] ; }
    
    if($langlbl['id'] == '210') { $lbl210 = $langlbl['title'] ; }
    if($langlbl['id'] == '231') { $lbl231 = $langlbl['title'] ; }
    if($langlbl['id'] == '261') { $lbl261 = $langlbl['title'] ; }
    if($langlbl['id'] == '238') { $lbl238 = $langlbl['title'] ; }
    if($langlbl['id'] == '243') { $lbl243 = $langlbl['title'] ; }
    if($langlbl['id'] == '259') { $lbl259 = $langlbl['title'] ; }
    if($langlbl['id'] == '355') { $lbl355 = $langlbl['title'] ; }
    if($langlbl['id'] == '369') { $lbl369 = $langlbl['title'] ; }
    if($langlbl['id'] == '725') { $lbl725 = $langlbl['title'] ; }
    if($langlbl['id'] == '1058') { $lbl1058 = $langlbl['title'] ; }
    if($langlbl['id'] == '1761') { $lbl1761 = $langlbl['title'] ; }
    if($langlbl['id'] == '2159') { $lbl2159 = $langlbl['title'] ; }
    if($langlbl['id'] == '2157') { $trackrd = $langlbl['title'] ; } 
} 
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class=" row">
                    <h2 class="col-md-11 heading"><?= $lbl2159 ?></h2>
                    <ul class="header-dropdown">
                        <li><a href="javascript:void(0)" title="<?= $bcklbl ?>" class="btn btn-sm btn-success" onclick="goBack()"><?= $bcklbl ?></a></li>
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
                        <input type="hidden" class="form-control session" id="select_year" name="start_year" value="<?= $sessionid ?>">
	                    <div class="col-sm-3">
	                        <label><?= $lbl355 ?> *</label>
                            <div class="form-group">
                                <select class="form-control class" id="class" name="class" required onchange="getstudentsessionscl(this.value)">
                                    <option value="">Choose Class</option>
                                    <?php
                                    foreach($classdetails as $key => $val){ 
                                        $sel = '';
                                        if($val['id'] == $clsid)
                                        {
                                            $sel = "selected";
                                        }
                                    ?>
                                          <option  value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['c_name'] ."-" . $val['c_section']." (". $val['school_sections'] .")";?> </option>
                                        <?php
                                        }
                                    ?>
                                </select> 
                            </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <label><?= $lbl238 ?> *</label>
                            <div class="form-group">
                                <select class="form-control session" id="session" name="session" required>
                                    <option value="">Choose Session</option>
                                    <?php
                                    foreach($sessiondtl as $key => $val) { 
                                        $sel = '';
                                        if($val['id'] == $sessionid)
                                        {
                                            $sel = "selected";
                                        }
                                    ?>
                                          <option value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                        <?php
                                        }
                                    ?>
                                </select> 
                            </div>
	                    </div>
	                    <div class="col-sm-1">
	                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?= $lbl243 ?></button>
	                    </div>
	                    </div>
	                    <?php echo $this->Form->end();?>
	                </div>
                </div>
                
    	       <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="ts_tracklog_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?= $lbl210 ?></th>
                                        <th><?= $lbl261 ?></th>
                                        <th><?= $lbl231 ?></th>
                                        <th>Last date of Publish Report Card</th>   
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tracklog_body" class="modalrecdel"> 
                                    <?php 
                                    if(!empty($logdetails)) {
                                        $d = 1;
                                        foreach($logdetails as $stud)
                                        { ?>
                                            <tr>
                                                <td><?= $stud['f_name']." ".$stud['l_name'] ?></td>
                                                <td><?= $stud['adm_no'] ?></td>
                                                <td><?= $stud['email'] ?></td>
                                                <td><?= $stud['publish_date'] ?></td>
                                                <td>
                                                <a href="javascript:void(0);" data-toggle="modal" data-d="<?=$d ?>" data-id="<?= $stud['id'] ?>" data-class="<?= $clsid ?>" data-target="#myModal<?=$d;?>" title="Detailing" class="btn btn-sm btn-success rcdetailing">Click here to see details</a>
    <!-- Modal -->
    <div class="modal classmodal animated zoomIn" id="myModal<?=$d;?>" role="dialog">
        <div class="modal-dialog" role="document" >
            <!-- Modal content-->
            <div class="modal-content">
                
                <div class="modal-header">
                    <h6 class="title" id="defaultModalLabel">Detail - Report Card</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="trackercontent<?=$d;?>">
                </div>
            </div>
        </div>
    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        $d++; }  
                                        }
                                    ?>
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>
 <!--<div class="row" style="margin-top: 15px;">
                        <div class="col-md-12" ><b>Date:</b> <?= date('M d, Y',$value->login_time) ?></div>
                        <div class="container row clearfix">
                            <div class="col-md-3"><b>Login Duration:</b> 
                                <?= date('h:i A',$value->login_time)." - ".$logouttime. " (". $myduration .")" ?>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 30px;">
                            <table class="table table-bordered" id="trackact_table">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Page Name</th>
                                        <th>Page Link</th>
                                    </tr>
                                </thead>
                                <tbody id="trackact_body_<?= $value->id ?>">
                                </tbody>
                            </table>
                        </div>
                    </div>-->
<!------------------ End --------------------->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>

$(document).on('click', '.trackreport', function()
{
    //alert("hi");
    var clsid = "<?php echo $clsid ?>";
    var strtdate = "<?php echo $startdate1 ?>";
    var enddate = "<?php echo $enddate1 ?>";
    $.ajax({
        url: baseurl +"/teachertracking/downloadreport",
        type: "POST",
        data: {
            clsid: clsid,
            strtdate: strtdate,
            enddate: enddate
        },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success: function(response)
        {
           
        } 
    });
});
</script>



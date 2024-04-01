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
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class=" row">
                    <h2 class="col-md-11 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2141') { echo $langlbl['title'] ; } } ?></h2>
                    <ul class="header-dropdown">
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="body" id="gen_pdf">
                <div class="row clearfix col-md-12 ">
                    <div class="error" id="summryerror"><?= $error ?></div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-12 row"  style="max-width:100%">
                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'schoolreport'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
                    <input type="hidden" name="searchby" value="1">
                    <div class="row col-md-12">
	                    <div class="col-sm-3">
	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?> *</label>
                            <div class="form-group">
                                <select class="form-control schoollist" id="schoollist" name="schoollist" required>
                                    <option value="">Choose School</option>
                                    <?php
                                    foreach($comp_details as $key => $val){
                                        if($sclid != '' && ($val['id'] == $sclid))
                                        {
                                            $sel = "selected";
                                        }
                                        else
                                        {
                                            $sel = "";
                                        } ?>
                                        <option  value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['comp_name'] ;?> </option>
                                    <?php }  ?>
                                </select> 
                            </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <?php  if(!empty($startdate1)) {
                                    $startdate1 = date("d-m-Y", $startdate1);
                                }
                                else
                                {
                                    $startdate1 = '';
                                }?>
                                <input type="text" class="form-control dobirthdatepicker" id="startdate" value="<?= $startdate1 ?>" data-date-format="dd-mm-yyyy" name="startdate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1761') { echo $langlbl['title'] ; } } ?>*">
                            </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                                <?php  if(!empty($enddate1)) {
                                    $enddate1 = date("d-m-Y", $enddate1);
                                }
                                else
                                {
                                    $enddate1 = '';
                                }?>
                                <input type="text" class="form-control dobirthdatepicker" id="enddate" value="<?= $enddate1 ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1058') { echo $langlbl['title'] ; } } ?> *">
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
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="tracklog_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Login Time</th>
                                        <th>Logout Time</th>   
                                        <th>Time Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tracklog_body" class="modalrecdel"> 
                                    <?php if(!empty($logdetails)) {
                                        $d = 1;
                                    foreach($logdetails as $value)
                                    { 
                                        $logouttime = '';
                                        $duration = '';
                                        $minutes = '';
                                        $hour = '';
                                        if($value->logout_time != "")
                                        {
                                            $logouttime = date('h:i A',$value->logout_time);
                                            $difference = date_diff($value->login_time, $value->logout_time); 
                                            $hour = $difference->h;
                                            
                                            $minutes = $difference->days * 24 * 60;
                                            $minutes += $difference->h * 60;
                                            $minutes += $difference->i;
                                            
                                            
                                            if($hour == "" && $minutes == "0")
                                            {
                                                $duration = "00:00";
                                            }
                                            elseif($hour == "")
                                            {
                                                $duration = "00:".$minutes;
                                            }
                                            else
                                            {
                                                $duration = $hour.":".$minutes;
                                            }
                                        }

                                        $totalduration = $value->logout_time - $value->login_time; 
                                     
                                        if($totalduration < 60 && $logouttime != ""){ $myduration = $totalduration." Seconds";}
                                        else if($totalduration > 60 && $totalduration < 3600 && $logouttime != ""){ 
                                            $minduration = $totalduration/60;
                                            $myduration =  round($minduration)." Minutes";
                                           }    
                                        else{
                                             $myduration = "";
                                        }
                                        

                                        ?>
                                        <tr>
                                            <td><?= date('M d, Y',$value->login_time) ?></td>
                                            <td><?= date('h:i A',$value->login_time) ?></td>
                                            <td><?= $logouttime ?></td>
                                            <td><?=$myduration?></td>
                                            <td>
                                            <a href="javascript:void(0);" data-toggle="modal" data-id="<?= $value->id ?>" data-target="#myModal<?=$d;?>" title="Activities" class="btn btn-sm btn-success acttracker">Activities Tracker</a>
<!-- Modal -->
<div class="modal classmodal animated zoomIn" id="myModal<?=$d;?>" role="dialog">
    <div class="modal-dialog  modal-lg" role="document" style="max-width:80%">
        <!-- Modal content-->
        <div class="modal-content">
            
            <div class="modal-header">
                <h6 class="title" id="defaultModalLabel">Activities Tracker</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-top: 25px;">
                    <div class="col-md-12" style="margin-bottom:0px;"><b>Date:</b> <?= date('M d, Y',$value->login_time) ?></div>
                    <div class="container row clearfix">
                        <div class="col-md-8">
                            <b>Login Duration:</b> <?= date('h:i A',$value->login_time). " - ". $logouttime ." (". $myduration .")" ?> 
                        </div>
                        
                        <!--<div class="col-md-2" style="text-align: center;"><b><?=$myduration?></b></div>-->
                        
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
                            <tbody id="trackact_body">
                                <!--<tr><td colspan="3" style="text-align:center;">Coming Soon.....</td></tr>-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                                            </td>
                                        </tr>
                                        <?php
                                    $d++; }  }
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

<!------------------ End --------------------->

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

  



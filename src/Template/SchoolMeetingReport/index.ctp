<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
    .col-sm-2 {
        -ms-flex: 0 0 18%;
        flex: 0 0 18.2%;
        max-width: 18.2%;
        padding-right:10px !important;
        padding-left:10px !important;
    }
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1417') { echo $langlbl['title'] ; } } ?></h2>
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
    	                        <div class="col-sm-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control listschool" id="listschool" name="schoolid" required onchange="getteachers(this.value)">
                                            <option value="">Choose School</option>
                                            <?php
                                            foreach($school_details as $key => $val){
                                                if($sclids != '' && ($val['id'] == $sclids))
                                                {
                                                    $sel = "selected";
                                                }
                                                else
                                                {
                                                    $sel = "";
                                                }
                                            ?>
                                              <option  value="<?=$val['id']?>" <?= $sel ?>><?php echo $val['comp_name'];?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '15') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control listteacher" id="listteacher" name="listteacher" required>
                                            <option value="">Choose Teachers</option>
                                            <?php if(!empty($tchrid))
                                            {
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
                                           }
                                            ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <?php  if(!empty($startdate1)) {
                                            $startdate1 = date("d-m-Y", $startdate1);
                                        }
                                        else
                                        {
                                            $startdate1 = '';
                                        }?>
                                        <input type="text" class="form-control dobirthdatepicker" id="startdate" value="<?= $startdate1 ?>" data-date-format="dd-mm-yyyy" name="startdate"  required placeholder="Start Date*">
                                    </div>
        	                    </div>
        	                    <div class="col-sm-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <?php  if(!empty($enddate1)) {
                                            $enddate1 = date("d-m-Y", $enddate1);
                                        }
                                        else
                                        {
                                            $enddate1 = '';
                                        }?>
                                        <input type="text" class="form-control dobirthdatepicker" id="enddate" value="<?= $enddate1 ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="End Date *">
                                    </div>
        	                    </div>
        	                    <div class="col-sm-1">
        	                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
        	                    </div>
        	                    </div>
        	                    <?php echo $this->Form->end();?>
        	               </div>
        	               
        	               <div class="col-md-12 row clearfix"><hr><div class="text-center" style="font-weight:bold;">OR</div><hr></div>
        	               
    	                    <div class="col-md-12 row"  style="max-width:100%">
    	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
    	                    <input type="hidden" name="searchby" value="2">
    	                    <div class="row col-md-12">
    	                        <div class="col-sm-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control class" id="schoolid" name="schoolid" required onchange="getclass(this.value)">
                                            <option value="">Choose School</option>
                                            <?php
                                            foreach($school_details as $key => $val){
                                                if($sclid != '' && ($val['id'] == $sclid))
                                                {
                                                    $sel = "selected";
                                                }
                                                else
                                                {
                                                    $sel = "";
                                                }
                                            ?>
                                              <option  value="<?=$val['id']?>" <?= $sel ?>><?php echo $val['comp_name'];?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control class" id="class" name="class" required onchange="subjctclssuperadmin(this.value)">
                                            <option value="">Choose Class</option>
                                            <?php if(!empty($clsid))
                                            {
                                            foreach($classdetails as $key => $val){
                                                if($clsid != '' && ($val['id'] == $clsid))
                                                {
                                                    $sel = "selected";
                                                }
                                                else
                                                {
                                                    $sel = "";
                                                }
                                            ?>
                                              <option  value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
                                            <?php
                                            }
                                           }
                                            ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-sm-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '10') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control subj_s" name="subjects" id="cls_sub" placeholder="Choose Subjects" required>
                                           <?php //echo $subjctid;
                                           if(!empty($subjctid))
                                           {
                                               foreach($subjectids as $key => $val)
                                               {
                                                  
                                                    if($subjctid != '' && ($val == $subjctid))
                                                    {
                                                        $sel = "selected";
                                                    }
                                                    else
                                                    {
                                                        $sel = "";
                                                    }
                                                   ?>
                                                   <option  value="<?=$val ?>" <?= $sel ?> ><?php echo $subjectnames[$key] ;?> </option>
                                                   <?php
                                               }
                                           }
                                           ?>
                                        </select>
                                    </div>
        	                    </div>
        	                    <div class="col-sm-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <?php  if(!empty($startdate)) {
                                            $startdate = date("d-m-Y", $startdate);
                                        }
                                        else
                                        {
                                            $startdate = '';
                                        }?>
                                        <input type="text" class="form-control dobirthdatepicker" id="startdate" value="<?= $startdate ?>" data-date-format="dd-mm-yyyy" name="startdate"  required placeholder="Start Date*">
                                    </div>
        	                    </div>
        	                    <div class="col-sm-2">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <?php  if(!empty($enddate)) {
                                            $enddate = date("d-m-Y", $enddate);
                                        }
                                        else
                                        {
                                            $enddate = '';
                                        }?>
                                        <input type="text" class="form-control dobirthdatepicker" id="enddate" value="<?= $enddate ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="End Date *">
                                    </div>
        	                    </div>
        	                    <div class="col-sm-1">
        	                        <button type="submit" class="btn btn-primary mt-4 clsummary_report" id="clsummary_report"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
        	                    </div>
        	                    </div>
        	                    <?php echo $this->Form->end();?>
        	               </div>
    	                </div>
    	                
    	                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="meetinglink_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1448') { echo $langlbl['title'] ; } } ?></th> 
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '906') { echo $langlbl['title'] ; } } ?></th>   
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1197') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1198') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1199') { echo $langlbl['title'] ; } } ?></th>                             
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1200') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '110') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($meetdetails)) {
                                        
                                    foreach($meetdetails as $value)
                                    {
                                        $meeting_id = $value->meeting_id; //"YOUME".uniqid(); 
                                        $meeting_name = $value->meeting_name;
                                        $secret = "aTGBy6CgNh5xqxvUOMDIsPNh671fkcLGnkq8qrfYrA"; 
                                        $logout = urlencode("https://you-me-globaleducation.org/ConferenceMeet/callback.php?meetingID=".$meeting_id);
                                        $string = "createname=".$meeting_name."&meetingID=".$meeting_id."&attendeePW=111&moderatorPW=222&meta_endCallbackUrl=".$logout.$secret;
                                        $sh = sha1($string);
                                        
                                        ?>
                                        <tr>
                                            <td><?= ucfirst($value->tchr_name) ?></td>
                                            <td><?= implode(" ", explode("+", $value->meeting_name)) ?></td>
                                            <td><?= $value->generate_for ?></td>
                                            <td><?= date('M d, Y H:i',strtotime($value->schedule_date)) ?></td>
                                            <td><?= date('M d, Y H:i',$value->expirelink_datetime) ?></td>
                                            <!--<td><?= $value->meeting_link ?></td>-->
                                            <td><?= date('M d, Y',$value->created_date ) ?></td>
                                            <td>
                                                <?php  
                                                
                                                if($value->expirelink_datetime <= time()) 
                                                { 
                                                    ?>
                                                    <a href="javascript:void(0);" class="btn btn-outline-secondary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1413') { echo $langlbl['title'] ; } } ?></a>
                                                    <?php
                                                }elseif($value->meeting_status == 2){ ?>
                                                <a href="javascript:void(0);" class="btn btn-outline-secondary" >Meeting Ended</a>
                                                <?php }else { ?>
                                                <a href="javascript:void(0);" class="btn btn-outline-secondary" >Meeting not taken yet.</a>
                                                <!--<a href="javascript:void(0);" class="btn btn-outline-secondary joinmeeting" id="joinmeeting" data-time= "<?= $value->schedule_datetime ?>" data-chksum= "<?= $sh ?>" data-mname= "<?= $value->meeting_name ?>" data-id= "<?= $value->id ?>" data-ctime= "<?= time() ?>" data-mid = "<?= $meeting_id ?>">Start Meeting</a>-->
                                                <?php } ?>
                                                
                                                
                                            </td>
                                        </tr>
                                        <?php
                                    } }
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
<!------------------ End --------------------->

<script>


function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    



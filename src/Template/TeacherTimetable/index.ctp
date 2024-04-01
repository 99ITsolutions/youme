<?php $statusarray = array('Inactive','Active' );
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1554') { $lbl1554 = $langlbl['title'] ; }
    if($langlbl['id'] == '1829') { $lbl1829 = $langlbl['title'] ; }
    if($langlbl['id'] == '1837') { $sundylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1830') { $mndylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1831') { $tuesdylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1832') { $wedlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '1833') { $thurdylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1834') { $frilbl = $langlbl['title'] ; }
    if($langlbl['id'] == '1835') { $satlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2100') { $nodatalbl = $langlbl['title'] ; } 
}

if(!empty($error))
{
    $error = $nodatalbl;
} else { $error = ''; }
?>
<style>
    table>tbody>tr>td { border:1px solid !important;}
    table>tbody>tr>th, table>tbody>tr>td { text-align:center !important;}
    .input-group-append
    {
        display:none !important;
    }
    .slotstym {
        background: #542583; 
        color: #fff !important; 
        border:1px solid #fff;
    }
</style>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1495') { echo $langlbl['title'] ; } } ?></h2>
                                 <ul class="header-dropdown">
                                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                                </ul>
                        </div>
                        <div class="body">
                            <?php	echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'method' => "post"  ]); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="error" id="gettterror"><?= $error ?></div>
                                    <div class="success" id="getttsuccess"></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">         
                                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1000') { echo $langlbl['title'] ; } } ?></label>
                                        <select class="form-control class" id="class_fil" name="class_fil" placeholder="Choose Class">
                                            <option value="">Choose Class</option>
                                            <?php
                                            foreach($classids as $key => $val){
                                            ?>
                                              <option  value="<?=$val ?>"  <?php if($val == $classid) { echo "selected"; } ?> ><?php echo $clsnames[$key];?> </option>
                                            <?php
                                            } 
                                            ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">         
                                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1003') { echo $langlbl['title'] ; } } ?></label>
                                        <select class="form-control session" id="session" name="session" placeholder="Choose session">
                                            <option value="">Choose Session</option>
                                            <?php
                                            foreach($session_details as $key => $val){
                                            ?>
                                              <option  value="<?=$val['id']?>" <?php if($val['id'] == $sessionid ) { echo "selected"; } ?>><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary timefilterbtn" id="timefilterbtn" style="margin-top: 25px;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1033') { echo $langlbl['title'] ; } } ?></button>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                           
                            
                            <div class="row" style="overflow-x:auto;">
                                <?php if(!empty($get_slot))
                                { 
                                    //print_r($timetbl_details);
                                ?>
                                <table style="width: 100%; border:1px solid #ccc;table-layout: fixed;">
                                    <tr style="background: #542583; color: #fff !important;">
                                        <th><?= $lbl1829 ?></th>
                                        <th><?= $mndylbl ?></th>
                                        <th><?= $tuesdylbl ?></th>
                                        <th><?= $wedlbl ?></th>
                                        <th><?= $thurdylbl ?></th>
                                        <th><?= $frilbl ?></th>
                                        <th><?= $satlbl ?></th>
                                    </tr>
                                    <?php foreach($get_slot as $slot) { ?>
                                    <tr>
                                        <td class="slotstym"><?= $slot['start_time']."-".$slot['end_time'] ?></td>
                                        <?php if(!empty($slot['period_name'])) { 
                                            echo "<td colspan='6' style='background:#ffa812; font-weight:bold;'>".ucfirst($slot['period_name'])."</td>" ;
                                        } else { ?><td><?php
                                            $mndy_st = [];
                                            foreach($timetbl_details as $tym)
                                            {
                                                if($tym['week_day'] == "Monday") {
                                                    $mndy_st[] = $tym['start_time'] ;
                                                    $mndy_sub[] = $tym['subjectName'] ;
                                                    $mndy_tchr[] = $tym['tchr_name'] ;
                                                }
                                            }
                                            
                                            if(in_array($slot['start_time'], $mndy_st)) { 
                                                $mon_st =  array_search($slot['start_time'], $mndy_st);
                                                
                                                echo $mndy_sub[$mon_st]."<br>(".$mndy_tchr[$mon_st].")";
                                            } ?> 
                                        </td> <td><?php
                                            $tues_st = [];
                                            foreach($timetbl_details as $tym)
                                            {
                                                if($tym['week_day'] == "Tuesday") {
                                                    $tues_st[] = $tym['start_time'] ;
                                                    $tues_sub[] = $tym['subjectName'] ;
                                                    $tues_tchr[] = $tym['tchr_name'] ;
                                                }
                                            }
                                            if(in_array($slot['start_time'], $tues_st)) { 
                                                $tue_st =  array_search($slot['start_time'], $tues_st);
                                                
                                                echo $tues_sub[$tue_st]."<br>(".$tues_tchr[$tue_st].")";
                                            }   ?> 
                                        </td><td><?php
                                            $wed_st = [];
                                            foreach($timetbl_details as $tym)
                                            {
                                                if($tym['week_day'] == "Wednesday") {
                                                    $wed_st[] = $tym['start_time'] ;
                                                    $wed_sub[] = $tym['subjectName'] ;
                                                    $wed_tchr[] = $tym['tchr_name'] ;
                                                }
                                            }
                                            if(in_array($slot['start_time'], $wed_st)) { 
                                                $we_st =  array_search($slot['start_time'], $wed_st);
                                                echo $wed_sub[$we_st]."<br>(".$wed_tchr[$we_st].")";
                                            }  ?> 
                                        </td><td><?php
                                            $thur_st = [];
                                            foreach($timetbl_details as $tym)
                                            {
                                                if($tym['week_day'] == "Thursday") {
                                                    $thur_st[] = $tym['start_time'] ;
                                                    $thur_sub[] = $tym['subjectName'] ;
                                                    $thur_tchr[] = $tym['tchr_name'] ;
                                                }
                                            }
                                            if(in_array($slot['start_time'], $thur_st)) { 
                                                $th_st =  array_search($slot['start_time'], $thur_st);
                                                echo $thur_sub[$th_st]."<br>(".$thur_tchr[$th_st].")";
                                            }   ?> 
                                        </td><td><?php
                                            $fri_st = [];
                                            foreach($timetbl_details as $tym)
                                            {
                                                if($tym['week_day'] == "Friday") {
                                                    $fri_st[] = $tym['start_time'] ;
                                                    $fri_sub[] = $tym['subjectName'] ;
                                                    $fri_tchr[] = $tym['tchr_name'] ;
                                                }
                                            }
                                            if(in_array($slot['start_time'], $fri_st)) { 
                                                $fr_st =  array_search($slot['start_time'], $fri_st);
                                                echo $fri_sub[$fr_st]."<br>(".$fri_tchr[$fr_st].")";
                                            }  ?> 
                                        </td><td><?php
                                        $sat_st = [];
                                        foreach($timetbl_details as $tym)
                                        {
                                            if($tym['week_day'] == "Saturday") {
                                                $sat_st[] = $tym['start_time'] ;
                                                $sat_sub[] = $tym['subjectName'] ;
                                                $sat_tchr[] = $tym['tchr_name'] ;
                                            }
                                        }
                                        if(in_array($slot['start_time'], $sat_st)) { 
                                            $sa_st =  array_search($slot['start_time'], $sat_st);
                                            echo $sat_sub[$sa_st]."<br>(".$sat_tchr[$sa_st].")";
                                        }  ?>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </table>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
            </div>


        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?php 
function SplitTime($StartTime, $EndTime, $Duration="60",  $breaktyms, $beakslots){
    $ReturnArray = array ();// Define output
    $StartTime    = strtotime ($StartTime); //Get Timestamp
    $EndTime      = strtotime ($EndTime); //Get Timestamp
    
    $AddMins  = $Duration * 60;
    
    $btimes = explode(",", $breaktyms);
    $bslot = explode(",", $beakslots);
    

    while ($StartTime < $EndTime) //Run loop
    {
        if(in_array($StartTime, $btimes))
        {
            $indx = array_search($StartTime, $btimes); 
            $brkslot = explode(" minutes", $bslot[$indx]);
            $slotbrk = $brkslot[0]*60;
            
            $stt = date ("H:i", $StartTime);
            $StartTime += $slotbrk; //Endtime check
            if($StartTime > $EndTime)
            {
                $et = date ("H:i", $EndTime);
            }
            else
            {
                $et = date ("H:i", $StartTime);
            }
            
            $ReturnArray[] = $stt."-".$et;
        }
        else
        {
            $stt = date ("H:i", $StartTime);
            $StartTime += $AddMins; //Endtime check
            if($StartTime > $EndTime)
            {
                $et = date ("H:i", $EndTime);
            }
            else
            {
                $et = date ("H:i", $StartTime);
            }
            $ReturnArray[] = $stt."-".$et;
        }
    }
    return $ReturnArray;
}
if(!empty($error))
{
    ?>
    <script>
        $("#gettterror").fadeIn().delay('5000').fadeOut('slow');
    </script>
    <?php
}
?>
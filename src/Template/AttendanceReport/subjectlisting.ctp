<style>
th {
    background-color: #001f3f;
    color: #fff;
    padding: 0.5em 1em;
}
td {
    border-top: 1px solid #eee;
    padding: 0.5em 1em;
}
a.attendcolor {
    color: #000000 !important;
}
a.attendnocolor {
    color: #000000 !important;
}
</style>
<?php
foreach($lang_label as $langlbl) {
    if($langlbl['id'] == '2047') { $auglbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2048') { $seplbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2049') { $octlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2050') { $novlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2051') { $declbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2052') { $janlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2053') { $feblbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2054') { $marlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2055') { $aprlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2056') { $maylbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2057') { $junlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2058') { $jlylbl = $langlbl['title'] ; }
}
   

if($month == "1") { $mnfrencheng = $janlbl; }
if($month == "2") { $mnfrencheng = $feblbl; }
if($month == "3") { $mnfrencheng = $marlbl; }
if($month == "4") { $mnfrencheng = $aprlbl; }
if($month == "5") { $mnfrencheng = $maylbl; }
if($month == "6") { $mnfrencheng = $junlbl; }
if($month == "7") { $mnfrencheng = $jlylbl; }
if($month == "8") { $mnfrencheng = $auglbl; }
if($month == "9") { $mnfrencheng = $seplbl; }
if($month == "10") { $mnfrencheng = $octlbl; }
if($month == "11") { $mnfrencheng = $novlbl; }
if($month == "12") { $mnfrencheng = $declbl; }
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header row">
                <?php $date = "05-".$month."-".date("Y"); ?>
                <h2 class="col-md-6 align-left heading">Student Name: <?= $stud_details['l_name']." ".$stud_details['f_name'] ?></h2>
                <h2 class="col-md-6 align-right"><a href="<?= $baseurl ?>AttendanceReport/view/<?= $class_details['id'] ?>/<?= $month ?>" title="Back" class="btn btn-sm btn-success" >Back </a></h2>
                <h2 class="col-md-12 align-left heading">Month: <?= $mnfrencheng ?></h2><br>
                <h2 class="col-md-12 align-left heading">Class: <?= $class_details['c_name']."-".$class_details['c_section']. " (".$class_details['school_sections']. ")" ?></h2>
            </div>
            <?php $mnthnm = date("F", strtotime($date)); ?>
            
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <?php  $year = date('Y'); ?>
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example table-custom table-striped m-b-0 c_list default_pageitem subjattendancereport_table no-footer dataTable" id="subjattendancereport_table" data-page-length="50"  >
                                <thead>
                                    <tr>
                                        <th class="name-col" width="35%">Student Name</th>
                                        <th class="name-col">Present</th>
                                        <th class="name-col">Absent</th>
                                        <th class="name-col">Exception</th>
                                        <?php if(($month == "01") || ($month == "03") || ($month == "05") || ($month == "07") || ($month == "08") || ($month == "10") || ($month == "12"))
                                        { for($i = 1; $i<= 31; $i++){ ?>
                                            <th><?= $i ?></th> 
                                        <?php }  } 
                                        elseif($month == "02")
                                        { for($i = 1; $i<= 28; $i++){ ?>
                                            <th><?= $i ?></th> 
                                        <?php }  } 
                                        else
                                        { for($i = 1; $i<= 30; $i++){ ?>
                                            <th><?= $i ?></th> 
                                        <?php }  } ?>   
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($holiday_details as $holi) { 
                                        $holidates[] = $holi['date'];
                                    }
                                    foreach($sub_details as $key => $sub)
                                    { 
                                        $subid = '';
                                        $subid = $sub['id'];
                                        if(($month == "01") || ($month == "03") || ($month == "05") || ($month == "07") || ($month == "08") || ($month == "10") || ($month == "12"))
                                        { 
                                            $strtdate = $year."-".$month."-01";
                                            $enddate = $year."-".$month."-31";
                                        }
                                        elseif($month == "02")
                                        {
                                            $strtdate = $year."-".$month."-01";
                                            $enddate = $year."-".$month."-28";
                                        }
                                        else
                                        {
                                            $strtdate = $year."-".$month."-01";
                                            $enddate = $year."-".$month."-30";
                                        }
                                        
                                        $gettotal = totalpresent($strtdate, $enddate, $stud_details['id'], $subid, $class_details['id']);
                                        ?>
                                        
                                        <tr>
                                            <td class="name-col"><?= ucfirst($sub['subject_name']) ?></td>
                                            <td class="attend-col"><?= $gettotal['present'] ?></td>
                                            <td class="attend-col"><?= $gettotal['absent'] ?></td>
                                             <td class="attend-col"><?= $gettotal['exception'] ?></td>
                                            <?php if(($month == "01") || ($month == "03") || ($month == "05") || ($month == "07") || ($month == "08") || ($month == "10") || ($month == "12"))
                                            { 
                                                for($i = 1; $i<= 31; $i++) { 
                                                    
                                                    if($i >= 1 && $i <= 9) { $j = "0".$i; } else { $j = $i; }
                                                    $datecol = $j."-".$month."-".$year;
                                                    $revdatecol = $year."-".$month."-".$j;
                                                    $dayofcolumn = date('w', strtotime($datecol));
                                                    if(in_array($revdatecol, $holidates))
                                                    {
                                                        $holiday = 1;
                                                    }
                                                    else
                                                    {
                                                        $holiday = 0;
                                                    }
                                                    
                                                    
                                                    if($dayofcolumn == 0) 
                                                    { ?>
                                                        <td class="attend-col" style="background-color: blue;color: #FFF;">H</td>
                                                    <?php } 
                                                    elseif($holiday == 1) { ?>
                                                        <td class="attend-col" style="background-color: blue;color: #FFF;">H</td>
                                                    <?php }
                                                    else { 
                                                        if($dateatt = checksubjectattendance($revdatecol, $stud_details['id'], $subid, $class_details['id'] ))
                                                        { 
                                                            if($dateatt == "P") { $attbg = 'style="background-color: green;color: #FFF;"' ; }
                                                            elseif($dateatt == "E") { $attbg = 'style="background-color: yellow;"' ; }
                                                            elseif($dateatt == "A") { $attbg = 'style="background-color: red;color: #FFF;"'; }
                                                            else { $attbg = ''; }
                                                        
                                                            if($dateatt != "")
                                                            {
                                                            ?>
                                                                <td class="attend-col" <?= $attbg ?> ><a href="javascript:void(0)" class="attendupdate attendnocolor" data-subid="<?= $subid ?>" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud_details['l_name'].' '.$stud_details['f_name'] ?>"  data-monthname = "<?= $mnthnm ?>"  data-studid="<?= $stud_details['id'] ?>" data-attend="<?= $dateatt ?>"><?= $dateatt ?></a></td>
                                                            <?php } 
                                                            else
                                                            { ?>
                                                                <td class="attend-col" <?= $attbg ?>><a href="javascript:void(0)" class="attendupdate attendnocolor" data-subid="<?= $subid ?>" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud_details['l_name'].' '.$stud_details['f_name'] ?>"  data-monthname = "<?= $mnthnm ?>"  data-studid="<?= $stud_details['id'] ?>" data-attend="">N/A</a></td>
                                                            <?php
                                                            }
                                                        }
                                                        else
                                                        { ?>
                                                            <td class="attend-col"><a href="javascript:void(0)" class="attendupdate attendnocolor" data-subid="<?= $subid ?>" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud_details['l_name'].' '.$stud_details['f_name'] ?>"  data-monthname = "<?= $mnthnm ?>"  data-studid="<?= $stud_details['id'] ?>" data-attend="">N/A</a></td>
                                                        <?php }
                                                    }
                                                } 
                                            }
                                            elseif($month == "02")
                                            { 
                                                for($i = 1; $i<= 28; $i++)
                                                { 
                                                    //$datecol = $i."-".$month."-".$year;
                                                    if($i >= 1 && $i <= 9) { $j = "0".$i; } else { $j = $i; }
                                                    $datecol = $j."-".$month."-".$year;
                                                    $revdatecol = $year."-".$month."-".$j;
                                                    $dayofcolumn = date('w', strtotime($datecol));
                                                    if(in_array($revdatecol, $holidates))
                                                    {
                                                        $holiday = 1;
                                                    }
                                                    else
                                                    {
                                                        $holiday = 0;
                                                    }
                                                    if($dayofcolumn == 0) 
                                                    { ?>
                                                        <td class="attend-col" style="background-color: blue;color: #FFF;">H</td>
                                                    <?php } 
                                                    elseif($holiday == 1) { ?>
                                                        <td class="attend-col" style="background-color: blue;color: #FFF;">H</td>
                                                    <?php }
                                                    else { 
                                                        if($dateatt = checksubjectattendance($revdatecol, $stud_details['id'], $sub['id'], $class_details['id'] ))
                                                        { 
                                                            if($dateatt == "P") { $attbg = 'style="background-color: green;color: #FFF;"' ; }
                                                            elseif($dateatt == "E") { $attbg = 'style="background-color: yellow;"' ; }
                                                            elseif($dateatt == "A") { $attbg = 'style="background-color: red;color: #FFF;"'; }
                                                            else { $attbg = ''; }
                                                        
                                                            if($dateatt != "")
                                                            {
                                                            ?>
                                                                <td class="attend-col" <?= $attbg ?> ><a href="javascript:void(0)" class="attendupdate attendnocolor" data-subid="<?= $subid ?>" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud_details['l_name'].' '.$stud_details['f_name'] ?>"  data-monthname = "<?= $mnthnm ?>"  data-studid="<?= $stud_details['id'] ?>" data-attend="<?= $dateatt ?>"><?= $dateatt ?></a></td>
                                                            <?php } 
                                                            else
                                                            { ?>
                                                                <td class="attend-col" <?= $attbg ?>><a href="javascript:void(0)" class="attendupdate attendnocolor" data-subid="<?= $subid ?>" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud_details['l_name'].' '.$stud_details['f_name'] ?>"  data-monthname = "<?= $mnthnm ?>"  data-studid="<?= $stud_details['id'] ?>" data-attend="">N/A</a></td>
                                                            <?php
                                                            }
                                                        }
                                                        else
                                                        { ?>
                                                            <td class="attend-col"><a href="javascript:void(0)" class="attendupdate attendnocolor" data-subid="<?= $subid ?>" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud_details['l_name'].' '.$stud_details['f_name'] ?>"  data-monthname = "<?= $mnthnm ?>"  data-studid="<?= $stud_details['id'] ?>" data-attend="">N/A</a></td>
                                                        <?php }
                                                    }
                                                }  
                                            } 
                                            else
                                            { 
                                                for($i = 1; $i<= 30; $i++) 
                                                { 
                                                    //$datecol = $i."-".$month."-".$year;
                                                    if($i >= 1 && $i <= 9) { $j = "0".$i; } else { $j = $i; }
                                                    $datecol = $j."-".$month."-".$year;
                                                    $revdatecol = $year."-".$month."-".$j;
                                                    $dayofcolumn = date('w', strtotime($datecol));
                                                    if(in_array($revdatecol, $holidates))
                                                    {
                                                        $holiday = 1;
                                                    }
                                                    else
                                                    {
                                                        $holiday = 0;
                                                    }
                                                    if($dayofcolumn == 0) 
                                                    { ?>
                                                        <td class="attend-col" style="background-color: blue;color: #FFF;">H</td>
                                                    <?php } 
                                                    elseif($holiday == 1) { ?>
                                                        <td class="attend-col" style="background-color: blue;color: #FFF;">H</td>
                                                    <?php }
                                                    else 
                                                    { 
                                                        if($dateatt = checksubjectattendance($revdatecol, $stud_details['id'], $sub['id'], $class_details['id'] ))
                                                        { 
                                                            if($dateatt == "P") { $attbg = 'style="background-color: green;color: #FFF;"' ; }
                                                            elseif($dateatt == "E") { $attbg = 'style="background-color: yellow;"' ; }
                                                            elseif($dateatt == "A") { $attbg = 'style="background-color: red;color: #FFF;"'; }
                                                            else { $attbg = ''; }
                                                        
                                                            if($dateatt != "")
                                                            {
                                                            ?>
                                                                <td class="attend-col" <?= $attbg ?> ><a href="javascript:void(0)" class="attendupdate attendnocolor" data-subid="<?= $subid ?>" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud_details['l_name'].' '.$stud_details['f_name'] ?>"  data-monthname = "<?= $mnthnm ?>"  data-studid="<?= $stud_details['id'] ?>" data-attend="<?= $dateatt ?>"><?= $dateatt ?></a></td>
                                                            <?php } 
                                                            else
                                                            { ?>
                                                                <td class="attend-col" <?= $attbg ?>><a href="javascript:void(0)" class="attendupdate attendnocolor" data-subid="<?= $subid ?>" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud_details['l_name'].' '.$stud_details['f_name'] ?>"  data-monthname = "<?= $mnthnm ?>"  data-studid="<?= $stud_details['id'] ?>" data-attend="">N/A</a></td>
                                                            <?php
                                                            }
                                                        }
                                                        else
                                                        { ?>
                                                            <td class="attend-col"><a href="javascript:void(0)" class="attendupdate attendnocolor" data-subid="<?= $subid ?>" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud_details['l_name'].' '.$stud_details['f_name'] ?>"  data-monthname = "<?= $mnthnm ?>"  data-studid="<?= $stud_details['id'] ?>" data-attend="">N/A</a></td>
                                                        <?php }
                                                    }
                                                }  
                                            } ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function showreport(month){
  if(month == 'September'){
          document.getElementById("september").style.display = "block"; 
          document.getElementById("august").style.display = "none"; 
  }else{
         document.getElementById("september").style.display = "none"; 
         document.getElementById("august").style.display = "block"; 
  }
}
</script>
<?php

  
function checksubjectattendance($revdatecol, $studid, $subid, $classid ){ 
    $hostname = "localhost";
    $username = "youmeglo_globaluser";
    $password = "DFmp)9_p%Kql";
    $database = "youmeglo_globalweb";
    $con = mysqli_connect($hostname, $username, $password, $database); 
    if(mysqli_connect_error($con)){ echo "Connection Error."; die();}
    $getdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `attendance` WHERE student_id = '".$studid."' AND date =  '".$revdatecol."' AND class_id = '".$classid."' AND subject_id = '".$subid."' "));
    
    $title = $getdata['title'];
    if("Exception" == $title)
    {
        $attendance = "E";
    }
    elseif("Present" == $title)
    {
        $attendance = "P";
    }
    elseif("Absent" == $title)
    {
        $attendance = "A";
    }
    else
    {
        $attendance = "";
    }
    return $attendance;
}

function totalpresent($strtdate, $enddate, $studid, $subid, $classid)
{
    $hostname = "localhost";
    $username = "youmeglo_globaluser";
    $password = "DFmp)9_p%Kql";
    $database = "youmeglo_globalweb";
    $con = mysqli_connect($hostname, $username, $password, $database); 
    if(mysqli_connect_error($con)){ echo "Connection Error."; die();}
    $strt = explode("-", $strtdate);
    
    $end = explode("-", $enddate);
    
    for($i = 1; $i<= $end[2]; $i++)
    {
        if($i >= 1 && $i <= 9) { $j = "0".$i; } else { $j = $i; }
        $date = $strt[0]."-".$strt[1]."-".$j;
        $getatt = mysqli_query($con, "SELECT * FROM `attendance` WHERE student_id = '".$studid."' AND date =  '".$date."' AND class_id = '".$classid."' AND subject_id = '".$subid."' ");
        $data = [];
        $exception = "";
        $present = "";
        $absent = "";
        $reason = '';
        while($getdata = mysqli_fetch_assoc($getatt))
        {
          $data[] = $getdata['title'];
          $resn[] = $getdata['reason'];
        }
        if(in_array("Exception", $data))
        {
            $exception .= "1,";
            $searhkey= array_search("Exception", $data);
            $reason .= $date." (". $resn[$searhkey] .")";
        }
        elseif(in_array("Present", $data))
        {
            $present .= "1,";
        }
        elseif(in_array("Absent", $data))
        {
            $absent .= "1,";
        }
        else
        {
            $notentr = "";
        }
        //echo $exception;
        $exc= explode(",", $exception);
        $excp = count($exc)-1;
        $excptn[] = $excp;
        
        $pre= explode(",", $present);
        $pres = count($pre)-1;
        $prsnt[] = $pres;
        
        $ab= explode(",", $absent);
        $abs = count($ab)-1;
        $absnt[] = $abs;
        $reson[] = $reason;
    }
    
    $excptn = array_sum($excptn);
    $prsnt = array_sum($prsnt);
    $absnt = array_sum($absnt); 
    $reson = implode(" ", $reson);
    $dataattendance['exception'] = $excptn;
    $dataattendance['absent'] = $absnt;
    $dataattendance['present'] = $prsnt;
    $dataattendance['reason'] = $reson;
    return $dataattendance;
}


?>

<div class="modal classmodal animated zoomIn" id="editsubattend" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <div class="row">
                    <h6 class="title col-md-5" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1110') { echo $langlbl['title'] ; } } ?> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '577') { echo $langlbl['title'] ; } } ?></h6>
                    <button type=" col-md-6 text-right button" class=" close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h6 class="col-md-12">Student Name: <span id="studname"></span></h6>
                    <h6 class="col-md-12">Date: <span id="date"></span></h6>
                    <h6 class="col-md-12">Month: <span id="monthname"></span></h6>
                </div>
                
                 
                
    	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'updatesubatend'] , 'id' => "updatesubatendform" , 'method' => "post"  ]); ?>
    
                <div class="row clearfix">
                    <input type="hidden" name="studid" id="studid">
                    <input type="hidden" name="seldate" id="seldate">
                    <input type="hidden" name="classid" id="classid">
                    <input type="hidden" name="subid" id="subid">
                    
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '14') { echo $langlbl['title'] ; } } ?></label>
                            <select name="attendance" id="attendnc_filter" class="form-control attend_filter">
                                <option value="P">Present</option>
                                <option value="A">Absent</option>
                                <option value="E">Exception</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '253') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <textarea type="text" class="form-control" name="reason"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="reportsclatterror"></div>
                        <div class="success" id="reportsclattsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary updateattend" id="updateattend"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '108') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
        </div>
    </div>
</div>              


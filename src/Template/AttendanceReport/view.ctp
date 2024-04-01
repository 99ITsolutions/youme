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

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="col-md-6 align-left heading"><?= $class_details['c_name'] ?>-<?= $class_details['c_section'] ?> (<?= $class_details['school_sections'] ?>)</h2>
                    <h2 class="col-md-6 align-right">
                        <a href="<?= $baseurl ?>AttendanceReport" title="Back" class="btn btn-sm btn-success" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                    </h2>
                </div>
            </div>
            <input type="hidden" id="clasid" value="<?= $class_details['id'] ?>">
            <div class="body">
                <?php  
                //setlocale(LC_TIME, "fr_FR");
                //echo strftime("%B");
                //$month = date('m');
                $month = $mnth; ?>
                <?php  //print_r($session_details);
                $monthnme = [];
                $monthid = [];
                $srtMonth= $session_details->startmonth." ".$session_details->startyear;
                $yearnme[0] = $session_details->startyear;
                $monthnme[0]= ucfirst($session_details->startmonth);
                $monthid[0]= date('m', strtotime($srtMonth));
                $yearnm = $session_details->startyear;
                
                $year = $yearnm; 
                $fromdata = "09-".$month."-".$year;
                $monthname = date("F", strtotime($fromdata));
                
                for($i=1; $i <= 11; $i++){
                    $monthnm = date('F', strtotime("+1 months", strtotime($srtMonth)));
                    
                    $mnthid = date('m', strtotime("+1 months", strtotime($srtMonth)));
                    $srtMonth = $monthnm;
                    $monthnme[$i]= $monthnm;
                    $yearnme[$i] = $yearnm;
                    if($mnthid == 12)
                    {
                        $yearnm = $yearnm+1;
                    }
                    $monthid[$i]= $mnthid;
                    //echo "<br>";
                }
                //print_r($monthnme);
               ?>
               
                <div style="width: 25%;margin-bottom: 40px;"> 
                    <div class="form-group">
                        <label for="sel1"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1970') { echo $langlbl['title'] ; } } ?>:</label>
                        <select class="form-control" id="sel1" onchange="showreport(this.value)">
                          
                            <?php
                            foreach($monthid as $key => $mid)
                            { 
                                if($monthnme[$key] == "January") { $mnfrencheng = $janlbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "February") { $mnfrencheng = $feblbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "March") { $mnfrencheng = $marlbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "April") { $mnfrencheng = $aprlbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "May") { $mnfrencheng = $maylbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "June") { $mnfrencheng = $junlbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "July") { $mnfrencheng = $jlylbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "August") { $mnfrencheng = $auglbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "September") { $mnfrencheng = $seplbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "October") { $mnfrencheng = $octlbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "November") { $mnfrencheng = $novlbl." ".$yearnme[$key]; }
                                if($monthnme[$key] == "December") { $mnfrencheng = $declbl." ".$yearnme[$key]; }
                            
                            ?>
                                <option value="<?= $mid."_".$yearnme[$key] ?>" <?php if($month == $mid) { echo "selected"; } ?> ><?= $mnfrencheng ?></option>
                            <?php } ?>
                        </select>
                    </div> 
                </div>  
                  
                <div class="row clearfix">
                    <div class="col-sm-12" id="monthattendance">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example table-custom table-striped m-b-0 c_list default_pageitem attendancereport_table no-footer dataTable" id="attendancereport_table" data-page-length="50"  >
                                <thead id="attendancereporthead">
                                    <tr>
                                        <th class="name-col" width="35%"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '147') { echo $langlbl['title'] ; } } ?></th>
                                        <th class="name-col"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '277') { echo $langlbl['title'] ; } } ?></th>
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
                                        <?php }  } 
                                        ?>
                                        <th class="name-col">Exception Details</th>
                                    </tr>
                                </thead>
                                <tbody id="attendancereportbody">
                                <?php 
                                foreach($holiday_details as $holi) { 
                                    $holidates[] = $holi['date'];
                                }
                                foreach($attend_details as $att) { 
                                    $studids[] = $att['student_id'];
                                    $attdates[] = $att['attdate'];
                                }
                                //print_r($studids);
                                
                                foreach($stud_details as $stud) { 
                                    $year = date("Y");
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
                                        $sid = $stud['id'];
                                        $gettotal = totalpresent($strtdate, $enddate, $sid);
                                        //print_r($gettotal);
                                    ?>     
                                    <tr class="student">
                                        <td class="name-col"><a href="<?= $baseurl ?>AttendanceReport/subjectlisting/<?= $sid ?>/<?= $month ?>/<?= $class_details['id'] ?>"><?= $stud['l_name']." ".$stud['f_name'] ?></a></td>
                                        <!-- <td class="name-col"><a href="#"><?= $stud['l_name']." ".$stud['f_name'] ?></a></td>-->
                                        
                                        <td class="attend-col"><?= $gettotal['present'] ?></td>
                                        <td class="attend-col"><?= $gettotal['absent'] ?></td>
                                        <td class="attend-col"><?= $gettotal['exception']  ?></td>
                                        
                                        <!--<td class="attend-col">12</td>
                                        <td class="attend-col">21</td>
                                        <td class="attend-col">2</td>-->
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
                                                    if($dateatt = checkattendance($revdatecol, $stud['id']))
                                                    { 
                                                        
                                                        if($dateatt == "P") { $attbg = 'style="background-color: green;color: #FFF;"' ; }
                                                        elseif($dateatt == "E") { $attbg = 'style="background-color: yellow;"' ; }
                                                        elseif($dateatt == "A") { $attbg = 'style="background-color: red;color: #FFF;"'; }
                                                        else { $attbg = ''; }
                                                        
                                                        if($dateatt != "")
                                                        {
                                                        ?>
                                                            <td class="attend-col" <?= $attbg ?> ><a href="javascript:void(0)" class="attendupdate attendcolor" data-clsid="<?= $class_details['id'] ?>" data-date="<?= $datecol ?>" data-studname="<?= $stud['l_name'].' '.$stud['f_name'] ?>"  data-monthname = "<?= $monthname ?>"  data-studid="<?= $stud['id'] ?>" data-attend="<?= $dateatt ?>"><?= $dateatt ?></a></td>
                                                        <?php } 
                                                        else
                                                        { ?>
                                                            <td class="attend-col" <?= $attbg ?>><a href="javascript:void(0)" class="attendupdate attendnocolor" data-clsid="<?= $class_details['id'] ?>" data-date="<?= $datecol ?>" data-studname="<?= $stud['l_name'].' '.$stud['f_name'] ?>"  data-monthname = "<?= $monthname ?>"  data-studid="<?= $stud['id'] ?>" data-attend="">N/A</a></td>
                                                        <?php
                                                        }
                                                    }
                                                    else
                                                    { ?>
                                                        <td class="attend-col"><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud['l_name'].' '.$stud['f_name'] ?>"  data-monthname = "<?= $monthname ?>"  data-studid="<?= $stud['id'] ?>" data-attend="">N/A</a></td>
                                                    <?php }
                                                }
                                            } 
                                        }
                                        elseif($month == "02")
                                        { 
                                            for($i = 1; $i<= 28; $i++)
                                            { 
                                                
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
                                                    if($dateatt = checkattendance($revdatecol, $stud['id']))
                                                    { 
                                                        
                                                        if($dateatt == "P") { $attbg = 'style="background-color: green;color: #FFF;"' ; }
                                                        elseif($dateatt == "E") { $attbg = 'style="background-color: yellow;"' ; }
                                                        elseif($dateatt == "A") { $attbg = 'style="background-color: red;color: #FFF;"'; }
                                                        else { $attbg = ''; }
                                                        
                                                        if($dateatt != "")
                                                        {
                                                        ?>
                                                            <td class="attend-col" <?= $attbg ?> ><a href="javascript:void(0)" class="attendupdate attendcolor" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud['l_name'].' '.$stud['f_name'] ?>"  data-monthname = "<?= $monthname ?>"  data-studid="<?= $stud['id'] ?>" data-attend="<?= $dateatt ?>"><?= $dateatt ?></a></td>
                                                        <?php } 
                                                        else
                                                        { ?>
                                                            <td class="attend-col" <?= $attbg ?>><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud['l_name'].' '.$stud['f_name'] ?>"  data-monthname = "<?= $monthname ?>"  data-studid="<?= $stud['id'] ?>" data-attend="">N/A</a></td>
                                                        <?php
                                                        }
                                                    }
                                                    else
                                                    { ?>
                                                        <td class="attend-col"><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud['l_name'].' '.$stud['f_name'] ?>"  data-monthname = "<?= $monthname ?>"  data-studid="<?= $stud['id'] ?>" data-attend="">N/A</a></td>
                                                    <?php }
                                                }
                                            }  
                                        } 
                                        else
                                        { 
                                            for($i = 1; $i<= 30; $i++) 
                                            { 
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
                                                    if($dateatt = checkattendance($revdatecol, $stud['id']))
                                                    { 
                                                        
                                                        if($dateatt == "P") { $attbg = 'style="background-color: green;color: #FFF;"' ; }
                                                        elseif($dateatt == "E") { $attbg = 'style="background-color: yellow;"' ; }
                                                        elseif($dateatt == "A") { $attbg = 'style="background-color: red;color: #FFF;"'; }
                                                        else { $attbg = ''; }
                                                        
                                                        if($dateatt != "")
                                                        {
                                                        ?>
                                                            <td class="attend-col" <?= $attbg ?> ><a href="javascript:void(0)" class="attendupdate attendcolor" data-clsid="<?= $class_details['id'] ?>" data-date="<?= $datecol ?>" data-studname="<?= $stud['l_name'].' '.$stud['f_name'] ?>"  data-monthname = "<?= $monthname ?>"  data-studid="<?= $stud['id'] ?>" data-attend="<?= $dateatt ?>"><?= $dateatt ?></a></td>
                                                        <?php } 
                                                        else
                                                        { ?>
                                                            <td class="attend-col" <?= $attbg ?>><a href="javascript:void(0)" class="attendupdate attendnocolor" data-clsid="<?= $class_details['id'] ?>" data-date="<?= $datecol ?>" data-studname="<?= $stud['l_name'].' '.$stud['f_name'] ?>"  data-monthname = "<?= $monthname ?>"  data-studid="<?= $stud['id'] ?>" data-attend="">N/A</a></td>
                                                        <?php
                                                        }
                                                    }
                                                    else
                                                    { ?>
                                                        <td class="attend-col"><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="<?= $datecol ?>" data-clsid="<?= $class_details['id'] ?>" data-studname="<?= $stud['l_name'].' '.$stud['f_name'] ?>"  data-studname="<?= $stud['l_name']." ".$stud['f_name'] ?>"  data-monthname = "<?= $monthname ?>"  data-studid="<?= $stud['id'] ?>" data-attend="">N/A</a></td>
                                                    <?php }
                                                }
                                            }  
                                        } ?>
                                        <td class="attend-col"><?= $gettotal['reason']  ?></td>
                                        
                                    </tr>
                                    <?php /*} 
                                    }*/
                                    }?>
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
<script type="text/javascript">
function showreport(val)
{
    //alert(val);
    var clsid = $("#clasid").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/AttendanceReport/getmonthreport',
        data:{'month':val, 'clsid' : clsid},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            
            $('#attendancereport_table').DataTable().destroy();
            $('#attendancereportbody').html(""); 
            $('#attendancereporthead').html(""); 
            $('#attendancereporthead').html(result.tablehead); 
            $('#attendancereportbody').html(result.tablebody); 
            
            $( "#attendancereport_table" ).DataTable({
                "language": {
                    "lengthMenu": show+" _MENU_"+entries,
                    "search": search+":",
                    "info": show+" _START_ to _END_ "+ ofentries +" _TOTAL_ "+ entries,
                    "infoEmpty": show+" 0 to _END_ "+ ofentries +" _TOTAL_ "+ entries,
                    "paginate": {
                      next: next,
                      previous: prev,
                    }
                }
            });
        }
    });
    
}
</script>
<?php

  
function checkattendance($revdatecol, $studid){
$hostname = "localhost";
$username = "youmeglo_globaluser";
$password = "DFmp)9_p%Kql";
$database = "youmeglo_globalweb";
$con = mysqli_connect($hostname, $username, $password, $database); 
if(mysqli_connect_error($con)){ echo "Connection Error."; die();}
  $getdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `attendance_school` WHERE student_id = '".$studid."' AND date =  '".$revdatecol."' "));
  //print_r($getdata);
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

function totalpresent($strtdate, $enddate, $studid) 
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
        $getatt = mysqli_query($con, "SELECT * FROM `attendance_school` WHERE student_id = '".$studid."' AND date =  '".$date."'  ");
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
<div class="modal classmodal animated zoomIn" id="editsclattend" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <div class="row">
                    <h6 class="title col-md-5" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1110') { echo $langlbl['title'] ; } } ?> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '245') { echo $langlbl['title'] ; } } ?></h6>
                    <button type=" col-md-6 text-right button" class=" close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h6 class="col-md-12">Student Name: <span id="studname"></span></h6>
                    <h6 class="col-md-12">Date: <span id="date"></span></h6>
                    <h6 class="col-md-12">Month: <span id="monthname"></span></h6>
                </div>
                
                 
                
    	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'updatesclatend'] , 'id' => "updatesclatendform" , 'method' => "post"  ]); ?>
    
                <div class="row clearfix">
                    <input type="hidden" name="studid" id="studid">
                    <input type="hidden" name="seldate" id="seldate">
                    <input type="hidden" name="classid" id="classid">
                    
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


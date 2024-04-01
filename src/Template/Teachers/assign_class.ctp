<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
    
?>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;you
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

.showclass th{ min-width: 80px; }
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1746') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">


<?php
$tchrid = '';
$grdesid = '';
if(!empty($_GET))
{
  $tchrid = $_GET['teacherval'];
  $grdesid = $_GET['gradesval'];
}
?>

<select class="col-sm-3 form-group form-control" name="teacher" style="float: left; margin-right: 20px;" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
<option value="?teacherval=&gradesval=<?=$grdesid;?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1588') { echo $langlbl['title'] ; } } ?></option>
<?php foreach($teacher_dropdown as $value){ ?>
<option value="?gradesval=<?=$grdesid;?>&teacherval=<?=base64_encode($value['id'])?>" <?php if($tchrid == base64_encode($value['id'])){ ?> selected  <?php }?>><?=$value['f_name']?>&nbsp;<?=$value['l_name']?></option>
 <?php }  ?>
</select>   


<select class="col-sm-3 form-group form-control " name="teacher" style="float: left; margin-right: 20px;" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
<option value="?gradesval=&teacherval=<?=$tchrid;?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '299') { echo $langlbl['title'] ; } } ?></option>
<?php foreach($grade_dropdwon as $cvalue){ 
if(!empty($sclsub_details[0]))
{ 
    if(strtolower($cvalue['school_sections']) == "creche" || strtolower($cvalue['school_sections']) == "maternelle") {
        $clsmsg = "kindergarten";
    }
    elseif(strtolower($cvalue['school_sections']) == "primaire") {
        $clsmsg = "primaire";
    }
    else
    {
        $clsmsg = "secondaire";
    }
    $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
    if(in_array($clsmsg, $subpriv)) { 
        $show = 1;
        $countclas[] = 1;
    }
    else
    {
        $show = 0;
    }
} else { 
    $show = 1;
    $countclas[] = 1;
}
if($show == 1) {
?>
<option value="?teacherval=<?=$tchrid;?>&gradesval=<?=base64_encode($cvalue['c_name'])?>" <?php if($grdesid == base64_encode($cvalue['c_name'])){ ?> selected  <?php }?> ><?=$cvalue['c_name']?>-<?=$cvalue['c_section']?>(<?=$cvalue['school_sections']?>)</option>
<?php } }  ?>
</select> 

                        <div style="clear: both;">&nbsp;</div>
                        <div class="row">
                            <div class="col-md-2">
                                <table style="width: 100%; float: left;  padding:0px !important;">
                                    <tr>
                                        <th style="height: 150px;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1749') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                    <?php
                                    $n=1; foreach($teacher_details as $value){
                                    ?>              
                                        <tr>
                                            <td><?=$value['f_name']?>&nbsp;<?=$value['l_name']?></td>
                                        </tr>
                                    <?php 
                                    $n++;
                                     }
                                    ?>        
                                </table>
                            </div>
                            <div class="col-md-10" style="float: left; overflow-x: scroll; padding:0px !important;">
                                <table style="width: 100%;" class="showclass">
                                    <tr>
                                        <?php 
                                        $countclas = [];
                                        foreach($class_details as $cvalue) {
                                        if(!empty($sclsub_details[0]))
                                        { 
                                            if(strtolower($cvalue['school_sections']) == "creche" || strtolower($cvalue['school_sections']) == "maternelle") {
                                                $clsmsg = "kindergarten";
                                            }
                                            elseif(strtolower($cvalue['school_sections']) == "primaire") {
                                                $clsmsg = "primaire";
                                            }
                                            else
                                            {
                                                $clsmsg = "secondaire";
                                            }
                                            $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                            if(in_array($clsmsg, $subpriv)) { 
                                                $show = 1;
                                                $countclas[] = 1;
                                            }
                                            else
                                            {
                                                $show = 0;
                                            }
                                        } else { 
                                            $show = 1;
                                            $countclas[] = 1;
                                        }
                                        if($show == 1) {?>
                                                <th style="height: 150px;"><?=$cvalue['c_name']?> - <?=$cvalue['c_section']?>(<?=$cvalue['school_sections']?>)</th>
                                        <?php } 
                                        } ?> 
                                    </tr>
                                    <?php
                                    $cs = count($countclas);
                                    $ct = count($teacher_details);
                                    $d = 0; 
                                    ?>
                                    <?php for ($y = 1; $y <= $ct; $y++) {?>         
                                    <tr>
                                        <?php for ($x = 1; $x <= $cs; $x++) {?> 
                                        <td>
                                            <?php
                                            $rod = $x - 1;
                                            $classid = json_decode($class_details[$rod]);
                                            $teacerid = json_decode($teacher_details[$d]);
                                            $selected = checkteachclass($classid->id, $teacerid->id);
                                            ?>
                                            <input type="radio" <?php if($selected == 1){?> checked="checked" <?php } ?> onclick="assginteacher(<?=$classid->id;?>, <?=$teacerid->id;?>, '<?=$classid->c_name;?>', '<?=$classid->c_section;?>', '<?=$teacerid->f_name;?>', '<?=$teacerid->l_name;?>');" name="class<?=$x;?>">
                                        </td>
                                        <?php } ?> 
                                    </tr>  
                                    <?php $d++;} ?> 
                                </table>
                            </div>
                        </div>
              
                    </div>
            </div>
        </div>
    </div>
</div>    

<?php

function checkteachclass($classid, $teacherid){
    $hostname = "localhost";
    $username = "youmeglo_globaluser";
    $password = "DFmp)9_p%Kql";
    $database = "youmeglo_globalweb";
    $con = mysqli_connect($hostname, $username, $password, $database); 
    if(mysqli_connect_error($con)){ echo "Connection Error."; die();}
    $replies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `classteacher` WHERE `classid` = '$classid' AND `teacherid` = '$teacherid' "));
    return $replies;
}

foreach($lang_label as $langlbl) 
{ 
    if($langlbl['id'] == '2115')  {  $tolbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '2116')  {  $areyousure = $langlbl['title'] ;  } 
    if($langlbl['id'] == '2117')  {  $suclbl = $langlbl['title'] ;  } 
}
 
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script type="text/javascript">
  function assginteacher(classid, teacerid, c_name, c_section, f_name, l_name){
    var result = confirm("<?php echo $areyousure ?> "+c_name+" - "+c_section+" <?php echo $tolbl ?> "+f_name+" "+l_name);
    if (result) {
        $.ajax({
            type: "POST",
            data: {classids:classid, teacerids:teacerid},
            beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            url: "https://you-me-globaleducation.org/school/teachers/excutedata",
            success: function(data){
                      alert("<?php echo $suclbl ?> "+c_name+" - "+c_section+" <?php echo $tolbl ?> "+f_name+" "+l_name);
                }
            });
     }
  }
</script>
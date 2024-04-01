<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
    
?>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
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
            <?php //print_r($class_details); ?>

            <?php //print_r($teacher_dropdown); ?>

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
<?php foreach($grade_dropdwon as $cvalue){ ?>
<option value="?teacherval=<?=$tchrid;?>&gradesval=<?=base64_encode($cvalue['c_name'])?>" <?php if($grdesid == base64_encode($cvalue['c_name'])){ ?> selected  <?php }?> ><?=$cvalue['c_name']?>-<?=$cvalue['c_section']?>(<?=$cvalue['school_sections']?>)</option>
 <?php }  ?>
</select> 

<div style="clear: both;">&nbsp;</div>


                        <table style="width: 15%; float: left;">
                          <tr>
                            <th style="height: 150px;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1749') { echo $langlbl['title'] ; } } ?></th>
                          </tr>
                          <?php
                            $n=1;
                            foreach($teacher_details as $value){
                          ?>              
                          <tr>
                            <td><?=$value['f_name']?>&nbsp;<?=$value['l_name']?></td>
                          </tr>
                          <?php 
                            $n++;
                             }
                          ?>        
                        </table>
                       <div style="width: 85%; float: left; overflow-x: scroll;">
                        <table style="width: 100%;" class="showclass">
                          <tr>

                          <?php
                            foreach($class_details as $cvalue){
                          ?>

                            <th style="height: 150px;"><?=$cvalue['c_name']?> - <?=$cvalue['c_section']?>(<?=$cvalue['school_sections']?>)</th>

                          <?php } ?>  
                           
                          </tr>

                           <?php
                             $cs = count($class_details);
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


                        <div style="height: 40px; clear: both;">&nbsp;</div>
              
                    </div>
            </div>
        </div>
    </div>
</div>    

<?php

function checkteachclass($classid, $teacherid){
  $hostname = "localhost";
  $username = "you_me_global";
  $password = "Pass!234";
  $database = "you_me_global";
  $con = mysqli_connect($hostname, $username, $password, $database); 
  if(mysqli_connect_error($con)){ echo "Connection Error."; die();}
  $replies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `classteacher` WHERE `classid` = '$classid' AND `teacherid` = '$teacherid' "));
  return $replies;
}
 
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
$(document).ready(function () {
 
      // allowed maximum input fields
      var max_input = 15;
 
      // initialize the counter for textbox
      var x = 1;
 
      // handle click event on Add More button
      $('.add-btn').click(function (e) {
        e.preventDefault();
        if (x < max_input) { // validate the condition
          x++; // increment the counter
          $('.wrapper').append(`
                <div class="input-box row container">
                    <select class="col-sm-4 form-group form-control" name="grades[]" style="margin-right:15px">
                        <option value="">Choose Grade</option>
                        <?php
                        foreach($class_details as $key => $val){
                        ?>
                          <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'] ."(" . $val['school_sections'].")";?> </option>
                        <?php
                        }
                        ?>
                    </select>
                    <select class="col-sm-4 form-group form-control" name="subjects[]" >
                        <option value="">Choose Subjects</option>
                        <?php
                        foreach($subject_details as $key => $val){
                        ?>
                          <option  value="<?=$val['id']?>" ><?php echo $val['subject_name'];?> </option>
                        <?php
                        }
                        ?>
                    </select>
                    <a href="#" class="col-sm-1 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      })
 
    });
</script>
<script type="text/javascript">
  function assginteacher(classid, teacerid, c_name, c_section, f_name, l_name){
    var result = confirm("Are you sure you want to assign "+c_name+" - "+c_section+" to "+f_name+" "+l_name);
    if (result) {
        $.ajax({
            type: "POST",
            data: {classids:classid, teacerids:teacerid},
            beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            url: "https://you-me-globaleducation.org/school/teachers/excutedata",
            success: function(data){
                      alert("Successfully assigned "+c_name+" - "+c_section+" to "+f_name+" "+l_name);
                }
            });
     }
  }
</script>
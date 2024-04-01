<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
?>
<style>
    .hide
    {
        display:none;
    }
    .input-group-text{
        font-size:0.8em;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header row">
                <h2 class="col-md-6 align-left heading"><?php echo $classes_name['c_name'] ."-" . $classes_name['c_section']." (" . $classes_name['school_sections'].")";?></h2>

                <h2 class="col-md-6 align-right">
                   <?php if($_GET['subid'] == ""){?>
                    <a href="javascript:void(0);" id="sendsubteac" title="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1871') { echo $langlbl['title'] ; } } ?>" class=" btn btn-primary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1871') { echo $langlbl['title'] ; } } ?></a>&nbsp;&nbsp;
                   <?php }else{ ?>
                    <a href="javascript:void(0);" id="sendclsteac" title="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1872') { echo $langlbl['title'] ; } } ?>" class=" btn btn-primary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1872') { echo $langlbl['title'] ; } } ?></a>&nbsp;&nbsp;
                   <?php } ?> 
                   
                    <?php if($_GET['subid'] == ""){?>
                    <a href="javascript:void(0);" id="rqbished" title="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1873') { echo $langlbl['title'] ; } } ?>" class=" btn btn-primary" style="opacity: 0.5;" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1873') { echo $langlbl['title'] ; } } ?></a>
                    <?php } ?> 


                    &nbsp;&nbsp;<a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1162') { echo $langlbl['title'] ; } } ?> </a></h2>

            </div>
            <div class="body">
              
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="clsattndnc_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><input type="checkbox" name="allpublish" value="" id="allpublish" class="allpublish"></th>
                                        <!--<th>Id</th>-->
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '147') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="clsattndnc_body" class="modalrecdel">  
                                    <?php if(!empty($classes_students)) {
                                    foreach($classes_students as $value)
                                    {
                                        ?>
                                        <tr>
                                            <td>
                                            <?php if(checkteachclass($value['id']) == 1){ ?>
                                            <input type="checkbox" name="publish[]" class="mypublish" value="<?=$value['id'];?>">
                                            <?php } ?>
                                            </td>
                                            <!--<td><?=$value['id'];?></td>-->
                                            <td><?= $value['l_name']." ".$value['f_name'] ?></td>
                                            <td><?= $value['adm_no'] ?></td>
                                            

                                            <td>
                                            <?php if($_GET['subid'] == ''){ ?>    
                                                <a href="<?=$baseurl?>teacherclass/getclassreport?classid=<?php echo $classes_name['id'];?>&studentid=<?= $value['id'];?>"><button type="button" class="btn btn-sm btn-outline-secondary" title="Edit Report"><i class="fa fa-edit"></i></button></a>

                                             <?php }else{?>
                                                   
                                                <?php if(checkteachclass($value['id']) == 1) { ?>  
                                                  <a href="<?=$baseurl?>teacherclass/editreport_subject?classid=<?php echo $classes_name['id'];?>&studentid=<?= $value['id'];?>&subid=<?=$_GET['subid'];?>"><button type="button" class="btn btn-sm btn-outline-secondary" title="Edit Report"><i class="fa fa-edit"></i></button></a>
                                                <?php }else{ ?>

                                                  <?php if(checkteachclass($value['id']) == 0){?>
                                                  <a href="javascript:void(0);"><button onclick="alert('The report has not been created  by the Class Teacher. Please wait for him to initiate the process.');" style="opacity:0.5;" type="button" class="btn btn-sm btn-outline-secondary" title="Edit Report"><i class="fa fa-edit"></i></button></a>
                                                  <?php } ?>

                                                  <?php if(checkteachclass($value['id']) == 2){?>
                                                  <a href="javascript:void(0);"><button onclick="alert('The report has been submitted to school by the Class Teacher.');" style="opacity:0.5;" type="button" class="btn btn-sm btn-outline-secondary" title="Edit Report"><i class="fa fa-edit"></i></button></a>
                                                  <?php } ?>


                                                <?php } ?>  
                                             
                                             <?php }  ?>  
                                               
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
<?php 
foreach($lang_label as $langlbl) 
{ 
    if($langlbl['id'] == '1871')  {  $notifysub = $langlbl['title'] ;  }
    if($langlbl['id'] == '1872')  {  $notifycls = $langlbl['title'] ;  }
    
    
    if($langlbl['id'] == '1926')  {  $sendnotcls = $langlbl['title'] ;  }
    if($langlbl['id'] == '1927')  {  $sendnotsub = $langlbl['title'] ;  }
    if($langlbl['id'] == '1928')  {  $arepublishreport = $langlbl['title'] ;  }
    if($langlbl['id'] == '1929')  {  $reqsentscl = $langlbl['title'] ;  }
    if($langlbl['id'] == '1930')  {  $allreportsubmtyou = $langlbl['title'] ;  }
    
} 

?>

<script type="text/javascript">
var notifysub = "<?= $notifysub ?>";
var notifycls = "<?= $notifycls ?>";
var sendnotcls = "<?= $sendnotcls ?>";
var sendnotsub = "<?= $sendnotsub ?>";
var arepublishreport = "<?= $arepublishreport ?>";
var reqsentscl = "<?= $reqsentscl ?>";
var allreportsubmtyou = "<?= $allreportsubmtyou ?>";

     $("#allpublish").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
     var checkeditem = $('input[type="checkbox"]:checked').length - 1;
     var totalstudent = <?=count($classes_students);?>;
     if($('#allpublish').is(":checked")){
         if(checkeditem == totalstudent){
           $("#rqbished").css({ 'opacity' : '1' }); 
         }else{
              alert(allreportsubmtyou);
              location.reload();
         }
      }else{
        $("#rqbished").css({ 'opacity' : '0.5' }); 
      }
     });


      $("#rqbished").click(function () {
         if($('#allpublish').is(":checked")){
          var result = confirm(arepublishreport);
          var classid = <?=$_GET['classid'];?>;
          if (result) {
                    $.ajax({
                        type: "POST",
                        data: {classids:classid},
                        beforeSend: function (xhr) { // Add this line
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        },
                        url: "https://you-me-globaleducation.org/school/teacherclass/excutepublist",
                        success: function(data){
                                alert(reqsentscl);  
                            }
                        });
           }
         }else{
          alert("Please select the students first.");
         }
      });  
     
    $("#sendsubteac").click(function () {
        var result = confirm(sendnotsub);
        var classid = <?=$_GET['classid'];?>;
        if (result) {
            $.ajax({
                type: "POST",
                data: {classids:classid},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                url: "https://you-me-globaleducation.org/school/teacherclass/excutenotfisubteacher",
                success: function(data){
                    alert(notifysub);  
                }
            });
        }
    });

      $("#sendclsteac").click(function () {
          var result = confirm(sendnotcls);
          var classid = <?=$_GET['classid'];?>;
          if (result) {
                    $.ajax({
                        type: "POST",
                        data: {classids:classid},
                        beforeSend: function (xhr) { // Add this line
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        },
                        url: "https://you-me-globaleducation.org/school/teacherclass/excutenotficlsteacher",
                        success: function(data){
                                alert(notifycls);  
                            }
                        });
           }
      });
</script>
<?php

function checkteachclass($studentids){
  $hostname = "localhost";
  $username = "youmeglo_globaluser";
  $password = "DFmp)9_p%Kql";
  $database = "youmeglo_globalweb";
  $con = mysqli_connect($hostname, $username, $password, $database); 
  if(mysqli_connect_error($con)){ echo "Connection Error."; die();}
  $replies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `reportcard` WHERE `stuid` LIKE '".$studentids."' AND (`status` = 1 OR `status` = 2)"));
  return $replies;
}

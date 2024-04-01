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

                <h2 class="col-md-6 align-right"><a href="javascript:void(0);" id="rqbished1" title="Send back to Class Teacher" class=" btn btn-primary" style="opacity: 0.5;" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1792') { echo $langlbl['title'] ; } } ?></a>&nbsp;&nbsp;

                    <a href="javascript:void(0);" id="rqbished" title="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1791') { echo $langlbl['title'] ; } } ?>" class=" btn btn-primary" style="opacity: 0.5;" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1791') { echo $langlbl['title'] ; } } ?></a>

                    &nbsp;&nbsp;<a href="<?= $baseurl ?>Reportcards" title="Back" class=" btn btn-primary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1162') { echo $langlbl['title'] ; } } ?> </a></h2>

            </div>
            <div class="body">
              
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="clsattndnc_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><input type="checkbox" name="allpublish" value="" id="allpublish" class="allpublish"></th>
                                        <th>Id</th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '877') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '580') { echo $langlbl['title'] ; } } ?></th>
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
                                            <td><?=$value['id'];?></td>
                                            <td><?= $value['l_name']." ".$value['f_name'] ?></td>
                                            <td><?= $value['adm_no'] ?></td>
                                            

                                            <td>
                                 
                                                <a href="<?=$baseurl?>classes/getclassreport?classid=<?php echo $classes_name['id'];?>&studentid=<?= $value['id'];?>"><button type="button" class="btn btn-sm btn-outline-secondary" title="Edit Report"><i class="fa fa-edit"></i></button></a>                                              
                                               
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
<script type="text/javascript">
$(".mypublish").click(function () {
    //$('input:checkbox').not(this).prop('checked', this.checked);
    var checkeditem = $('input[type="checkbox"]:checked').length - 1;
    var checkedstu = $('input[type="checkbox"]:checked').length;
    var totalstudent = <?=count($classes_students);?>;
    //if($('#allpublish').is(":checked")){
    //alert(checkedstu);
        //if(checkeditem == totalstudent){
        if(checkedstu > 0) {
            $("#rqbished").css({ 'opacity' : '1' }); 
            $("#rqbished1").css({ 'opacity' : '1' });
        } else{
            alert('Report has not been submitted by the Class Teacher.');
            location.reload();
        }
    /*}else{
        $("#rqbished").css({ 'opacity' : '0.5' }); 
        $("#rqbished1").css({ 'opacity' : '0.5' }); 
    }*/
});

$("#allpublish").click(function () {
    $('input:checkbox').not(this).prop('checked', this.checked);
    var checkeditem = $('input[type="checkbox"]:checked').length - 1;
    var checkedstu = $('input[type="checkbox"]:checked').length;
    var totalstudent = <?=count($classes_students);?>;
    //if($('#allpublish').is(":checked")){
    //alert(checkedstu);
        //if(checkeditem == totalstudent){
        if(checkedstu > 0) {
            $("#rqbished").css({ 'opacity' : '1' }); 
            $("#rqbished1").css({ 'opacity' : '1' });
        } else{
            alert('Report has not been submitted by the Class Teacher.');
            location.reload();
        }
    /*}else{
        $("#rqbished").css({ 'opacity' : '0.5' }); 
        $("#rqbished1").css({ 'opacity' : '0.5' }); 
    }*/
});

$("#rqbished").click(function () {
    var post_arr = [];
    $('#clsattndnc_table input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.value;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
    console.log(post_arr);
    if(post_arr.length > 0){
    //if($('#allpublish').is(":checked")){
        var result = confirm("Are you sure you want to publish the report?");
        var classid = <?=$_GET['classid'];?>;
        if (result) {
                $.ajax({
                    type: "POST",
                    data: {'classids':classid, 'stuids':post_arr},
                    beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },
                    url: "https://you-me-globaleducation.org/school/classes/excutepublist",
                    success: function(data){
                            alert("Report published successfully.");    
                        }
                    });
        }
    }else{
        alert("Please select the students first.");
    }
});  

      $("#rqbished1").click(function () {
         if($('#allpublish').is(":checked")){
          var result = confirm("Are you sure you want to send all reports to Class Teacher for correction?");
          var classid = <?=$_GET['classid'];?>;
          if (result) {
                    $.ajax({
                        type: "POST",
                        data: {classids:classid},
                        beforeSend: function (xhr) { // Add this line
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        },
                        url: "https://you-me-globaleducation.org/school/classes/excuteunpublist",
                        success: function(data){
                                alert("All reports sent to Class Teacher for correction");   
                                location.reload(); 
                            }
                        });
           }
         }else{
          alert("Please select the students first.");
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
  $replies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `reportcard` WHERE `stuid` LIKE '".$studentids."' AND `status` = 2"));
  return $replies;
}

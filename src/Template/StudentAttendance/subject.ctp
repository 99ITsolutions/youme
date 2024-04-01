<style>
.bg-dash
{
	background-color: #242e3b !important;
}
</style>
<style>

#attendancecalendar {
    width: 700px;
    margin:0 auto !important;
}
#subattendancecalendar {
    width: 700px;
    margin: 0 auto;
}

.subjectattendnce {
    width: 700px;
    margin:0 auto ;
}
.response {
    height: 60px;
}

.success {
    background: #cdf3cd;
    padding: 10px 60px;
    border: #c3e6c3 1px solid;
    display: inline-block;
}
.subjectactive {
    background-color:#fea60f !important; 
    color:#ffffff;
}
.attendncdtl_0 {
    background-color:#fea60f !important; 
    color:#ffffff;
}
.fc-unthemed td.fc-today
{
    background: #7199d6 !important;
}
.fc .fc-toolbar .fc-today-button, .fc .fc-toolbar .fc-state-default
{
    text-transform: capitalize !important;
}
</style>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class=" row">
                                <h2 class="col-md-6 heading text-left"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1344') { echo $langlbl['title'] ; } } ?></h2>
                                <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>studentAttendance" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1326') { echo $langlbl['title'] ; } } ?></a></h2>
                            </div>
                            
                        </div>
                        <div class="body">
                            <div class="row clearfix">
        	                    <?php
        	                    foreach($student_subjects as $s_sub)
        	                    {
        	                        //print_r($s_sub);
        	                        if(!empty($s_sub['subjects_name']))
        	                        {
        	                            $subjects = explode(",", $s_sub['subjects_name']);
        	                            $subIds = explode(",", $s_sub['class_subjects']['subject_id']);
        	                            //print_r($subjects);
        	                            foreach($subjects as $key => $sub)
        	                            {
        	                                
                    	                    ?>
                    	                    <div class="col-lg-2 col-md-3 col-sm-6">
                                                <div class="card text-center bg-dash attendncdtl_<?= $key ?>" id = "attendncdtl_<?= $subIds[$key] ?>" >
                                                    <div class="body" style="height:85px !important;">
                                                        <div class="text-light attendancesubjcts">
                                                            <span><b><a style="color:#FFFFFF !important" href="javascript:void(0);" class="attendncdtl"  data-subid="<?= $subIds[$key] ?>" data-clsid="<?= $s_sub['class'] ?>"><?= $sub ?> </a></b></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
        	                            }
        	                        }
                                }
                                ?>
        	                </div>
                                
                            <section class="container py-4">
                                <div class="row" >
                                    <div class="response"></div>
                                    <div id='attendancecalendar' style="margin:0 auto !important; margin-left:140px !important;"></div>
                                    <div id="attendance" style="margin:0 auto !important;">
                                        <div id='subattendancecalendar' style="display:none;"></div>
                                    </div>
                                    <!--<div class="col-md-12 text-center">
                                        <img src="<?= $baseurl ?>/img/cal.png" width="628px" style="border:1px solid #242e3b">
                                    </div>-->
                                </div>
                            </section>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>

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

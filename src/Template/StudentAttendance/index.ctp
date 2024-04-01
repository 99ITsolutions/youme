<style>
/*.bg-dash
{
	background-color: #242e3b !important;
}*/
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
</style>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class=" row">
                                <h2 class="col-md-6 heading text-left"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1323') { echo $langlbl['title'] ; } } ?></h2>
                                <h2 class="col-md-6 text-right"><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1326') { echo $langlbl['title'] ; } } ?></a></h2>
                            </div>
                            
                        </div>
                        <div class="body">
                            <div class="row clearfix">
        	                    <div class="col-md-6">
        	                        <div class="col-md-6 col-sm-6" style="margin: 0 auto;">
                                        <div class="card text-center bg-dash ">
                                            <div class="body" style="height:70px !important;">
                                                <div class="text-light attendancesubjcts">
                                                    <span><b><a  class="colorBtn" href="<?= $baseurl ?>StudentAttendance/day" class="daywise"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1324') { echo $langlbl['title'] ; } } ?></a></b></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        	                    </div>
        	                    <div class="col-md-6 text-center">
        	                        <div class="col-md-6 col-sm-6"  style="margin: 0 auto;">
                                        <div class="card text-center bg-dash ">
                                            <div class="body" style="height:70px !important;">
                                                <div class="text-light attendancesubjcts">
                                                    <span><b><a style="color:#FFFFFF !important" href="<?= $baseurl ?>StudentAttendance/subject" class="subjectwise"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1325') { echo $langlbl['title'] ; } } ?></a></b></span>
                                                </div>
                                            </div>
                                        </div>
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

<script>


function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    

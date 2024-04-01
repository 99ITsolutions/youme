<style>
.bg-dash
{
	background-color: #242e3b !important;
}
</style>
<style>

#sclattendancecalendar {
    width: 700px;
    margin:0 auto !important;
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
                                <h2 class="col-md-6 heading text-left"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1333') { echo $langlbl['title'] ; } } ?></h2>
                                <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>studentAttendance" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1326') { echo $langlbl['title'] ; } } ?></a></h2>
                            </div>
                            
                        </div>
                        <div class="body">
                            
                                
                            <section class="container py-4">
                                <div class="row" >
                                    <div class="response"></div>
                                    <div id='sclattendancecalendar' style="margin:0 auto !important;"></div>
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

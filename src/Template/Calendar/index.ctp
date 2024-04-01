<style>
#studentcalendar {
    width: 700px;
    margin: 0 auto;
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

@media screen and (max-width: 444px) and (min-width: 200px) 
{
    .fc .fc-view-container .fc-view.fc-basic-view>table>thead tr th.fc-widget-header
    {
        padding:4px !important;
    }
    .fc-toolbar .fc-right
    {
        display:block;
        float: none;
    }
}
</style>
<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '1312') { $lbl1312 = $langlbl['title'] ; } 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
} 
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-6 heading text-left"><?= $lbl1312 ?></h2>
                            <h2 class="col-md-6 text-right"><a  href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></h2>
                        </div>
                        <!--<h2 class="heading"></h2>-->
                    </div>
                    <div class="body">
                        <div class="row container">
                            <!--<img src="<?= $baseurl ?>/img/calendar.png">-->
                            <div class="response"></div>
                            <div id='studentcalendar'  class="notranslate"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
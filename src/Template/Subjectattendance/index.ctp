<style>

#subjectattendance {
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
</style>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-6 heading text-left"><?= $sub_name ?> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '14') { echo $langlbl['title'] ; } } ?></h2>
                            <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>Studentsubjects?openmodal=1&subjectid=<?= $subjectid ?>&classid=<?= $classid ?>" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                        </div>
                        <!--<h2 class="heading"></h2>-->
                    </div>
                    <input type="hidden" id="classid" value="<?= $classid ?>">
                    <input type="hidden" id="subjectid" value="<?= $subjectid ?>">
                    <div class="body">
                        <div class="row container">
                            <!--<img src="<?= $baseurl ?>/img/calendar.png">-->
                            <div class="response"></div>
                            <div id='subjectattendance'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!------------------ End --------------------->
<script>
window.onload = function() {
    $('#subjectattendance').fullCalendar({
        editable: true,
        events: baseurl +"/Subjectattendance/getattendance?classid=<?php echo $classid ?>&subjectid=<?php echo $subjectid ?>",
        displayEventTime: false,
        //eventColor: '#68e216',
        eventRender: function (event, element, view) {
            console.log(event);
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                if(event.title == "Present")
                {
                    element.css('background-color', '#45ab01')
                   
                }
                
                else if(event.title == "Leave")
                {
                    element.css('background-color', '#fea60f')
                }
                else
                {
                    element.css('background-color', '#ff0000')
                }
                event.allDay = false;
            }
        },
        selectable: true,
        selectHelper: true,
        dayNamesShort: caldays,
        monthNames: calmonths,
    });
}
</script>

<script>
    function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>   
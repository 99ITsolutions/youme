<style>
.bg-dash
{
    background-color:#242E3B !important;
}
</style>

    <div class="row container clearfix ">
        <div class="card ">
            <div class="header">
                <h2 class="heading">You-Me Live - (<?= $classname ?>)</h2>     
                 <ul class="header-dropdown">
                    <li><a href="<?= $baseurl?>kinderdashboard" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1394') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
                <div class="row clearfix container mt-4"><h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1395') { echo $langlbl['title'] ; } } ?>*: </h2></div>
                <div class="row clearfix container mt-1">1. <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1396') { echo $langlbl['title'] ; } } ?>.</div>
                <div class="row clearfix container mt-1">2. <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1397') { echo $langlbl['title'] ; } } ?>.</div>
            </div>
            <div class="body">
                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="meetinglink_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1401') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1403') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1404') { echo $langlbl['title'] ; } } ?></th>                                    
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1405') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1406') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($link_details)) {
                                    foreach($link_details as $value)
                                    {
                                        //print_r($value);
                                        $sctime = strtotime($value->schedule_date);
                                        $ctime = time()+3*60;
                                        if($ctime > $sctime) {
                                        ?>
                                        <tr>
                                            <td><?= implode(" ", explode("+", $value->meeting_name)) ?></td>
                                            <td><?= date('M d, Y h:i A',strtotime($value->schedule_date)) ?></td>
                                            <td><?= date('M d, Y h:i A',$value->expirelink_datetime) ?></td>
                                            <td><?= date('M d, Y',$value->created_date ) ?></td>
                                            <td>
                                                <?php  
                                                
                                                if($value->expirelink_datetime <= time()) 
                                                { 
                                                    ?>
                                                    <a href="javascript:void(0);" class="btn btn-outline-secondary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1413') { echo $langlbl['title'] ; } } ?></a>
                                                    <?php
                                                }
                                                elseif($value->meeting_status == 2){ ?>
                                                <a href="javascript:void(0);" class="btn btn-outline-secondary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1499') { echo $langlbl['title'] ; } } ?></a>
                                                <?php }
                                                else { ?>
                                                <a href="javascript:void(0);" class="btn btn-outline-secondary joinmeeting" id="joinmeeting" data-mid = "<?= $value->id ?>" data-meetingid = "<?= $value->meeting_id ?>" data-time= "<?= $value->schedule_datetime ?>" data-ctime= "<?= time() ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1500') { echo $langlbl['title'] ; } } ?></a>
                                                <?php } ?>
                                                
                                              
                                            </td>
                                        </tr>
                                        <?php
                                    } } }
                                    ?>
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
	    </div>
	</div>
	<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>
 
<!----------------------------------------->

                    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='https://meet.jit.si/external_api.js'></script>
<script>
$('#meetinglink_table tbody').on("click",".joinmeeting",function(){

    var id = $(this).data('mid');
    var meetid = $(this).data('meetingid');
    var ctime = $(this).data('ctime');
    var time = $(this).data('time');
        
   
    if(ctime >= time)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/kinderdashboard/getmeetingsts',
            data:{'id':id},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                if(result.data == "1")
                {    
                    /*var style_url = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
                    var url = "https://meeting.you-me-globaleducation.org/bigbluebutton/api/join?meetingID="+result.meetingID+"&password=111&fullName="+result.studname+"&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url="+style_url+"&checksum="+result.checksumm;
                    window.location.assign(url);*/
                    window.location.assign(result.url);
                }
                else if(result.data == "0")
                {
                    swal("Class has not started yet. Please try after some time.");
                }
                else if(result.data == "2")
                {
                    swal("Moderator has end the class. Please wait for his response");
                }
            }
        });
        
    }
    else
    {
        swal("Meeting will start on schedule time.");
    }
});

</script>
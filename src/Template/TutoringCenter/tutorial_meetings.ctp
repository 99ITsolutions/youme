<style>
	    .bg-dash
	    {
	        background-color:#242E3B !important;
	    }
	</style>
<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '33') { $tutcntr = $langlbl['title'] ; } } ?>
    <div class="row container clearfix ">
        <div class="card ">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1393') { echo $langlbl['title'] ; } } ?> - (<?= $classname ?> (<?= $subjectname ?>))</h2>     
                 <ul class="header-dropdown">
                    <li><a href="<?= $baseurl?>tutoringCenter/subjects/<?=$tid?>/<?=$classid?>/<?=$subjectid?>" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1394') { echo $langlbl['title'] ; } } ?></a></li>
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
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1402') { echo $langlbl['title'] ; } } ?></th>
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
                                        $sctime = strtotime($value->schedule_date);
                                        $ctime = time()+3*60;
                                        if($ctime > $sctime) {
                                            if( $value->generate_for == "Tutoring Center")
                                            {
                                                $genfor = $tutcntr;
                                            }
                                            else
                                            {
                                                $genfor = "Classe";
                                            }
                                        ?>
                                        <tr>
                                            <td><?= implode(" ", explode("+", $value->meeting_name)) ?></td>
                                            <td><?= $genfor ?></td>
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
                                                <a href="javascript:void(0);" class="btn btn-outline-secondary joinmeeting" id="joinmeeting" data-time= "<?= $value->schedule_datetime ?>" data-ctime= "<?= time() ?>" data-mid = "<?= $value->id ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1500') { echo $langlbl['title'] ; } } ?></a>
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


    
<div class="modal classmodal animated zoomIn" id="generatemeeting" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Meeting Link - <span id="class"></span> (<span id="subject"></span>)</h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'generatemeeting'] , 'id' => "generatemeetingform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label>Link Generation For*</label>
                            <select name="link_for" id="link_for" required class="form-control chngstatus">
                                <option value="">Choose One</option>
                                <option value="Tutoring Center">For Tutorial Center</option>
                                <option value="Class">For Class</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label>Schedule Date</label>
                            <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"  name="start_date" id="start_date" required/>
                              <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label>Expire Link Date</label>
                           <!-- <input type="date" class="form-control" name="end_date" id="end_date" required>-->
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="end_date" id="end_date" required>
                              <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label>Meeting Link</label>
                              <input type="text" class="form-control"  name="link" id="link" required disabled/>
                              <input type="hidden" class="form-control"  name="meeting_link" id="meeting_link" />
                        </div>
                    </div>
                    <input type="hidden" name="classid" id="classid">
                    <input type="hidden" name="subjectid" id="subjectid">
                    
                    <div class="col-md-12">
                        <div class="error" id="submitreqerror"></div>
                        <div class="success" id="submitreqsuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary submitreqbtn" id="submitreqbtn">Generate</button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
                    
<div class="modal classmodal animated zoomIn" id="meeting_room" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="meetingroom"></div>
            </div>
        </div>
    </div>
</div>
                    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='https://meet.jit.si/external_api.js'></script>
<script>
$('#meetinglink_table tbody').on("click",".joinmeeting",function(){

    var id = $(this).data('mid');
    var ctime = $(this).data('ctime');
    var time = $(this).data('time');
 
    if(ctime >= time)
    {
      
        $.ajax({
            type:'POST',
            url: baseurl + '/meetings/getmeetingsts',
            data:{'id':id},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                if(result.data == "1")
                {    
                    var style_url = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
                    //var windowReference = window.open();
                    var url = "https://meeting.you-me-globaleducation.org/bigbluebutton/api/join?meetingID="+result.meetingID+"&password=111&fullName="+result.studname+"&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url="+style_url+"&checksum="+result.checksumm;
                    window.location.assign(url);
                   //window.open("https://meeting.you-me-globaleducation.org/bigbluebutton/api/join?meetingID="+result.meetingID+"&password=111&fullName="+result.studname+"&checksum="+result.checksumm, "_blank");
                }
                else if(result.data == "0")
                {
                    swal("Class is not started yet. Please try after some time.");
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
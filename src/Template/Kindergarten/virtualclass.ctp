<style>
    .bg-dash
    {
        background-color:#242E3B !important;
    }
</style>

    <div class="row container clearfix ">
        <div class="card ">
            <div class="header">
                <h2 class="heading">Virtual Online Class</h2>     
                 <ul class="header-dropdown">
                    <li><a href="javascript:void(0);"  title="Add" class="btn btn-sm btn-success generatemeeting">Generate Virtual Class</a></li>
                    <li><a href="<?= $baseurl?>Kindergarten" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1189') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
                <div class="row clearfix container mt-4"><h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1190') { echo $langlbl['title'] ; } } ?>*: </h2></div>
                <div class="row clearfix container mt-1">1. <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1191') { echo $langlbl['title'] ; } } ?>.</div>
                <div class="row clearfix container mt-1">2. <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1192') { echo $langlbl['title'] ; } } ?>.</div>
            </div>
            <div class="body">
                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="meetinglink_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1196') { echo $langlbl['title'] ; } } ?></th>   
                                        
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1198') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1199') { echo $langlbl['title'] ; } } ?></th>                             
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1200') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1201') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($link_details)) {
                                        
                                    foreach($link_details as $value)
                                    {
                                        $meeting_id = $value->meeting_id; //"YOUME".uniqid(); 
                                        $meeting_name = $value->meeting_name;
                                        $secret = "aTGBy6CgNh5xqxvUOMDIsPNh671fkcLGnkq8qrfYrA"; 
                                        $logout = urlencode("https://you-me-globaleducation.org/ConferenceMeet/callback.php?meetingID=".$meeting_id);
                                        $string = "createname=".$meeting_name."&meetingID=".$meeting_id."&attendeePW=111&moderatorPW=222&meta_endCallbackUrl=".$logout.$secret;
                                        $sh = sha1($string);
                                        
                                        ?>
                                        <tr>
                                            <td><?= implode(" ", explode("+", $value->meeting_name)) ?></td>
                                            
                                            <td><?= date('M d, Y h:i A',strtotime($value->schedule_date)) ?></td>
                                            <td><?= date('M d, Y h:i A',$value->expirelink_datetime) ?></td>
                                            <!--<td><?= $value->meeting_link ?></td>-->
                                            <td><?= date('M d, Y',$value->created_date ) ?></td>
                                            <td>
                                                <?php  
                                                
                                                if($value->expirelink_datetime <= time()) 
                                                { 
                                                    ?>
                                                    <a href="javascript:void(0);" class="btn btn-outline-secondary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1413') { echo $langlbl['title'] ; } } ?></a>
                                                    <?php
                                                }elseif($value->meeting_status == 2){ ?>
                                                <a href="javascript:void(0);" class="btn btn-outline-secondary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1499') { echo $langlbl['title'] ; } } ?></a>
                                                <?php }else { ?>
                                                <a href="javascript:void(0);" class="btn btn-outline-secondary joinmeeting" id="joinmeeting" data-time= "<?= $value->schedule_datetime ?>" data-chksum= "<?= $sh ?>" data-mname= "<?= $value->meeting_name ?>" data-id= "<?= $value->id ?>" data-ctime= "<?= time() ?>" data-mid = "<?= $meeting_id ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '899') { echo $langlbl['title'] ; } } ?></a>
                                                <?php } ?>
                                                
                                                <button type="button" data-id="<?=$value['id']?>" data-url="deletevirtual" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Virtual Class Link" data-type="confirm">Delete</button>
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
	<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>
 
<!----------------------------------------->


    
<div class="modal classmodal animated zoomIn" id="generatemeeting" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Generate Virtual Class</h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'generatemeeting'] , 'id' => "generatemeetingform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '355') { echo $langlbl['title'] ; } } ?></label>
                            <!--<select class="form-control class_s" name="classid" id="eclass" onchange="subjctcls(this.value)" required>-->
                            <select class="form-control class_s" name="classid" id="eclass" required>
                                <option value="">Choose Class</option>
                                <?php foreach($class_details as $cls)  { ?>
                                <option value="<?= $cls['id'] ?>"><?= $cls['c_name']."-".$cls['c_section']. " (".$cls['school_sections'].")" ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!--<div class="col-md-6">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '365') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control subj_s" name="subjectid" id="cls_sub" required>
                                <option value="">Choose Subject</option>
                            </select>
                        </div>
                    </div>-->
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1203') { echo $langlbl['title'] ; } } ?></label>
                              <input type="text" class="form-control"  name="meeting_name" id="meeting_name" required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1205') { echo $langlbl['title'] ; } } ?></label>
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
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1206') { echo $langlbl['title'] ; } } ?></label>
                           <!-- <input type="date" class="form-control" name="end_date" id="end_date" required>-->
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="end_date" id="end_date" required>
                              <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!--<input type="hidden" name="classid" id="classid">
                    <input type="hidden" name="subjectid" id="subjectid">-->
                    <input type="hidden" class="form-control"  name="meeting_id" id="meeting_id" value="YOUME<?= uniqid() ?>" />
                    
                    <div class="col-md-12">
                        <div class="error" id="submitreqerror"></div>
                        <div class="success" id="submitreqsuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary submitreqbtn" id="submitreqbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1208') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1209') { echo $langlbl['title'] ; } } ?></button>
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

    var id = $(this).data('id');
    var meetingName = $(this).data('mname');
    var meetingID = $(this).data('mid');
    var chksum = $(this).data('chksum');
    
   
    
    var ctime = $(this).data('ctime');
    var time = $(this).data('time');
    
  
    if(ctime >= time)
    {
        $.ajax({
            type:'POST',
            url: baseurl+"/Kindergarten/updatemeetingsts",
            data: {"id":id, "meetingID":meetingID }, 
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                if(result.data == "success")
                {
                    var style_url = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
                    var url = "https://meeting.you-me-globaleducation.org/bigbluebutton/api/join?meetingID="+meetingID+"&password=222&fullName="+result.tchrname+"&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url="+style_url+"&checksum="+result.checksumm;
                    $(location).attr('href', url);
                    
                }
                else
                {
                    swal("Please start again. Something Wrong happened.");
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
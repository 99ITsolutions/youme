<style>
    .bg-dash
    {
        background-color:#242E3B !important;
    }
</style>

    <div class="row container clearfix ">
        <div class="card ">
            <div class="header ">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '887') { echo $langlbl['title'] ; } } ?></h2>     
                 <ul class="header-dropdown">
                    <li><a href="javascript:void(0);" title="Add" class="btn btn-sm btn-success generatemeeting"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '891') { echo $langlbl['title'] ; } } ?></a></li>
                    <?php if($iid != '' || $sid != ''){ ?>
                    <li><a href="javascript:void(0)"  title="Delete All" data-str= "Meetings" data-url = "school-meet/deleteallmeetings"  id="deleteallmeetings" class="btn btn-sm btn-success approve"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1532') { echo $langlbl['title'] ; } } ?> </a></li>
                    <?php } ?>
                </ul>
                <div class="row clearfix container mt-4"><h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '888') { echo $langlbl['title'] ; } } ?>*: </h2></div>
                <div class="row clearfix container mt-1">1. <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '889') { echo $langlbl['title'] ; } } ?>.</div>
                <div class="row clearfix container mt-1">2. <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '890') { echo $langlbl['title'] ; } } ?>.</div>
            
            </div>
            
            <div class="body">
                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="meetinglink_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th>
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
                                            </label>
                                        </th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '895') { echo $langlbl['title'] ; } } ?></th>   
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '896') { echo $langlbl['title'] ; } } ?></th>                            
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '897') { echo $langlbl['title'] ; } } ?></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($link_details)) {
                                        $n = 1;
                                    foreach($link_details as $value)
                                    {
                                        /*$meeting_id = "YOUME".uniqid(); //$value->meeting_id;
                                        
                                        $secret = "aTGBy6CgNh5xqxvUOMDIsPNh671fkcLGnkq8qrfYrA"; 
                                        
                                        $string = "createname=".$meeting_name."&meetingID=".$meeting_id."&attendeePW=111&moderatorPW=222".$secret;
                                        $sh = sha1($string);*/
                                        $meeting_name = $value->meeting_name;
                                        ?>
                                        <tr>
                                            <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox"  id="<?= $value->id ?>">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td><?= implode(" ", explode("+", $value->meeting_name)) ?></td>
                                            <td> https://you-me-globaleducation.org/school/schoolconference?mid=<?= $value->meeting_id ?></td>
                                            <td><?= date('d M, Y',$value->created_date ) ?></td>
                                            <td>
                                                <?php  
                                                 if($value->meeting_status == 2) 
                                                { 
                                                    ?>
                                                    <a href="javascript:void(0);" class="btn btn-outline-secondary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1413') { echo $langlbl['title'] ; } } ?></a>
                                                    <?php
                                                } else { ?>
                                                <a href="javascript:void(0);" class="btn btn-outline-secondary joinmeeting" id="joinmeeting"  data-mname= "<?= $value->meeting_name ?>" data-id= "<?= $value->id ?>" data-mid = "<?= $value->meeting_id ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '899') { echo $langlbl['title'] ; } } ?></a>
                                                <?php } ?>
                                                
                                                <p id="p<?=$n?>" style="display:none;">
                                                    You have been invited on You-Me Live Meet__ (<?= $retrieve_user['comp_name'] ?>).<br>
                                                    Date & Time (USA): <?= date('M d, Y',$value->created_date ) ?><br>
                                                    Topic: Personal Meeting Room.  (<?= implode(" ", explode("+", $value->meeting_name))  ?>)<br>
                                                    You can use your computer’s microphone and speakers; however, a headset is recommended. Or, call in using your phone.<br>
                                                    Join You-Me Live Meet Link<br><br>https://you-me-globaleducation.org/school/schoolconference?mid=<?= $value->meeting_id ;?></p>
                                                <button onclick="copyToClipboard('#p<?=$n?>')" title="Copy Url " class="btn btn-sm btn-outline-secondary"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '900') { echo $langlbl['title'] ; } } ?></button>
                                                 <?php if($iid != '' || $sid != '' || $value->subadmin_id != ''){ ?>      
                                                    <button type="button" data-id="<?=$value['id']?>" data-url="delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Meeting Link" data-type="confirm"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '901') { echo $langlbl['title'] ; } } ?></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $n++;
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '905') { echo $langlbl['title'] ; } } ?> </h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'generatemeeting'] , 'id' => "generatemeetingform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '906') { echo $langlbl['title'] ; } } ?></label>
                              <input type="text" class="form-control"  name="meeting_name" id="meeting_name" required />
                        </div>
                    </div>
                    <input type="hidden" class="form-control"  name="meeting_id" id="meeting_id" value="YOUME<?= uniqid() ?>" />
                    
                    <div class="col-md-12">
                        <div class="error" id="submitreqerror"></div>
                        <div class="success" id="submitreqsuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary submitreqbtn" id="submitreqbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '907') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '908') { echo $langlbl['title'] ; } } ?></button>
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
    
    $.ajax({
        type:'POST',
        url: baseurl+"/school-meet/updatemeetingsts",
        data: {"id":id }, 
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            if(result.data != "failed")
            {
                var url = result.data;
                window.location.assign(url);
            }
            else
            {
                swal("Please start again. Something Wrong happened.");
            }
        }
    });
        
     
});

</script>
<script> 

function copyToClipboard(element) {
    var text = $(element).clone().find('br').prepend('\r\n').end().text()
    element = $('<textarea>').appendTo('body').val(text).select()
    document.execCommand('copy')
    element.remove()
}
</script>    

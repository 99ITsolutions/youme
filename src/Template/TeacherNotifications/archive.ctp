<?php
    $statusarray = array('Inactive','Active' );

 foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '1794') { $nsentlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1795') { $sentlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '240') { $studlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '447') { $tchrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1564') { $scllbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2096') { $alllbl = $langlbl['title'] ; } 
} ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                
                    <div class="row">
                        <h2 class="heading col-md-6 align-left" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1084') { echo $langlbl['title'] ; } } ?></h2>
                        <div class="col-md-6 align-right" >
                            <a href="javascript:void(0)"  title="Delete All" data-str= "Teacher Notifications" data-url = "Notification/deleteallarchives"  id="deleteallnotify" class="btn btn-sm btn-success approvesclnotifys"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1532') { echo $langlbl['title'] ; } } ?> </a>
                             
                            <a href="<?=$baseurl?>teacherNotifications" title="Back" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1077') { echo $langlbl['title'] ; } } ?></a>
                        </div>
                    </div>
                    
                    <!--<li><a href="<?=$baseurl?>gallery/view" title="Add" class="btn btn-info">View Gallery</a></li>-->
                            
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem notification_table" id="notification_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1085') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1086') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1078') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1079') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1080') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1081') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="notificationbody" class="modalrecdel"> 
                            <?php foreach($notify_details as $value)
                            { 
                                $sts_notify = $value['sent_notify'] == 0 ? "Not Sent" : "Sent";
                                
                                if(strtolower($sts_notify) == "sent") { $nsts = $sentlbl; } else { $nsts = $nsentlbl; }
                                
                                if(strtolower($value['notify_to']) == "students") { $noti_to = $studlbl; } 
                                elseif(strtolower($value['notify_to']) == "teachers") { $noti_to = $tchrlbl; } 
                                elseif(strtolower($value['notify_to']) == "schools") { $noti_to = $scllbl; } 
                                elseif(strtolower($value['notify_to']) == "all") { $noti_to = $alllbl; } 
                            ?>
                                <tr>
                                    <td class="width45">
                                        <label class="fancy-checkbox">
                                            <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value['id'] ?>">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?= $value['title']; ?></td>
                                    <td><?= date("M d, Y H:i" , strtotime($value['schedule_date'])); ?></td>
                                    <td><?= ucfirst($noti_to); ?></td>
                                    <td><?= ucfirst($nsts); ?></td>
                                    <td>
                                    <?php    
                                        if( $value['status'] == 0)
                                        {
                                        ?>
                                           <label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>
                                        <?php 
                                        }
                                        else 
                                        { ?>
                                            <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>
                                        <?php 
                                        }
                                    ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" title="View" data-id="<?= $value['id']; ?>" data-title="<?= ucfirst($value['title']); ?>" data-attch="<?= $value['attachment']; ?>" data-sctime="<?= date("H:i" , strtotime($value['schedule_date'])); ?>" data-scdate="<?= date("M d, Y" , strtotime($value['schedule_date'])); ?>" data-sentto = "<?= ucfirst($noti_to); ?>"  class="btn btn-sm btn-outline-secondary viewnotify" ><i class="fa fa-eye"></i></a>
                                        <button type="button" data-id="<?=$value['id']?>" data-url="delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Notification" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                            <?php 
                            } ?>
	                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->create(false , [ 'method' => "post"  ]); ?>
<?php echo $this->Form->end(); ?> 
 <!------------------ Add Class --------------------->

    
<div class="modal classmodal animated zoomIn" id="viewnotify" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1419') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1085') { echo $langlbl['title'] ; } } ?>: </b></span><span id="title"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2094') { echo $langlbl['title'] ; } } ?>: </b></span><span id="schedule_date"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1506') { echo $langlbl['title'] ; } } ?>: </b></span><span id="schedule_time"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1087') { echo $langlbl['title'] ; } } ?>: </b></span><span id="sento" ></span></p>
                    </div>
                    <div class="col-md-12" id="attach" style="display:none">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1105') { echo $langlbl['title'] ; } } ?>: </b></span><span id="attchmnt" ></span></p>
                    </div>
                    <div class="col-md-12">
                       <p> <span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1108') { echo $langlbl['title'] ; } } ?>: </b></span><span id="description" ></span></p>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>              


 
<style>
    h5
    {
        color:#191c21 !important;
        margin-left:15px !important;
    }
</style>

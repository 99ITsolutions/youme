<?php
    $statusarray = array('Inactive','Active' );
?>
<style>
    .badge { 
        position: relative;
        top: -12px !important;
        left: -3px !important;
        border: 1px solid;
        border-radius: 50%;
        background: #6c757d;
        color: #fff;
    }
    .bg-dash
    {
        max-height:65px !important;
    }
</style>`
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                    <?php //$total_count = 0; ?>
                    <div class="row">
                        <h2 class="heading col-md-6 align-left" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '432') { echo $langlbl['title'] ; } } ?></h2>
                        <div class="col-md-6 align-right">
                            
                            <?php if(!empty($sclsub_details[0]))
                            { 
                                $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                if(in_array("55", $roles)) { ?>
                                    <a href="<?=$baseurl?>schoolNotification/archive" title="Archive" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '433') { echo $langlbl['title'] ; } } ?></a>
                                <?php } if(in_array("57", $roles)) { ?>
                                    <a href="<?=$baseurl?>schoolNotification/add" title="Add" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2045') { echo $langlbl['title'] ; } } ?></a>
                                <?php } if(in_array("56", $roles)) { ?>
                                    <a href="<?=$baseurl?>schoolNotification/receive" title="Receive" class="btn btn-info" id="schoolnotifycount"><i class="fa fa-bell"></i> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2046') { echo $langlbl['title'] ; } } ?></a><span class="badge" id="sclrcvnotify"><?= $schoolnotfycount ?></span>
                                <?php }
                            } else { ?>
                                <a href="<?=$baseurl?>schoolNotification/archive" title="Archive" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '433') { echo $langlbl['title'] ; } } ?></a>
                                <a href="<?=$baseurl?>schoolNotification/add" title="Add" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2045') { echo $langlbl['title'] ; } } ?></a>
                                <a href="<?=$baseurl?>schoolNotification/receive" title="Receive" class="btn btn-info" id="schoolnotifycount"><i class="fa fa-bell"></i> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2046') { echo $langlbl['title'] ; } } ?></a><span class="badge" id="sclrcvnotify"><?= $schoolnotfycount ?></span>
                            <?php } ?>
                            
                            <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
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
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '435') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '436') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '437') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '438') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '439') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '410') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="notificationbody" class="modalrecdel"> 
                            <?php 
                            foreach($lang_label as $langlbl) { 
                                if($langlbl['id'] == '1794') { $ntsent = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1795') { $sent =  $langlbl['title'] ; } 
                            } 
                            
                            foreach($notify_details as $value)
                            { 
                                $sts_notify = $value['sent_notify'] == 0 ? $ntsent : $sent;
                            ?>
                                <tr>
                                    <td class="width45">
                                        <label class="fancy-checkbox">
                                            <input class="checkbox-tick" type="checkbox" name="checkbox">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?= ucfirst($value['title']); ?></td>
                                    <td><?= date("M d, Y H:i" , strtotime($value['schedule_date'])); ?></td>
                                    <td><?= ucfirst($value['notify_to']); ?></td>
                                    <td><?= ucfirst($sts_notify); ?></td>
                                    <td>
                                    <?php    //echo $value['status'];
                                        if( $value['status'] == "0")
                                        {
                                        ?>
                                            <!--<label class="switch"><input type="checkbox"><span class="slider round" ></span></label>-->
                                            <a href="javascript:void()" data-url="schoolNotification/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Notification Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>
                                        <?php 
                                        }
                                        else 
                                        { ?>
                                            <!--<label class="switch"><input type="checkbox" checked><span class="slider round"></span></label>-->
                                            <a href="javascript:void()" data-url="schoolNotification/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Notification Status" class="btn btn-sm js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>
                                        <?php 
                                        }
                                    ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($sclsub_details[0]))
                                        { 
                                            $now = strtotime('now');
                                            $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                            if(in_array("104", $roles)) { ?>
                                                <a href="javascript:void(0)" title="View" data-id="<?= $value['id']; ?>" data-title="<?= ucfirst($value['title']); ?>" data-attch="<?= $value['attachment']; ?>" data-sctime="<?= date("H:i" , strtotime($value['schedule_date'])); ?>" data-scdate="<?= date("M d, Y" , strtotime($value['schedule_date'])); ?>"  data-sentto = "<?= ucfirst($value['notify_to']); ?>"  class="btn btn-sm btn-outline-secondary viewnotify" ><i class="fa fa-eye"></i></a>
                                            <?php } if(in_array("58", $roles)) { 
                                                if(strtotime($value['schedule_date']) >= $now ) { ?>
                                                    <a href="<?=$baseurl?>schoolNotification/edit/<?= md5($value['id'])?>" title="Edit" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                <?php } 
                                            } if(in_array("59", $roles)) { ?>
                                                <button type="button" data-id="<?=$value['id']?>" data-url="schoolNotification/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Notification" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                            <?php } else { 
                                            $now = strtotime('now');
                                            if(strtotime($value['schedule_date']) >= $now ) { ?>
                                            <a href="<?=$baseurl?>schoolNotification/edit/<?= md5($value['id'])?>" title="Edit" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                            <?php } ?>
                                            <a href="javascript:void(0)" title="View" data-id="<?= $value['id']; ?>" data-title="<?= ucfirst($value['title']); ?>" data-attch="<?= $value['attachment']; ?>" data-sctime="<?= date("H:i" , strtotime($value['schedule_date'])); ?>" data-scdate="<?= date("M d, Y" , strtotime($value['schedule_date'])); ?>"  data-sentto = "<?= ucfirst($value['notify_to']); ?>"  class="btn btn-sm btn-outline-secondary viewnotify" ><i class="fa fa-eye"></i></a>
                                            <button type="button" data-id="<?=$value['id']?>" data-url="schoolNotification/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Notification" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                        <?php } } ?>
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
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '435') { echo $langlbl['title'] ; } } ?>: </b></span><span id="title"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1205') { echo $langlbl['title'] ; } } ?>: </b></span><span id="schedule_date"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1506') { echo $langlbl['title'] ; } } ?>: </b></span><span id="schedule_time"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '437') { echo $langlbl['title'] ; } } ?>: </b></span><span id="sento" ></span></p>
                    </div>
                    <div class="col-md-12" id="attach" style="display:none">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '450') { echo $langlbl['title'] ; } } ?>: </b></span><span id="attchmnt" ></span></p>
                    </div>
                    <div class="col-md-12">
                       <p> <span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '453') { echo $langlbl['title'] ; } } ?>: </b></span><span id="description" ></span></p>
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

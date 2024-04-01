<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                
                    <div class="row">
                        <h2 class="heading col-md-6 align-left" >Notification</h2>
                        <div class="col-md-6 align-right" >
                            <a href="<?=$baseurl?>notification/archive" title="Archive" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '680') { echo $langlbl['title'] ; } } ?></a>
                            <a href="<?=$baseurl?>notification/add" title="Add" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '681') { echo $langlbl['title'] ; } } ?></a>
                            
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
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '673') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '674') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '675') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '676') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '677') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '678') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '679') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="notificationbody" class="modalrecdel"> 
                            <?php foreach($notify_details as $value)
                            { 
                                $sts_notify = $value['sent_notify'] == 0 ? "Not Sent" : "Sent";
                            ?>
                                <tr>
                                    <td class="width45">
                                        <label class="fancy-checkbox">
                                            <input class="checkbox-tick" type="checkbox" name="checkbox" value="<?=$value['id']?>">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?= ucfirst($value['added_by']); ?></td>
                                    <td><?= ucfirst($value['title']); ?></td>
                                    <td><?= date("d M, Y H:i" , strtotime($value['schedule_date'])); ?></td>
                                    <td><?= ucfirst($value['notify_to']); ?></td>
                                    <td><?= ucfirst($sts_notify); ?></td>
                                    <td>
                                    <?php    
                                        if( $value['status'] == 0)
                                        {
                                        ?>
                                            <a href="javascript:void()" data-url="notification/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Notification Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>
                                        <?php 
                                        }
                                        else 
                                        { ?>
                                            <a href="javascript:void()" data-url="notification/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Notification Status" class="btn btn-sm js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>
                                        <?php 
                                        }
                                    ?>
                                    </td>
                                    <td>
                                        <?php $now = strtotime('now');
                                        if(strtotime($value['schedule_date']) >= $now ) { ?>
                                        <a href="<?=$baseurl?>notification/edit/<?= md5($value['id'])?>" title="Edit" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                        <?php } ?>
                                        <a href="javascript:void(0)" title="View" data-id="<?= $value['id']; ?>" data-title="<?= ucfirst($value['title']); ?>" data-attch="<?= $value['attachment']; ?>" data-sctime="<?= date("H:i" , strtotime($value['schedule_date'])); ?>" data-scdate="<?= date("M d, Y" , strtotime($value['schedule_date'])); ?>"  data-sentto = "<?= ucfirst($value['notify_to']); ?>" class="btn btn-sm btn-outline-secondary viewnotify" ><i class="fa fa-eye"></i></a>
                                        <button type="button" data-id="<?=$value['id']?>" data-url="notification/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Notification" data-type="confirm"><i class="fa fa-trash-o"></i></button>
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
                <h6 class="title" id="defaultModalLabel">View Notification</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <p><span><b>Title: </b></span><span id="title"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b>Schedule Date: </b></span><span id="schedule_date"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b>Schedule Time: </b></span><span id="schedule_time"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b>Sent To: </b></span><span id="sento" ></span></p>
                    </div>
                    <div class="col-md-12" id="attach" style="display:none">
                        <p><span><b>Attachment: </b></span><span id="attchmnt" ></span></p>
                    </div>
                    <div class="col-md-12">
                       <p> <span><b>Description: </b></span><span id="description" ></span></p>
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

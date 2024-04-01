
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                    <?php //$total_count = 0; ?>
                    <div class="row">
                        <h2 class="heading col-md-6 align-left" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1446') { echo $langlbl['title'] ; } } ?></h2>
                        <h2 class="heading col-md-6 align-right" >
                            <a href="javascript:void(0)"  title="Approve All" data-str= "All Status" data-url = "schools/approveallnotify"  id="approvenotify" class="btn btn-sm btn-success approve"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1437') { echo $langlbl['title'] ; } } ?></a>
                            <a class="btn btn-success" href="../approveStatus/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '881') { echo $langlbl['title'] ; } } ?></a>
                            </h2>
                    </div>
                    
                            
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem notification_table" id="notification_table approveTable" data-page-length='50'>
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
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '101') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '121') { echo $langlbl['title'] ; } } ?></th>
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
                                            <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value['id'] ?>">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?= ucfirst($value['title']); ?></td>
                                    <td><?= date("m-d-Y h:i A" , strtotime($value['schedule_date'])); ?></td>
                                    <td><?= ucfirst($value['notify_to']); ?></td>
                                    <td><?= ucfirst($sts_notify); ?></td>
                                    <td>
                                    <?php 
                                        if( $value['status'] == 0)
                                        {
                                            echo '<a href="javascript:void()" data-url="schools/notifyapprovestatus" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Notification Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                                        }
                                        else 
                                        { 
                                            echo '<a href="javascript:void()" data-url="schools/notifyapprovestatus" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Notification Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                                        }
                                    ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" title="View" data-id="<?= $value['id']; ?>" data-title="<?= ucfirst($value['title']); ?>" data-attch="<?= $value['attachment']; ?>" data-scdate="<?= date("M d, Y " , strtotime($value['schedule_date'])); ?>"  data-sctime="<?= date("h:i A" , strtotime($value['schedule_date'])); ?>" data-sentto = "<?= ucfirst($value['notify_to']); ?>" class="btn btn-sm btn-outline-secondary viewnotify" ><i class="fa fa-eye"></i></a>
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

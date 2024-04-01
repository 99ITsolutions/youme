<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '1419') { $lbl1419 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1385') { $lbl1385 = $langlbl['title'] ; }
    if($langlbl['id'] == '1386') { $lbl1386 = $langlbl['title'] ; }
    if($langlbl['id'] == '1390') { $lbl1390 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '1371') { $lbl1371 = $langlbl['title'] ; }
    if($langlbl['id'] == '1372') { $lbl1372 = $langlbl['title'] ; }
    if($langlbl['id'] == '1373') { $lbl1373 = $langlbl['title'] ; }
    if($langlbl['id'] == '1374') { $lbl1374 = $langlbl['title'] ; }
    if($langlbl['id'] == '21') { $lbl1366 = $langlbl['title'] ; }
    if($langlbl['id'] == '2072') { $lbl2072 = $langlbl['title'] ; }
    if($langlbl['id'] == '2071') { $lbl2071 = $langlbl['title'] ; }
} ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class=" heading"><?= $lbl1366 ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                </ul>
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
                                <th><?= $lbl1372 ?></th>
                                <th><?= $lbl2072 ?></th>
                                <th><?= $lbl2071 ?></th>
                                <th><?= $lbl1371 ?></th>
                                
                                <th><?= $lbl1373 ?></th>
                                <th><?= $lbl1374 ?></th>
                            </tr>
                        </thead>
                        <tbody id="notificationbody">
                            <?php foreach($notify_details as $value) { 
                                
                                
                                if($value['notify_to'] == "all" && $value['added_by'] == "superadmin")
                                { 
                                    $display = 1;
                                }
                                elseif($value['notify_to'] == "all" && $value['added_by'] == "school" && in_array($value['school_id'], $schoolid))
                                { 
                                    $display = 1;
                                }
                                elseif($value['added_by'] == "teachers" && in_array($value['tchrscholid'], $schoolid))
                                { 
                                    $tchrscls = explode(",", $value['tchrcls']);
                                    
                                    if(array_intersect($studclass, $tchrscls))
                                    {
                                        $display = 1;
                                    }
                                    else
                                    {
                                        $display = 0;
                                    }
                                }
                                else
                                {
                                    $display = 0;
                                    if($value['class_opt'] == "multiple")
                                    {
                                        $incls = [];
                                        $clsids = explode(",", $value['class_ids']);
                                        foreach($studclass as $sdcls)
                                        {
                                            if(in_array($sdcls, $clsids))
                                            {
                                                $incls[] = 1;
                                            }
                                            else
                                            {
                                                $incls[] = 0;
                                            }
                                        }
                                        $display = in_array("1", $incls) ? "1" : "0";
                                    }
                                    else
                                    {
                                        $parids = explode(",", $value['parent_ids']);
                                      
                                        if(in_array($parntid, $parids))
                                        {
                                            $display = 1;
                                        }
                                        else
                                        {
                                            $display = 0;
                                        }
                                    }
                                    
                                }
                                
                                //echo $value['id']."--".$display.","; 
                                if($display == 1)
                                { if($value['notifybold'] == 0) { $bold = 'style="font-weight:bold"'; } else { $bold = ""; } ?>
                                    <tr>
                                        <td class="width45">
                                            <label class="fancy-checkbox">
                                                <input class="checkbox-tick" type="checkbox" name="checkbox" value="<?= $value['id'] ?>">
                                            </label>
                                        </td>
                                        <td <?= $bold ?> ><?php if($value['added_by'] == "teachers") { echo "Teacher"; } else { echo ucfirst($value['added_by']); }?></b></td>
                                        <td <?= $bold ?> ><img src="/school/img/<?= $value['sentlogo'] ?>" width="60 px"><?= ucfirst($value['sentby']) ?></b></td>
                                        <td <?= $bold ?> ><?= ucfirst($value['studentname']); ?></b></td>
                                        <td <?= $bold ?> ><?= ucfirst($value['title']); ?></b></td>
                                        
                                        <td <?= $bold ?> ><?= date("M d, Y" , strtotime($value['schedule_date'])); ?></td>
                                        <td>
                                            <a href="javascript:void(0)" title="View" data-id="<?= $value['id']; ?>" data-announce="parent"  data-studid="<?= $parntid ?>" data-title="<?= ucfirst($value['title']); ?>" data-attch="<?= $value['attachment']; ?>" data-sctime="<?= date("H:i" , strtotime($value['schedule_date'])); ?>" data-scdate="<?= date("M d, Y" , strtotime($value['schedule_date'])); ?>" data-sentto = "<?= ucfirst($value['notify_to']); ?>"  class="btn btn-sm btn-outline-secondary viewnotify1" ><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
            
<div class="row clearfix">
    <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
</div>
    </div>
</div>

<div class="modal classmodal animated zoomIn" id="viewnotify" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl1419 ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <p><span><b><?= $lbl1385 ?>: </b></span><span id="title"></span></p>
                    </div>
                    <div class="col-md-12" id="attach" style="display:none">
                        <p><span><b><?= $lbl1386 ?> </b></span><span id="attchmnt" ></span></p>
                    </div>
                    <div class="col-md-12">
                       <p> <span><b><?= $lbl1390 ?>: </b></span><span id="description" ></span></p>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>              

              


            
    
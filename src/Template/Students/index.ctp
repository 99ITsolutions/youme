<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2059') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <?php if(!empty($sclsub_details[0]))
                    {        
                        $privilages = explode(",", $sclsub_details[0]['privilages']); 
                        $roles = explode(",", $sclsub_details[0]['sub_privileges']);
                        if(in_array("10", $roles)) { ?>
                            <li><a href="<?=$baseurl?>students/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '127') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php } if(in_array("14", $roles)) { ?>
                            <li><a href="javascript:void(0);" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addstdnt"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '126') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php } if(in_array("15", $roles)) { ?>
                            <li><a href="<?=$baseurl?>students/export" class="btn btn-sm btn-success" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1533') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php } 
                    } else { ?>
                        <li><a href="<?=$baseurl?>students/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '127') { echo $langlbl['title'] ; } } ?></a></li>
                        <li><a href="javascript:void(0);" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addstdnt"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '126') { echo $langlbl['title'] ; } } ?></a></li>
                        <li><a href="<?=$baseurl?>students/export" class="btn btn-sm btn-success" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1533') { echo $langlbl['title'] ; } } ?></a></li>
                    <?php } ?>
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            			
			    <div class="row mt-3">
			        <div class="col-md-3 ">
                        <div class="form-group">   
                            <select class="form-control" id="session" name="session" onchange="getsessionstudentss(this.value)">
                                <option value="">Choose One</option>
                                <?php foreach($session_details as $key => $val){ ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
			    </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem student_table" id="student_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <!--<th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>-->
                                <th>QR Code Image</th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?></th>
                                <th>E-mail</th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '131') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '132') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1579') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '238') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1780') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '137') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '138') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="studentbody">
                                    <?php
                                    $n=1;
                                    foreach($studentdetails as $value){
                                        //print_r($value);
                                        $libacc = ($value['library_access'] == 0) ? "No" : "Yes";
                                        if($value['show'] == 1) {
                                        ?>
                                        <tr>
                                            <!--<td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value['id'] ?>">
                                                </label>
                                            </td>--> 
                                            <?php
                                            if($value['qrcode_img'] == "" ){
                                                $img = "" ;
                                                $imgqr = "#";
                                            }
                                            else{
                                                $img = '<img src="'.$baseurl.'codeqr/'.$value['qrcode_img'].'" class="rounded-circle avatar" alt="">';
                                                $imgqr = $baseurl.'codeqr/'.$value['qrcode_img'];
                                            }
                                            ?>
                                            <td>
                                                <span><a class="example-image-link" href="<?= $imgqr ?>" data-lightbox="example-1"><?= $img ?></a></span>
                                            </td>
                                            <td>
                                                <span><?= $value['class']['c_name']. "-".$value['class']['c_section']. " (". $value['class']['school_sections']. ")" ?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['email']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['password']?></span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value['l_name']. " ".$value['f_name']?></span>
                                            </td>
                                            <td>
                                                <span><?= $libacc ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['session']['startyear']."-".$value['session']['endyear'] ?></span>
                                            </td> 
                                            <td>
                                                <span><?= $value['del_req_reason'] ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                /*if(!empty($user_details[0]['id']))
                                                {*/
                                                    if( $value['status'] == 0)
                                                    {
                                                    ?>
                                                    <a href="javascript:void()" data-url="students/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Student Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>
                                                    <?php 
                                                    }
                                                    else 
                                                    { ?>
                                                        <a href="javascript:void()" data-url="students/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Student Status" class="btn btn-sm js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>
                                                    <?php 
                                                    }
                                                /*}
                                                else
                                                {
                                                    if( $value['status'] == 0)
                                                    {
                                                    ?>
                                                    <label class="switch">
                                                      <input type="checkbox" disabled >
                                                      <span class="slider round"></span>
                                                    </label>
                                                    <?php 
                                                    }
                                                    else 
                                                    { ?>
                                                        <label class="switch">
                                                          <input type="checkbox" checked disabled >
                                                          <span class="slider round"></span>
                                                        </label>
                                                    <?php 
                                                    }
                                                }*/
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach($lang_label as $langlbl) 
                                                { 
                                                    if($langlbl['id'] == '1781') { $req = $langlbl['title'] ; } 
                                                    if($langlbl['id'] == '1782') { $reqsent = $langlbl['title'] ; } 
                                                }
                                                if(!empty($sclsub_details[0]))
                                                {        
                                                    $privilages = explode(",", $sclsub_details[0]['privilages']); 
                                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']);
                                                    if(in_array("11", $roles)) { ?>
                                                        <a href="<?=$baseurl?>students/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                    <?php } if(in_array("12", $roles)) { ?>
                                                        <a href="<?=$baseurl?>students/view/<?= md5($value['id'])?>" title="View" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-eye"></i></a>
                                                    <?php } if(in_array("13", $roles)) {
                                                        if($value['delete_request'] == 0) { ?>
                                                            <a href="javascript:void()" data-url="student/deleterequest" data-id="<?=$value['id']?>" data-delreq="<?=$value['delete_request']?>" data-str="Student" class="btn btn-sm btn-outline-success deleterequeststu" title="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1781') { echo $langlbl['title'] ; } } ?>" data-type="status_change"><?=str_replace("0",$req,$value['delete_request']) ?> </a></span>
                                                        <?php } else { ?>
                                                            <a href="javascript:void()" data-url="student/deleterequest" data-id="<?=$value['id']?>" data-delreq="<?=$value['delete_request']?>" data-str="Student" class="btn btn-sm btn-outline-danger deleterequeststu" title="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1781') { echo $langlbl['title'] ; } } ?>" data-type="status_change"><?= str_replace("1",$reqsent,$value['delete_request'])?> </a></span>
                                                        <?php } 
                                                    }
                                                } else { ?>
                                                    <a href="<?=$baseurl?>students/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                    <a href="<?=$baseurl?>students/view/<?= md5($value['id'])?>" title="View" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-eye"></i></a>
                                                    <?php if($value['delete_request'] == 0) { ?>
                                                    <a href="javascript:void()" data-url="student/deleterequest" data-id="<?=$value['id']?>" data-delreq="<?=$value['delete_request']?>" data-str="Student" class="btn btn-sm btn-outline-success deleterequeststu" title="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1781') { echo $langlbl['title'] ; } } ?>" data-type="status_change"><?=str_replace("0",$req,$value['delete_request']) ?> </a></span>
                                                    <?php } else { ?>
                                                    <a href="javascript:void()" data-url="student/deleterequest" data-id="<?=$value['id']?>" data-delreq="<?=$value['delete_request']?>" data-str="Student" class="btn btn-sm btn-outline-danger deleterequeststu" title="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1781') { echo $langlbl['title'] ; } } ?>" data-type="status_change"><?= str_replace("1",$reqsent,$value['delete_request'])?> </a></span>
                                                    <?php } ?>
                                                <?php } ?>
                                                
                                                
                                            </td>
                                        </tr>
                                        <?php
                                        $n++;
                                        }
                                    }
                                    ?>
                                       
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

<!------------------ Import Student --------------------->

    <style>
        a {        }
    </style>
<div class="modal animated zoomIn" id="addstdnt" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '180') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'import'] , 'id' => "addstdntcsvform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '181') { echo $langlbl['title'] ; } } ?>*  <a href="webroot/student.csv" download class="" style="color: #ffa812 !important;"><i class="fa fa-download"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '182') { echo $langlbl['title'] ; } } ?></i></a></label>
                        <div class="form-group">                                    
                            <input type="file" class="form-control" required  name="file" >
                            <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '187') { echo $langlbl['title'] ; } } ?></small>
                            <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1779') { echo $langlbl['title'] ; } } ?></small>
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                        <div class="error" id="stdntcsverror"></div>
                        <div class="success" id="stdntcsvsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <a href="webroot/student.csv" download class="btn btn-success mt-1"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '182') { echo $langlbl['title'] ; } } ?></a>
                    <button type="submit" class="btn btn-primary addstdntcsvbtn" id="addstdntcsvbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '185') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '186') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>


<!------------------ End --------------------->

<div class="modal fade" id="delreqpopup" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1781') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                 <?php	echo $this->Form->create(false , ['url' => ['action' => 'deleterequest'] , 'id' => "delrsnform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" id="studelreq" name="studelreqsts">
                    <input type="hidden" id="stuid" name="stuid">
                    <div class="col-md-12">
                        <div class="form-group">    
                            <labe><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1780') { echo $langlbl['title'] ; } } ?></labe>
                            <textarea name="delrsn" id="delrsn" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="sclstserror"></div>
                        <div class="success" id="sclstssuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary sclstsbtn" id="sclstsbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '108') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>    
<script>
function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

</script>    

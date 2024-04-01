
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '15') { echo $langlbl['title'] ; } } ?></h2>
                            <ul class="header-dropdown">
                                <?php if(!empty($sclsub_details[0]))
                                {        
                                    $privilages = explode(",", $sclsub_details[0]['privilages']); 
                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']);
                                    if(in_array("16", $roles)) { ?>
                                        <li><a href="<?=$baseurl?>teachers/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '280') { echo $langlbl['title'] ; } } ?></a></li>
                                    <?php } if(in_array("21", $roles)) { ?>
                                        <li><a href="<?=$baseurl?>teachers/assignClass" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1746') { echo $langlbl['title'] ; } } ?></a></li>
                                    
                                    <?php } if(in_array("22", $roles)) { ?>
                                        <li><a href="javascript:void(0);" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addtchr"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '281') { echo $langlbl['title'] ; } } ?></a></li>
                                    <?php } if(in_array("23", $roles)) { ?>
                                        <li><a href="<?=$baseurl?>teachers/export" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1533') { echo $langlbl['title'] ; } } ?></a></li>
                                    <?php } if(in_array("20", $roles)) { ?>
                                        <li><a href="javascript:void(0)"  title="Delete All" data-str= "Teachers" data-url = "teachers/deletealltchrs"  id="deletealltchrs" class="btn btn-sm btn-success approve"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1532') { echo $langlbl['title'] ; } } ?> </a></li>
                                    <?php } 
                                } else { ?>
                                <li><a href="<?=$baseurl?>teachers/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '280') { echo $langlbl['title'] ; } } ?></a></li>
                                <li><a href="<?=$baseurl?>teachers/assignClass" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1746') { echo $langlbl['title'] ; } } ?></a></li>
                                <li><a href="javascript:void(0);" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addtchr"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '281') { echo $langlbl['title'] ; } } ?></a></li>
                                <li><a href="<?=$baseurl?>teachers/export" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1533') { echo $langlbl['title'] ; } } ?></a></li>
                                <li><a href="javascript:void(0)"  title="Delete All" data-str= "Teachers" data-url = "teachers/deletealltchrs"  id="deletealltchrs" class="btn btn-sm btn-success approve"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1532') { echo $langlbl['title'] ; } } ?> </a></li>
                                <?php } ?>
                                
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
                                
                            
                            <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="teacher_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '81') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '82') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '83') { echo $langlbl['title'] ; } } ?></th>
                                            
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '286') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '288') { echo $langlbl['title'] ; } } ?></th>
                                            
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '85') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '72') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n=1;
                                    foreach($teacher_details as $value){
                                        if($value['show'] == 1) {
                                        ?>
                                        <tr>
                                            <td class="width45">
                                            <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox"  id="<?= $value['id'] ?>">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value['l_name']." ".$value['f_name']?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['email'] ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['password'] ?></span>
                                            </td>
                                            
                                            <td>
                                                <span><?=$value['mobile_no']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['quali']?></span>
                                            </td>
                                            <td>
                                                <span><?= date('d-m-Y', strtotime( $value['doj']))?></span>
                                            </td>
                                            
                                            <td>
                                                <?php
                                                /*if(!empty($user_details[0]['id']))
                                                {*/
                                                    if( $value['status'] == 0)
                                                    {
                                                    ?>
                                                    <a href="javascript:void()" data-url="teachers/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Teacher Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>
                                                    <?php 
                                                    }
                                                    else 
                                                    { ?>
                                                        <a href="javascript:void()" data-url="teachers/status" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Teacher Status" class="btn btn-sm js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>
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
                                                <?php if(!empty($sclsub_details[0]))
                                                {        
                                                    $privilages = explode(",", $sclsub_details[0]['privilages']); 
                                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']);
                                                    if(in_array("17", $roles)) { ?>
                                                        <a href="<?=$baseurl?>teachers/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                    <?php } if(in_array("18", $roles)) { ?>
                                                        <a href="<?=$baseurl?>teachers/view/<?= md5($value['id'])?>" title="View" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-eye"></i></a>
                                                    <?php } if(in_array("19", $roles)) { ?>
                                                        <button type="button" data-id="<?=$value['id']?>" data-url="teachers/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Teacher" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                <?php } } else { ?>    
                                                    <a href="<?=$baseurl?>teachers/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                    <a href="<?=$baseurl?>teachers/view/<?= md5($value['id'])?>" title="View" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-eye"></i></a>
                                                    <button type="button" data-id="<?=$value['id']?>" data-url="teachers/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Teacher" data-type="confirm"><i class="fa fa-trash-o"></i></button>
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
            
           
        </div>
    </div>



<!------------------ Import Teacher --------------------->

    
<div class="modal animated zoomIn" id="addtchr" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '308') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'import'] , 'id' => "addtchrcsvform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '309') { echo $langlbl['title'] ; } } ?>*  <a href="webroot/teacher.csv" download class="" style="color: #ffa812 !important;"><i class="fa fa-download"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '310') { echo $langlbl['title'] ; } } ?></i></a></label>
                        <div class="form-group">                                    
                            <input type="file" class="form-control" required  name="file" >
                            <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '315') { echo $langlbl['title'] ; } } ?></small>
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                        <div class="error" id="tchrcsverror"></div>
                        <div class="success" id="tchrcsvsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <a href="webroot/teacher.csv" download class="btn btn-success mt-1"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '310') { echo $langlbl['title'] ; } } ?></a>
                    <button type="submit" class="btn btn-primary" id="addtchrcsvbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '313') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '314') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>


<!------------------ End --------------------->


    


 

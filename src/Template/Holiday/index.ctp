<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $bcklbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2005') { $holidylstlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2006') { $addholilbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2007') { $holilbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2008') { $holinmlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2013') { $entrholinmlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2009') { $editholilbl = $langlbl['title'] ; }
    if($langlbl['id'] == '1118') { $savelbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1412') { $updtlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '1209') { $clslbl = $langlbl['title'] ; }
} 
?>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?= $holidylstlbl ?></h2>
                            <ul class="header-dropdown">
                                <?php if(!empty($sclsub_details[0]))
                                { 
                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                    if(in_array("26", $roles)) { ?>
                                        <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addholiday"><?= $addholilbl ?></a></li>
                                    <?php }
                                } else { ?>
                                    <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addholiday"><?= $addholilbl ?></a></li>
                                <?php } ?>
                                
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $bcklbl ?></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem holiday_table" id="holiday_table" data-page-length='50'>
 
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th><?= $holilbl ?></th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n=1;
                                    foreach($holiday_details as $value){
                                    ?>
                                        <tr>
                                            <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?= $value['holi_type'] ?></span>
                                            </td>
                                            <td>
                                                <span><?=date('d-m-Y', strtotime($value['date']))?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['descs'] ?></span>
                                            </td>
                                            <td>
                                                <?php if(!empty($sclsub_details[0]))
                                                { 
                                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                                    if(in_array("27", $roles)) { ?>
                                                        <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary editholidy" data-toggle="modal" data-target="editholiday" title="Edit"><i class="fa fa-edit"></i></button>
                                                    <?php } if(in_array("28", $roles)) {    ?>
                                                        <button type="button" data-url="holiday/delete" data-id="<?=$value['id']?>" data-str="Holiday" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                    <?php }
                                                } else { ?>
                                                    <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary editholidy" data-toggle="modal" data-target="editholiday" title="Edit"><i class="fa fa-edit"></i></button>
                                                    <button type="button" data-url="holiday/delete" data-id="<?=$value['id']?>" data-str="Holiday" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                <?php } ?>
                                                
                                            </td>
                                        </tr>
                                        <?php
                                        $n++;
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
    <div><?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?></div>               

<div class="modal animated zoomIn" id="addholiday" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $addholilbl ?> </h6>
                <button type="button" class=" close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                </button>
        </div>
        <div class="modal-body">
            <?php echo $this->Form->create(false , ['url' => ['action' => 'addholi'] , 'id' => "addholiform" ,  'method' => "post"  ]); ?>
            <div class="row clearfix">
                <div class="col-sm-12">
                    <label><?= $holinmlbl ?>*</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="holi_type" required placeholder="<?= $entrholinmlbl ?>*" >
                    </div>
                </div>
                <div class="col-sm-12">
                    <label>Date*</label>
                    <div class="form-group">
                        <input type="text" class="form-control backdatepicker" required data-date-format="dd-mm-yyyy" name="date"  placeholder="dd-mm-yyyy *" >
                    </div>
                </div>
                <div class="col-sm-12">
                    <label>Description*</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="descs" required placeholder="Description*" >
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="error" id="holierror"></div>
                    <div class="success" id="holisuccess"></div>
                </div>
                <div class="button_row">
                <hr>
                <button type="submit" id="addholibtn" class="btn btn-primary addholibtn"><?= $savelbl ?> </button>
                <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $clslbl ?></button>
                </div>
            </div>
            </form>
        </div>
        </div>
    </div>
</div>


<div class="modal animated zoomIn" id="editholiday" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $editholilbl ?></h6>
                <button type="button" class=" close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                </button>
        </div>
        <div class="modal-body">
            <?php echo $this->Form->create(false , ['url' => ['action' => 'editholi'] , 'id' => "editholiform" ,  'method' => "post"  ]); ?>
            <div class="row clearfix">
                <div class="col-sm-12">
                    <label><?= $holinmlbl ?>*</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="holi_type" id="holi_type" required placeholder="<?= $entrholinmlbl ?>*" >
                    </div>
                </div>
                <input type="hidden" name="id" id="hid">
                <div class="col-sm-12">
                    <label>Date*</label>
                    <div class="form-group">
                        <input type="text" class="form-control backdatepicker" required data-date-format="dd-mm-yyyy" name="date" id="date"  placeholder="dd-mm-yyyy *" >
                    </div>
                </div>
                <div class="col-sm-12">
                    <label>Description*</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="descs" id="descs"  required placeholder="Description*" >
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="error" id="eholierror"></div>
                    <div class="success" id="eholisuccess"></div>
                </div>
                <div class="button_row">
                <hr>
                <button type="submit" id="editholibtn" class="btn btn-primary editholibtn"><?= $updtlbl ?></button>
                <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $clslbl ?></button>
                </div>
            </div>
            </form>
        </div>
        </div>
    </div>
</div>


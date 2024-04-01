<?php
    $statusarray = array('Inactive','Active' );
    foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2027') { $yerlylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2219') { $lbl2219 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1019') { $hlfyrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1020') { $quatrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1021') { $mnthlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2028') { $chosesesslbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2121') { $lbl2121 = $langlbl['title'] ; }
    if($langlbl['id'] == '2175') { $lbl2175 = $langlbl['title'] ; }
} 
?>
<style>
@media screen and (max-width: 440px) and (min-width: 200px)
{
    .sclstufeemodule>.col-md-8
    {
       text-align:left !important;
    }
    .sclstufeemodule>.col-md-8>.btn
    {
       display:block !important;
       margin-top:5px !important;
       width:60% !important;
    }
}
</style>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row sclstufeemodule">
                    <h2 class="col-md-3 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '316') { echo $langlbl['title'] ; } } ?>    </h2>
                    <div class="col-md-9 text-right">
                        <?php if(!empty($sclsub_details[0])) { 
                            $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                            if(in_array("122", $roles)) { ?>
                                <a href="<?=$baseurl?>fees/canteen" title="Canteen Fund" class="btn btn-sm btn-success"><?= $lbl2219 ?></a>
                            <?php }
                            if(in_array("33", $roles)) { ?>
                                <a href="<?=$baseurl?>feehead" title="Fee Description Head" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '317') { echo $langlbl['title'] ; } } ?></a>
                            <?php } if(in_array("117", $roles)) { ?>
                                <a href="<?=$baseurl?>feediscount" title="<?= $lbl2121 ?>" class="btn btn-sm btn-success"><?= $lbl2121 ?></a>
                            <?php } if(in_array("29", $roles)) { ?>
                                <a href="javascript:void(0);" title="Add" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addfeestruc"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '318') { echo $langlbl['title'] ; } } ?></a>
                            <?php } if(in_array("37", $roles)) { ?>
                                <a href="<?=$baseurl?>fees/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '319') { echo $langlbl['title'] ; } } ?></a>
                            <?php } if(in_array("119", $roles) || in_array("120", $roles) || in_array("121", $roles)) { ?>
                                <a href="<?=$baseurl?>fees/feereport" title="<?= $lbl2175 ?>" class="btn btn-sm btn-success"><?= $lbl2175 ?></a>
    	                <?php }
                        } else { ?>
                        <a href="<?=$baseurl?>fees/canteen" title="Canteen Fund" class="btn btn-sm btn-success"><?= $lbl2219 ?></a>
                        <a href="<?=$baseurl?>feehead" title="Fee Description Head" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '317') { echo $langlbl['title'] ; } } ?></a>
                        <a href="<?=$baseurl?>feediscount" title="<?= $lbl2121 ?>" class="btn btn-sm btn-success"><?= $lbl2121 ?></a>
    	                <a href="javascript:void(0);" title="Add" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addfeestruc"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '318') { echo $langlbl['title'] ; } } ?></a>
    	                <a href="<?=$baseurl?>fees/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '319') { echo $langlbl['title'] ; } } ?></a>
                        <a href="<?=$baseurl?>fees/feereport" title="<?= $lbl2175 ?>" class="btn btn-sm btn-success"><?= $lbl2175 ?></a>
                    <?php } ?>
                    <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                    </div>
                </div>           
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="feestruc_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '337') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '339') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '321') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '322') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '402') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '138') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="strucbody" class="modalrecdel"> 
                        <?php
                                    $n=1;
                                    foreach($feestructure_details as $value){
                                        if(!empty($sclsub_details[0]))
                                        { 
                                            if(strtolower($value['sclsection']) == "creche" || strtolower($value['sclsection']) == "maternelle") {
                                                $clsmsg = "kindergarten";
                                            }
                                            elseif(strtolower($value['sclsection']) == "primaire") {
                                                $clsmsg = "primaire";
                                            }
                                            else
                                            {
                                                $clsmsg = "secondaire";
                                            }
                                            $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                            if(in_array($clsmsg, $subpriv)) { 
                                                $show = 1;
                                            }
                                            else
                                            {
                                                $show = 0;
                                            }
                                        } else { 
                                            $show = 1;
                                        }
                                        if($show == 1) {
                                        ?>
                                        <tr>
                                           <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value['c_name']."-".$value['c_section']." (".$value['school_section'].")" ?></span>
                                            </td>
                                            <td> 
                                                <span><?= ucfirst($yerlylbl) ?></span>
                                                <!--<span><?= ucfirst($value['frequency']) ?></span>-->
                                            </td>
                                            <td>
                                                <span><?=$value['start_year']?></span>
                                            </td>
                                            <td>
                                                <span><?= "$".$value['amount']?></span>
                                            </td>
                                            <td>
                                                <?php
                                               
                                                    if( $value['active'] == 0)
                                                    {
                                                    ?>
                                                    <a href="javascript:void()" data-url="fees/status" data-id="<?=$value['id']?>" data-status="<?=$value['active']?>" data-str="Fee Structure Status" class="btn btn-sm js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>
                                                  
                                                    <?php 
                                                    }
                                                    else 
                                                    { ?>
                                                     <a href="javascript:void()" data-url="fees/status" data-id="<?=$value['id']?>" data-status="<?=$value['active']?>" data-str="Fee Structure Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>
                                                       
                                                    <?php 
                                                    }
                                                ?>
                                                 
                                               
                                            </td>
                                            <td>
                                                <?php if(!empty($sclsub_details[0])) { 
                                                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                                        if(in_array("30", $roles)) { ?>
                                                            <button type="button" data-id='<?= $value['id'] ?>' class="btn btn-sm btn-outline-secondary editstruc" data-toggle="modal"  data-target="#editstruc" title="Edit"><i class="fa fa-edit"></i></button>
                                                        <?php } if(in_array("31", $roles)) { ?>
                                                            <button type="button" data-id='<?= $value['id'] ?>' data-sess= '<?=$value['start_year']?>' data-cname = '<?=$value['c_name']."-".$value['c_section']." (".$value['school_section'].")"?>' data-amt='$<?= $value['amount'] ?>'  data-frequency='<?= ucfirst($value['frequency']) ?>'  class="btn btn-sm btn-outline-secondary viewstruc" title="View"><i class="fa fa-eye"></i></button>
                                                        <?php } if(in_array("32", $roles)) { ?>
                                                            <button type="button" data-id="<?=$value['id']?>" data-url="fees/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Fees structure" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                    <?php }
                                                } else { ?>
                                                    <button type="button" data-id='<?= $value['id'] ?>' class="btn btn-sm btn-outline-secondary editstruc" data-toggle="modal"  data-target="#editstruc" title="Edit"><i class="fa fa-edit"></i></button>
                                                    <button type="button" data-id='<?= $value['id'] ?>' data-sess= '<?=$value['start_year']?>' data-cname = '<?=$value['c_name']."-".$value['c_section']." (".$value['school_section'].")"?>' data-amt='$<?= $value['amount'] ?>'  data-frequency='<?= ucfirst($value['frequency']) ?>'  class="btn btn-sm btn-outline-secondary viewstruc" title="View"><i class="fa fa-eye"></i></button>
                                                    <button type="button" data-id="<?=$value['id']?>" data-url="fees/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Fees structure" data-type="confirm"><i class="fa fa-trash-o"></i></button>
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

<!------------------ Add Class --------------------->

<div class="modal classmodal animated zoomIn" id="addfeestruc" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '318') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addstructure'] , 'id' => "add_fee_structure" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2146') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control js-states" id="sclclass" required name="sclsection" onchange="sclsectiona(this.value)">
                                <option value="">Choose Section</option>
                                <option  value="Maternelle" >Kindergarten</option>
                                <option  value="Primaire" >Primaire</option>
                                <option  value="secondaire" >Secondaire</option>
                            </select>  
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '337') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control js-states" id="aclass" required name="class[]" multiple>
                                <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '338') { echo $langlbl['title'] ; } } ?></option>
                                <?php
                                foreach($class_details as $key => $val){
                                    if(!empty($sclsub_details[0]))
                                    { 
                                        if(strtolower($val['school_sections']) == "creche" || strtolower($val['school_sections']) == "maternelle") {
                                            $clsmsg = "kindergarten";
                                        }
                                        elseif(strtolower($val['school_sections']) == "primaire") {
                                            $clsmsg = "primaire";
                                        }
                                        else
                                        {
                                            $clsmsg = "secondaire";
                                        }
                                        $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                        if(in_array($clsmsg, $subpriv)) { 
                                            $show = 1;
                                        }
                                        else
                                        {
                                            $show = 0;
                                        }
                                    } else { 
                                        $show = 1;
                                    }
                                    if($show == 1) {
                                    ?>
                                      <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']." (". $val['school_sections'] . ")";?> </option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>  
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '339') { echo $langlbl['title'] ; } } ?></label>
                            <select readonly class="form-control" required name="frequency" >
                                <!--<option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '340') { echo $langlbl['title'] ; } } ?></option> -->
                                <option value="yearly" selected="selected"><?= $yerlylbl ?></option>
                                <!-- <option value="half yearly">Half Yearly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="monthly">Monthly</option> -->
                                
                            </select> 
                        </div>
                    </div>
                      <div class="col-md-12">
                        <div class="form-group">   
                            <!--<label>Year</label>
                           <div class="input-group date" id="datetimepicker_year" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker_year"  required name="start_year" id="select_year" required/>
                              <div class="input-group-append" data-target="#datetimepicker_year" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>-->
                            <label><?= $chosesesslbl ?></label>
                            <select class="form-control session" id="select_year" name="start_year" required>
                                <option value=""><?= $chosesesslbl ?></option>
                                <?php
                                foreach($session_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '343') { echo $langlbl['title'] ; } } ?>*</label>
                            <input type="number" class="form-control" name="amount"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '527') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="strucerror"></div>
                        <div class="success" id="strucsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addstrucbtn" id="addstrucbtn" style="margin-right: 10px;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '344') { echo $langlbl['title'] ; } } ?></button>
                    <!--<button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>-->
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              



    <!------------------ Edit Class --------------------->

    
<div class="modal classmodal  animated zoomIn" id="editstruc"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1540') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editstruc'] , 'id' => "editstrucform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="eid"  name="eid" >
                        </div>
                    </div>
		            <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '337') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control class" id="class" required name="class" >
                                <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '338') { echo $langlbl['title'] ; } } ?></option>
                                <?php
                                foreach($class_details as $key => $val){
                                    if(!empty($sclsub_details[0]))
                                    { 
                                        if(strtolower($val['school_sections']) == "creche" || strtolower($val['school_sections']) == "maternelle") {
                                            $clsmsg = "kindergarten";
                                        }
                                        elseif(strtolower($val['school_sections']) == "primaire") {
                                            $clsmsg = "primaire";
                                        }
                                        else
                                        {
                                            $clsmsg = "secondaire";
                                        }
                                        $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                        if(in_array($clsmsg, $subpriv)) { 
                                            $show = 1;
                                        }
                                        else
                                        {
                                            $show = 0;
                                        }
                                    } else { 
                                        $show = 1;
                                    }
                                    if($show == 1) {
                                    ?>
                                      <option  value="<?=$val['id']?>" id="class_<?=$val['id']?>"><?php echo $val['c_name'] ."-" . $val['c_section']." (". $val['school_sections'] . ")";?> </option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>  
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '339') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control" readonly required name="frequency" >
                                <!--<option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '340') { echo $langlbl['title'] ; } } ?></option> -->
                                    <option value="yearly" id="f_yearly"><?= $yerlylbl ?></option>
                              <!--<option value="half yearly" id="f_half_yearly">Half Yearly</option>
                                <option value="quarterly" id="f_quarterly">Quarterly</option>
                                <option value="monthly" id="f_monthly">Monthly</option> -->
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <!--<label>Year</label>
                           <div class="input-group date" id="datetimepicker_year2" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker_year2"  required name="start_year" id="select_year2" required/>
                              <div class="input-group-append" data-target="#datetimepicker_year2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>-->
                            <label><?= $chosesesslbl ?></label>
                            <select class="form-control class" id="select_year2" name="start_year" required>
                                <option value=""><?= $chosesesslbl  ?></option>
                                <?php
                                foreach($session_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" id="sy_<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '343') { echo $langlbl['title'] ; } } ?></label>
                            <input type="number" class="form-control" id="amount" name="amount"  required placeholder="Amount *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="editstrucerror"></div>
                        <div class="success" id="editstrucsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editstrucbtn" id="editstrucbtn" style="margin-right:15px"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?> & <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '75') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

<div class="modal classmodal animated zoomIn" id="view_edit" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">View/Edit student Fee</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'vieweditfee'] , 'id' => "add_fee_structure" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label>Class</label>
                            <select class="form-control class" id="class_id" name="class" required onchange="getstudents(this.value)">
                                <option value="">Choose Class</option>
                                <?php
                                foreach($class_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']." (". $val['school_sections'] . ")";?> </option>
                                <?php
                                }
                                ?>
                            </select>    
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label>Student</label>
                        <div class="form-group">
                        <select class="form-control class" name="student" id="student" placeholder="Choose Student">
                            <option value="">Choose Student</option>
                        </select>  
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label>Year</label>
                           <div class="input-group date" id="datetimepicker_year3" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker_year3"  required name="start_year" id="select_year" required/>
                              <div class="input-group-append" data-target="#datetimepicker_year3" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addstrucbtn" id="addstrucbtn">Go</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>     


<div class="modal classmodal animated zoomIn" id="viewstruc" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1548') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
               <div class="row clearfix">
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '241') { echo $langlbl['title'] ; } } ?>: </b></span><span id="sessn"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '337') { echo $langlbl['title'] ; } } ?>: </b></span><span id="classname"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '339') { echo $langlbl['title'] ; } } ?>: </b></span><span id="frequencyview"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '343') { echo $langlbl['title'] ; } } ?>: </b></span><span id="dollar"></span></p>
                    </div>
                    <!--<div class="col-md-12">
                         <span><b>Fee Description</b></span>
                    </div>-->
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem viewfeestruc_table" id="viewfeestruc_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '52') { echo $langlbl['title'] ; } } ?> </th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '322') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                </thead>
                                <tbody id="viewstrucbody" class="modalrecdel"> 
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function sclsectiona(val)
    {
        $("#aclass").html("");
        var refscrf = $("input[name='_csrfToken']").val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax
        ({
            data : {_csrfToken:refscrf, 'sclsctn':val },
            type : "post",
    		url: baseurl + '/fees/selclass',
            success: function(response){
                console.log(response);
                $("#aclass").html(response);
            }
        })  
    }
</script>
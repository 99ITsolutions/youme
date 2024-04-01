<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <!--<h2 class="heading">Contact M</h2>-->
                <ul class="header-dropdown">
	                <li><a href="<?=$baseurl?>StudentMessages/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1380') { echo $langlbl['title'] ; } } ?></a></li>
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
                            
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
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1381') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1382') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1383') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1384') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="strucbody" class="modalrecdel"> 
                        <?php
                                    $n=1;
                                    foreach($message_details as $value){
                                        if($value['read_count'] > 0){  $read ='class="font-weight-bold"'; }else{ $read =''; }
                                        ?>
                                        <tr>
                                           <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span <?= $read ?>><?=$value['sender']." - ". $value['student_no']. " (Class: ". $value['classname'] . ")"?></span>
                                            </td>
                                            <td>
                                                <?php if($value['read_count'] > 0){  ?><b>Re: </b><?php } ?><span <?= $read ?>><?= $value['subject']; ?></span>
                                            </td>
                                             <td>
                                                <span><?= date('M d, Y H:i', $value->created_date)?></span>
                                            </td>
                                            <td>
                                                <a href="<?=$baseurl?>StudentMessages/view/<?= base64_encode($value['id']) ?>"  class="btn btn-sm btn-outline-secondary" title="View"><i class="fa fa-eye"></i></a>
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

<!------------------ Add Class --------------------->

<div class="modal classmodal animated zoomIn" id="addfeestruc" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Add Fee Structure</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addstructure'] , 'id' => "add_fee_structure" , 'method' => "post"  ]); ?>

                <div class="row clearfix"><hr>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label>Class</label>
                            <select class="form-control js-states" id="aclass" required name="class" >
                                <option value="">Choose Class</option>
                                <?php
                                foreach($class_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
                                <?php
                                }
                                ?>
                            </select>  
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label>Frequency</label>
                            <select class="form-control js-states " id="afrequency" required name="frequency" >
                                <option value="">Choose Frequency</option>
                                <option value="yearly">Yearly</option>
                                <option value="half yearly">Half Yearly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="monthly">Monthly</option>-->
                                <?php // foreach($state_details as $val){?>
                                 <!-- <option  value="<?php // echo $val['id']?>" ><?php // echo $val['name'];?> </option> -->
                                <?php //} ?>
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
                            <label>Choose Session</label>
                            <select class="form-control session" id="select_year" name="start_year" required>
                                <option value="">Choose Session</option>
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
                            <label>Total Amount (in Dollars)*</label>
                            <input type="number" class="form-control" name="amount"  required placeholder="Amount *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="strucerror"></div>
                        <div class="success" id="strucsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addstrucbtn" id="addstrucbtn" style="margin-right: 10px;">Submit</button>
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
                <h6 class="title" id="defaultModalLabel">Edit Fee Structure</h6>
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
                            <label>Class</label>
                            <select class="form-control class" id="class" required name="class" >
                                <option value="">Choose Class</option>
                                <?php
                                foreach($class_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" id="class_<?=$val['id']?>"><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
                                <?php
                                }
                                ?>
                            </select>  
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label>Frequency</label>
                            <select class="form-control class" id="frequency" required name="frequency" >
                                <option value="">Choose State</option>
                                <option value="yearly" id="f_yearly">Yearly</option>
                                <option value="half yearly" id="f_half_yearly">Half Yearly</option>
                                <option value="quarterly" id="f_quarterly">Quarterly</option>
                                <option value="monthly" id="f_monthly">Monthly</option>
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
                            <label>Choose Session</label>
                            <select class="form-control class" id="select_year2" name="start_year" required>
                                <option value="">Choose Session</option>
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
                            <label>Amount (in Dollars*)</label>
                            <input type="number" class="form-control" id="amount" name="amount"  required placeholder="Amount *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="editstrucerror"></div>
                        <div class="success" id="editstrucsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editstrucbtn" id="editstrucbtn" style="margin-right:15px">Update & Next</button>
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
                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
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
                <h6 class="title" id="defaultModalLabel">View Fee Structure</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
               <div class="row clearfix">
                    <div class="col-md-12">
                        <p><span><b>Session: </b></span><span id="sessn"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b>Class: </b></span><span id="classname"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b>Frequency: </b></span><span id="frequencyview"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><span><b>Total Amount (in USD Dollar): </b></span><span id="dollar"></span></p>
                    </div>
                    <!--<div class="col-md-12">
                         <span><b>Fee Description</b></span>
                    </div>-->
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem viewfeestruc_table" id="viewfeestruc_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Description </th>
                                        <th>Amount (in US Dollar)</th>
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


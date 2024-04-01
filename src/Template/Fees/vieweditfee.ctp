<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Fee structure    </h2>
                <ul class="header-dropdown">
	               <li><a href="<?=$baseurl?>fees/add" title="Add" class="btn btn-sm btn-success">Add Fees</a></li>
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
                                <th>Class Name</th>
                                <th>Class Section</th>
                                <th>Student</th>
                                <th>Frequency</th>
                                <th>Year</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="strucbody" class="modalrecdel"> 
                        <?php
                                    $n=1;
                                    foreach($feestructure_details as $value){
                                        ?>
                                        <tr>
                                           <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value['c_name']?></span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value['c_section']?></span>
                                            </td>
                                            
                                            <td>
                                                <span class="font-weight-bold"><?=$value['f_name']?> <?=$value['l_name']?> (<?=$value['email']?>)</span>
                                            </td>
                                            
                                            <td>
                                                <span><?=$value['frequency']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['start_year']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['amount']?></span>
                                            </td>
                                            <td>
                                                <!--<a href="<?=$baseurl?>fees/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>-->
                                                <button type="button" data-id='<?= $value['id'] ?>' class="btn btn-sm btn-outline-secondary editfee" data-toggle="modal"  data-target="#editfee" title="Edit"><i class="fa fa-edit"></i></button>
                                                <button type="button" data-id="<?=$value['id']?>" data-url="fees/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Fees structure" data-type="confirm"><i class="fa fa-trash-o"></i></button>
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

<div class="modal classmodal  animated zoomIn" id="editfee"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Edit Student Fee</h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editfee'] , 'id' => "editfeeform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="eid"  name="eid" >
                        </div>
                    </div>
		             <div class="col-md-12">
                        <div class="form-group">   
                            <label>Year</label>
                           <div class="input-group date" id="datetimepicker_year3" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker_year3"  required name="start_year" id="select_year2" required/>
                              <div class="input-group-append" data-target="#datetimepicker_year3" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                            
                            <!--<select class="form-control class" id="session" name="class" required>
                                <option value="">Choose Session</option>
                                <?php
                                foreach($session_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                <?php
                                }
                                ?>
                            </select>-->
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <label>Class</label>
                        <div class="form-group">
                            <select class="form-control class" id="class" name="class" required onchange="getstudents(this.value)">
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
                            <label>Select Month</label>
                           <select class="form-control class" id="month" name="frequency" required onchange="getfrequency(this.value)">
                                <option value="">Choose Month</option>
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label>Amount <span id="session_amount"></span></label>
                            <input type="number" class="form-control" id="amount" name="amount"  required placeholder="Amount *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="editfeeerror"></div>
                        <div class="success" id="editfeesuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editfeebtn" id="editfeebtn">Update</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>
   
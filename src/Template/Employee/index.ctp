
<?php
	$empstatus = array('All Employee','Left Employee','Active Employee');	
	$activemp = 'Active Employee';
?>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading">List Of Employee</h2>
							<?php
							if(!empty($emp_details[0]))
							{ ?>
								<div class="row">
									<?php
									if(in_array(1, $emp_privilages))
									{ ?>
									<h2 class="col-lg-12 align-right"><a href="<?=$baseurl?>employee/add" title="Add" class="btn btn-sm btn-success">Add New</a></h2>
									<?php 
									} 
									if(in_array(75, $emp_privilages)){?>
									<h2 class="col-lg-12 align-right"><a href="<?=$baseurl?>employee/export" title="Export" class="btn btn-sm btn-success mt-1">Export in Excel</a> </h2>
									<?php 
									} 
									if(in_array(74, $emp_privilages)){?>
									<h2 class="col-lg-12 align-right"> <a href="webroot/employee.csv" download class="btn btn-sm btn-success mt-1">Download CSV Format</a></h2>
									<?php 
									} ?>
									<div class="col-lg-2 heading" style="max-width:13% !important; padding-right: 0px !important;"> <h6>Students Filter</h6></div>
									<div class="col-lg-4" style="margin-right: 38px; padding-left: 0px !important;">
										<div class="form-group" style="margin-bottom: -1rem;">
											<input type="radio" name="chngstatus" id="All" class="chngstatus" value="All" > All
											<input type="radio" name="chngstatus" id="Active" class="chngstatus" value="Active" checked> Active
											<input type="radio" name="chngstatus" id="Left" class="chngstatus" value="Left"> Left
											
										</div>
									</div> 
									<?php 
									if(in_array(75, $emp_privilages)){?>
									<h2 class="col-lg-6 align-right"> <a href="javascript:void(0);" class="btn btn-sm btn-success mt-1" data-toggle="modal" data-target="#addemp">Add Employee CSV File</a></h2>
									<?php 
									} ?>
								</div>
		   
		       <?php  }	
			else
			  { ?>
		  
				<div class="row">
					<h2 class="col-lg-12 align-right"><a href="<?=$baseurl?>employee/add" title="Add" class="btn btn-sm btn-success">Add New</a></h2>
					<h2 class="col-lg-12 align-right"><a href="<?=$baseurl?>employee/export" title="Export" class="btn btn-sm btn-success mt-1">Export in Excel</a> </h2>
					<h2 class="col-lg-12 align-right"> <a href="webroot/employee.csv" download class="btn btn-sm btn-success mt-1">Download CSV Format</a></h2>
					<div class="col-lg-2 heading" style="max-width:13% !important; padding-right: 0px !important;"> <h6>Students Filter</h6></div>
					<div class="col-lg-4" style="margin-right: 38px; padding-left: 0px !important;">
						<div class="form-group" style="margin-bottom: -1rem;">
							<input type="radio" name="chngstatus" id="All" class="chngstatus" value="All" > All
							<input type="radio" name="chngstatus" id="Active" class="chngstatus" value="Active" checked> Active
							<input type="radio" name="chngstatus" id="Left" class="chngstatus" value="Left"> Left
							
						</div>
					</div> 
					<h2 class="col-lg-6 align-right"> <a href="javascript:void(0);" class="btn btn-sm btn-success mt-1" data-toggle="modal" data-target="#addemp">Add Employee CSV File</a></h2>
                </div>
			    

                           
			<?php } ?>
	
                       </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem emp_table" id="emp_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Mobile No</th>
                                            <th>Qualifications</th>
                                            <th>Role</th>
                                            <th>Date Of Join</th>
                                            <th>Date Of Birth</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="employeebody" class="modalrecdel">
                                    <?php
                                    foreach($emp_detail as $value){
                                        ?>
                                        <tr>
                                            <td class="width45">
                                            <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span><?=$value['e_code']?></span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value['e_name']?></span>
                                            </td>
                                            
                                            <td>
                                                <span><?=$value['mobile_no']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['quali']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['sclownrlname']?></span>
                                            </td>
                                            <td>
                                                <span><?= date('d-m-Y', strtotime( $value['doj']))?></span>
                                            </td>
                                            <td>
                                                <span><?= date('d-m-Y', strtotime( $value['dob']))?></span>
                                            </td>
                                            <td>
						<?php 
						if(!empty($emp_details[0]))
						{
	
						if(in_array(2, $emp_privilages)){ ?>

                                                <a href="<?=$baseurl?>employee/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary " ><i class="fa fa-edit"></i></a>
						
						<?php }
						if(in_array(3, $emp_privilages)){ ?>

                                                <button type="button" data-id="<?=$value['id']?>" data-url="delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Employee" data-type="confirm"><i class="fa fa-trash-o"></i></button> 

						<?php }
						if(in_array(4, $emp_privilages)){ ?>

                                                <a href="<?=$baseurl?>employee/view/<?= md5($value['id'])?>" title="View" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-primary" ><i class="fa fa-eye"></i></a>
						<?php  }

						}
						else
						{ ?>

						<a href="<?=$baseurl?>employee/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary " ><i class="fa fa-edit"></i></a>

                                                <button type="button" data-id="<?=$value['id']?>" data-url="employee/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Employee" data-type="confirm"><i class="fa fa-trash-o"></i></button>

                                                <a href="<?=$baseurl?>employee/view/<?= md5($value['id'])?>" title="View" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-primary" ><i class="fa fa-eye"></i></a>
						
						<?php } ?>

                                            </td>
                                        </tr>
                                        <?php
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
                
                <div class="col-lg-4 col-md-6">

                </div>
                <div class="col-lg-4 col-md-12">
                    
                </div>
            </div>


        </div>
    </div>



<!------------------ Import Employee --------------------->

    
<div class="modal animated zoomIn" id="addemp" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Import Employee</h6>
            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'import'] , 'id' => "addempcsvform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label>CSV File*</label>
                        <div class="form-group">                                    
                            <input type="file" class="form-control" required  name="file" >
                            <small id="fileHelp" class="form-text text-muted">Only csv format file are allowed</small>
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                        <div class="error" id="empcsverror"></div>
                        <div class="success" id="empcsvsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addempcsvbtn" id="addempcsvbtn">Import</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">CLOSE</button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>


<!------------------ End --------------------->


    


 

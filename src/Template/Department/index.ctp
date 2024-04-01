
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading">Department List</h2>
                            <ul class="header-dropdown">
				<?php 
                        	if(!empty($emp_details[0]))
                        	{
                        	if(in_array(58, $emp_privilages)){ ?>
                                	<li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#adddprt">Add New</a></li>
				<?php  }
                        	}
                        	else
                        	{ ?>
					<li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#adddprt">Add New</a></li>
				<?php } ?>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="department_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th>Name</th>
                                            <th>Created on</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="departmentbody"  class="modalrecdel">
                                    
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>



    <!------------------ Add Department  --------------------->

    
<div class="modal animated zoomIn" id="adddprt" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Add Department</h6>
		<button type="button" class="close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
         	</button>

            </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'adddprt'] , 'id' => "adddprtform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <div class="form-group">                                    
                            <input type="text" class="form-control" required name="dprt_name" placeholder="Department Name">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="dprterror"></div>
                        <div class="success" id="dprtsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary adddprtbtn" id="adddprtbtn">Save</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">CLOSE</button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>



    <!------------------ Edit Department --------------------->

    
<div class="modal animated zoomIn" id="editdprt" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Update Department</h6>
		<button type="button" class="close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
         	</button>

            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editdprt'] , 'id' => "editdprtform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <div class="form-group">                                    
                            <input type="text" class="form-control" id="dprt_name" required name="dprt_name" placeholder="Department Name">
                            <input type="hidden" id="id"  name="id" >
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="edprterror"></div>
                        <div class="success" id="edprtsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editdprtbtn" id="editdprtbtn">Update</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">CLOSE</button>
                    </div>
        
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>
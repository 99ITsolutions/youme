<div class="row clearfix">
	<div class="col-lg-12">
		<div class="card">
			<div class="header">
				<h2 class="heading">Fee Structure List</h2>
				<ul class="header-dropdown">
					<?php 
					if(!empty($emp_details[0]))
					{
						if(in_array(101, $emp_privilages)){ ?>
							<li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addfeeset">Add New</a></li>
							<li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#copyclass">Copy Class Structure</a></li>
						<?php  }
					}
					else
					{ ?>        	
						<li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addfeeset">Add New</a></li>
						<li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#copyclass">Copy Class Structure</a></li>
					<?php } ?>
				</ul>
			</div>
			<div class="body">
				<div class="table-responsive">
					<table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="feesetup_table" data-page-length='50'>
						<thead class="thead-dark">
							<tr>
								<th>
									<label class="fancy-checkbox">
										<input class="select-all" type="checkbox" name="checkbox">
										<span></span>
									</label>
								</th> 
								<th>Class</th>
								<th>Created on</th>
								<th>Action</th>
                            </tr>
						</thead>
						<tbody>
							<?php
                            foreach($feesetup_details as $value){
							?>
								<tr>
									<td class="width45">
										<label class="fancy-checkbox">
											<input class="checkbox-tick" type="checkbox" name="checkbox">
											<span></span>
										</label>
									</td>

									<td>
										<span class="mb-0 font-weight-bold"><?=$value['class']['c_name']." - ".$value['class']['c_section']?></span>
									</td>

                                    <td><?=date('d-m-Y', $value['added_date'])?></td>

                                    <td>
									<?php 
										if(!empty($emp_details[0]))
										{
											if(in_array(102, $emp_privilages))
											{ ?>						
												<a href="<?=$baseurl?>feesetup/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary " ><i class="fa fa-edit"></i></a>
											<?php 
											}
											if(in_array(103, $emp_privilages))
											{ ?>
                                                <button type="button" data-url="feesetup/delete" data-id="<?=$value['id']?>" data-str="Fee setup" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
					
											<?php  
											}
										}
										else
										{ ?>
											<!-- <button type="button" data-id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary editfeesets" data-toggle="modal"  data-target="#editfeeset" title="Edit"><i class="fa fa-edit"></i></button> -->
											
											<a href="<?=$baseurl?>feesetup/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary " ><i class="fa fa-edit"></i></a>

                                            <button type="button" data-url="feesetup/delete" data-id="<?=$value['id']?>" data-str="Fee setup" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>

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







<!------------------ Duplicate class structure  --------------------->    

<div class="modal animated zoomIn" id="copyclass" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Duplicate Fee Structure</h6>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
				<?php	echo $this->Form->create(false , ['url' => ['action' => 'duplicatefeestructure'] , 'id '=>'duplicatefeestructureform' ,'method' => "post"  ]); ?>

				<div class="row clearfix">

					<div class="col-md-12">
                        <div class="form-group">
                            <small id="fileHelp" class="form-text text-muted">Session Class</small> 
                            <select class="form-control class" id="class_whole" name="sessionclass" required >
                                <option value="">Choose One</option>
                                <?php
                                foreach($class_details as $val){
                                ?>
									<option  value="<?=$val['id']?>" ><?php echo $val['c_name']."-".$val['c_section'];?> </option>
                                <?php
                                }
                                ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <small id="fileHelp" class="form-text text-muted">Select structure Class</small> 
                            <select class="form-control class_list" id="class_list" name="structureclass" required >
                                <option value="">Choose One</option>
                                <?php
								foreach($feesetup_details as $val){
								?>
									<option  value="<?=$val['class']['id']?>" ><?php echo $val['class']['c_name']."-".$val['class']['c_section'];?> </option>
                                <?php
                                }
                                ?>
                            </select> 
                        </div>
                    </div>
					
					
                    <div class="col-md-12">
                        <div class="error" id="feeerror"></div>
                        <div class="success" id="feesuccess"></div>
                    </div>
                    <div class="button_row" >
						<hr>
						<button type="submit" class="btn btn-primary" id="duplicatefeestbtn">Save</button>
						<button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">CLOSE</button>
                    </div>    
					<?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!------------------ Add Fee Setup  --------------------->    

<div class="modal animated zoomIn" id="addfeeset" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Add Fee Structure</h6>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
				<?php	echo $this->Form->create(false , ['url' => ['action' => 'addfeeset'] , 'id '=>'addfeesetform' ,'method' => "post"  ]); ?>

				<div class="row clearfix">  
                    <!--<div class="col-md-12">
                        <div class="form-group">
                            <small id="fileHelp" class="form-text text-muted">Session Name</small>
                            <?php
                                $starting_year = 2019;
                                $current_year = date('Y')*1;
                                $nxt_year = 1 + $starting_year;   
                                echo '<select name="sess_name" class="form-control sess_name" required>';
                                echo '<option value="">Select One</option>';
                                do {

                                    echo '<option value="'.$starting_year."-".$nxt_year.'">'.$starting_year."-".$nxt_year.'</option>';
                                    $starting_year++;
                                    $nxt_year++;
                                }
                                while ($current_year >= $starting_year);
                                echo '</select>';
                            ?>  
                        </div>
                    </div>  --> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <small id="fileHelp" class="form-text text-muted">Session Class</small> 
                            <select class="form-control class" id="class" name="class" required >
                                <option value="">Choose One</option>
                                <?php
                                foreach($class_details as $val){
                                ?>
									<option  value="<?=$val['id']?>" ><?php echo $val['c_name']."-".$val['c_section'];?> </option>
                                <?php
                                }
                                ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="feeerror"></div>
                        <div class="success" id="feesuccess"></div>
                    </div>
                    <div class="button_row" >
						<hr>
						<button type="submit" class="btn btn-primary" id="addfeesetbtn">Next</button>
						<button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">CLOSE</button>
                    </div>    
					<?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>




<!------------------ Edit Fee Setup --------------------->

<div class="modal animated zoomIn" id="editfeeset"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Update Fee Structure</h6>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>
            <div class="modal-body">
				<?php   echo $this->Form->create(false , ['url' => ['action' => 'editfeeset'] , 'id' => "editfeesetform" , 'method' => "post"  ]); ?>
					<div class="row clearfix">
						<input type="hidden" id="id"  name="id" >
						<div class="col-md-12">
							<div class="form-group">
								<small id="fileHelp" class="form-text text-muted">Session Name</small>  
								<?php

									$starting_year = 2019;
									$current_year = date('Y')*1;
									$nxt_year = 1 + $starting_year;

									echo '<select name="sess_name" id="sess_name" class="form-control sess_name" required>';
									echo '<option value="">Select One</option>';

									do 
									{
										echo '<option value="'.$starting_year."-".$nxt_year.'">'.$starting_year."-".$nxt_year.'</option>';
										$starting_year++;
										$nxt_year++;
									}
									while ($current_year >= $starting_year);
									echo '</select>';
								?> 

							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<small id="fileHelp" class="form-text text-muted">Session Class</small> 
								<select class="form-control class" id="classid" name="class" required>
									<option value="">Choose One</option>
									<?php
									foreach($class_details as $val){
									?>
										<option  value="<?=$val['id']?>" ><?php echo $val['c_name']." ".$val['c_section'];?> </option>
									<?php
									}
									?>
								</select> 
							</div>
						</div>
						<div class="col-md-12">
							<div class="error" id="feeerror"></div>
							<div class="success" id="feesuccess"></div>
						</div>
						<div class="button_row" >
							<hr>
							<button type="submit" class="btn btn-primary" id="editfeesetbtn">Update</button>
							<button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">CLOSE</button>
						</div>
					<?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
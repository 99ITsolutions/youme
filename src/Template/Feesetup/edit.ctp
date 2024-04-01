         



            <div class="row clearfix">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="header">

                            <h2 class="heading">Edit Fee Structure</h2>

                            <ul class="header-dropdown">

                                <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addfeedet">Add Head</a></li>

                            </ul>

                        </div>

						<?php	echo $this->Form->create(false , ['url' => ['action' => 'editfeeset'] , 'id' => "editfeesetform" , 'method' => "post"  ]); ?>

						<div class="col-md-12">

							<div class="error" id="ufeeerror"></div>

							<div class="success" id="ufeesuccess"></div>

						</div>

						<div class="container row">

                        <?php foreach($feeset_details as $feeset):?>

						 <input type="hidden" value="<?= $feeset['id'] ?>" id="setupid"  name="setupid" >
		<!--
                        <div class="col-md-3">

                            <div class="form-group">

                                <small id="fileHelp" class="form-text text-muted">Session Name</small>

                                <?php

                                    $starting_year = 2019;

                                    $current_year = date('Y')*1;

                                    $nxt_year = 1 + $starting_year;

                                    

                                    echo '<select name="sess_name" id="sess_name" class="form-control sess_name" >';

                                    echo '<option value="">Choose One</option>';

                                    do {

                                        echo '<option value="'.$starting_year."-".$nxt_year.'" '.(($starting_year."-".$nxt_year == $feeset['sess_name'])?'selected="selected"':"").' >'.$starting_year."-".$nxt_year.'</option>';

                                        $starting_year++;

                                        $nxt_year++;

                                    }



                                    while ($current_year >= $starting_year);

                                    echo '</select>';

                                ?>                                    

                            </div>

							

                        </div>

			-->

						

                    

                        <div class="col-md-3">

                            <div class="form-group">   

                                <small id="fileHelp" class="form-text text-muted">Class</small>                                 

                                <select class="form-control class " id="class" name="class"  >

                                    <option value="">Choose One</option>

                                    <?php

                                    foreach($class_details as $val){

                                    ?>

                                      <option  value="<?=$val['id']?>" <?php if($feeset['class'] == $val['id']) { ?> selected="true" <?php } ?> ><?php echo $val['c_name']."-".$val['c_section'];?> </option>

                                    <?php

                                    }

                                    ?>

                                </select> 

                            </div>

                        </div>

						<?php endforeach; ?>

							<div class="col-md-2 mt-3">

							<button type="submit" class="btn btn-primary" id="editfeesetbtn">Update</button>

							</div>

						</div>

						 <?php echo $this->Form->end(); ?>

						 

						 

                        <div class="body">

                            <div class="table-responsive">

                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="feedet_table" data-page-length='50'>

                                    <thead class="thead-dark">

                                        <tr>

                                            <th>

                                                <label class="fancy-checkbox">

                                                    <input class="select-all" type="checkbox" name="checkbox">

                                                    <span></span>

                                                </label>

                                            </th>

                                            <th>Head Name</th>

                                            <th>Amount</th>

                                            <th>Action</th>

                                        </tr>

                                    </thead>

                                    <tbody id="feedetailbody"  class="modalrecdel">

                                    <?php foreach ($feedetail_details as $value) { ?>

                    

										<tr>

											<td class="width45">

											<label class="fancy-checkbox">

													<input class="checkbox-tick" type="checkbox" name="checkbox">

													<span></span>

												</label>

											</td>

											<td>

												<span class="mb-0 font-weight-bold"><?= $value['feehead']['head_name'] ?></span>

											</td>

											<td>

												<span><?= $value['amount'] ?></span>

											</td>

											<td>

												<button type="button" data-id='<?= $value['id'] ?>' class="btn btn-sm btn-outline-secondary editfeedets" data-toggle="modal"  data-target="#editfeedet" title="Edit"><i class="fa fa-edit"></i></button>

											

												<button type="button" data-url="feesetup/deleteDtl" data-id='<?= $value['id'] ?>' data-str="Fee detail" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>

											</td>

										</tr>



											

										<?php } ?>

										

                                       

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>





        </div>

    </div>







    <!------------------ Add Fee Detail  --------------------->



    

<div class="modal animated zoomIn" id="addfeedet"  role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header header">

                <h6 class="title" id="defaultModalLabel">Add Fee Detail</h6>

		<button type="button" class="close" data-dismiss="modal">

  		   <span aria-hidden="true">&times;</span>

         	</button>



            </div>

            <div class="modal-body">

            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addfeedet'] , 'id' => "addfeedetform" , 'method' => "post"  ]); ?>



                <div class="row clearfix">

                     <?php foreach($feeset_details as $feeset):?>

                    <input type="hidden" value="<?= $feeset['id'] ?>" name="setupid" >

					<?php endforeach; ?>

                    <div class="col-md-12">

                        <div class="form-group">

                            <small id="fileHelp" class="form-text text-muted">Head Name</small>

                            <select class="form-control headname"  name="head_name" required >

                                <option value="">Choose One</option>

                                <?php

                                foreach($feehead_details as $val){

                                ?>

                                  <option  value="<?=$val['id']?>" ><?php echo $val['head_name'];?> </option>

                                <?php

                                }

                                ?>

                            </select>                                     

                        </div>

                    </div>

                    

                    <div class="col-md-12">

                        <div class="form-group">

                            <small id="fileHelp" class="form-text text-muted">Amount</small>                                    

                            <input type="text" class="form-control" required name="amount" placeholder="Amount">

                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="error" id="feeerror"></div>

                        <div class="success" id="feesuccess"></div>

                    </div>

                    <div class="button_row" >

                    <hr>

                    <button type="submit" class="btn btn-primary" id="addfeedetbtn">Save</button>

                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">CLOSE</button>

                    </div>

                    

                   <?php echo $this->Form->end(); ?>

                   

                </div>

            </div>

             

        </div>

    </div>

</div>







    <!------------------ Edit Fee Detail --------------------->



    

<div class="modal animated zoomIn" id="editfeedet"  role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header header">

                <h6 class="title" id="defaultModalLabel">Update Fee Detail</h6>

		<button type="button" class="close" data-dismiss="modal">

  		   <span aria-hidden="true">&times;</span>

         	</button>



            </div>

            <div class="modal-body">

            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editfeedet'] , 'id' => "editfeedetform" , 'method' => "post"  ]); ?>



                <div class="row clearfix">

                    

                   

                    <input type="hidden" id="id"  name="id" >

                        

                    <div class="col-md-12">

                        <div class="form-group">

                            <small id="fileHelp" class="form-text text-muted">Head Name</small>

                            <select class="form-control headname" id="fee_h_id" name="head_name" required>

                                <option value="">Choose One</option>

                                <?php

                                foreach($feehead_details as $val){

                                ?>

                                  <option  value="<?=$val['id']?>" ><?php echo $val['head_name'];?> </option>

                                <?php

                                }

                                ?>

                            </select>                                     

                        </div>

                    </div>

                    

                    <div class="col-md-12">

                        <div class="form-group">  

                            <small id="fileHelp" class="form-text text-muted">Amount</small>                                    

                            <input type="text" class="form-control" required id="amountno" name="amount" placeholder="Amount">

                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="error" id="feeerror"></div>

                        <div class="success" id="feesuccess"></div>

                    </div>

                    <div class="button_row" >

                    <hr>

                    <button type="submit" class="btn btn-primary" id="editfeedetbtn">Update</button>

                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">CLOSE</button>

                    </div>

        

                   <?php echo $this->Form->end(); ?>

                   

                </div>

            </div>

             

        </div>

    </div>

</div>
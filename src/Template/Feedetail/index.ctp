<?php 
    $feestpid = $_SESSION['fee_detailingids'];
    $feesclsid = $feeset_details[0]['class_id'];
    $totalamt = $feeset_details[0]['amount'];
    $sessionid = $feeset_details[0]['start_year'];
    
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '345') { $amtlbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '2028') { $chosesesslbl = $langlbl['title'] ; }
        
    } 
?>
           

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1542') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addfeedet"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1543') { echo $langlbl['title'] ; } } ?></a></li>
                            <li><a href="<?= $baseurl ?>fees" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
                    </div>
                    <input type="hidden" value="<?= $feestpid ?>" id="setupid" >
                    <input type="hidden" value="<?= $_GET['id'] ?>" id="getsetupid" >
                    <!--<div class="col-md-12">
                        <div class="form-group">   
                            <span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '337') { echo $langlbl['title'] ; } } ?>: </b></span>  
                            <span><?= $classesname ?></span>
                            
                        </div>
                    </div>-->
                    <div class="col-md-12">
                        <div class="form-group">   
                            <span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '343') { echo $langlbl['title'] ; } } ?>:  </b></span>      
                            <span>$</span><span id="totalAmt"><?=  $totalamt ?></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">  
                            <span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1544') { echo $langlbl['title'] ; } } ?>: </b></span>
                            <span>$</span><span id="totalleft"><?= $amtleft ?></span>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem feedet_table" id="feedet_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th>
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                            </label>
                                        </th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1545') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '322') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '333') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                </thead>
                                <tbody id="feedetailbody"  class="modalrecdel">
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
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1546') { echo $langlbl['title'] ; } } ?></h6>
		<button type="button" class="close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
         	</button>

            </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addfeedet'] , 'id' => "addfeedetform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <input type="hidden" value="<?= $feestpid ?>" name="class" >

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1545') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control headname"  name="head_name" required >
                                <option value="">Choose One</option>
                                <?php
                                foreach($feehead_details as $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo ucwords($val['head_name']);?> </option>
                                <?php
                                }
                                ?>
                            </select>                                     
                        </div>
                    </div>
                    <input type="hidden" name="fee_s_id" value="<?= $feestpid ?>" >
                    <input type="hidden" name="session_id" value="<?= $sessionid ?>" >
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '322') { echo $langlbl['title'] ; } } ?></label>                                    
                            <input type="text" class="form-control" required name="amount" id="addheadamt" placeholder="<?= $amtlbl ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="feeerror"></div>
                        <div class="success" id="feesuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary" id="addfeedetbtn"><th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '396') { echo $langlbl['title'] ; } } ?></th></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></th></button>
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
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1547') { echo $langlbl['title'] ; } } ?></h6>
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
                            <small id="fileHelp" class="form-text text-muted"><th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1545') { echo $langlbl['title'] ; } } ?></th></small>
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
                    <input type="hidden" name="fee_s_id" value="<?= $feestpid ?>" >
                    <input type="hidden" id="fee_s" value="<?= $feestpid ?>" >
                    <input type="hidden" name="session_id" value="<?= $sessionid ?>" >
                    <div class="col-md-12">
                        <div class="form-group">  
                            <small id="fileHelp" class="form-text text-muted"><th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '322') { echo $langlbl['title'] ; } } ?></th></small>                                    
                            <input type="text" class="form-control" required id="amountno" name="amount" placeholder="<?= $amtlbl ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="efeeerror"></div>
                        <div class="success" id="efeesuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary" id="editfeedetbtn"><th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></th></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></th></button>
                    </div>
        
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>
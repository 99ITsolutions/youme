<?php $statusarray = array('Inactive','Active' ); ?>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <h2 class="heading col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '11') { echo $langlbl['title'] ; } } ?></h2>
                                <h2 class="align-right col-md-6">
                                    <?php if(!empty($sclsub_details[0]))
                                    { 
                                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                        if(in_array("7", $roles)) { ?>
                                            <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addclass"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '113') { echo $langlbl['title'] ; } } ?></a>
                                        <?php }
                                    } else { ?>
                                        <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addclass"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '113') { echo $langlbl['title'] ; } } ?></a>
                                    <?php } ?>
                                    
                                    <a href="<?=$baseurl ?>ClassesSubjects" title="Back" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                                </h2>
                            </div>
                            <div class="row col-md-12">
                                <div class="col-sm-3">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2167') { echo $langlbl['title'] ; } } ?></label>
                                    <?php
                                    //$subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                                    //print_r($subpriv);?>
                                    <div class="form-group">                                    
                                        <select class="form-control grades" id="grades" name="grades" onchange="getscl_sctn1(this.value)">
                                            <option value="">Choose Grades</option>
                                            <?php
                                            foreach($grade_details as $key => $val){
                                                $tchrid = '';
                                                if($tchrid != '' && ($val['id'] == $tchrid))
                                                {
                                                    $sel = "selected";
                                                }
                                                else
                                                {
                                                    $sel = "";
                                                }
                                                if(!empty($sclsub_details[0]))
                                                { 
                                                    //echo "subadmin";
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
                                                    //print_r($subpriv);
                                                    $clsmsg = trim($clsmsg);
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
                                                if($show == 1) { ?>
                                                    <option  value="<?=$val['c_name']?>" <?= $sel ?> ><?php echo $val['c_name'] ;?> </option>
                                            <?php } } ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '100') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">                                    
                                         <select class="form-control sections" id="sections" name="sections" onchange="getclasses_grades1(this.value)">
                                             <option value="">Choose Section</option>
                                         </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">  
                                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '9') { echo $langlbl['title'] ; } } ?></label>
                                        <select class="form-control classes" id="aclass" name="classes" onchange="getclassessections1(this.value)">
                                             <option value="">Choose Classes</option>
                                         </select>
                                    </div>
                                </div>
                                <!--<div class="col-sm-1">
                                    <button type="submit" class="btn btn-primary addclsbtn mt-4" id="addclsbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
                                </div>-->
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem subjectsclass_table" id="subjectsclass_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '118') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '119') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '120') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '121') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="subjectsclassbody" class="modalrecdel"> 
                                   
				    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
            </div>


        </div>
    </div>

 <!------------------ Add Class Subject--------------------->

    
<div class="modal classmodal animated zoomIn" id="addclass" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                  <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '122') { echo $langlbl['title'] ; } } ?></h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addclssub'] , 'id' => "addclssubform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '123') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select class="form-control class_s" id="class" name="class[]" placeholder="Choose Class" multiple="multiple" >
                                <option value="">Choose Class</option>
                                <?php
                                foreach($class_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'] ."(" . $val['school_sections'].")";?> </option>
                                <?php
                                }
                                ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1015') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select class="form-control js-example-basic-multiple subj_s" multiple="multiple" name="subjects[]" id="subjects" placeholder="Choose Subjects">
                                <option value="">Choose Subjects</option>
                                <?php
                                foreach($subject_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['subject_name'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="subclserror"></div>
                        <div class="success" id="subclssuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addclsbtn" id="addsubclsbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '124') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '125') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              



    <!------------------ Edit Class Subjects--------------------->

    
<div class="modal classmodal  animated zoomIn" id="editsubjectclass"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1523') { echo $langlbl['title'] ; } } ?></h6>
		<button type="button" class="close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
         	</button>
 	
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editclssub'] , 'id' => "editclssubform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" id="eid"  name="eid" >
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '123') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select class="form-control eclass_s" id="eclass" name="eclass[]" multiple="multiple"  placeholder="Choose Class">
                                <option value="">Choose Class</option>
                                <?php
                                foreach($class_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'] ."(" . $val['school_sections'].")";?> </option>
                                <?php
                                }
                                ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1015') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <select class="form-control js-example-basic-multiple esubj_s" multiple="multiple" name="esubjects[]" id="esubjects" placeholder="Choose Subjects">
                                <option value="">Choose Subjects</option>
                                <?php
                                foreach($subject_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['subject_name'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="editclssuberror"></div>
                        <div class="success" id="editclssubsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editclssubbtn" id="editclssubbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '125') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

<!------------------ view Subjects --------------------->

<div class="modal classmodal animated zoomIn" id="viewsub" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">View Subjects of <span>Class: <span id="clsname"></span></span></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                <div class="row clearfix container">
                    <div id="all_subjects">
                        
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>              

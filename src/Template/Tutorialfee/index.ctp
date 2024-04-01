<?php
    $statusarray = array('Inactive','Active' );
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '1018') { $yrly = $langlbl['title'] ; }  
        if($langlbl['id'] == '1019') { $hlfyr = $langlbl['title'] ; } 
        if($langlbl['id'] == '1020') { $qurtly = $langlbl['title'] ; }
        if($langlbl['id'] == '1021') { $monthl = $langlbl['title'] ; } 
        if($langlbl['id'] == '1800') { $wekly = $langlbl['title'] ; } 
        
    }
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '997') { echo $langlbl['title'] ; } } ?>  </h2>
                <ul class="header-dropdown">
	                <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfeestruc"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1008') { echo $langlbl['title'] ; } } ?></a></li>
	                <!--<li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#view_edit">View/edit student fee</a></li>-->
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
                            
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="tut_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1037') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1003') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1004') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1005') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="tutbody" class="modalrecdel"> 
                        <?php
                                    $n=1;
                                    foreach($tutorial_details as $value){
                                        if($value->frequency == "yearly") { $freq = $yrly; }
                                        elseif($value->frequency == "half yearly") { $freq = $hlfyr; }
                                        elseif($value->frequency == "quarterly") { $freq = $qurtly; }
                                        elseif($value->frequency == "monthly") { $freq = $monthl; }
                                        elseif($value->frequency == "weekly") { $freq = $wekly; }
                                        
                                        ?>
                                        <tr>
                                           <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value->class['c_name']."-".$value->class['c_section']."(".$value->class['school_sections'].")"?></span>
                                            </td>
                                            <td>
                                                <span><?= $value->subjects['subject_name']; ?></span>
                                            </td>
                                            <td>
                                                <span><?= ucwords($freq); ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value->session['startyear'].'-'.$value->session['endyear']; ?></span>
                                            </td>
                                            <td>
                                                <span>$<?= $value['fee']; ?></span>
                                            </td>
                                            
                                            <td>
                                                
                                                <button type="button" data-id='<?= $value['id'] ?>' class="btn btn-sm btn-outline-secondary editstruc" data-toggle="modal"  data-target="#editstruc" title="Edit"><i class="fa fa-edit"></i></button>
                                                
                                                <button type="button" data-id="<?=$value['id']?>" data-url="tutorialfee/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Tutoring Fees" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                <!--<a href="<?=$baseurl?>tutorialfee/view/<?= md5($value['id'])?>" title="View" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-eye"></i></a>-->
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
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1011') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addtut'] , 'id' => "add_fee_tut" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1012') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control class_s" id="eclass" name="class" placeholder="Choose Class" required onchange="getsubcls(this.value, this.id)">
                                <option value="">Choose Class</option>
                                <?php foreach($empcls_details as $empdtl)
        	                    { 
        	                        /*$grades = explode(",", $empdtl['gradesName']);
        	                        $subjects = explode(",", $empdtl['subjectName']);
        	                        $gradeids = explode(",", $empdtl['grades']);
        	                        $subjectids = explode(",", $empdtl['subjects']);
        	                        
        	                        foreach($grades as $key=>$val):*/
            	                    ?>
            	                        <option  value="<?= $empdtl['class']['id'] ?>" ><?= $empdtl['class']['c_name']."-".$empdtl['class']['c_section']." (".$empdtl['class']['school_sections'].")" ?> </option>
                                        
                                    <?php
                                    //endforeach;
        	                    } ?>
                                
                                
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1014') { echo $langlbl['title'] ; } } ?></label>
                           <!--<select class="form-control subj_s" name="subjects" id="esubjects" placeholder="Choose Subjects" required>
                                <option value="">Choose Subjects</option>
                                <?php foreach($employees_details as $empdtl)
        	                    { 
        	                        $grades = explode(",", $empdtl['gradesName']);
        	                        $subjects = explode(",", $empdtl['subjectName']);
        	                        $gradeids = explode(",", $empdtl['grades']);
        	                        $subjectids = explode(",", $empdtl['subjects']);
        	                        
        	                        foreach($grades as $key=>$val):
            	                    ?>
            	                        <option  value="<?= $subjectids[$key] ?>" ><?= $subjects[$key] ?> </option>
                                        
                                    <?php
                                    endforeach;
        	                    } ?>
                            </select>-->
                            <select class="form-control subj_s" name="subjects" id="esubjects" placeholder="Choose Subjects" required>
                                
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1016') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control js-states " id="afrequency" required name="frequency" >
                                <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1017') { echo $langlbl['title'] ; } } ?></option>
                                <option value="yearly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1018') { echo $langlbl['title'] ; } } ?></option>
                                <option value="half yearly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1019') { echo $langlbl['title'] ; } } ?></option>
                                <option value="quarterly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1020') { echo $langlbl['title'] ; } } ?></option>
                                <option value="monthly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1021') { echo $langlbl['title'] ; } } ?></option>
                                <option value="weekly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1800') { echo $langlbl['title'] ; } } ?></option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1022') { echo $langlbl['title'] ; } } ?></label>
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
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1024') { echo $langlbl['title'] ; } } ?> *</label>
                            <input type="number" class="form-control" name="amount"  required placeholder="Amount *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="tuterror"></div>
                        <div class="success" id="tutsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addtutbtn" id="addtutbtn" style="margin-right: 10px;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1025') { echo $langlbl['title'] ; } } ?></button>
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
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1508') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'edittut'] , 'id' => "edittutform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="eid"  name="eid" >
                        </div>
                    </div>
		            <div class="col-md-12">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1012') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control class_s" id="class" name="class" placeholder="Choose Class" required onchange="getsubcls(this.value, this.id)">
                                <option value="">Choose Class</option>
                                <?php foreach($empcls_details as $empdtl)
        	                    { 
        	                        /*$grades = explode(",", $empdtl['gradesName']);
        	                        $subjects = explode(",", $empdtl['subjectName']);
        	                        $gradeids = explode(",", $empdtl['grades']);
        	                        $subjectids = explode(",", $empdtl['subjects']);
        	                        
        	                        foreach($grades as $key=>$val):*/
            	                    ?>
            	                        <option  value="<?= $empdtl['class']['id'] ?>" ><?= $empdtl['class']['c_name']."-".$empdtl['class']['c_section']." (".$empdtl['class']['school_sections'].")" ?> </option>
                                        
                                    <?php
                                    //endforeach;
        	                    } ?>
                                
                                
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                             <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1014') { echo $langlbl['title'] ; } } ?></label>
                           <select class="form-control subj_s" name="subjects" id="subjects" placeholder="Choose Subjects" required>
                                <!--<option value="">Choose Subjects</option>
                                <?php foreach($employees_details as $empdtl)
        	                    { 
        	                        $grades = explode(",", $empdtl['gradesName']);
        	                        $subjects = explode(",", $empdtl['subjectName']);
        	                        $gradeids = explode(",", $empdtl['grades']);
        	                        $subjectids = explode(",", $empdtl['subjects']);
        	                        
        	                        foreach($grades as $key=>$val):
            	                    ?>
            	                        <option  value="<?= $subjectids[$key] ?>" ><?= $subjects[$key] ?> </option>
                                        
                                    <?php
                                    endforeach;
        	                    } ?>-->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1016') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control js-states " id="efrequency" required name="frequency" >
                                <option value="">Choose Frequency</option>
                                <option value="yearly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1018') { echo $langlbl['title'] ; } } ?></option>
                                <option value="half yearly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1019') { echo $langlbl['title'] ; } } ?></option>
                                <option value="quarterly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1020') { echo $langlbl['title'] ; } } ?></option>
                                <option value="monthly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1021') { echo $langlbl['title'] ; } } ?></option>
                                <option value="weekly"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1800') { echo $langlbl['title'] ; } } ?></option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1022') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control session" id="newselect_year" name="start_year" required>
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
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1024') { echo $langlbl['title'] ; } } ?> *</label>
                            <input type="number" class="form-control" id="eamount" name="amount"  required placeholder="Amount *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="edittuterror"></div>
                        <div class="success" id="edittutsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary edittutbtn" id="edittutbtn" style="margin-right:15px"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
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
                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']."(".$val['school_sections'].")";?> </option>
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


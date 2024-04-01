<style>
	    .bg-dash
	    {
	        background-color:#242E3B !important;
	    }
	</style>

    <div class="row clearfix ">
        <div class="card ">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1028') { echo $langlbl['title'] ; } } ?></h2>    
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php   echo $this->Form->create(false , ['url' => ['action' => 'students'] , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="container row ">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></label>
                                <!--<select name="class_list" id="class_list" class="form-control class_list" required onchange="getsubjectattendance(this.value);">-->
                                <?php if(!empty($classid)) { ?>
                                    <select name="class_list" id="class_list" class="form-control class_list" >
                                        <option value="">Choose Class</option>
                                        <?php foreach($class_details as $clsdtl)
                                        {
                    	                    ?>
                    	                    <option value="<?= $clsdtl['id'] ?>" <?php if($clsdtl['id'] == $classid) { echo "selected"; } ?>><?= $clsdtl['c_name'] ."-". $clsdtl['c_section'] ."(". $clsdtl['school_sections'] .")" ?></option>
                                            <?php
                                        } ?>
                                    </select>
                                <?php } else { ?>
                                    <select name="class_list" id="class_list" class="form-control class_list">
                                        <option value="">Choose Class</option>
                                        <?php foreach($class_details as $clsdtl)
                                        {
                    	                    ?>
                    	                    <option value="<?= $clsdtl['id'] ?>"><?= $clsdtl['c_name'] ."-". $clsdtl['c_section'] ."(". $clsdtl['school_sections'] .")"?></option>
                                            <?php
                                        } ?>
                                    </select>
                                <?php } ?>
                                
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?></label>
                                <?php if(!empty($subjectid)) { ?>
                                    <select name="subject_list" id="subject_list" class="form-control subject_list" >
                                        <option value="">Choose Subject</option>
                                        <?php foreach($subject_details as $sub)
                                        {
                    	                    ?>
                    	                    <option value="<?= $sub['id'] ?>" <?php if($sub['id'] == $subjectid) { echo "selected"; } ?>><?= $sub['subject_name'] ?></option>
                                            <?php
                                        } ?>
                                    </select>
                                <?php } else { ?>
                                    <select name="subject_list" id="subject_list" class="form-control subject_list">
                                        <option value="">Choose Subject</option>
                                        <?php foreach($subject_details as $sub)
                                        {
                    	                    ?>
                    	                    <option value="<?= $sub['id'] ?>"><?= $sub['subject_name'] ?></option>
                                            <?php
                                        } ?>
                                    </select>
                                <?php } ?>
                                
                            </div>
                        </div>
                        
                        <div class="col-sm-1">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary choosedatebtn" id="choosedatebtn" style="margin-top:1.6rem!important"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1033') { echo $langlbl['title'] ; } } ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="sclattndnc_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?></th>                                        
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1036') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1037') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1038') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1039') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1040') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1044') { echo $langlbl['title'] ; } } ?>e</th>
                                    </tr>
                                </thead>
                                <tbody id="sclattndnc_body" class="modalrecdel"> 
                                    <?php if(!empty($fee_details)) {
                                    foreach($fee_details as $value)
                                    {
                                        ?>
                                        <tr>
                                            <td><?= $value->class['c_name'].'-'.$value->class['c_section']."(". $value->class['school_sections'] .")"?></td>
                                            <td><?= $value->subjects['subject_name'] ?></td>
                                            <td><?= $value->student['f_name'].' '.$value->student['l_name']  ?></td>
                                            <td><?= $value->frequency ?></td>
                                            <td>$<?= $value->fee ?></td>
                                            <td><?= $value->username ?></td>
                                            <td><?= $value->password ?></td>
                                            <td><?= date('M d, Y',$value->submission_date ) ?></td>
                                        </tr>
                                        <?php
                                    } }
                                    ?>
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
	    </div>
	</div>
	<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>
 

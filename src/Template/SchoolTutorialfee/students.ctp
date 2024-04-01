<style>
	    .bg-dash
	    {
	        background-color:#242E3B !important;
	    }
	</style>

    <div class="row clearfix ">
        <div class="card ">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '508') { echo $langlbl['title'] ; } } ?></h2>     
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
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '355') { echo $langlbl['title'] ; } } ?></label>
                                <!--<select name="class_list" id="class_list" class="form-control class_list" required onchange="getsubjectattendance(this.value);">-->
                                <?php if(!empty($classid)) { ?>
                                    <select name="class_list" id="class_list" class="form-control class" onchange="students_by_class(this.value); ">
                                        <option value="">Choose Class</option>
                                        <?php foreach($class_details as $clsdtl)
                                        {
                                            if(!empty($sclsub_details[0]))
                                            { 
                                                //echo "subadmin";
                                                if(strtolower($clsdtl['school_sections']) == "creche" || strtolower($clsdtl['school_sections']) == "maternelle") {
                                                    $clsmsg = "kindergarten";
                                                }
                                                elseif(strtolower($clsdtl['school_sections']) == "primaire") {
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
                                            if($show == 1) {
                    	                    ?>
                    	                    <option value="<?= $clsdtl['id'] ?>" <?php if($clsdtl['id'] == $classid) { echo "selected"; } ?>><?= $clsdtl['c_name'] ."-". $clsdtl['c_section']."(" . $clsdtl['school_sections'].")"; ?></option>
                                            <?php
                                            }
                                        } ?>
                                    </select>
                                <?php } else { ?>
                                    <select name="class_list" id="class_list" class="form-control class" onchange="students_by_class(this.value);" >
                                        <option value="">Choose Class</option>
                                        <?php foreach($class_details as $clsdtl)
                                        {
                                            if(!empty($sclsub_details[0]))
                                            { 
                                                //echo "subadmin";
                                                if($clsdtl['school_sections'] == "Creche" || $clsdtl['school_sections'] == "Maternelle") {
                                                    $clsmsg = "kindergarten";
                                                }
                                                elseif($clsdtl['school_sections'] == "Primaire") {
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
                                            if($show == 1) {
                    	                    ?>
                    	                    <option value="<?= $clsdtl['id'] ?>"><?= $clsdtl['c_name'] ."-". $clsdtl['c_section']."(" . $clsdtl['school_sections'].")"; ?></option>
                                            <?php
                                            }
                                        } ?>
                                    </select>
                                <?php } ?>
                                
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '365') { echo $langlbl['title'] ; } } ?></label>
                                <?php if(!empty($subjectid)) { ?>
                                    <select name="subject_list" id="subject_list" class="form-control subj_s" >
                                        <option value="">Choose Subject</option>
                                        <?php foreach($subject_details as $sub)
                                        {
                    	                    ?>
                    	                    <option value="<?= $sub['id'] ?>" <?php if($sub['id'] == $subjectid) { echo "selected"; } ?>><?= $sub->subject_name ?></option>
                                            <?php
                                        } ?>
                                    </select>
                                <?php } else { ?>
                                    <select name="subject_list" id="subject_list" class="form-control subj_s">
                                        <option value="">Choose Subject</option>
                                        <?php foreach($subject_details as $sub)
                                        {
                    	                    ?>
                    	                    <option value="<?= $sub['id'] ?>"><?= $sub->subject_name ?></option>
                                            <?php
                                        } ?>
                                    </select>
                                <?php } ?>
                                
                            </div>
                        </div>
                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '505') { echo $langlbl['title'] ; } } ?></label>
                                 <?php if(!empty($studentid)) { ?>
                                    <select name="student_list" id="list_student" class="form-control studentchose" >
                                        <option value="">Choose Student</option>
                                        <?php foreach($student_details as $stu)
                                        {
                    	                    ?>
                    	                    <option value="<?= $stu['id'] ?>" <?php if($stu['id'] == $studentid) { echo "selected"; } ?>><?= $stu->l_name ?> <?= $stu->f_name ?> (<?= $stu->email ?>)</option>
                                            <?php
                                        } ?>
                                    </select>
                                <?php } else { ?>
                                    <select name="student_list" id="list_student" class="form-control studentchose">
                                        <option value="">Choose Student</option>
                                        
                                    </select>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="col-sm-1">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary choosedatebtn" id="choosedatebtn" style="margin-top:1.6rem!important"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '503') { echo $langlbl['title'] ; } } ?></button>
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
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '355') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '365') { echo $langlbl['title'] ; } } ?></th>                                        
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '534') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '535') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '536') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '537') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '538') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '539') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                </thead>
                                <tbody id="sclattndnc_body" class="modalrecdel"> 
                                    <?php if(!empty($fee_details)) {
                                    foreach($fee_details as $value)
                                    {
                                        if(!empty($sclsub_details[0]))
                                        { 
                                            //echo "subadmin";
                                            if($value->class['school_sections'] == "Creche" || $value->class['school_sections'] == "Maternelle") {
                                                $clsmsg = "kindergarten";
                                            }
                                            elseif($value->class['school_sections'] == "Primaire") {
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
                                        if($show == 1) {
                                        ?>
                                        <tr>
                                            <td><?= $value->class['c_name'].'-'.$value->class['c_section'].'('.$value->class['school_sections'].')'?></td>
                                            <td><?= $value->subjects['subject_name'] ?></td>
                                            <td><?= $value->student['l_name'].' '.$value->student['f_name']  ?></td>
                                            <td><?= $value->frequency ?></td>
                                            <td>$<?= $value->fee ?></td>
                                            <td><?= $value->username ?></td>
                                            <td><?= $value->password ?></td>
                                            <td><?= date('M d, Y',$value->submission_date ) ?></td>
                                        </tr>
                                        <?php
                                        }
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
 
 <script>
     function students_by_class(get_val)
{
    
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
            type:'POST',
            url: baseurl + '/SchoolTutorialfee/subjectstudentbyclass',
            data:'classId='+get_val,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                $( "#list_student" ).html('');
                $( "#list_student" ).append( "<option value=''></option>" );
                $.each(html[0], function(key,value) {
                    $( "#list_student" ).append( "<option value='"+value.id+"'>"+value.l_name+" "+value.f_name+" ("+value.email+" )</option>" );
                     // alert(value.f_name);
                }); 
                $('#subject_list').html('');
                $('#subject_list').html(html[1]).trigger('change.select2');
                
                
            }

        });
    
}
 </script>

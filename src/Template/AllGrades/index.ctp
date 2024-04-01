<style>
.col-md-3 {
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
}
</style>
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '1717') { $lbl1717 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '1243') { $lbl1243 = $langlbl['title'] ; }
    if($langlbl['id'] == '936') { $lbl936 = $langlbl['title'] ; }
    if($langlbl['id'] == '1245') { $lbl1245 = $langlbl['title'] ; }
    if($langlbl['id'] == '2126') { $lbl2126 = $langlbl['title'] ; }
    if($langlbl['id'] == '2127') { $lbl2127 = $langlbl['title'] ; }
    if($langlbl['id'] == '1244') { $exmlabl = $langlbl['title'] ; }
    if($langlbl['id'] == '1742') { $asslabl = $langlbl['title'] ; }
    if($langlbl['id'] == '1584') { $chosvallabl = $langlbl['title'] ; }
    
    if($langlbl['id'] == '1800') { $weekly = $langlbl['title'] ; }
    if($langlbl['id'] == '1801') { $mnthly = $langlbl['title'] ; }
    if($langlbl['id'] == '1802') { $quartly = $langlbl['title'] ; }
    if($langlbl['id'] == '1803') { $hlfyearly = $langlbl['title'] ; }
    if($langlbl['id'] == '1804') { $yearly = $langlbl['title'] ; }
}
?>

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header"> <?php //print_r($subject_details);?>
                <div class=" row">
                    <h2 class="col-md-6 heading text-left"><?= $lbl1717 ?></h2>
                    <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>studentdashboard/studentprofile/" title="Back" class="btn btn-sm btn-success"><?= $lbl41 ?></a></h2>
                </div>
                	
                <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] ,  'method' => "post"  ]);  ?>
    	            <div class="row clearfix col-md-12 ">
                        <div class="error" id="summryerror"><?= $error ?></div>
                    </div>       
                    <div class=" row mt-3">
                    <div class="form-group col-md-3">
                        <select class="form-control subjects " id="subjects" name="subjects">
                            <option value=""><?= $chosvallabl ?></option>
                            <?php
                            $subje = $subjectdata['id'];
                            $subjname = $subjectdata['subject_name'];
                            foreach($subje as $key => $val)
                            {
                                $sel = '';
                                if($sub_id != "" && ($sub_id ==$val))
                                {
                                    $sel = "selected";
                                }
                            ?>
                               <option value="<?= $val ?>" <?= $sel ?> ><?= $subjname[$key] ?></option>
                            <?php 
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <select class="form-control community_filter " id="exams_ass" name="exams_ass" onchange="exam_assfilter(this.value)">
                            <option value=""><?= $chosvallabl ?></option>
                            <?php  
                                if($exams_ass != "" )
                                {
                                    ?>
                                    <option value="Exams" <?php if($exams_ass == "Exams") { echo "selected"; } ?> ><?= $exmlabl ?></option>
                                    <option value="Assessment" <?php if($exams_ass == "Assessment") { echo "selected"; } ?> ><?= $asslabl?></option>
                                    <option value="Quiz" <?php if($exams_ass == "Quiz") { echo "selected"; } ?> >Quiz</option>
                                <?php
                                }
                                else
                                {
                                ?>
                                    <option value="Exams"><?= $exmlabl ?></option>
                                    <option value="Assessment"><?= $asslabl?></option>
                                    <option value="Quiz">Quiz</option>
                                <?php } ?>
                        </select>
                    </div>
                    <?php if($exams_ass == "Exams") { ?>
                    <div class="form-group col-md-3" id="examtype" >
                        <select class="form-control exam_type" id="exam_type" name="exam_type">
                            <option value= "">Choose Exam Type</option>
                            <?php if($exmtype != "" )
                            {
                                ?>
                                <option value= "Weekly" <?php if($exmtype == "Weekly") { echo "selected"; } ?>><?= $weekly ?></option>
                                <option value= "Monthly" <?php if($exmtype == "Monthly") { echo "selected"; } ?>><?= $mnthly ?></option>
                                <option value= "Quartly" <?php if($exmtype == "Quartly") { echo "selected"; } ?>><?= $quartly ?></option>
                                <option value= "Half-Yearly" <?php if($exmtype == "Half-Yearly") { echo "selected"; } ?>><?= $hlfyearly ?></option>
                                <option value= "Yearly" <?php if($exmtype == "Yearly") { echo "selected"; } ?>><?= $yearly ?></option>
                            <?php
                            }
                            else
                            {
                            ?>
                                <option value= "Weekly"><?= $weekly ?></option>
                                <option value= "Monthly"><?= $mnthly ?></option>
                                <option value= "Quartly"><?= $quartly ?></option>
                                <option value= "Half-Yearly"><?= $hlfyearly ?></option>
                                <option value= "Yearly"><?= $yearly ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php 
                    } else
                    { ?>
                    <div class="form-group col-md-3" id="examtype" style="display:none;">
                        <select class="form-control exam_type" id="exam_type" name="exam_type">
                            <option value= "">Choose Exam Type</option>
                            <?php if($exmtype != "" )
                            {
                                ?>
                                <option value= "Weekly" <?php if($exmtype == "Weekly") { echo "selected"; } ?>><?= $weekly ?></option>
                                <option value= "Monthly" <?php if($exmtype == "Monthly") { echo "selected"; } ?>><?= $mnthly ?></option>
                                <option value= "Quartly" <?php if($exmtype == "Quartly") { echo "selected"; } ?>><?= $quartly ?></option>
                                <option value= "Half-Yearly" <?php if($exmtype == "Half-Yearly") { echo "selected"; } ?>><?= $hlfyearly ?></option>
                                <option value= "Yearly" <?php if($exmtype == "Yearly") { echo "selected"; } ?>><?= $yearly ?></option>
                            <?php
                            }
                            else
                            {
                            ?>
                                <option value= "Weekly"><?= $weekly ?></option>
                                <option value= "Monthly"><?= $mnthly ?></option>
                                <option value= "Quartly"><?= $quartly ?></option>
                                <option value= "Half-Yearly"><?= $hlfyearly ?></option>
                                <option value= "Yearly"><?= $yearly ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php } ?>
                    <div class="form-group col-md-3">
                        <select class="form-control listteacher" id="listteacher" name="listteacher">
                            <option value="">Choose Teachers</option>
                            <?php
                            foreach($emp_details as $val)
                            {
                                $sel = '';
                                if($tchr_id != "" && ($tchr_id ==$val['employee']['id']))
                                {
                                    $sel = "selected";
                                }
                                ?>
                               <option value="<?= $val['employee']['id'] ?>" <?= $sel ?> ><?= $val['employee']['f_name']." ".$val['employee']['l_name'] ?></option>
                            <?php 
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary meetingreport" id="meetingreport"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                </div>
                <?php echo $this->Form->end();?>
                    
                
                
                
            </div>
            
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem student_table" id="allgrade_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th><?= $lbl1243 ?></th>
                                <th>Type</th>
                                <th>Periode</th>
                               <!-- <th><?= $lbl1245 ?></th>-->
                                <th><?= $lbl2126 ?></th>
                                <th><?= $lbl2127 ?></th>
                            </tr>
                        </thead>
                        <tbody id="allgrade_body">
                            
                            <?php
                            if(!empty($grade_details)) {
                                //print_r($grade_details);
                            foreach($grade_details as $grades)
                            {
                            ?>
                            <tr>
                                
                                <td><?php
                                if($grades['type'] == "Exams")
                                {
                                    echo $grades['subjects']['subject_name'];
                                }
                                else
                                {
                                    echo $grades['subjects']['subject_name'];
                                } ?></td>
                                <td><?php
                                /*if($grades['type'] == "Exams")
                                {*/
                                    echo $grades['type']." (". $grades['exam_type'].")";
                                /*}
                                else
                                {
                                    echo $grades['type'];
                                }*/?></td>
                                <td>
                                    <?= $grades['exam_period'] ?>
                                </td>
                                <!--<td><?php
                                if($grades['employee']['f_name'] != "")
                                {
                                    echo $grades['employee']['f_name'] ." ". $grades['employee']['l_name'];
                                }
                                else
                                {
                                    echo $grades['company']['comp_name'];
                                }
                                 ?></td>-->
                                <td><?= $grades['submit_exams']['marks'] ."/". $grades['max_marks'] ?></td>
                                <td><?= $grades['submit_exams']['grade'] ?></td>
                               
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
            
<div class="row clearfix">
    <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
</div>
    </div>
</div>
   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?php
if(!empty($error))
{
    ?>
    <script>
        $("#summryerror").fadeIn().delay('5000').fadeOut('slow');
    </script>
    <?php
}
?>


            
    
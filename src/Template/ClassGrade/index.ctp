<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header"> <?php //print_r($subject_details);?>
                <div class=" row">
                    <h2 class="col-md-6 heading text-left"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '942') { echo $langlbl['title'] ; } } ?> >  <?= $class_details['c_name']."-".$class_details['c_section'] ?> (<?= $class_details['school_sections'] ?>) (<?= $subject_details['subject_name'] ?>) > <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1858') { echo $langlbl['title'] ; } } ?></h2>
                    <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>TeacherSubject?studentdetails=0&subid=<?= $subjectid ?>&gradeid=<?= $classid ?>" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
            </div>
            
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem student_table" id="allgrade_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <!--<th>Code</th>-->
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1243') { echo $langlbl['title'] ; } } ?></th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Periode</th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '147') { echo $langlbl['title'] ; } } ?></th>
                                
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1246') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1229') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1864') { echo $langlbl['title'] ; } } ?></th>
                                <!--<th>Action</th>-->
                            </tr>
                        </thead>
                        <tbody id="allgrade_body">
                            <?php foreach($grades_details as $grade) { ?>
                            <tr>
                                <!--<td>001</td>-->
                                <td><?= $subject_details['subject_name']." (".$grade['type'].")" ?></td>
                                <td><?= $grade['title'] ?></td>
                                <td><?= $grade['exam_type'] ?></td>
                                <td><?= $grade['exam_period'] ?></td>
                                <td><?= $grade['student']['adm_no'] ?></td>
                                <td><?= $grade['student']['f_name']." ".  $grade['student']['l_name']?></td>
                                
                                <td><?= $grade['submit_exams']['marks'] ?>/<?= $grade['max_marks'] ?></td>
                                <td><?= $grade['submit_exams']['grade'] ?></td>
                                <td><?= $grade['start_date'] ?></td>
                                <!--<td><a href="javascript:void(0)" title="View" class="btn btn-sm btn-success viewgrade" data-id="1" data-toggle="modal" data-target="#viewgrade"><i class="fa fa-eye"></i></a></td>-->
                            </tr>
                            <?php } ?>
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

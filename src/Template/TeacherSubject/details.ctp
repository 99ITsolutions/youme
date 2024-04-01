<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header"> <?php //print_r($class_details);?>
                <div class="row">
                    <h2 class="col-md-6 heading align-left">Teacher Dashboard > <?= $class_details['c_name']."-".$class_details['c_section'] ?>(<?= $class_details[0]['school_sections'] ?>) (<?= $subject_details['subject_name'] ?>) > <?= ucfirst($examdtl['type']) ?> Details</h2>
                    <input type="hidden" name="subid" id="subid" value="<?= $subject_details['id'] ?>">
                    <input type="hidden" name="clsid" id="clsid" value="<?= $class_details['id'] ?>">
                    <div class="col-md-6  align-right"><a href="<?= $baseurl ?>TeacherSubject?studentdetails=1&subid=<?= $subject_details['id'] ?>&gradeid=<?= $class_details['id'] ?>" title="Back"  id="assbackbutton" class=" btn btn-primary" ><span class="notranslate">Back</span> </a></div>
                    
                    <!--<div class="col-md-6  align-right"><a href="javascript:void(0)" title="Back"  id="assbackbutton" class=" btn btn-primary" >Back </a></div>-->
                </div>
                <div class="row">
                    <h2 class="col-md-6 align-left" style="font-size: 15px !important; font-weight: bold;"><?= $studentdtl['f_name']." ". $studentdtl['l_name'] ?> > <?= $subject_details['subject_name']." ". ucfirst($examdtl['type']) ?></h2>
                    
                </div>
                                                
                
            </div>
            
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-2"><label>Title : </label></div>
                    <div class="col-md-10"><?= $subject_details['subject_name']." ". ucfirst($examdtl['type']) ?></div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Special Instruction : </label></div>
                    <div class="col-md-10"> <?= ucfirst($examdtl['special_instruction']) ?>  </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Status : </label></div>
                    <div class="col-md-10">
                        <?php if($subexamdtl['status'] == 0) { 
                            echo "Not Done";
                        } else { 
                            echo "Done";
                        } ?>
                    </div>
                </div>
                <?php if(!empty( $examdtl['file_name'] )) { ?>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Questions Sheet: </label></div>
                    <div class="col-md-10"><a href="<?= $baseurl ?>webroot/uploadExams/<?= $examdtl['file_name'] ?>" download><?= $examdtl['file_name'] ?></a></div>
                </div>
                <?php } 
                if(!empty( $subexamdtl['submit_answersheet'] )) { ?>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Evaluated Sheet : </label></div>
                    <div class="col-md-10"><a href="<?= $baseurl ?>webroot/uploadevaluatedanswersheet/<?= $subexamdtl['submit_answersheet'] ?>" download><?= $subexamdtl['submit_answersheet'] ?></a></div>
                </div>
                <?php } ?>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Maximum Marks : </label></div>
                    <div class="col-md-10"><?= $examdtl['max_marks'] ?> </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Minimum Marks : </label></div>
                    <div class="col-md-10"><?= $subexamdtl['marks'] ?> </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label><span class="notranslate">Grades</span> : </label></div>
                    <div class="col-md-10"><?= ucfirst($subexamdtl['grade']) ?>  </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Date : </label></div>
                    <div class="col-md-10"><?= date("d M, Y", $examdtl['created_date']) ?></div>
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
   



            
    
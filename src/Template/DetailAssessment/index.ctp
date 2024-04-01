<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header"> <?php //print_r($subject_details);?>
                <div class="row">
                    <h2 class="col-md-6 heading align-left">Teacher Dashboard > I-B (English) > Assessment Details</h2>
                    <input type="hidden" name="subid" id="subid" value="<?= $subjectid ?>">
                    <input type="hidden" name="clsid" id="clsid" value="<?= $classid ?>">
                    <div class="col-md-6  align-right"><a href="<?= $baseurl ?>TeacherSubject?studentdetails=1&subid=4&gradeid=2" title="Back"  id="assbackbutton" class=" btn btn-primary" >Back </a></div>
                    
                    <!--<div class="col-md-6  align-right"><a href="javascript:void(0)" title="Back"  id="assbackbutton" class=" btn btn-primary" >Back </a></div>-->
                </div>
                <div class="row">
                    <h2 class="col-md-6 align-left" style="font-size: 15px !important;    font-weight: bold;">Nancy Singla > Assessment 1</h2>
                    
                </div>
                                                
                
            </div>
            
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-2"><label>Title : </label></div>
                    <div class="col-md-10">Assessment 1 </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Special Instruction : </label></div>
                    <div class="col-md-10">Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Cicero's De Finibus Bonorum et Malorum for use in a type specimen book. 1 </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Status : </label></div>
                    <div class="col-md-10">Done/Not Done </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>View Assessment : </label></div>
                    <div class="col-md-10">view_assessment.doc </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Upload Assessment : </label></div>
                    <div class="col-md-10">upload_assessment.doc </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Marks : </label></div>
                    <div class="col-md-10">88/100 </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Grade : </label></div>
                    <div class="col-md-10">A </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-2"><label>Date : </label></div>
                    <div class="col-md-10">12th Nov, 2020 </div>
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
   



            
    


<div class="row clearfix container" >
   <div class="col-lg-12">
        <div class="card">
            <div class="header ">
                <div class="row clearfix">
                <h2 class=" col-md-10" style="font-size: 1.2rem; color:#1B0951 !important; float:left;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1436') { echo $langlbl['title'] ; } } ?></h2>
                <h2 class="align-right col-md-2"><a href="<?=$baseurl?>schools" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '881') { echo $langlbl['title'] ; } } ?></a> </h2>
                </div>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card text-center bg-dash ">
                            <div class="body">
                                <div class="text-light">
                                    <span><b><a style="color:#ffffff !important" href="../approveclasses/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '9') { echo $langlbl['title'] ; } } ?> (<?= $class_sts?>) </a></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card text-center bg-dash">
                            <?php	//echo $this->Form->create(false , ['url' => ['action' => 'approvesubjects'] , 'id' => "addsubform" , 'method' => "post"  ]); ?>
                            <div class="body">
                                <div class="text-light">
                                    <span><b><a style="color:#ffffff !important" href="../approvesubjects/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '10') { echo $langlbl['title'] ; } } ?> (<?= $subject_sts?>) </a></b></span>
                                    <!--<button type="submit" ><span><b>Subjects (<?= $subject_sts ?>) </b></span></button>-->
                                </div>
                            </div>
                            <input type="hidden" name="scl_id" id="scl_id">
                            <?php //echo $this->Form->end(); ?>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card text-center bg-dash">
                            <div class="body">
                                <div class="text-light">
                                    <span><b><a style="color:#ffffff" href="../approveclasssubject/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '11') { echo $langlbl['title'] ; } } ?> (<?= $subjcls_sts ?>) </a></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card text-center bg-dash">
                            <div class="body">
                                <div class="text-light">
                                    <span><b><a style="color:#ffffff" href="../approveteachers/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '15') { echo $langlbl['title'] ; } } ?> (<?= $teacher_sts?>) </a></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card text-center bg-dash">
                            <div class="body">
                                <div class="text-light">
                                    <span><b><a style="color:#ffffff" href="../approvestudents/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '12') { echo $langlbl['title'] ; } } ?> (<?= $student_sts?>) </a></b></span>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <!--<div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card text-center bg-dash">
                            <div class="body">
                                <div class="text-light">
                                    <span><b><a style="color:#ffffff" href="../approveknowledgebase/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '18') { echo $langlbl['title'] ; } } ?> (<?= $knowledge_sts?>) </a></b></span>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card text-center bg-dash">
                            <div class="body">
                                <div class="text-light">
                                    <span><b><a style="color:#ffffff" href="../approveexamsass/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '17') { echo $langlbl['title'] ; } } ?> (<?= $examass_sts?>) </a></b></span>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card text-center bg-dash">
                            <div class="body">
                                <div class="text-light">
                                    <span><b><a style="color:#ffffff" href="../approvegallery/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '19') { echo $langlbl['title'] ; } } ?> (<?= $gallery_sts?>) </a></b></span>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <!--<div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="card text-center bg-dash">
                            <div class="body">
                                <div class="text-light">
                                    <span><b><a style="color:#ffffff" href="../approvenotify/<?= $sclid ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '21') { echo $langlbl['title'] ; } } ?> (<?= $notify_sts?>) </a></b></span>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>             
</div>






            
     
<style>
    /*.bg-dash
    {
        background-color:#242E3B !important;
    }*/
</style>
<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { $bcklbl = $langlbl['title'] ; } } ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header ">
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $bcklbl ?></a></li>
                </ul>
            </div>
           <div class="body">
                <div class="row clearfix">
                    <?php foreach($tutorsub_details as $tutorsub)
                    { 
                        //print_r($tutorsub);
                        ?>
	                    <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash tutoriallogin">
                                <!--<a style="color:#FFFFFF !important" href="javascript:void(0)" data-id="<?= $tutorsub['id'] ?>" class = "tutorlogin" data-toggle="modal" data-target="#tutoringlogin">-->
                                <a  class="colorBtn tutorlogin" href="javascript:void(0)" data-id="<?= $tutorsub['id'] ?>" >
                                    <div class="body" style="height:90px !important;">
                                        <div class="p-10 text-light">
                                            <span>
                                                <b>
                                                    <p style="margin-bottom:0px"><?= $tutorsub['subjects_name'] ." (". $tutorsub['grades_name'] . ")"?></p>
                                                    <p><?= "(". $tutorsub['emp_name'] . ")" ?></p>
                                                </b>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
<?php echo $this->Form->end(); ?>

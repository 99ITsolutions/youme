<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2') { $lbl2 = $langlbl['title'] ; } 
    if($langlbl['id'] == '3') { $lbl3 = $langlbl['title'] ; } 
    if($langlbl['id'] == '4') { $lbl4 = $langlbl['title'] ; } 
    if($langlbl['id'] == '5') { $lbl5 = $langlbl['title'] ; } 
}

$priv = explode("," , $privilages);
if (!empty($sclsub_details[0])) 
{ ?>
	<div class="row clearfix">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-center bg-dash">
                <?php if(in_array("5", $priv)) { ?>
                <a class="colorBtn" href="<?=$baseurl?>teachers">
                    <div class="body">
                        <div class="p-15 text-light">
                            <h3 style="color:#FFFFFF"><?= $employees_details ?></h3>
                            <span><b><?= $lbl2 ?></b></span>
                        </div>
                    </div>
                </a>
                <?php } else { ?>
                <div class="body">
                    <div class="p-15 text-light">
                        <h3 style="color:#FFFFFF"><?= $employees_details ?></h3>
                        <span><b><?= $lbl2 ?></b></span>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-center bg-dash">
                <?php if(in_array("4", $priv)) { ?>
                <a class="colorBtn" href="<?=$baseurl?>students">
                    <div class="body">
                        <div class="p-15 text-light">
                            <h3 style="color:#FFFFFF"><?= $students_details ?></h3>
                            <span><b><?= $lbl3 ?></b></span>
                        </div>
                    </div>
                </a>
                <?php } else { ?>
                <div class="body">
                    <div class="p-15 text-light">
                        <h3 style="color:#FFFFFF"><?= $students_details ?></h3>
                        <span><b><?= $lbl3 ?></b></span>
                    </div>
                </div>   
                <?php } ?>
            </div>
        </div>    
        <div class="col-lg-3 col-md-6 col-sm-6">
            
                <?php if(in_array("1", $priv)) { ?>
                <div class="card text-center bg-dash " style="height: 110px;padding: 20px;">
                <a class="colorBtn" href="<?=$baseurl?>classes">
                    <div class="p-15 text-light">
                        <h3 style="color:#FFFFFF !important"><?= $class_details ?></h3>
                        <span><b><?= $lbl4 ?></b></span>
                    </div>
                </a>
                <?php } else { ?>
                <div class="card text-center bg-dash ">
                <div class="body">
                    <div class="p-15 text-light">
                        <h3 style="color:#FFFFFF !important"><?= $class_details ?></h3>
                        <span><b><?= $lbl4 ?></b></span>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-center bg-dash">
                <?php if(in_array("2", $priv)) { ?>
                <a class="colorBtn" href="<?=$baseurl?>subjects">
                <div class="body">
                    <div class="p-15 text-light">
                        <h3 style="color:#FFFFFF"><?= $subjects_details ?></h3>
                        <span><b><?= $lbl5 ?></b></span>
                    </div>
                </div>
                </a>
                <?php } else { ?>
                <div class="body">
                    <div class="p-15 text-light">
                        <h3 style="color:#FFFFFF"><?= $subjects_details ?></h3>
                        <span><b><?= $lbl5 ?></b></span>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-center bg-dash">
                <div class="body">
                    <div class="p-7 text-light">
                        <span><a class="colorBtn" href="http://learn.eltngl.com/" target="_blank"><img src="<?=$baseurl?>img/NationalGeoG1-logo.png" 
style="width: 100%; max-width: 280px;" width="210px" height="68px"></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>	
    <?php } ?>       

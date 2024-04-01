<?php
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2175') { $lbl2175 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2176') { $lbl2176 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2177') { $lbl2177 = $langlbl['title'] ; }
        if($langlbl['id'] == '2190') { $lbl2190 = $langlbl['title'] ; }
    }
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="col-md-4 heading"><?= $lbl2175 ?></h2>
                <ul class="header-dropdown">
                    <li><a href="<?= $baseurl ?>fees" title="Back" class="btn btn-sm btn-success"><?= $lbl41 ?></a></li>
                </ul>
		    </div>
            <div class="body" id="knowledgecenter">
                <div class="row clearfix">
                    <?php if(!empty($sclsub_details[0])) { 
                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                        if(in_array("119", $roles)) { ?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span><b><a class="colorBtn" href="<?= $baseurl ?>fees/consolidated"><?= $lbl2177 ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } if(in_array("120", $roles)) { ?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a class="colorBtn" href="<?= $baseurl ?>fees/groupclass"><?= $lbl2176 ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } if(in_array("121", $roles)) { ?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a class="colorBtn" href="<?= $baseurl ?>fees/completedue"><?= $lbl2190 ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php } } else { ?> 
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="card text-center bg-dash ">
                            <div class="body">
                                <div class="p-15 text-light">
                                   <span><b><a class="colorBtn" href="<?= $baseurl ?>fees/consolidated"><?= $lbl2177 ?></a></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="card text-center bg-dash">
                            <div class="body">
                                <div class="p-15 text-light">
                                    <span><b><a class="colorBtn" href="<?= $baseurl ?>fees/groupclass"><?= $lbl2176 ?></a></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="card text-center bg-dash">
                            <div class="body">
                                <div class="p-15 text-light">
                                    <span><b><a class="colorBtn" href="<?= $baseurl ?>fees/completedue"><?= $lbl2190 ?></a></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
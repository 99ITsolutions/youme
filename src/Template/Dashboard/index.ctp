<style>
.bg-dash { background-color:#000036 !important; }
.card .body { padding:12px !important; }
</style>
<div class="row clearfix">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card text-center bg-dash ">
            <div class="body">
                <div class="p-15 text-light">
                    <h3 style="color:#ffffff !important"><?= $school_details ?></h3>
                    <span><b><a style="color:#ffffff !important" href="<?= $baseurl ?>schools"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '597') { echo $langlbl['title'] ; } } ?></a></b></span>
                </div>
            </div>
        </div>
    </div>
    <?php if($role == 2) { ?>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="p-15 text-light">
                    <h3 style="color:#ffffff"><?= $users_details ?></h3>
                    <span><b><a style="color:#ffffff" href="<?= $baseurl ?>subadmin"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '598') { echo $langlbl['title'] ; } } ?></a></b></span>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="p-15 text-light">
                    <h3 style="color:#ffffff"><?= $employees_details ?></h3>
                    <span><b><a style="color:#ffffff" href="#"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '599') { echo $langlbl['title'] ; } } ?></a></b></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="p-15 text-light">
                    <h3 style="color:#ffffff"><?= $students_details ?></h3>
                    <span><b><a style="color:#ffffff" href="#"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '600') { echo $langlbl['title'] ; } } ?></a></b></span>
                </div>
            </div>
        </div>
    </div>                
</div>
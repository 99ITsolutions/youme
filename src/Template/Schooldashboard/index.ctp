<div class="row clearfix">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="p-15 text-light">
                    <h3 class="colorBtn"><?= $employees_details ?></h3>
                    <span><b><a class="colorBtn" href="<?=$baseurl?>teachers"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2') { echo $langlbl['title'] ; } } ?></a></b></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="p-15 text-light">
                    <h3 class="colorBtn"><?= $students_details ?></h3>
                    <span><b><a class="colorBtn" href="<?=$baseurl?>students"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '3') { echo $langlbl['title'] ; } } ?></a></b></span>
                </div>
            </div>
        </div>
    </div>    
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card text-center bg-dash ">
            <div class="body">
                <div class="p-15 text-light">
                    <h3 class="colorBtn"><?= $class_details ?></h3>
                    <span><b><a class="colorBtn" href="<?=$baseurl?>classes"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '4') { echo $langlbl['title'] ; } } ?></a></b></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="p-15 text-light">
                    <h3 class="colorBtn"><?= $subjects_details ?></h3>
                    <span><b><a class="colorBtn" href="<?=$baseurl?>subjects"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '5') { echo $langlbl['title'] ; } } ?></a></b></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="p-7 text-light">
                    <span><a class="colorBtn" href="http://learn.eltngl.com/" target="_blank"><img src="<?=$baseurl?>img/NationalGeoG1-logo.png" style="width: 100%; max-width: 280px;" width="210px" height="68px"></a></span>
                </div>
            </div>
        </div>
    </div>
</div>
      


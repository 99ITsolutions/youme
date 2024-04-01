<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '704') { $titl =  $langlbl['title'] ; }
    if($langlbl['id'] == '716') { $desc = $langlbl['title'] ; } 
} ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2143') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                 </ul>
            </div>
            <div class="body"> 
                <div class="row clearfix">
                    <div class="col-sm-12" style="font-size:16px; margin-bottom:10px;">
                        <b><?= $titl ?> - </b>
                        <?= $code_details['title'] ?>
                    </div>
                    <div class="col-sm-12">
                        <b><?= $desc ?> - </b>
                        <?= $code_details['content'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    



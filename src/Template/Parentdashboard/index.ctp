<style>
    h2.heading a
    {
        color:#242E3B !important;
    }
    .bg-dash{
        background-color:#ffffff !important;
        color:#000000 !important;
    }
    .card2 {
        box-shadow: 0 1px 2px 0 #080808 !important;
    }
</style>
<?php
$style ='';
if(count($parent_details) == 1)
{
   $style = '<div class="col-lg-4 col-md-4 col-sm-6"></div>';
}
elseif(count($parent_details) == 2)
{
   $style = '<div class="col-lg-2 col-md-2 col-sm-6"></div>';
}
elseif(count($parent_details) > 2)
{
   $style = '';
}
//print_r($parent_details);
if(!empty($parent_details) && count($parent_details) != 0)
{ 
?>
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            
	           <div class="body">
	                <div class="row clearfix text-center">
	                    <?php 
	                    echo $style;
	                    foreach($parent_details as $val) { ?>
	                    <div class="col-lg-4 col-md-4 col-sm-6">
	                        <a href="javascript:void(0)" data-id="<?= $val['student']['id'] ?>" data-type="1" data-language="<?= $langua ?>" data-password = "<?= $val['student']['password'] ?>" data-email="<?= $val['student']['adm_no'] ?>"  title="studdash" class="studentdash" >
                            <div class="card card2 text-center bg-dash ">
                                <div class="body">
                                    <p><img src="<?= $baseurl ?>/img/<?=  $val['company']['comp_logo'] ?>" width="90px" style="height:60px !important;"></p>
                                    <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '81') { echo $langlbl['title'] ; } } ?>: <?= $val['student']['f_name']." ".$val['student']['l_name'] ?></b></p>
                                    <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?>: <?= $val['class']['c_name']."-".$val['class']['c_section']." (". $val['class']['school_sections']. ")" ?></b></p>
                                    <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1985') { echo $langlbl['title'] ; } } ?>: <?= $val['company']['comp_name'].", ".$val['company']['city']?></b></p>
                                   
                                </div>
                            </div>
                            </a>
                        </div>
                        <?php } ?>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
<?php
}
?>
<?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>

            
    
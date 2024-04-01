<style>
h2.heading a
{
    color:#242E3B !Important;
}
.kindertext {
    font-size: 23px;
    font-weight: bold;
    text-shadow: 1px 2px 0px #ded9d8 !important;
    font-family: cursive;
}
</style>

<?php 
foreach($lang_label as $langlbl) {
    if($langlbl['id'] == '41') { $bcklbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2037') { $discvrlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2038') { $animllbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2039') { $allactlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2040') { $scienclbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2041') { $frtveglbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2042') { $alphnumlbl = $langlbl['title'] ; }
}

?>


    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="header">
                    <ul class="header-dropdown">
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $bcklbl ?></a></li>
                    </ul>
                </div>
	           <div class="body">
	               <div class="row clearfix">
	                   <?php foreach($kinderdash_details as $kinderdash) 
	                    { if(strtolower($kinderdash['dash_name']) == "virtual class") { ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                            <a style="color:#000000 !important" href="<?= $baseurl ?>Teacherkindergarten/virtualclass">
                                <img src="<?= $baseurl ?>img/<?= $kinderdash['image'] ?>"  width="270px"/>
                                <div class="text-center kindertext" style="color:#000000 !important">You-Me Live</div>
                            </a>
                        </div>
                        <?php } } ?>
	                    <?php foreach($kinderdash_details as $kinderdash) 
	                    { if(strtolower($kinderdash['dash_name']) != "virtual class") { ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                            <a style="color:#000000 !important" href="<?= $baseurl ?>Teacherkindergarten/activity/<?= $kinderdash['id'] ?>">
                                <img src="<?= $baseurl ?>img/<?= $kinderdash['image'] ?>"  width="270px"/>
                                
                                <?php //echo $kinderdash['dash_name'];
                                if(strtolower($kinderdash['dash_name']) == "discovery") {  $dashname = $discvrlbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "coding") {  $dashname = "Coding"; }
                                elseif(strtolower($kinderdash['dash_name']) == "animals") {  $dashname = $animllbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "all activities") {  $dashname = $allactlbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "science") {  $dashname = $scienclbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "fruits & vegetables") {  $dashname = $frtveglbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "alphabets & numbers") {  $dashname = $alphnumlbl; }
                                ?>
                                <div class="text-center kindertext" style="color:#000000 !important"><?= ucwords($dashname) ?></div>
                            </a>
                        </div>
                        <?php } }?>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
<?php   echo $this->Form->create(false , [ 'method' => "post"  ]); echo $this->Form->end(); ?>

            
    
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
#ac-wrapper {
    position: absolute;
    top:0;
    left:0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,.6);
    z-index: 1001;
    margin: 0 auto !important;
}

#popup {
    width: 90% ;
    height: 500px;
    background: #FFFFFF;
    border: 4px solid #000;
    border-radius: 10px;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    box-shadow: #64686e 0px 0px 3px 3px;
    -moz-box-shadow: #64686e 0px 0px 3px 3px;
    -webkit-box-shadow: #64686e 0px 0px 3px 3px;
    position: relative;
    overflow-y:scroll;
    
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
	           <div class="body">
	               <div class="row clearfix">
	                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4" style="padding-top:1rem;">
                            
                            <a style="color:#000000 !important" href="http://learn.eltngl.com/" target="_blank">
                                <img src="<?= $baseurl ?>img/NationalGeoG1-logo-1-blk.png"  width="270px"/>
                                <div class="text-center kindertext" style="color:#000000 !important; padding-top:2.8rem">National Geographic</div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-4" style="padding-top:1rem;">
                            <a style="color:#000000 !important" href="<?= $baseurl ?>kinderdashboard/kinderdropbox">
                                <img src="<?= $baseurl ?>img/dropbox.png"  width="270px"/>
                                <div class="text-center kindertext" style="color:#000000 !important">Drop Box</div>
                            </a>
                        </div>
	                   <?php foreach($kinderdash_details as $kinderdash) 
	                    { if($kinderdash['dash_name'] == "Virtual Class")  { ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                            <a style="color:#000000 !important" href="<?= $baseurl ?>kinderdashboard/virtualclass">
                                <img src="<?= $baseurl ?>img/<?= $kinderdash['image'] ?>"  width="270px"/>
                                <div class="text-center kindertext" style="color:#000000 !important">You-Me Live</div>
                            </a>
                        </div>
                        <?php } }?>
	                    <?php foreach($kinderdash_details as $kinderdash) 
	                    { if($kinderdash['dash_name'] != "Virtual Class") { 
	                    
                                if(strtolower($kinderdash['dash_name']) == "discovery") {  $dashname = $discvrlbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "coding") {  $dashname = "Coding"; }
                                elseif(strtolower($kinderdash['dash_name']) == "animals") {  $dashname = $animllbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "all activities") {  $dashname = $allactlbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "science") {  $dashname = $scienclbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "fruits & vegetables") {  $dashname = $frtveglbl; }
                                elseif(strtolower($kinderdash['dash_name']) == "alphabets & numbers") {  $dashname = $alphnumlbl; }
                                
	                    ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                            <a style="color:#000000 !important" href="<?= $baseurl ?>kinderdashboard/activity/<?= $kinderdash['id'] ?>">
                                <img src="<?= $baseurl ?>img/<?= $kinderdash['image'] ?>"  width="270px"/>
                                <div class="text-center kindertext" style="color:#000000 !important"><?= ucwords($dashname) ?></div>
                            </a>
                        </div>
                        <?php } } ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-4" style="padding-top:1rem;">
                            <a style="color:#000000 !important" href="<?= $baseurl ?>Canteen">
                                <img src="<?= $baseurl ?>canteen/canteen_mb.png"  width="270px" />
                                <div class="text-center kindertext" style="color:#000000 !important">Morning break</div>
                            </a>
                        </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
<?php   echo $this->Form->create(false , [ 'method' => "post"  ]); echo $this->Form->end(); ?>


<?php 
if(!empty($codeconduct)) {
if($codeagree['status'] == 0) { ?>
<!--<div id="ac-wrapper">
    <div id="popup">
        <center>
            <h2><?= $codeconduct['title'] ?></h2>
            <hr>
	        <div class="row clearfix col-lg-11 m-3">
                <?= $codeconduct['content'] ?>
            </div>
            <hr>
            <input type="hidden" id="stuid" value="<?= $codeagree['student_id'] ?>" >

            <input type="hidden" id="agreeid" value="<?= $codeagree['id'] ?>" >
            <p class="row clearfix col-lg-11 ml-3" style="text-align:left !important;">
                <input type="checkbox" name="agreemnt" class="pl-2" id="agreement" required >  I agree to these Code of Conduct agreement.
            </p>
            <input type="submit" name="submit" value="Submit" onClick="PopUp()" />
        </center>
    </div>
</div>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
function PopUp(){
    if ($("#agreement").prop("checked")) 
    {
        var agreement = 1; 
        var id = $("#agreeid").val();
        var refscrf = $("input[name='_csrfToken']").val();
        $.ajax({ 
            url: baseurl +"/studentdashboard/updateagreement", 
            data: {"agreement":agreement, "id":id, _csrfToken : refscrf}, 
            type: 'post',
            success: function (result) 
            {       
                if (result) 
                {
                    location.reload();
                }
            }
        });
    }
    else
    {
        var agreement = 0;
        alert ("Please accept agreement first.");
    }
    
    //document.getElementById('ac-wrapper').style.display="none"; 
}
</script>
<?php } } ?>
  
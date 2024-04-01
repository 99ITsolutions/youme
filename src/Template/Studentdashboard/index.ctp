<style>
h2.heading a
{
    color:#242E3B !important;
}
.card .body
{
    padding: 20px !important;
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
if($students_details != "")
{ 
?><?php //echo $students_details[0]['f_name']. " ". $students_details[0]['l_name']. " (". $students_details[0]['class']['c_name']. " - ". $students_details[0]['class']['c_section']. "(". $students_details[0]['class']['school_sections'] ."))"?>
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="header">
	                 <h2 class="text-center heading"><a href="<?=$baseurl?>students/profile/<?=md5($students_details[0]['id'])?>" title = "Click Here to Reset your Password"><?= $students_details[0]['class']['c_name']. " - ". $students_details[0]['class']['c_section']. "(". $students_details[0]['class']['school_sections'] .")"?></a></h2>
	           </div>
	           <div class="body">
	               <div class="row clearfix">
	                    <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash ">
                                <div class="body" style="height:90px !important;">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF !important" href="<?=$baseurl?>studentknowledge"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '593') { echo $langlbl['title'] ; } } ?></a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b>
                                            <a style="color:#FFFFFF" href="<?=$baseurl?>studentdashboard/studentprofile/"  title="Student Profile"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1225') { echo $langlbl['title'] ; } } ?></a>
                                        </b></span>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="text-center">
                                <input type="text" minlength="1" maxlength="1" id="s_pin_1" name="s_pin_1" class="col-md-2" style="padding-right: 7px !important;   padding-left: 7px !important;">
                                <input type="text" minlength="1" maxlength="1" id="s_pin_2" name="s_pin_2" class="col-md-2" style="padding-right: 7px !important;   padding-left: 7px !important;">
                                <input type="text" minlength="1" maxlength="1" id="s_pin_3" name="s_pin_3" class="col-md-2" style="padding-right: 7px !important;   padding-left: 7px !important;">
                                <input type="text" minlength="1" maxlength="1" id="s_pin_4" name="s_pin_4" class="col-md-2" style="padding-right: 7px !important;   padding-left: 7px !important;">
                            </div>-->
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF" href="<?=$baseurl?>Studentdairy"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2144') { echo $langlbl['title'] ; } } ?></a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF" href="<?=$baseurl?>studentdashboard/myschool"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '35') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        <!--<span><b><a style="color:#FFFFFF" href="<?=$baseurl?>viewKnowledge"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '35') { echo $langlbl['title'] ; } } ?></a></b></span>-->
                                    </div>
                                </div>
                            </div>
                            <!--<div class="text-center">
                                <input type="text" minlength="1" maxlength="1" id="p_pin_1" name="p_pin_1" class="col-md-2" style="padding-right: 7px !important;   padding-left: 7px !important;">
                                <input type="text" minlength="1" maxlength="1" id="p_pin_2" name="p_pin_2" class="col-md-2" style="padding-right: 7px !important;   padding-left: 7px !important;">
                                <input type="text" minlength="1" maxlength="1" id="p_pin_3" name="p_pin_3" class="col-md-2" style="padding-right: 7px !important;   padding-left: 7px !important;">
                                <input type="text" minlength="1" maxlength="1" id="p_pin_4" name="p_pin_4" class="col-md-2" style="padding-right: 7px !important;   padding-left: 7px !important;">
                            </div>-->
                        </div>
                        
                        <?php if($publish == 1) { ?>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                            <?php if($stusts == 0) {  ?>
                                <div class="card text-center hoverbutton pulse-button">
                            <?php } else { ?>
                                <div class="card text-center bg-dash">
                            <?php } ?>
                            
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b>
                                            <a style="color:#FFFFFF" href="<?=$baseurl?>studentdashboard/returnreport?classid=<?=$_SESSION['class_id'];?>&studentid=<?=$_SESSION['student_id'];?>">
                                            <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1986') { echo $langlbl['title'] ; } } ?>
                                        </a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="col-lg-3 col-md-6 col-sm-6" style="opacity:0.5">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b>
                                            <a style="color:#FFFFFF" href="#">
                                            <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1986') { echo $langlbl['title'] ; } } ?>
                                        </a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-7 text-light">
                                        <span><a class="colorBtn" href="http://learn.eltngl.com/" target="_blank"><img src="<?=$baseurl?>img/NationalGeoG1-logo.png" style="width: 100%; max-width: 280px;" width="210px" height="68px"></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-7 text-light">
                                        <span><a class="colorBtn" href="<?=$baseurl?>Canteen/"><img src="<?=$baseurl?>canteen/morning break white_low.png" style="width: 100%; max-width: 280px;" width="210px" height="68px"></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF" href="<?=$baseurl?>Studentmarketplace"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1213') { echo $langlbl['title'] ; } } ?></a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>  

	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	</div>
<?php
}
?>

<?php echo $this->Form->create(false , [ 'method' => "post"  ]); echo $this->Form->end(); ?>

<?php 
if(!empty($codeconduct)) {
if($codeagree['status'] == 0) { ?>
<div id="ac-wrapper">
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
</div>

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

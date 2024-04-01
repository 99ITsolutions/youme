<style>
	    /*.bg-dash
	    {
	        background-color:#242E3B !important;
	    }*/
	    h2.heading a
	    {
	        color:#242E3B !Important;
	    }
	    
	</style>
<?php 
if(!empty($parent_details))
{ ?>
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="header">
	                 <h2 class="text-center heading"><a href="<?=$baseurl?>parents/profile/<?=md5($parent_details[0]['id'])?>" title = "Click Here to Reset your Password"><?= $students_details[0]['class']['c_name']. " - ". $students_details[0]['class']['c_section'] ?></a></h2>
	           </div>
	           <div class="body">
	                <div class="row clearfix">
	                    <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash ">
                                <div class="body" style="height:90px !important;">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF !important" href="<?=$baseurl?>parentknowledge"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '593') { echo $langlbl['title'] ; } } ?></a></b></span>
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
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF" href="<?=$baseurl?>viewKnowledge"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1212') { echo $langlbl['title'] ; } } ?></a></b></span>
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
<?php
}
?>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]); echo $this->Form->end(); ?>

            
    
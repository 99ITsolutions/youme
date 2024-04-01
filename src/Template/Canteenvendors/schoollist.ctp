<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
} 
?>
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="header">
                    <h2 class="heading">School List</h2>
                    <ul class="header-dropdown">
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                    </ul>
                </div>  
                <div class="body">
	                <div class="row clearfix">
	                    <?php foreach($sclinfo as $scl) { ?>   
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#ffffff" href="<?=$baseurl?>Cvendordashboard/scldata/<?= $scl['id'] ?>"><?= $scl['comp_name'] ?></a></b></span>
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
<?php	echo $this->Form->create(false , ['method' => "post"  ]); echo $this->Form->end(); 

?>

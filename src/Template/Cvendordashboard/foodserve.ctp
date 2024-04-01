<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2202') { $lbl2202 = $langlbl['title'] ; }
} 
 ?>
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
                <div class="header">
                    <h2 class="heading"><?= $sclinfo['comp_name']." -> ".$fssinfo[0]['class_section'] ?></h2>
                    <ul class="header-dropdown">
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                    </ul>
                </div>
                <div class="body">
	                <div class="row clearfix">
	                    <?php foreach($fssinfo as $fss) { ?>   
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#ffffff" href="<?=$baseurl?>Cvendordashboard/listfoodserve/<?= $fss['id'] ?> ">
                                            <span style="font-size:14px"><?= $fss['weekday'] ." (".$fss['timings'].")" ?></span>
                                            <br>
                                            Click here to check the order
                                        </a></b></span>
                                        <br>
                                        <span><b>(Booking closed before: </b><?= $fss['order_booking_closed'] ?>)</span>
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

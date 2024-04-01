<?php 
foreach($lang_label as $langlbl) { 
  
    if($langlbl['id'] == '2204') { $lbl2204 = $langlbl['title'] ; }
    if($langlbl['id'] == '2202') { $lbl2202 = $langlbl['title'] ; }
    if($langlbl['id'] == '2365') { $lbl2365 = $langlbl['title'] ; }
    if($langlbl['id'] == '2375') { $lbl2375 = $langlbl['title'] ; }
    if($langlbl['id'] == '2376') { $lbl2376 = $langlbl['title'] ; }
    if($langlbl['id'] == '2377') { $lbl2377 = $langlbl['title'] ; }
    if($langlbl['id'] == '2378') { $lbl2378 = $langlbl['title'] ; }
    if($langlbl['id'] == '2381') { $lbl2381 = $langlbl['title'] ; }
    if($langlbl['id'] == '2384') { $lbl2384 = $langlbl['title'] ; }
    if($langlbl['id'] == '2385') { $lbl2385 = $langlbl['title'] ; }
    if($langlbl['id'] == '2397') { $lbl2397 = $langlbl['title'] ; }
    if($langlbl['id'] == '108') { $lbl108 = $langlbl['title'] ; }
    if($langlbl['id'] == '2398') { $lbl2398 = $langlbl['title'] ; }
    if($langlbl['id'] == '2399') { $lbl2399 = $langlbl['title'] ; }
} 
if(!empty($cvndr_details))
{ ?>
<!-- ................... -->
<style>
    /*a{
    color:#fff !important;
    }*/
    @media screen and (max-width: 1024px) {
    .modifier-btn{
    display:flex;
    align-items:center;
    }
    }
    @media screen and (max-width: 767px) {
    .modifier-btn{
    text-align:left !important;
    }

    }
</style>
<!-- .................. -->
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="row header ">
                    <div class=" col-md-9 col-sm-6 mt-4 mb-2" >
                        <?php	echo $this->Form->create(false , ['url' => ['action' => 'orderinfo'] , 'id' => "orderinfo" , 'method' => "post"  ]); ?>
                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <!--<label>Enter order no.</label>-->
                                    <div class="form-group">                                    
                                        <input type="text" class="form-control" required name="order_no" placeholder="<?= $lbl2375 ?>*">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary addorderbtn" id="addorderbtn"><?= $lbl2376 ?></button>
                                </div>
                                <div class="col-md-12">
                                    <div class="error" id="ordererror"></div>
                                    <div class="success" id="ordersuccess"></div>
                                </div>
                            </div>    
                        <?php echo $this->Form->end(); ?>
                    </div>
                    <div class="col-md-3 modifier-btn align-right">
                       <a href="<?=$baseurl?>canteenvendors/profile/<?=md5($cvndr_details[0]['id']) ?>" title="Edit Vendor Profile" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> <?= $lbl2202 ?></a>
                    </div>
                    <div class=" col-md-3 col-sm-4 mt-2 mb-2">
                        <img src="canteen/<?= $cvndr_details[0]['logo']?>" width="175px" height="150px" style="border:1px solid">
                    </div>
                    <div class=" col-md-4 col-sm-6 mt-2 mb-2" >
                        <p><b><?= $lbl2204 ?> : <?= $cvndr_details[0]['vendor_company'] ?></b></p>
                        <p><b><?= $lbl2397 ?> : </b><?= $cvndr_details[0]['f_name'] . " ".$cvndr_details[0]['l_name'] ?></p>
                        <p><b><?= $lbl2377 ?> : </b><?= date("h:i A", strtotime($cvndr_details[0]['deliver_starttime'])) ?></p>
                        <p><b><?= $lbl2378 ?> : </b><?= date("h:i A", strtotime($cvndr_details[0]['deliver_endtime'])) ?></p>
                    </div>
                </div>
                <div class="body">
	                <div class="row clearfix">
	                    <?php foreach($sclinfo as $scl) { ?>   
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#000000" href="<?=$baseurl?>Cvendordashboard/scldata/<?= $scl['id'] ?>"><?= $scl['comp_name'] ?></a></b></span>
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
}
?>
<div class="modal classmodal animated zoomIn" id="addclass" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Order Number</h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'orderinfo'] , 'id' => "orderinfo" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <!--<div class="col-md-12">
                        <label>Enter order no.</label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control" required name="order_no" placeholder="Enter order no.*">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="ordererror"></div>
                        <div class="success" id="ordersuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addorderbtn" id="addorderbtn">Click here to get detail of order no.</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '108') { echo $langlbl['title'] ; } } ?></button>
                    </div>-->
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>    

<div class="modal classmodal animated zoomIn" id="orderdinfo" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl2398 ?></h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-10" id="studinfo">
                    </div>
                    <div class="col-md-2" id="delvrall">
                    </div>
                </div>
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'orderdinfo'] , 'id' => "orderdinfo" , 'method' => "post"  ]); ?>
                <input type="hidden" name="orderid" id="orderid" >
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem viewdtlorder_table" id="viewdtlorder_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <!--<th>
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
                                            </label>
                                        </th>-->
                                        <th><?= $lbl2399 ?></th>
                                        <th><?= $lbl2381 ?></th>
                                        <th><?= $lbl2384 ?></th>
                                        <th><?= $lbl2385 ?></th>
                                        <th><?= $lbl2265 ?></th>
                                        <th><?= $lbl2386 ?></th>
                                    </tr>
                                </thead>
                                <tbody id="viewdtlordrbody" class="modalrecdel"> 
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="orderderror"></div>
                        <div class="success" id="orderdsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr> 
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $lbl108 ?></button>
                    </div>
                </div>    
                <?php echo $this->Form->end(); ?>
            </div>
             
        </div>
    </div>
</div>    

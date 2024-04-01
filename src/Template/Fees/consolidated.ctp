<style>
    #left-sidebar { left: -250px; }
    #main-content { width:100%;}
</style>
<?php
foreach($lang_label as $langlbl) 
{
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
    if($langlbl['id'] == '243') { $lbl243 = $langlbl['title'] ; }
    if($langlbl['id'] == '1057') { $lbl1057 = $langlbl['title'] ; }
    if($langlbl['id'] == '1058') { $lbl1058 = $langlbl['title'] ; }
    if($langlbl['id'] == '2177') { $lbl2177 = $langlbl['title'] ; }
    if($langlbl['id'] == '1422') { $lbl1422 = $langlbl['title'] ; }
    if($langlbl['id'] == '147') { $studnme = $langlbl['title'] ; }
    if($langlbl['id'] == '2186') { $clslbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2161') { $pdlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2160') { $pmlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2162') { $lbl2162 = $langlbl['title'] ; }
    if($langlbl['id'] == '2187') { $lbl2187 = $langlbl['title'] ; }
}

$str_startdate = strtotime($startdate." 00:01");
$str_enddate = strtotime($enddate." 23:59");
?>

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class=" row">
                    <h2 class="col-md-11 heading"><?= $lbl2177 ?></h2>
                    <ul class="header-dropdown">
                        <li><a href="<?= $baseurl ?>fees/downloadconsolidated/<?= $str_startdate ?>/<?= $str_enddate ?>" class="btn btn-info" title="<?= $lbl1422 ?>" <?= $styledr ?>><?= $lbl1422 ?> </a></li>
                        <li><a href="<?= $baseurl ?>fees/feereport" title="Back" class="btn btn-sm btn-success" ><?= $lbl41 ?></a></li>
                    </ul>
                </div>
            </div>
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'consolidated'] , 'method' => "post"  ]);  ?>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
    					<label><?= $lbl1057 ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control commondatepicker" data-date-format="dd-mm-yyyy" id="startdate" value="<?= $startdate ?>" name="startdate"  required placeholder="<?= $lbl1057 ?>*">
                        </div>
    				</div>
                    <div class="col-md-3">
    				    <label><?= $lbl1058 ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control commondatepicker" data-date-format="dd-mm-yyyy" id="enddate" value="<?= $enddate ?>" name="enddate"  required placeholder="<?= $lbl1058 ?>*">
                        </div>
    				</div>
    				<div class="col-md-1">
                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?= $lbl243 ?></button>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end();?>
            <div class="body">
                
                <?php //print_r($studfee); ?>
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem feeconsolid_table" id="feeconsolid_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <th style="display:none">ID</th>
                            <th><?= $studnme ?></th>
                            <th><?= $clslbl ?></th>
                            <th>Description</th>
                            <th><?= $pdlbl ?></th>
                            <th><?= $pmlbl ?></th>
                            <th>Trans. ID</th>
                            <th><?= $lbl2162 ?></th>
                            <th><?= $lbl2187 ?></th>
                        </thead>
                        <tbody id="feeconsolid_body" class="modalrecdel">
                            <?php 
                            $coupm_arr = 0;
                            $amt_arr = 0;
                            foreach($studfee as $sfee) { ?>
                            <tr>
                                <?php 
                                if($sfee['coupon_amt'] != "") { 
                                    $ca = "$".$sfee['coupon_amt'];
                                    $coupm_arr += $sfee['coupon_amt'];
                                } else { 
                                    $ca = ''; 
                                } 
                                $amt_arr += $sfee['amount'];
                                ?>
                                <td style="display:none"><?= $sfee['id'] ?></td>
                                <td><?= ucfirst($sfee['stud_name']) ?></td>
                                <td><?= $sfee['cls_name'] ?></td>
                                <td><?= $sfee['head_name'] ?></td>
                                <td><?= date("d/m/Y", $sfee['created_date']) ?></td>
                                <td><?= $sfee['payment_mode'] ?></td>
                                <td><?= $sfee['trans_id'] ?></td>
                                <td><?= $ca ?></td>
                                <td><?= "$".$sfee['amount'] ?></td>
                            </tr>
                            <?php } if(!empty($studfee)) { ?>
                            <tr>
                                <td style="display:none"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b><?= "$".$coupm_arr ?></b></td>
                                <td><b><?= "$".$amt_arr ?></b></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

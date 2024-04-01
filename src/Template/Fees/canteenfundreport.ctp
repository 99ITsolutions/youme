<style>
    /*#left-sidebar { left: -250px; }
    #main-content { width:100%;}*/
</style>
<?php
foreach($lang_label as $langlbl) 
{
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2250') { $lbl2250 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '243') { $lbl243 = $langlbl['title'] ; }
    if($langlbl['id'] == '1057') { $lbl1057 = $langlbl['title'] ; }
    if($langlbl['id'] == '1058') { $lbl1058 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '1422') { $lbl1422 = $langlbl['title'] ; }
    if($langlbl['id'] == '147') { $studnme = $langlbl['title'] ; }
    if($langlbl['id'] == '2186') { $clslbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2161') { $pdlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2160') { $pmlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2162') { $lbl2162 = $langlbl['title'] ; }
    if($langlbl['id'] == '2187') { $lbl2187 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '130') { $lbl130 = $langlbl['title'] ; }
    if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; }
    if($langlbl['id'] == '337') { $lbl337 = $langlbl['title'] ; }
    if($langlbl['id'] == '321') { $lbl321 = $langlbl['title'] ; }
    if($langlbl['id'] == '322') { $lbl322 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2237') { $lbl2237 = $langlbl['title'] ; }
    if($langlbl['id'] == '2238') { $lbl2238 = $langlbl['title'] ; }
    if($langlbl['id'] == '2249') { $lbl2249 = $langlbl['title'] ; }
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
                    <h2 class="col-md-11 heading"><?= $lbl2250 ?></h2>
                    <ul class="header-dropdown">
                        <li><a href="<?= $baseurl ?>fees/downloadcanteenfund/<?= $str_startdate ?>/<?= $str_enddate ?>" class="btn btn-info" title="<?= $lbl1422 ?>" <?= $styledr ?>><?= $lbl1422 ?> </a></li>
                        <li><a href="<?= $baseurl ?>fees/canteen" title="Back" class="btn btn-sm btn-success" ><?= $lbl41 ?></a></li>
                    </ul>
                </div>
            </div>
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'canteenfundreport'] , 'method' => "post"  ]);  ?>
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
                            <!--<th style="display:none">ID</th >-->
                            <th><?= $lbl147 ?></th>
                            <th><?= $lbl130 ?></th>
                            <th><?= $lbl337 ?></th>
                            <th><?= $lbl321 ?></th>
                            <th><?= $lbl322?></th>
                            <th><?= $lbl2237?></th>
                            <th><?= $lbl2238 ?></th>
                            <th><?= $lbl2249 ?></th>
                        </thead>
                        <tbody id="feeconsolid_body" class="modalrecdel">
                            <?php if(!empty($feecanteen_details)) {
                            $n=1;
                            $ttlamt = [];
                            foreach($feecanteen_details as $value){
                                if(!empty($sclsub_details[0]))
                                { 
                                    if(strtolower($value['sclsection']) == "creche" || strtolower($value['sclsection']) == "maternelle") {
                                        $clsmsg = "kindergarten";
                                    }
                                    elseif(strtolower($value['sclsection']) == "primaire") {
                                        $clsmsg = "primaire";
                                    }
                                    else
                                    {
                                        $clsmsg = "secondaire";
                                    }
                                    $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                    if(in_array($clsmsg, $subpriv)) { 
                                        $show = 1;
                                    }
                                    else
                                    {
                                        $show = 0;
                                    }
                                } else { 
                                    $show = 1;
                                }
                                if($show == 1) { $ttlamt[] = $value['amount'];
                                ?>
                                <tr>
                                    
                                    <td class="width45">
                                        <span><?=$value['student']?></span>
                                    </td>
                                    <td class="width45">
                                        <span><?=$value['adm']?></span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold"><?=$value['c_name']."-".$value['c_section']." (".$value['school_section'].")" ?></span>
                                    </td>
                                    <td>
                                        <span><?=$value['start_year']?></span>
                                    </td>
                                    <td>
                                        <span><?= "$".$value['amount']?></span>
                                    </td>
                                    <td>
                                        <span><?= "$".$value['daily_limit']?></span>
                                    </td>
                                    
                                    <td>
                                        <span><?= date("d-m-Y h:i A", $value['created_date']) ?></span>
                                    </td>
                                    <td>
                                        <span><?= ucfirst($value['deposit_by']) ?></span>
                                    </td>
                                </tr>
                                <?php
                                $n++;
                                }
                            }
                            ?>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <b>Total</b>
                                </td>
                                <td>
                                    <b><?= "$".array_sum($ttlamt) ?></b>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
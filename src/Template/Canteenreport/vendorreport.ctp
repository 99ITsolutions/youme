<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
   
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading">Canteen Vendor Report</h2>
                            <ul class="header-dropdown">
                                <li><?= $downloadpdf ?></li>
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="body" id="gen_pdf">
                        <div class="row clearfix col-md-12 ">
                            <div class="error" id="summryerror"><?= $error ?></div>
                        </div>
                        <div>
                            <?php echo $this->Form->create(false , ['url' => ['action' => 'vendorreport'] , 'id' => "selectvendorform" , 'method' => "post"  ]);  ?>
	                        <div class="row clearfix">
	                        <!--<div class="row col-md-12">-->
	                            <!--<div class="row">-->
            	                    <div class="col-sm-3">
            	                        <label>Vendor *</label>
                                        <div class="form-group">
                                            <select class="form-control vendor" id="vendor" name="vendor" required>
                                                <option value="">Choose Vendor</option>
                                                <?php
                                                foreach($vendor_details as $key => $val){
                                                    if($vendrid != '' && ($val['id'] == $vendrid))
                                                    {
                                                        $sel = "selected";
                                                    }
                                                    else
                                                    {
                                                        $sel = "";
                                                    } ?>
                                                    <option  value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['vendor_company']; ?> </option>
                                                <?php
                                                }
                                                ?>
                                            </select> 
                                        </div>
            	                    </div>
            	                    <div class="col-sm-3">
            	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?>*</label>
                                        <div class="form-group">
                                            <?php  if(!empty($startdate)) {
                                                $startdate = date("d-m-Y", $startdate);
                                            }
                                            else
                                            {
                                                $startdate = '';
                                            }?>
                                            <input type="text" class="form-control dobirthdatepicker" id="startdate" value="<?= $startdate ?>" data-date-format="dd-mm-yyyy" name="startdate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1761') { echo $langlbl['title'] ; } } ?>*">
                                        </div>
            	                    </div>
            	                    <div class="col-sm-3">
            	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?>*</label>
                                        <div class="form-group">
                                            <?php  if(!empty($enddate)) {
                                                $enddate = date("d-m-Y", $enddate);
                                            }
                                            else
                                            {
                                                $enddate = '';
                                            }?>
                                            <input type="text" class="form-control dobirthdatepicker" id="enddate" value="<?= $enddate ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1058') { echo $langlbl['title'] ; } } ?> *">
                                        </div>
            	                    </div>
            	                    <div class="col-sm-1">
            	                        <button type="submit" class="btn btn-primary mt-4 clsummary_report" id="clsummary_report"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
            	                    </div>
        	                    <!--</div>-->
    	                    </div>
    	                    <?php echo $this->Form->end();?>
    	                </div>
    	                
    	                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="meetinglink_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th>School Name</th>   
                                        <th>Date</th>
                                        <th>Food Quantity Undelivered </th>
                                        <th>Food Quantity Cancelled </th>
                                        <th>Food Quantity Pending </th>
                                        <th>Food Quantity Sold</th>
                                        <th>Total amount (food delivered)</th>  
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($cso_details)) {
                                        
                                    foreach($cso_details as $value)
                                    {
                                        ?>
                                        <tr>
                                            <td><?= $value['company']['comp_name'] ?></td>
                                            <td><?= $value['date'] ?></td>
                                            <td class="text-center"><?= $value->undelivr ?></td>
                                            <td class="text-center"><?= $value->canclld ?></td>
                                            <td class="text-center"><?= $value->pendng ?></td>
                                            <td class="text-center"><?= $value->delivr ?></td>
                                            <td class="text-center"><?= "$".$value->ttlamt ?></td>
                                        </tr>
                                        <?php
                                    } }
                                    ?>
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>

<!------------------ End --------------------->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?php
if(!empty($error))
{
    ?>
    <script>
        $("#summryerror").fadeIn().delay('5000').fadeOut('slow');
    </script>
    <?php
}
?>

 


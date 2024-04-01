
<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
</style>
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2352') { $lbl2352 = $langlbl['title'] ; }
    if($langlbl['id'] == '2353') { $lbl2353 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2343') { $lbl2343 = $langlbl['title'] ; }
    if($langlbl['id'] == '2344') { $lbl2344 = $langlbl['title'] ; }
    if($langlbl['id'] == '2345') { $lbl2345 = $langlbl['title'] ; }
    if($langlbl['id'] == '2346') { $lbl2346 = $langlbl['title'] ; }
    if($langlbl['id'] == '2347') { $lbl2347 = $langlbl['title'] ; }
    if($langlbl['id'] == '2348') { $lbl2348 = $langlbl['title'] ; }
    if($langlbl['id'] == '2349') { $lbl2349 = $langlbl['title'] ; }
} 
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading"><?= $lbl2352 ?></h2>
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
                            <?php echo $this->Form->create(false , ['url' => ['action' => 'schoolvendorreport'] , 'id' => "selectvendorform" , 'method' => "post"  ]);  ?>
	                        <div class="row clearfix">
            	                    <div class="col-sm-3">
            	                        <label><?= $lbl2353 ?> *</label>
                                        <div class="form-group">
                                            <select class="form-control vendor" id="vendor" name="vendor[]" required multiple>
                                                <option value="">Choose Vendor</option>
                                                <?php 
                                                if(!empty($vendor_details)) { 
                                                    if($vendrid != ''  && in_array("all", $vendrid))
                                                    {
                                                        $selc = "selected";
                                                    }
                                                    else
                                                    {
                                                        $selc = "";
                                                    } ?>
                                                    <option  value="all" <?= $selc ?> >All</option>
                                                <?php foreach($vendor_details['vendor_company'] as $key => $val){
                                                    if(!empty($vendrid) && in_array($vendor_details['id'][$key], $vendrid))
                                                    {
                                                        $sel = "selected";
                                                    }
                                                    else
                                                    {
                                                        $sel = "";
                                                    } ?>
                                                    <option  value="<?=$vendor_details['id'][$key] ?>" <?= $sel ?> ><?php echo $val; ?> </option>
                                                <?php
                                                } }
                                                ?>
                                            </select> 
                                        </div>
            	                    </div>
            	                    <div class="col-sm-3" style="flex: 0 0 20%; max-width: 20%;">
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
            	                    <div class="col-sm-3" style="flex: 0 0 20%; max-width: 20%;">
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
        	                   
    	                    </div>
    	                    <?php echo $this->Form->end();?>
    	                </div>
    	                
    	                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="meetinglink_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="display:none"></th>
                                        <th><?= $lbl2343 ?></th>   
                                        <th>Date</th>
                                        <th><?= $lbl2344 ?></th>
                                        <th><?= $lbl2345 ?></th>
                                        <th><?= $lbl2346 ?></th>
                                        <th><?= $lbl2347 ?></th>
                                        <th><?= $lbl2348 ?></th>  
                                        <!--<th>Action</th>-->
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($cso_details)) {
                                        
                                    foreach($cso_details as $value)
                                    {
                                        ?>
                                        <tr>
                                            <td style="display:none"></td>
                                            <td><?= $value['canteen_vendor']['vendor_company'] ?></td>
                                            <td><?= $value['date'] ?></td>
                                            <td class="text-center"><?= $value->undelivr ?></td>
                                            <td class="text-center"><?= $value->canclld ?></td>
                                            <td class="text-center"><?= $value->pendng ?></td>
                                            <td class="text-center"><?= $value->delivr ?></td>
                                            <td class="text-center"><?= "$".$value->tammt ?></td>
                                            <!--<td class="text-center"><a href="viewsclvdtl/<?= $value->vendor_id."/".$value['date'] ?>" class="btn btn-outline-secondary">View detailing</a></td>
                                        -->
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
<script>
    //$('.wrapper').on("change", ".scllist", function (e) {
    function getvendors(val) {
        //var sclid = $(this).val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        
        $("#vendor").html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/Canteenvendors/getvendors',
            data:{'sclid':val, 'multi': 'multi'},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                if(html)
                {    
                    $("#vendor").html(html);
                }
            }
        });
    } //) 
</script>

 

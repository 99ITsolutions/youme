<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
   
</style>
<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2209') { $lbl2209 = $langlbl['title'] ; }
    if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; }
    if($langlbl['id'] == '2222') { $lbl2222 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2223') { $lbl2223 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2224') { $lbl2224 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2225') { $lbl2225 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2226') { $lbl2226 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2208') { $lbl2208 = $langlbl['title'] ; }
    if($langlbl['id'] == '2228') { $lbl2228 = $langlbl['title'] ; }
    if($langlbl['id'] == '566') { $lbl566 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '637') { $lbl637 = $langlbl['title'] ; }
} 
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading">Vendor's Food Ordering</h2>
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
                            <?php echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "selectvendorform" , 'method' => "post"  ]);  ?>
	                        <div class="row clearfix">
	                                <div class="col-sm-3">
            	                        <label>School *</label>
                                        <div class="form-group">
                                            <select class="form-control school" id="listschool" name="school" required onchange="getvendors(this.value)">
                                                <option value="">Choose School</option>
                                                <?php
                                                foreach($school_details as $key => $val){
                                                    if($sclid != '' && ($val['id'] == $sclid))
                                                    {
                                                        $sel = "selected";
                                                    }
                                                    else
                                                    {
                                                        $sel = "";
                                                    } ?>
                                                    <option  value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['comp_name']; ?> </option>
                                                <?php
                                                }
                                                ?>
                                            </select> 
                                        </div>
            	                    </div>
            	                    <div class="col-sm-3"> <?php //print_r($vendrid); ?>
            	                        <label>Vendor *</label>
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
            	                    
            	                    <!--<div class="col-sm-3" style="flex: 0 0 20%; max-width: 20%;">
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
            	                    </div>-->
            	                    
            	                    <div class="col-sm-3" style="flex: 0 0 20%; max-width: 20%;">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?>*</label>
                                    <div class="form-group">
                                        <?php
                                        if(!empty($strtdate1)) {
                                            $strtdate1 = date("Y-m-d", strtotime($strtdate1));
                                        }
                                        else
                                        {
                                            $strtdate1 = '';
                                        }?>
                                        <input type="date" class="form-control typedate" id="strtdate" value="<?= $strtdate1 ?>" data-date-format="dd-mm-yyyy" name="startdate"  required placeholder="Start Date *">
                                        <input type="hidden" id="seldate" name="seldate">
                                    </div>
        	                    </div>
            	                    <div class="col-sm-3" style="flex: 0 0 20%; max-width: 20%;">
            	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?>*</label>
                                        <div class="form-group">
                                            <?php
                                            if(!empty($enddate1)) {
                                                $enddate1 = date("Y-m-d", strtotime($enddate1));
                                            }
                                            else
                                            {
                                                $enddate1 = '';
                                            }?>
                                            <input type="date" class="form-control typedate" id="enddate" value="<?= $enddate1 ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="End Date *">
                                            <input type="hidden" id="seldate" name="seldate">
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
                                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="deliverfood_table" data-page-length='50'>
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="display:none"></th>
                                                <th><?= $lbl637 ?></th> 
                                                <th>Vendor Company</th>
                                                <th><?= $lbl2209 ?></th>     
                                                <th><?= $lbl2208 ?></th>
                                                <th><?= $lbl2226 ?></th>  
                                                <th>Date</th>
                                                <th><?= $lbl2222 ?></th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="meetinglink_body" class="modalrecdel"> 
                                            <?php if(!empty($cso_details)) {
                                            foreach($cso_details as $value)
                                            { ?>
                                                <tr>
                                                    <td style="display:none"></td>
                                                    <td><?= ucfirst($value['company']['comp_name']) ?></td>
                                                    <td><?= ucfirst($value['canteen_vendor']['vendor_company']) ?></td>
                                                    <td><img src="c_food/<?= $value['food_item']['food_img'] ?>" width="50px" /></td>
                                                    <td><?= ucfirst($value['food_item']['food_name']) ?></td>
                                                    <td><?= $value['quant'] ?></td>
                                                    <td><?= $value->date ?></td>
                                                    <td><?= $value->timings ?></td>
                                                    <td>
                                                        <!--<button type="button" data-enddate='<?= $enddate1 ?>' data-strtdate='<?= $strtdate1 ?>' data-sclids ='<?= $sclidsd ?>' data-foodid='<?= $value['food_id'] ?>' data-schoolid='<?= $value['school_id'] ?>' data-date='<?= $value['date'] ?>' class="btn btn-sm btn-outline-secondary viewfdtl" title="View food detailing"><i class="fa fa-eye"></i></button>-->
                                                        <a class="btn btn-secondary" href="<?= $baseurl ?>cvendordashboard/foodquantitydetail/<?= $value['food_id']."/".$value['school_id']."/".$value['date'] ?>"><i class="fa fa-eye"></i></a>
                                                    </td>
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
window.onload=function(){ //from ww  w . j  a  va2s. c  o  m
    var today = new Date().toISOString().split('T')[0];
    //alert(today);
    document.getElementsByName("enddate")[0].setAttribute('min', today);
    document.getElementsByName("startdate")[0].setAttribute('min', today);
}
</script>
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

 


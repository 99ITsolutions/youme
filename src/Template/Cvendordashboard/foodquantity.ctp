<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
    .col-sm-2 {
        -ms-flex: 0 0 18%;
        flex: 0 0 18.2%;
        max-width: 18.2%;
        padding-right:10px !important;
        padding-left:10px !important;
    }
    
    @media (max-width: 767px) {
    .col-sm-3 {
        -ms-flex: 0 0 25%;
        flex: 0 0 24%;
        max-width: 24%;
    }
    .card .header .header-dropdown {
    position: absolute;
    top: 43px;
    right: 107px;
    list-style: none;
}
}
@media (max-width: 391px) {
    .col-sm-3 {
        -ms-flex: 0 0 25%;
        flex: 0 0 24%;
        max-width: 24%;
    }
    .card .header .header-dropdown {
    position: absolute;
    top: 43px;
    right: 70px;
    list-style: none;
}
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
    if($langlbl['id'] == '243') { $lbl243 = $langlbl['title'] ; }
    if($langlbl['id'] == '635') { $lbl635 = $langlbl['title'] ; }
    if($langlbl['id'] == '368') { $lbl368 = $langlbl['title'] ; }
    if($langlbl['id'] == '369') { $lbl369 = $langlbl['title'] ; }
    if($langlbl['id'] == '2385') { $lbl2385 = $langlbl['title'] ; }
} 

if(is_array($sclid))
{
    $sclidsd = implode(",", $sclid);
}
else
{
    $sclidsd = $sclid;
}
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-3 heading"><?= $lbl2385 ?></h2>
                            <ul class="header-dropdown">
                                <li><?= $downloadpdf ?></li>
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="body" id="gen_pdf">
                        <div class="row clearfix">
                            <div class="col-md-12 row"  style="max-width:100%">
    	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'foodquantity'] , 'id' => "summaryreportform" , 'method' => "post"  ]);  ?>
    	                    <input type="hidden" name="searchby" value="1">
    	                    <div class="row col-md-12">
    	                        <div class="col-sm-4">
        	                        <label><?= $lbl635 ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control listschool" id="listschool" name="schoolid[]" multiple required>
                                            <option value="">Choose School</option>
                                            <option value="all" <?php if($sclid == "all") { echo "selected"; } ?> >All</option>
                                            <?php
                                            foreach($scl_details as $key => $val){
                                                if(!empty($sclid) && (in_array($val['id'], $sclid)))
                                                {
                                                    $sel = "selected";
                                                }
                                                else
                                                {
                                                    $sel = "";
                                                }
                                            ?>
                                              <option  value="<?=$val['id']?>" <?= $sel ?>><?php echo $val['comp_name'];?> </option>
                                            <?php } ?>
                                        </select> 
                                    </div>
        	                    </div>
        	                    <div class="col-md-3 col-sm-6">
        	                        <label><?= $lbl368 ?>*</label>
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
        	                    <div class="col-md-3 col-sm-6">
        	                        <label><?= $lbl369 ?>*</label>
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
        	                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?= $lbl243 ?></button>
        	                    </div>
        	                    </div>
        	                    <?php echo $this->Form->end();?>
        	               </div>
        	               
    	                </div>
    	                
    	                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="deliverfood_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?= $lbl637 ?></th>     
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
                                        //print_r($cso_details);
                                    foreach($cso_details as $value)
                                    { ?>
                                        <tr>
                                            <td><?= ucfirst($value['company']['comp_name']) ?></td>
                                            <td><img src="../c_food/<?= $value['food_item']['food_img'] ?>" width="50px" /></td>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
window.onload=function(){ //from ww  w . j  a  va2s. c  o  m
    var today = new Date().toISOString().split('T')[0];
    //alert(today);
    document.getElementsByName("enddate")[0].setAttribute('min', today);
    document.getElementsByName("startdate")[0].setAttribute('min', today);
}
</script>



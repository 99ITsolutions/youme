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
    @media (min-width: 576px) {
    .col-sm-3 {
        -ms-flex: 0 0 25%;
        flex: 0 0 24%;
        max-width: 24%;
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
} 
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-3 heading">Food orders list</h2>
                            <ul class="header-dropdown">
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
    	                        <!--<div class="col-sm-3">
        	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?> *</label>
                                    <div class="form-group">
                                        <select class="form-control listschool" id="listschool" name="schoolid" required>
                                            <option value="">Choose School</option>
                                            <?php
                                            foreach($scl_details as $key => $val){
                                                if($sclids != '' && ($val['id'] == $sclids))
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
        	                    </div>-->
        	                    <div class="col-sm-3">
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
                                        <input type="date" class="form-control" id="strtdate" value="<?= $strtdate1 ?>" data-date-format="dd-mm-yyyy" name="startdate"  required placeholder="Start Date *">
                                        <input type="hidden" id="seldate" name="seldate">
                                    </div>
        	                    </div>
        	                    <div class="col-sm-3">
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
                                        <input type="date" class="form-control" id="enddate" value="<?= $enddate1 ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="End Date *">
                                        <input type="hidden" id="seldate" name="seldate">
                                    </div>
        	                    </div>
        	                    
        	                    <div class="col-sm-1">
        	                        <button type="submit" class="btn btn-primary mt-4 meetingreport" id="meetingreport"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
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
                                        <th><?= $lbl2209 ?></th>     
                                        <th><?= $lbl2208 ?></th>       
                                        <th><?= $lbl2226 ?></th>  
                                        <th>Date</th>
                                        <th><?= $lbl2222 ?></th>
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($cso_details)) {
                                        //print_r($cso_details);
                                    foreach($cso_details as $value)
                                    { ?>
                                        <tr>
                                            <td><img src="../c_food/<?= $value['food_item']['food_img'] ?>" width="50px" /></td>
                                            <td><?= ucfirst($value['food_item']['food_name']) ?></td>
                                            <td><?= $value['quant'] ?></td>
                                            <td><?= $value->date ?></td>
                                            <td><?= $value->timings ?></td>
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





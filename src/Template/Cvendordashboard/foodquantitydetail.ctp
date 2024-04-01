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
    right: 90px;
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
    right: 52px;
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
} 
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-3 heading">Food detailing list</h2>
                            <ul class="header-dropdown">
                                <!--<li><a href="<?= $baseurl ?>cvendordashboard/foodquantity" title="Back" class="btn btn-sm btn-success"><?= $lbl41 ?></a></li>-->
                                <li><?= $downloadpdf ?></li>
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                            </ul>
                        </div>
                        <div class="row clearfix col-md-12 mt-5">
                            <p><b>School: </b><?= $scl_details['comp_name'] ?></br>
                             <b>Food Name: </b><?= $foodname['food_name'] ?></br>
                             <b>Date: </b><?= $seldate ?></p>
                        </div>
                    </div>
                    <div class="body" id="gen_pdf">
    	                <div class="row  clearfix">
                        <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="deliverfood_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Order No</th>     
                                        <th>Student No.</th>     
                                        <th>Student Name</th>
                                        <th><?= $lbl2226 ?></th>  
                                        <th>Remarks(if any allergy)</th>
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($cso_details))
                                    { 
                                    foreach($cso_details as $value)
                                    {?>
                                        <tr>
                                            <td><?= ucfirst($value['order_no']) ?></td>
                                            <td><?= ucfirst($value['student']['adm_no']) ?></td>
                                            <td><?= ucfirst($value['student']['l_name']." ".$value['student']['f_name']) ?></td>
                                            <td><?= $value['quantity'] ?></td>
                                            <td><?= $value->remark ?></td>
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

<script>
window.onload=function(){ //from ww  w . j  a  va2s. c  o  m
    var today = new Date().toISOString().split('T')[0];
    //alert(today);
    document.getElementsByName("enddate")[0].setAttribute('min', today);
    document.getElementsByName("startdate")[0].setAttribute('min', today);
}
</script>



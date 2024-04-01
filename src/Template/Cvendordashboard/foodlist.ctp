<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2380') { $lbl2380 = $langlbl['title'] ; }
    if($langlbl['id'] == '2381') { $lbl2381 = $langlbl['title'] ; }
    if($langlbl['id'] == '2382') { $lbl2382 = $langlbl['title'] ; }
    if($langlbl['id'] == '2383') { $lbl2383 = $langlbl['title'] ; }
    if($langlbl['id'] == '2399') { $lbl2399 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2380') { $lbl2380 = $langlbl['title'] ; } 
}
?>
<style>
.fieldpaddings {
    padding: 0px 8px !important;
}
h5
{
	color: #007bff !important;
}
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?= $lbl2380 ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem foodserve_table" id="foodserve_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th><?= $lbl2399 ?></th>
                                <th><?= $lbl2381 ?></th>
                                <th><?= $lbl2382 ?></th>
                                <th><?= $lbl2383 ?></th>
                            </tr>
                        </thead>
                        <tbody id="fitembody" class="modalrecdel"> 
                        <?php foreach($foodinfo as $value) {
                            
                            echo '<tr>
                                    <td>
                                        <img src="../c_food/'.$value['food_item']['food_img'].'" width="50px" />
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['food_item']['food_name'].'</span>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">$'.$value['price'].'</span>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['food_item']['details'].'</span>
                                    </td>
                                </tr>';
                            } ?>
	                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
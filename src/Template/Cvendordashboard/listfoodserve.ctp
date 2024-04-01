<?php 
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
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
                <h2 class="heading">Food Order List - (<?= $fssinfo['weekday'] ."(". $fssinfo['timings']."))" ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                </ul>
                <!--<div class="row container mt-4">
                    <input type="text" class="form-control col-md-3 commondatepicker" id="enddate" value="<?= $enddate1 ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="Date">
                </div>-->
            </div>
           
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem foodserve_table" id="foodserve_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>Image</th>
                                <th>Food Name</th>
                                <th>Price</th>
                                <!--<th>Quantity</th>
                                <th>Action</th>-->
                            </tr>
                        </thead>
                        <tbody id="fitembody" class="modalrecdel"> 
                        <?php foreach($foodlist as $value) {
                            
                            echo '<tr>
                                    <td>
                                        <img src="../../c_food/'.$value['image'].'" width="50px" />
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['name'].'</span>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">$'.$value['price'].'</span>
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
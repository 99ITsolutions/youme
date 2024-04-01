<?php
    $time = '00:00';	
    $statusarray = array('Inactive','Active' );
    $smsarray = array('Yes','No' );
    $emailarray = array('Yes','No' );
    $school_no = $school_details['id'];
    $school_no++;
    
    foreach($lang_label as $langlbl) { 
        
        if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
        if($langlbl['id'] == '669') { $lbl669 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2216') { $lbl2216 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2217') { $lbl2217 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2218') { $lbl2218 = $langlbl['title'] ; }
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
                <h2 class="heading">Food Serve to School(Daywise)</h2>
                <ul class="header-dropdown">
                    <li><a href="<?= $baseurl ?>Canteenvendors/addservefoodscl" title="Add" class="btn btn-info" >Add Serve food</a></li>
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem foodserve_table" id="foodserve_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>Vendor Company</th>
                                <th>School</th>
                                <th>Class Section</th>
                                <th>Timings</th>
                                <th>WeekDay</th>
                                <th>Food Items</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="fitembody" class="modalrecdel"> 
                        <?php foreach($food_details as $value) {
                            $edit = '<a href='.$baseurl.'Canteenvendors/editservefoodscl/'.$value['id'].' class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa fa-edit"></i></a>';
    					    $delete = '<button type="button" data-url="deleteserve" data-id="'.$value['id'].'" data-str="Served FoodItems" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    		    
                            echo '<tr>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['canteen_vendor']['l_name'].' '.$value['canteen_vendor']['f_name'].'('.$value['canteen_vendor']['vendor_company'].')</span>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['company']['comp_name'].'</span>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['class_section'].'</span>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['timings'].'</span>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['weekday'].'</span>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['foodnames'].'</span>
                                    </td>
                                    <td>
        							    '.$edit.$delete.'
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
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="heading col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2211') { echo $langlbl['title'] ; } } ?></h2>
                    <h2 class="align-right col-md-6">
                        <a href="<?=$baseurl?>Canteenvendors/addassignfood" title="Add" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2212') { echo $langlbl['title'] ; } } ?></a>
                        <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                    </h2>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem fitem_table" id="fitem_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th>Vendor Company</th>
                                <th>Schools Alotted</th>
                                <th>Food Items</th>
                                <!--<th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2214') { echo $langlbl['title'] ; } } ?></th>-->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="fitembody" class="modalrecdel"> 
                        <?php foreach($vendor_dtl as $value) {
                        $edit = '<a href='.$baseurl.'Canteenvendors/editassignfood/'.$value['id'].' class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa fa-edit"></i></a>';
					    $delete = '<button type="button" data-url="deleteassign" data-id='.$value['id'].' data-str="Assigned Items/School" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    		    
                        echo '<tr>
                                <td class="width45">
                                    <label class="fancy-checkbox">
                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['canteen_vendor']['vendor_company'].'</span>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['schools'].'</span>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['vendorfood'].'</span>
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

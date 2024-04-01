<style>
.lb-details
{
    display:none !important;
}
th.productname {
width:400px !important;
}
</style>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1213') { echo $langlbl['title'] ; } } ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class=" container row clearfix">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1290') { echo $langlbl['title'] ; } } ?></label>
                        <select class="form-control" name="categ" id="categ" onchange="viewcategfilter(this.value)">
                            <option value="">Choose Categories</option>
                            <option value="all">All</option>
                            <?php
                            foreach($categories as $val)
                            {
                                ?><option value="<?= $val['id'] ?>"><?= ucfirst($val['name']) ?></option><?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1291') { echo $langlbl['title'] ; } } ?></label>
                        <select class="form-control" name="dealerfilter" id="dealerfilter" onchange="viewdealerfilter(this.value)">
                            <option value="">Choose Dealers</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1292') { echo $langlbl['title'] ; } } ?></label>
                        <select class="form-control" name="productsfilter" id="productsfilter" onchange="viewproductsfilter(this.value)">
                            <option value="">Choose Products</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem sclproduct_table" id="sclproduct_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1296') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1297') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '804') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1299') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1300') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1301') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1303') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="sclproductbody" class="modalrecdel">
                        <?php foreach($products as $pro) {
                            //print_r($pro);
                            ?>
                            <tr>
                                <td><span>
                                     <a class="example-image-link" href="<?=$baseurl ?>productimages/<?= $pro['product_image']?>" data-lightbox="example-1"><img class="example-image" src="<?=$baseurl ?>productimages/<?= $pro['product_image']?>" alt="image-1"  width="70px" height="70px"  /></a>
                       
                                </span></td>
                                <td><?= $pro['product_name'] ?></td>
                                <td><?= $pro['dealname'] ?></td>
                                <td><?= $pro['catname'] ?></td>
                                <td><?= '$'.$pro['price'] ?></td>
                                <td><?= $pro['quantity'] ?></td>
                                <td><button type="button" data-id="<?= $pro['id'] ?>" data-prodname ="<?= $pro['product_name'] ?>" data-logo="<?= $pro['product_image'] ?>" class="btn btn-sm btn-secondary contactform" data-toggle="modal" title="Purchase"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1496') { echo $langlbl['title'] ; } } ?></button></td>
                            </tr>
                            <?php
                        }
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


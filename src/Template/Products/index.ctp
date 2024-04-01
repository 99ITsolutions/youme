<style>
.lb-details
{
    display:none !important;
}
</style>


            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '840') { echo $langlbl['title'] ; } } ?> </h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addproduct"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '844') { echo $langlbl['title'] ; } } ?> </a></li>
                            </ul>
                        </div>
                        <div class=" container row clearfix">
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '841') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control" name="categ" id="categ" onchange="getcategfilter(this.value)">
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
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '842') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control" name="dealerfilter" id="dealerfilter" onchange="dealerfilter(this.value)">
                                        <option value="">Choose Sellers</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '843') { echo $langlbl['title'] ; } } ?></label>
                                    <select class="form-control" name="productsfilter" id="productsfilter" onchange="productsfilter(this.value)">
                                        <option value="">Choose Products</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem product_table" id="product_table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '855') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '856') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '857') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '847') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '851') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '852') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '853') { echo $langlbl['title'] ; } } ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productbody" class="modalrecdel">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>



    <!------------------ Extra HTML --------------------->

    
<div class="modal animated zoomIn" id="addproduct"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '858') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body ">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addproduct'] , 'id' => "addproductform" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '859') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="proimage"  required placeholder="Upload File *" required>
                        </div>
                    </div>
                    <div class="col-sm-8"></div>
                    <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '862') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <select class="form-control" name="dealers" id="dealers" onchange="getdealercate(this.value)">
                                <option value="">Choose Seller</option>
                                <?php
                                foreach($dealers as $val)
                                {
                                    ?>
                                    <option value="<?= $val['id'] ?>"><?= ucfirst($val['name']) ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '864') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <select class="form-control" name="dealrcat" id="dealrcat" >
                                <option value="">Choose Category</option>
                            </select>
                        </div>
                    </div>
		            <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '866') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '866') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '867') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="price" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '868') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '869') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="quantity" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '870') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="proderror"></div>
                        <div class="success" id="prodsuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary addprodbtn" id="addprodbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '871') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '872') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                </div>
            <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<!------------------ Edit Product --------------------->

    
<div class="modal animated zoomIn" id="editproduct"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1416') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editproduct'] , 'id' => "editproductform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" id="id"  name="id" >
                    <div class="col-sm-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '859') { echo $langlbl['title'] ; } } ?>*</label>
                        <p id="prodctimg"></p>
                        <div class="form-group">
                            <input type="file" class="form-control" name="eproimage" id="eproimage"   placeholder="Upload File *">
                            <input type="hidden" class="form-control" name="prodimg" id="prodimg">
                        </div>
                    </div>
                    <div class="col-sm-8"></div>
		             <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '862') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <select class="form-control dealers" name="dealers" id="pro_dealers" onchange="getprodealercate(this.value, this.class)">
                                <option value="">Choose Seller</option>
                                <?php
                                foreach($dealers as $val)
                                {
                                    ?>
                                    <option value="<?= $val['id'] ?>"><?= ucfirst($val['name']) ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '864') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <select class="form-control" name="dealrcat" id="pro_dealrcat" >
                                <option value="">Choose Category</option>
                            </select>
                        </div>
                    </div>
		            <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '866') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" id="pro_name" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '866') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '867') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="price" id="pro_price" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '868') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '869') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="quantity" id="pro_quantity" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '870') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="eproderror"></div>
                        <div class="success" id="eprodsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editcatbtn" id="editcatbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '872') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

<style>
table.dataTable th:nth-child(4) {
  width: 700px;
  max-width: 700px;
  word-break: break-word;
  white-space: inherit;
}

table.dataTable td:nth-child(4){
  width: 700px;
  max-width: 700px;
  word-break: break-word;
  white-space: inherit;
}
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '780') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li><a href="javascript:void(0)"  title="Delete All" data-str= "Study Abroad Queries" data-url = "queries/deleteallunivqueries"  id="deletealluniqueries" class="btn btn-sm btn-success deletealluniqueries"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1532') { echo $langlbl['title'] ; } } ?></a></li>
                            
                            <li><a href="<?= $baseurl ?>queries" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '784') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem univtable" id="univtable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
                                            </label>
                                        </th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '785') { echo $langlbl['title'] ; } } ?></th>
                                        <!--<th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '812') { echo $langlbl['title'] ; } } ?></th>-->
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '786') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '787') { echo $langlbl['title'] ; } } ?> </th>
                                        <th>  </th>
                                    </tr>
                                </thead>
                                <tbody id="univbody" class="modalrecdel">
                                    <?php foreach($queries_details as $query) { ?>
                                        <tr>
                                            <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $query['id'] ?>">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <?= ucfirst($query['name']); ?>
                                            </td>
                                            <td>
                                                <?= date("d M, Y", $query['created_date']); ?>
                                            </td>
                                            <td>
                                                <?= ucfirst($query['message']); ?>
                                            </td>
                                            <td>
                                                <button type="button" data-id="<?=$query['id']?>" data-url="deleteunivqueries" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Study Abroad Queries" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->create(false , ['url' => ['action' => 'adduniversity'] , 'id' => "adduniversityform", 'enctype' => "multipart/form-data"  , 'method' => "post"  ]);
echo $this->Form->end();  ?>

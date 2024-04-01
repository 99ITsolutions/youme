<style>
table#mentortable th:nth-child(4) {
  width: 700px;
  max-width: 700px;
  word-break: break-word;
  white-space: inherit;
}

table#mentortable td:nth-child(4){
  width: 700px;
  max-width: 700px;
  word-break: break-word;
  white-space: inherit;
}
#mentortable {
  width: 1100px;
}
</style>
<?php //print_r($queries_details); ?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1568') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li><a href="javascript:void(0)"  title="Delete All" data-str= "Scholarship Queries" data-url = "queries/deleteall"  id="deleteallqueries" class="btn btn-sm btn-success deleteall"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1532') { echo $langlbl['title'] ; } } ?></a></li>
                            <li><a href="<?= $baseurl ?>queries" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '784') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem mentortable" id="mentortable">
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
                                <tbody id="mentorbody" class="modalrecdel">
                                    <?php foreach($queries_details as $query) { 
                                        $fontwt = '';
                                        /*if($query['status'] == 0)
                                        {
                                            $fontwt = "style='font-weight:bold'";
                                        }*/
                                    ?>
                                        <tr>
                                            <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $query['id'] ?>">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td <?= $fontwt ?>>
                                                <?= ucfirst($query['name']); ?>
                                            </td>
                                            <td <?= $fontwt ?>>
                                                <?= date("d M, Y", $query['created_date']); ?>
                                            </td>
                                            <td <?= $fontwt ?>>
                                                <?= ucfirst($query['email_message']); ?>
                                            </td>
                                            <td>
                                                <button type="button" data-id="<?=$query['id']?>" data-url="delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Queries" data-type="confirm"><i class="fa fa-trash-o"></i></button>
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



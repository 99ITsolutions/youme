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
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '874') { echo $langlbl['title'] ; } } ?> </h2>
                        <ul class="header-dropdown">
                            <li><a href="javascript:void(0)"  title="Delete All" data-str= "Market Queries" data-url = "MarketQueries/deleteall"  id="deleteallmarket" class="btn btn-sm btn-success deleteallmarket"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1532') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem marktqueriestable" id="marktqueriestable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
                                            </label>
                                        </th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '877') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '878') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '879') { echo $langlbl['title'] ; } } ?> </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody id="marketqueriesbody" class="modalrecdel">
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
                                                <?= substr($query['description'], 0, 260); ?> 
                                                <p class="text-right"><a href="javascript:void(0);" class="btn btn-success marketdesc" data-id="<?= $query['id'] ?>" data-toggle="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '882') { echo $langlbl['title'] ; } } ?></a></p>
                                            </td>
                                            <td>
                                                <button type="button" data-id="<?=$query['id']?>" data-url="MarketQueries/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Market Queries" data-type="confirm"><i class="fa fa-trash-o"></i></button>
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

<div class="modal fade " id="fulldesc" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog " role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1418') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <div id="fulldescview"></div>
                        </div>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>  
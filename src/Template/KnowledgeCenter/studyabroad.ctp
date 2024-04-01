<style>
table.dataTable th:nth-child(2) {
  width: 800px;
  max-width: 800px;
  word-break: break-word;
  white-space: inherit;
}

table.dataTable td:nth-child(2){
  width: 800px;
  max-width: 800px;
  word-break: break-word;
  white-space: inherit;
}
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '39') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li style="width:160px; padding:0px 10px">
                                <select class=" form-control country " id="country_filter" onchange="country_filter(this.value)">
    					            <option value="">Choose One</option>
    					            <?php foreach($countries_details as $country) { ?>
    					            <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
    					            <?php } ?>
    					        </select>
                            </li>
                            <li><a href="<?= $baseurl ?>knowledgeCenter/adduniv" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '750') { echo $langlbl['title'] ; } } ?></a></li>
                            <li><a href="<?= $baseurl ?>knowledgeCenter" class="btn btn-info"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '740') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem univtable" id="univtable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '746') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '747') { echo $langlbl['title'] ; } } ?></th>
                                    </tr>
                                </thead>
                                <tbody id="univbody" class="modalrecdel">
                                    <?php foreach($univ_details as $uni) { ?>
                                        <tr>
                                            <td>
                                                <img src="<?=$baseurl?>univ_logos/<?= $uni['logo'] ?>" class="avatar" alt=""style="width: 140px; height: 100px;">
                                            </td>
                                            <td>
                                                <p>
                                                    <span style="width:90%; float:left; margin-bottom:8px;"><b><?= $uni['univ_name'] ?></b></span>
                                                    <span style="width:10%; float:right;">
                                                        <a href="<?= $baseurl ?>knowledgeCenter/edituniv/<?= $uni['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa fa-edit"></i></a>
                                                        <button type="button" data-id="<?=$uni['id']?>" data-url="deleteuniv" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="University" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                    </span>
                                                </p>
                                                <?php 
                                                if(!empty( $uni['country_name'] )) { ?>
                                                <p><b>Country: </b><span><?= $uni['country_name'] ?></span></p>
                                                <?php } 
                                                if(!empty( $uni['email'] )) { ?>
                                                <p><b>Email: </b><span><?= $uni['email'] ?></span></p>
                                                <?php } 
                                                if(!empty( $uni['contact_number'] )) { ?>
                                                <p><b>Contact No: </b><span><?= $uni['contact_number'] ?></span></p>
                                                <?php } 
                                                if(!empty( $uni['website_link'] )) { ?>
                                                <p><b>Website Link: </b><span><?= $uni['website_link'] ?></span></p>
                                                <?php } 
                                                if(!empty( $uni['academics'] )) { ?>
                                                <p><b>Courses Offered: </b><span><?= $uni['academics'] ?></span></p>
                                                <?php } ?>
                                                <p><b>Description: </b><span id="unidesc_<?= $uni['id'] ?>"><?= substr($uni['about_univ'], 0, 250); ?>...</span></p>
                                                <p class="align-right" style="margin-right: 35px;"><span><a href="javascript:void(0);" class="see_more" id="see_more<?= $uni['id'] ?>" data-id="<?= $uni['id'] ?>">See More</a></span</p>
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

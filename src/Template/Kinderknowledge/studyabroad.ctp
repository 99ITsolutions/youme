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
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1258') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li style="width:160px; padding:0px 10px">
                               <select class=" form-control country " id="country_filter" onchange="scountry_filter(this.value)">
    					            <option value="">Choose One</option>
    					            <?php foreach($countries_details as $country) { ?>
    					            <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
    					            <?php } ?>
    					        </select>
                            </li>
                            <li><a href="<?= $baseurl ?>Kinderknowledge" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem univtable" id="univtable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1263') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1264') { echo $langlbl['title'] ; } } ?> </th>
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
                                                    <span style="width:82%; float:left; margin-bottom:8px;"><b><?= $uni['univ_name'] ?></b></span>
                                                    <span style="width:18%; float:right;">
                                                        <a href="javascript:void(0);" data-id="<?= $uni['id'] ?>" data-uniname="<?= $uni['univ_name'] ?>" data-logo = "<?= $uni['logo'] ?>" class="btn btn-success youmecontct" title="Contact Form"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1266') { echo $langlbl['title'] ; } } ?></a>
                                                    </span>
                                                </p>
                                                <?php
                                                if(!empty( $uni['country_name'] )) { ?>
                                                <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1267') { echo $langlbl['title'] ; } } ?>: </b><span><?= $uni['country_name'] ?></span></p>
                                                <?php } 
                                                if(!empty( $uni['email'] )) { ?>
                                                <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1268') { echo $langlbl['title'] ; } } ?>: </b><span><?= $uni['email'] ?></span></p>
                                                <?php } 
                                                if(!empty( $uni['contact_number'] )) { ?>
                                                <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1269') { echo $langlbl['title'] ; } } ?>: </b><span><?= $uni['contact_number'] ?></span></p>
                                                <?php } 
                                                if(!empty( $uni['website_link'] )) { ?>
                                                <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1270') { echo $langlbl['title'] ; } } ?>: </b><span><?= $uni['website_link'] ?></span></p>
                                                <?php } 
                                                if(!empty( $uni['academics'] )) { ?>
                                                <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1271') { echo $langlbl['title'] ; } } ?>: </b><span><?= $uni['academics'] ?></span></p>
                                                <?php } ?>
                                                <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1272') { echo $langlbl['title'] ; } } ?>: </b><span id="unidesc_<?= $uni['id'] ?>"><?= substr($uni['about_univ'], 0, 250); ?>...</span></p>
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
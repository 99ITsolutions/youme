 <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '806') { echo $langlbl['title'] ; } } ?></h2>
                            <ul class="header-dropdown">
                                <li><a href="<?=$baseurl?>dealers/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '805') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem dealer_table" id="dealer_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '810') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '811') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '812') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '813') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '814') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '815') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($dealer_details as $user){
                                        ?>
                                        <tr>
                                            <td>
                                                <b><?=ucfirst($user['name'])?></b>
                                            </td>
                                            <td>
                                                <b><?=ucfirst($user['fname']). " ".ucfirst($user['lname'])?></b>
                                            </td>
                                            <td>
                                                <span><?=$user['email']?></span>
                                            </td>
                                            <td><span><?=$user['phone_no']?></span></td>
                                            <!--<td><?=$user['r']['name']?></td>-->
                                            <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '667') { $inactv =  $langlbl['title'] ; } } ?>
                                            <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '668') { $actv = $langlbl['title'] ; } } ?>
                                            <?php
                                            if($user['status'] == 0)
                                            {
                                            ?>
                                            <td><span><a href="javascript:void()" data-url="dealers/status" data-id="<?=$user['id']?>" data-status="<?=$user['status']?>" data-str="Dealer" class="btn btn-sm btn-outline-danger js-sweetalert" title="Status" data-type="status_change"><?=str_replace("0",$inactv,str_replace("1",$actv,$user['status']))?> </a></span></td>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                            <td><span><a href="javascript:void()" data-url="dealers/status" data-id="<?=$user['id']?>" data-status="<?=$user['status']?>" data-str="Dealer" class="btn btn-sm btn-outline-success js-sweetalert" title="Status" data-type="status_change"><?=str_replace("0",$inactv,str_replace("1",$actv,$user['status']))?> </a></span></td>
                                            <?php
                                                }
                                            ?>
                                            <td>
                                                <a href="<?=$baseurl?>dealers/edit/<?= md5($user['id'])?>" title="Edit" id="<?= $user['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                <button type="button" data-url="dealers/delete" data-id="<?=$user['id']?>" data-str="Dealer" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                            </td>
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


<div>
        <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
</div> 

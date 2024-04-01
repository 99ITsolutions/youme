 <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '605') { echo $langlbl['title'] ; } } ?></h2>
                            <ul class="header-dropdown">
                                <li><a href="<?=$baseurl?>subadmin/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '616') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="emp-table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '609') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '610') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '611') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '612') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '613') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '614') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '615') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($user_details as $user){
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                if($user['picture'] == "" ){
                                                    $img = "avatar.jpg" ;
                                                }
                                                else{
                                                    $img = $user['picture'] ;

                                                }
                                                    ?>
                                                <img src="<?=$baseurl?>img/<?=$img?>" class="rounded-circle avatar" alt="">
                                            </td>
                                            <td>
                                                <b><?=ucfirst($user['fname']). " ".ucfirst($user['lname'])?></b>
                                            </td>
                                            <td>
                                                <span><?=$user['email']?></span>
                                            </td>
                                            <td><span><?=$user['phone']?></span></td>
                                            <td><?=$user['r']['name']?></td>
                                            <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '667') { $inactv =  $langlbl['title'] ; } } ?>
                                            <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '668') { $actv = $langlbl['title'] ; } } ?>
                                            <?php
                                            
                                            if($user['status'] == 0)
                                            {
                                            ?>
                                            <td><span><a href="javascript:void()" data-url="subadmin/status" data-id="<?=$user['id']?>" data-status="<?=$user['status']?>" data-str="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '8') { echo $langlbl['title'] ; } } ?>" class="btn btn-sm btn-outline-danger js-sweetalert" title="Status" data-type="status_change"><?=str_replace("0",$inactv,str_replace("1",$actv,$user['status']))?> </a></span></td>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                            <td><span><a href="javascript:void()" data-url="subadmin/status" data-id="<?=$user['id']?>" data-status="<?=$user['status']?>" data-str="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '8') { echo $langlbl['title'] ; } } ?>" class="btn btn-sm btn-outline-success js-sweetalert" title="Status" data-type="status_change"><?=str_replace("0",$inactv,str_replace("1",$actv,$user['status']))?> </a></span></td>
                                            <?php
                                                }
                                            ?>
                                            <td>
                                                <a href="<?=$baseurl?>subadmin/edit/<?= md5($user['id'])?>" title="Edit" id="<?= $user['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                <?php
                                                    if($user['role'] != 2)
                                                    {
                                                ?>
                                                <button type="button" data-url="subadmin/delete" data-id="<?=$user['id']?>" data-str="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '8') { echo $langlbl['title'] ; } } ?>" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                <?php
                                                    }
                                                ?>
                                                <!--<a href="<?=$baseurl?>users/view/<?= md5($user['id'])?>" title="View" id="<?= $user['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-eye"></i></a>-->
                                              
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

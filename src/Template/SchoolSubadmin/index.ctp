 <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '77') { echo $langlbl['title'] ; } } ?></h2>
                            <ul class="header-dropdown">
                                <li><a href="<?=$baseurl?>schoolSubadmin/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '76') { echo $langlbl['title'] ; } } ?></a></li>
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="schoolsubtable" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '80') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '81') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1754') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '82') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '83') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '84') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '85') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '86') { echo $langlbl['title'] ; } } ?></th>
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
                                                <b><?=ucfirst($user['lname']). " ".ucfirst($user['fname'])?></b>
                                            </td>
                                            <td>
                                                <span><?=$user['jobtitle']?></span>
                                            </td>
                                            <td>
                                                <span><?=$user['email']?></span>
                                            </td>
                                            <td><span><?=$user['password']?></span></td>
                                            <?php 
                                            $privi = explode(",", $user['pri_name']);
                                            if(!empty($privi[1])) 
                                            { 
                                                $priname = $privi[0].",".$privi[1];
                                            }
                                            else
                                            {
                                                $priname = $privi[0];
                                            }?>
                                            <td><a href="javascript:void(0)" class="privilegess" data-id="<?= $user['pri_name'] ?>" ><span><?= $priname ?></span> <span>...</span></a></td>
                                            
                                            <!--<td><span><?=$user['phone']?></span></td>
                                            <td><?=$user['role']?></td>-->
                                            <?php
                                            if($user['status'] == 0)
                                            {
                                            ?>
                                            <td><span><a href="javascript:void()" data-url="schoolSubadmin/status" data-id="<?=$user['id']?>" data-status="<?=$user['status']?>" data-str="Subadmin" class="btn btn-sm btn-outline-danger js-sweetalert" title="Status" data-type="status_change"><?=str_replace("0","Inactive",str_replace("1","Active",$user['status']))?> </a></span></td>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                            <td><span><a href="javascript:void()" data-url="schoolSubadmin/status" data-id="<?=$user['id']?>" data-status="<?=$user['status']?>" data-str="Subadmin" class="btn btn-sm btn-outline-success js-sweetalert" title="Status" data-type="status_change"><?=str_replace("0","Inactive",str_replace("1","Active",$user['status']))?> </a></span></td>
                                            <?php
                                                }
                                            ?>
                                            <td>
                                                <a href="<?=$baseurl?>schoolSubadmin/edit/<?= md5($user['id'])?>" title="Edit" id="<?= $user['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                                <?php
                                                    if($user['role'] != 2)
                                                    {
                                                ?>
                                                <button type="button" data-url="schoolSubadmin/delete" data-id="<?=$user['id']?>" data-str="Subadmin" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
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

<!------------------ Add Class --------------------->

<div class="modal classmodal animated zoomIn" id="viewpri" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1527') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                <div class="row clearfix container">
                    <div id="all_priviledges">
                        
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>              


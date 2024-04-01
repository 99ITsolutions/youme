<style>
h2.heading a
{
    color:#242E3B !Important;
}
.profile-pic {
    border-radius: 50%;
    height: 90px;
    width: 90px;
    background-size: cover;
    background-position: center;
    background-blend-mode: multiply;
    vertical-align: middle;
    text-align: center;
    color: transparent;
    transition: all .3s ease;
    text-decoration: none;
    cursor: pointer;
    margin-left:45px;
    border-color:1px solid #fff;
}

.profile-pic:hover {
    background-color: rgba(0,0,0,.5);
    z-index: 10000;
    color: #fff;
    transition: all .3s ease;
    text-decoration: none;
}

.profile-pic span {
    display: inline-block;
    /*padding-top: 4.5em;*/
    padding-bottom: 4.5em;
}
.col_img {
    margin-bottom:20px !important;
   /* max-width:200px;
    min-width:180px;*/
}
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    bottom: 0px;
    left: 10px;
    padding: 5px 16px;
}
#right_icon {
    list-style:none;
    position: absolute;
    right: 15px;
}
#right_icon li {
    background: #444;
    color: #fff;
    padding: 5px;
    border-top: 1px solid #fff;
}
#right_icon li a i{
    color: #fff;
    border-top: 1px solid #fff;
}
</style>

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1816') { echo $langlbl['title'] ; } } ?></h2>     
                <ul class="header-dropdown">
                    <?php if(!empty($sclsub_details[0]))
                    { 
                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                        if(in_array("88", $roles)) { ?>
                            <li><a href="javascript:void(0);"  title="Add" class="btn btn-sm btn-success addkinderdash" data-toggle="modal" data-target="#addkinderdash"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '76') { echo $langlbl['title'] ; } } ?></a></li>
		                <?php }
                    } else { ?>
                        <li><a href="javascript:void(0);"  title="Add" class="btn btn-sm btn-success addkinderdash" data-toggle="modal" data-target="#addkinderdash"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '76') { echo $langlbl['title'] ; } } ?></a></li>
                   <?php } ?>
                    
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <div class="row kinderactivities"  id="kinderactivityimg">
                    <?php foreach($kinderdash as $value) {  
                    if($value['dash_name'] != "Virtual Class") {
                    ?>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body col_img">
                                    <ul id="right_icon">
                                        <?php if(!empty($sclsub_details[0]))
                                        { 
                                            $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                            if(in_array("105", $roles)) { ?>
                                                <li> 
                                                 <?php if($value['dash_name'] == "Virtual Class") 
                                                { ?>
                                                    <a href="<?= $baseurl ?>Kindergarten/virtualclass" title="Add Content for Activity"><i class="fa fa-plus"></i></a>
                                                <?php } else { ?>
                                                    <a href="<?= $baseurl ?>Kindergarten/activity/<?php echo $value['id']?>" title="Add Content for Activity"><i class="fa fa-plus"></i></a>
                                                <?php } ?>
                                                </li>
                                            <?php }
                                        } 
                                        else
                                        { ?>
                                            <li> 
                                             <?php if($value['dash_name'] == "Virtual Class") 
                                            { ?>
                                                <a href="<?= $baseurl ?>Kindergarten/virtualclass" title="Add Content for Activity"><i class="fa fa-plus"></i></a>
                                            <?php } else { ?>
                                                <a href="<?= $baseurl ?>Kindergarten/activity/<?php echo $value['id']?>" title="Add Content for Activity"><i class="fa fa-plus"></i></a>
                                            <?php } ?>
                                            </li>
                                        <?php }
                                        
                                        if(!empty($sclsub_details[0]))
                                        { 
                                            $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                            if(in_array("89", $roles)) { ?>
                                                <li> <a href="javascript:void(0)" data-name="<?= $value['dash_name'] ?>" data-img='<?= $value['image'] ?>' data-id="<?php echo $value['id']?>" data-toggle="modal" data-target="#editimages" class="editimage" title="Edit Activity Name & Image"><i class="fa fa-edit"></i></a></li>
        					                <?php } if(in_array("90", $roles)) { ?>
        					                    <li> <a href="javascript:void(0)" data-id="<?php echo $value['id']?>" data-url="Kindergarten/deleteactivity" class=" js-sweetalert " title="Delete" data-str="Kinder Activity" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                           <?php }
                                        } else { ?>
                                            <li> <a href="javascript:void(0)" data-name="<?= $value['dash_name'] ?>" data-img='<?= $value['image'] ?>' data-id="<?php echo $value['id']?>" data-toggle="modal" data-target="#editimages" class="editimage" title="Edit Activity Name & Image"><i class="fa fa-edit"></i></a></li>
                                            <li> <a href="javascript:void(0)" data-id="<?php echo $value['id']?>" data-url="Kindergarten/deleteactivity" class=" js-sweetalert " title="Delete" data-str="Kinder Activity" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                        <?php } ?>
                                        
                                    </ul>
                                    <div class="profile-pic" style="background-image: url('<?= $baseurl ?>img/<?= $value['image'] ?>')">
                                        <span class="glyphicon glyphicon-camera"></span>
                                    </div>
                                    
                                    <div class="p-15 text-light">
                                        <span style="color:#ffffff" ><b>
                                            <?php foreach($lang_label as $langlbl) { 
                                                if($langlbl['id'] == '2037') { $discvrlbl = $langlbl['title'] ; } 
                                                if($langlbl['id'] == '2038') { $animllbl = $langlbl['title'] ; } 
                                                if($langlbl['id'] == '2039') { $allactlbl = $langlbl['title'] ; } 
                                                if($langlbl['id'] == '2040') { $scinclbl = $langlbl['title'] ; } 
                                                if($langlbl['id'] == '2041') { $fruitveglbl = $langlbl['title'] ; } 
                                                if($langlbl['id'] == '2042') { $alphanumlbl = $langlbl['title'] ; } 
                                            } ?>
                                            <?php
                                           /* if($value['dash_name'] == "Virtual Class")
                                            {
                                                $dashname = 
                                            }
                                            else*/if($value['dash_name'] == "Alphabets & Numbers")
                                            {
                                                $dashname = $alphanumlbl; 
                                            }
                                            elseif($value['dash_name'] == "Coding")
                                            {
                                                $dashname = $value['dash_name'];
                                            }
                                            elseif($value['dash_name'] == "Fruits & Vegetables")
                                            {
                                                $dashname = $fruitveglbl;
                                            }
                                            elseif($value['dash_name'] == "Science")
                                            {
                                                $dashname = $scinclbl;
                                            }
                                            elseif($value['dash_name'] == "All Activities")
                                            {
                                                $dashname = $allactlbl;
                                            }
                                            elseif($value['dash_name'] == "Discovery")
                                            {
                                                $dashname = $discvrlbl;
                                            }
                                            elseif($value['dash_name'] == "Animals")
                                            {
                                                $dashname = $animllbl;
                                            }
                                            else
                                            {
                                                $dashname = ucfirst($value['dash_name']);
                                            }
                                            ?>
                                            <?= $dashname ?>
                                        </b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }  }?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php   echo $this->Form->create(false , [ 'method' => "post"  ]); echo $this->Form->end(); ?>


 <!------------------ Change Image --------------------->

    
<div class="modal classmodal animated zoomIn" id="editimages" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
            <h6 class="title" id="defaultModalLabel">Edit Activity  - <span id="activitynam"></span></h6>
            <button type="button" class=" close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
            </button>
	    </div>
        <div class="modal-body">
            <?php echo $this->Form->create(false , ['url' => ['action' => 'addimage'] , 'id' => "addimageform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1818') { echo $langlbl['title'] ; } } ?>*</label>
                            <input type="text" name="activity_name" id="activity_name" required class="form-control">
                        </div>
                    </div>
                    <input type="hidden" name="activity_id" id="activity_id" required >
                    <input type="hidden" name="activity_img" id="activity_img" required >
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1520') { echo $langlbl['title'] ; } } ?>
                            <span id="imgactivity"></span>
                        </label>
                    </div>
        	  	    <div class="slim" data-label="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '395') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 430px;" data-ratio="5:4" data-fetcher="fetch.php">
                        <input type="file" name="slim[]" id="pcrop" accept="image/jpeg, image/png, image/gif"/>
                    </div><br>
                    <div class="col-sm-12">
                        <div class="error" id="addimageerror"></div>
                        <div class="success" id="addimagesuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="addimagebtn" class="btn btn-primary addimagebtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '774') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>  

<!------------------ End --------------------->
    
   
<div class="modal classmodal animated zoomIn" id="addkinderdash" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
            <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1817') { echo $langlbl['title'] ; } } ?></h6>
            <button type="button" class=" close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
            </button>
	    </div>
        <div class="modal-body">
            <?php echo $this->Form->create(false , ['url' => ['action' => 'addactivity'] , 'id' => "addnewactivityform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1818') { echo $langlbl['title'] ; } } ?>*</label>
                        <input type="text" name="activity_name" required class="form-control">
                    </div>
                    
                    <div class="col-sm-12">
                        <label>
                            <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1520') { echo $langlbl['title'] ; } } ?>
                        </label>
                    </div>
        	  	    <div class="slim" data-label="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '395') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 430px;" data-ratio="5:4" data-fetcher="fetch.php">
                        <input type="file" name="slim[]" id="pcrop" accept="image/jpeg, image/png, image/gif"/>
                    </div><br>
                    <div class="col-sm-12">
                        <div class="error" id="addactivityerror"></div>
                        <div class="success" id="addactivitysuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="addactivitybtn" class="btn btn-primary addactivitybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '269') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '774') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>  

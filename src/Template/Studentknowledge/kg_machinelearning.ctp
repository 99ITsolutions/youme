<style>
div#knowldge_layout
{
    margin-left: 0px;
    display: inline-block !important;
    opacity: 1;+
    min-height:80px !important;
    max-width: 200px !important;
}
#knowldge_layout:hover .edit, #knowldge_layout:hover .delete, #knowldge_layout:hover .view {
	display: inline-block;
}

#knowldge_layout:hover
{
    border: solid 1px #242e3b;
    -moz-box-shadow: 7px 7px 7px #242e3b;
    -webkit-box-shadow: 7px 7px 7px #242e3b;
        box-shadow: 7px 7px 7px #242e3b;
}

.edit {
	margin-top: 7px;	
	padding-right: 7px;
	position: absolute;
	right: 0;
	top: 0;
	display: none;
}

.delete {
	margin-top: 32px;	
	padding-right: 7px;
	position: absolute;
	right: 0;
	top: 5px;
	display: none;
}
fa-lg {
    font-size: 1.2em;
}


.col-container {
  display: table;
  width: 100%;
}
.col {
  display: table-cell;
  padding: 16px;
}
.col_img img{
    height:100%;
    width:100%;
    max-height:250px;
    min-height:220px;
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
    top: 200px;
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
				<div class="row">
				    <input type="hidden" id="added_for" name="added_for" value="<?= $added_for ?>" > 
				    <?php
				    if($added_for == "kinder")
				    {
				        foreach($lang_label as $langlbl) { if($langlbl['id'] == '1519') { $label =  $langlbl['title'] ; } }
				    }
				    elseif($added_for == "primary")
				    {
				        foreach($lang_label as $langlbl) { if($langlbl['id'] == '1577') { $label =  $langlbl['title'] ; } }
				    }
				    elseif($added_for == "highscl")
				    {
				        foreach($lang_label as $langlbl) { if($langlbl['id'] == '1578') { $label =  $langlbl['title'] ; } }
				    }
				    ?>
				    <h2 class="col-lg-6 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2079') { echo $langlbl['title'] ; } } ?> -> <?= $label ?></h2>
				    <ul class="header-dropdown">
                        
                        <li style="width:160px; padding:0px 10px">
                            <select class=" form-control community_filter" id="comm_filter" onchange="stud_machinelearning_filter(this.value)">
					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '754') { echo $langlbl['title'] ; } } ?></option>
					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '755') { echo $langlbl['title'] ; } } ?></option>
					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '756') { echo $langlbl['title'] ; } } ?></option>
					            <option value="word"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1470') { echo $langlbl['title'] ; } } ?></option>
					        </select>
                        </li>
                        <li><a href="<?= $baseurl ?>Studentknowledge/machinelearning" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="body">
                <div class="row col-sm-12" id="viewcommunity">
                        <?php
                        foreach($know_details as $content)
                        {
                        ?>
                        <div class="col-sm-2 col_img">
                            <a href="<?=$baseurl?>Studentknowledge/viewmachinelearning/<?php echo md5($content['id']) ?>" class="viewknow" >
                            <?php if(!empty($content->image )) { ?>
                            <img src ="<?= $baseurl ?>img/<?= $content->image ?>" >
                            <?php } else { ?>
                            <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                            <?php } ?>
                            <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'word'){ ?><i class="fa fa-file-word-o"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                            </a>
                        </div>
                        <?php
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->create(false , ['method' => "post"  ]); ?>
<?php echo $this->Form->end(); ?>
</div>
</div>


<script>
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    

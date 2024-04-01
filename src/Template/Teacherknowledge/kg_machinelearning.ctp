<style>
.col-sm-2 {
    flex: 0 0 20%;
    max-width: 20%;
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
    background: #fff;
    color: #000;
    padding: 5px;
    border: 1px solid #000;
}
#right_icon li a i{
    color: #000;
    border-top: 1px solid #fff;
}
@media only screen and (max-width: 767px)
{
.header-page{
position: relative !important;
    left: 5px;     width: 100%;   
}
.class-gap{

}
.gap-width{
       width: 40% !important;
}
.col-sm-2 {
    flex: 0 0 100%;
    max-width: 100%;
}
}
</style>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <input type="hidden" name="addedfor" id="addedfor" value="<?= $added_for ?>" >
				    <input type="hidden" name="addedfor" id="added_for" value="<?= $added_for ?>" > 
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
				    <ul class="header-dropdown header-page">
                        <li style="width:160px; padding:0px 10px" class="gap-width">
                            <select class=" form-control class_s" id="clsfilter" onchange="tchr_guidecls_filter(this.value)">
                                <option value="">Choose Class</option>
                                <?php foreach($cls_details as $clsdtl) {
                                    
                                    //print_r($clsdtl); 
                                    if($added_for == "kinder" && ($clsdtl['school_sections'] == "Creche" || $clsdtl['school_sections'] == "Maternelle"))
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }
                				    elseif($added_for == "primary" && ($clsdtl['school_sections'] == "Primaire"))
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }
                				    elseif($added_for == "highscl" && ($clsdtl['school_sections'] != "Creche" && $clsdtl['school_sections'] != "Maternelle" && $clsdtl['school_sections'] != "Primaire" ) )
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }   
            				    } ?>
					        </select>
                        </li>
                        <li style="width:160px; padding:0px 10px" class="gap-width">
                            <select class=" form-control community_filter" id="comm_filter" onchange="tchr_machinelearning_filter(this.value)">
					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '754') { echo $langlbl['title'] ; } } ?></option>
					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '755') { echo $langlbl['title'] ; } } ?></option>
					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '756') { echo $langlbl['title'] ; } } ?></option>
					            <option value="word"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1470') { echo $langlbl['title'] ; } } ?></option>
					        
					        </select>
                        </li>
                        <li class="class-gap"><a href="<?= $baseurl ?>Teacherknowledge/machinelearning" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '759') { echo $langlbl['title'] ; } } ?></a></a></li>
                    </ul>
                </div>
                <div class="row mt-4">
                    <div class="col-md-3 ">
                        <select class="form-control js-states chosetitle" id="title_filter" onchange="tchrtitle_filter(this.value, 'machinelearning')">
                            <option value="">Choose Title</option>
				            <?php foreach($title_details as $title) { ?>
				            <option value="<?= $title['title'] ?>"><?= $title['title'] ?></option>
				            <?php } ?>
				        </select>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="row col-sm-12" id="viewcommunity">
                        <?php
                        foreach($know_details as $content)
                        { ?>
                        <div class="col-sm-2 col_img">
                            
                                <?php if($content['shared'] == "shared") { ?>
                                <button type="button" onclick="sharedcontent(<?= $content['id'] ?>)"  id="right_icon"><i class="fa fa-share"></i></button>
                                <?php } ?>
                                <a href="<?=$baseurl?>Teacherknowledge/viewmachinelearning/<?php echo md5($content['id']) ?>" class="viewknow" >
                                <?php if(!empty($content->image )) { ?>
                                <img src ="<?= $baseurl ?>img/<?= $content->image ?>" >
                                <?php } else { ?>
                                <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                <?php } ?>
                                <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'word'){ ?><i class="fa fa-file-word-o"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                                </a>
                                <p class="title" style="color:#000"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->title) ?></br>
                                <!--<b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->classname) ?></p>-->
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

<div class="modal fade" id="shared_content_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding-left: 15px;">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Shared with</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive" id="add_shared_to">
            
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!------------------ End --------------------->


<script>
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    

<style>
.col_img img{
    height:100%;
    width:100%;
    max-height:250px;
    min-height:220px;
    border:1px solid #000 !important;
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
</style>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <h2 class="col-lg-6 heading">Drop Box</h2>
				    <ul class="header-dropdown">
                        <!--<li style="width:160px; padding:0px 10px">
                            <select class=" form-control subj_s" id="subjfilter" onchange="dropbox_sub_filter(this.value)">
                                <option value="">Choose Subject</option>
                                <?php foreach($subjects as $key => $sub) {
                                    echo '<option value="'.$sid[$key].'">'.$sub.'</option>';
            				    } ?>
					        </select>
                        </li>
                        <li style="width:160px; padding:0px 10px">
                            <select class=" form-control community_filter" id="comm_filter" onchange="dropbox_filter(this.value)">
					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '754') { echo $langlbl['title'] ; } } ?></option>
					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '755') { echo $langlbl['title'] ; } } ?></option>
					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '756') { echo $langlbl['title'] ; } } ?></option>
					            <option value="word"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1470') { echo $langlbl['title'] ; } } ?></option>
					        
					        </select>
                        </li>-->
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '759') { echo $langlbl['title'] ; } } ?></a></a></li>
                    </ul>
                </div>
                <!--<div class="row mt-4">
                    <div class="col-md-3">
                        <select class="form-control js-states chosetitle" id="title_filter" onchange="droptitle_filter(this.value, 'machinelearning')">
                            <option value="">Choose Title</option>
				            <?php foreach($title_details as $title) { 
				                $strtdate = strtotime($title['start_date']);
                                $enddate = strtotime($title['end_date']);
                                $now = time();
                                if($now >= $strtdate && $now <= $enddate) {
				                ?>
				                <option value="<?= $title['title'] ?>"><?= $title['title'] ?></option>
				                <?php }
				            } ?>
				        </select>
                    </div>
                </div>-->
            </div>
            <div class="body">
                <div class="row col-sm-12" id="viewcommunity">
                        <?php
                        foreach($know_details as $content)
                        { 
                            $strtdate = strtotime($content->start_date);
                            $enddate = strtotime($content->end_date);
                            $now = time();
                            if($now >= $strtdate && $now <= $enddate) {
                        ?>
                        <div class="col-sm-3 col_img">
                            <a href="<?=$baseurl?>kinderdashboard/viewdropbox/<?php echo md5($content['id']) ?>" class="viewknow" >
                                <?php if(!empty($content->image )) { ?>
                                <img src ="<?= $baseurl ?>img/<?= $content->image ?>" >
                                <?php } else { ?>
                                <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                <?php } ?>
                                <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'word'){ ?><i class="fa fa-file-word-o"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                                </a>
                                <p class="title" style="color:#000"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->title) ?></br>
                                <b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '365') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->subject_name) ?></br>
                                <b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1448') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->teacher_name) ?></p>
                            </a>
                        </div>
                        <?php
                            }
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

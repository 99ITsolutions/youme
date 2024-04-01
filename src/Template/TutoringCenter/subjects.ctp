<style>
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
    max-height:230px;
    min-height:230px;
    margin-bottom:20px;
}
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    bottom: 20px;
    left: 10px;
    padding: 5px 16px;
}
#right_icon {
    list-style:none;
    position: absolute;
    right: 15px;
}
#right_icon li{
    background: #444;
    color: #fff;
    padding: 5px;
    border-top: 1px solid #fff;
}
.col_img button, .col_img a{
    background:none;
    color:#fff;
    box-shadow:none;
    border:none;
    text-align:center;
}
.col_img a{
    margin-left:5px;
}
</style>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
            				<div class="row">
            				    <h2 class="col-lg-6 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '540') { echo $langlbl['title'] ; } } ?></h2>
            				    <ul class="header-dropdown">
                				     <li style="width:160px; padding:0px 10px">
                                        <select class=" form-control stucommunity_filter" id="stutut_filter" onchange="stututorial_filter(this.value)">
            					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1285') { echo $langlbl['title'] ; } } ?></option>
            					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1286') { echo $langlbl['title'] ; } } ?></option>
            					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1287') { echo $langlbl['title'] ; } } ?></option>
            					            <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1288') { echo $langlbl['title'] ; } } ?></option>
            					        </select>
                                    </li>
                                    <li><a href="<?=$baseurl?>tutoringCenter/tutorialMeetings/<?=$tid?>/<?=$classId?>/<?=$subjectid?>" title="Online Tutoring Meetings" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1503') { echo $langlbl['title'] ; } } ?></a></li>
                                    <li><a href="<?= $baseurl?>tutoringCenter/index" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                
                                </ul>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row" id="viewtutcontent">
                                <?php foreach($content_details as $content){ if($content->display == 1) { ?>
                                <div class="col-sm-2 col_img">
                                    <a href="<?=$baseurl?>tutoringCenter/viewcontent/<?php echo md5($content['id']) ?>" title="view" >
                                    <ul id="right_icon">
                                        <li><i class="fa fa-eye"></i></li>
                                    </ul>
                                    <?php if($content->image != '' || $content->image != null) { ?>
                                        <img src ="<?= $baseurl ?>img/<?= $content->image ?>" ?>
                                    <?php } else { ?>
                                        <img src ="http://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                    <?php } ?>
                                    <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'audio'){ ?><i class="fa fa-headphones"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                                     <p class="title" style="color:#000"><b>Titre</b>: <?= ucfirst($content->title) ?></p>
                                     </a>
                                </div>
                                <?php } } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

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
    bottom: 60px;
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
.crop_preview {
	background:#e1e1e1;
	width:300px;
	padding:30px;
	height:300px;
	margin-top:30px
}
</style>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '58') { $newest = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $back = $langlbl['title'] ; }
    if($langlbl['id'] == '1497') { $libcontnt = $langlbl['title'] ; }
    
} ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <h2 class="col-lg-6 heading"><?= $libcontnt ?> (<?= $sub_name ?> (<?= $cls_name ?>))</h2>
				    <ul class="header-dropdown">
                       
                        <input type="hidden" value="<?= $classid ?>" id="class" name="class" >
                        <input type="hidden" value="<?= $subjectid ?>" id="subject" name="subject" >
                        <li style="width:160px; padding:0px 10px">
                            <select class=" form-control community_filter" id="tut_filter" onchange="viewlibrary_filter(this.value)">
					            <option value="newest"><?= $newest ?></option>
					            <option value="pdf">PDF</option>
					            <option value="video">Video</option>
					            <option value="audio">Audio</option>
					        </select>
                        </li>
                        <li>
                            <a href="<?= $baseurl ?>Studentsubjects?openmodal=1&subjectid=<?= $subjectid ?>&classid=<?= $classid ?>" class="btn btn-sm btn-success"><?= $back ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="body">
                <div class="row viewtutcontent" id="viewtutcontent">
                    <?php foreach($content_details as $content){ ?>
                    
                    <div class="col-sm-2 col_img">
                    <a href="<?=$baseurl?>ClassLibrary/viewcontent/<?php echo md5($content['id']) ?>" title="view" >
                        <!--<ul id="right_icon">
                           <li><i class="fa fa-eye"></i></li>
                        </ul>-->
                        <?php if($content->image != '' || $content->image != null) { ?>
                            <img src ="<?= $baseurl ?>img/<?= $content->image ?>" ?>
                        <?php } else { ?>
                            <img src ="http://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                        <?php } ?>
                        <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'audio'){ ?><i class="fa fa-headphones"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                    </a>
                     <p class="title" style="color:#000"><b>Titre</b>: <?= ucfirst($content->title) ?></p>
                    </div>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
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


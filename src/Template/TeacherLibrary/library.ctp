<!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>-->
<style>
.col-sm-2 {
    flex: 0 0 20%;
    max-width: 20%;
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
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <h2 class="col-lg-6 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1497') { echo $langlbl['title'] ; } } ?> (<?= $sub_name ?> (<?= $cls_name ?>))</h2>
				    <ul class="header-dropdown">
                       
                        <input type="hidden" value="<?= $classid ?>" id="class" name="class" >
                        <input type="hidden" value="<?= $subjectid ?>" id="subject" name="subject" >
                        <li style="width:160px; padding:0px 10px">
                            <select class=" form-control community_filter" id="tut_filter" onchange="viewtchrlibrary_filter(this.value)">
					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '380') { echo $langlbl['title'] ; } } ?></option>
					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '385') { echo $langlbl['title'] ; } } ?></option>
					            <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '386') { echo $langlbl['title'] ; } } ?></option>
					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '387') { echo $langlbl['title'] ; } } ?></option>
					        </select>
                        </li>
                        <li>
                            <a href="<?= $baseurl ?>TeacherLibrary" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '382') { echo $langlbl['title'] ; } } ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="body">
                <div class="row viewtutcontent" id="viewtutcontent">
                    <?php foreach($content_details as $content){ ?>
                    
                    <div class="col-sm-2 col_img">
                    <a href="<?=$baseurl?>teacherLibrary/viewcontent/<?php echo md5($content['id']) ?>" title="view" >
                        <!--<ul id="right_icon">
                           <li><i class="fa fa-eye"></i></li>
                        </ul>-->
                        <?php if($content->image != '' || $content->image != null) { ?>
                            <img src ="<?= $baseurl ?>img/<?= $content->image ?>" ?>
                        <?php } else { ?>
                            <img src ="http://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                        <?php } ?>
                        <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'audio'){ ?><i class="fa fa-headphones"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                        <p class="title" style="color:#000"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->title) ?></p>
                    </a>
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


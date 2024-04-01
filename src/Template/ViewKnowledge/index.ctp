<style>
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
    top: 200px;
    left: 10px;
    padding: 5px 16px;
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
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '1212') { $lbl1212 = $langlbl['title'] ; }
    if($langlbl['id'] == '1285') { $lbl1285 = $langlbl['title'] ; }
    if($langlbl['id'] == '1286') { $lbl1286 = $langlbl['title'] ; }
    if($langlbl['id'] == '1287') { $lbl1287 = $langlbl['title'] ; }
    if($langlbl['id'] == '1288') { $lbl1288 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
}
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <h2 class="col-lg-6 heading"> <span class="notranslate"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '35') { echo $langlbl['title'] ; } } ?></span></h2>
				    <ul class="header-dropdown">
                       
                        <!--<input type="hidden" value="<?= $classid ?>" id="class" name="class" >
                        <input type="hidden" value="<?= $subjectid ?>" id="subject" name="subject" >-->
                        <li style="width:160px; padding:0px 10px">
                            <select class=" form-control community_filter" id="know_filter" onchange="knowledgebase_filter(this.value)">
					            <option value="newest"><?= $lbl1285 ?></option>
					            <option value="pdf"><?= $lbl1286 ?></option>
					            <option value="video"><?= $lbl1287 ?></option>
					            <option value="audio"><?= $lbl1288 ?></option>
					        </select>
                        </li>
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                    
               
                
                        
                    </ul>
                </div>
            </div>
            <div class="body">
                <div class="row viewtutcontent" id="viewtutcontent">
                    <?php foreach($content_details as $content){ ?>
                    
                    <div class="col-sm-2 col_img">
                    <a href="<?=$baseurl?>viewKnowledge/view/<?php echo md5($content['id']) ?>" title="view" >
                        <?php if($content->image != '' || $content->image != null) { ?>
                            <img src ="<?= $baseurl ?>img/<?= $content->image ?>" ?>
                        <?php } else { ?>
                            <img src ="http://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                        <?php } ?>
                        <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'audio'){ ?><i class="fa fa-headphones"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                    </a>
                     <p class="title" style="color:#000"><b>Titre</b>: <?= ucfirst($content->file_title) ?></br>
                    </div>
                    
                    <?php } ?>
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

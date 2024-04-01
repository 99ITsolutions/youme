<style>
.col_img img{
    height:100%;
    width:100%;
    max-height:200px;
    min-height:180px;
}
.col_img {
    margin-bottom:20px !important;
}
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    bottom: 122px;
    left: 10px;
    padding: 5px 11px;
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
p {
    margin-bottom: 5px !important;
}
</style>
<?php 
foreach($lang_label as $langlbl) {
    if($langlbl['id'] == '41') { $bcklbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2037') { $discvrlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2038') { $animllbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2039') { $allactlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2040') { $scienclbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2041') { $frtveglbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2042') { $alphnumlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '388') { $titlelbl = $langlbl['title'] ; }
    if($langlbl['id'] == '136') { $clslbl = $langlbl['title'] ; }
    if($langlbl['id'] == '365') { $sublbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2097') { $lbl2097 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2098') { $lbl2098 = $langlbl['title'] ; }
    if($langlbl['id'] == '483') { $lbl483 = $langlbl['title'] ; }
    if($langlbl['id'] == '1531') { $lbl1531 = $langlbl['title'] ; }
}

if($dashname == "Discovery") {  $dashname1 = $discvrlbl; }
elseif($dashname == "Coding") {  $dashname1 = "Coding"; }
elseif($dashname == "Animals") {  $dashname1 = $animllbl; }
elseif($dashname == "All activities") {  $dashname1 = $allactlbl; }
elseif($dashname == "Science") {  $dashname1 = $scienclbl; }
elseif($dashname == "Fruits & Vegetables") {  $dashname1 = $frtveglbl; }
elseif($dashname == "Alphabets & Numbers") {  $dashname1 = $alphnumlbl; }
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <input type="hidden" value="<?= $dashid ?>" id="dashid">
				    <h2 class="col-lg-6 heading"><?= $dashname1 ?></h2>
				    <ul class="header-dropdown">
                        
                        <li style="width:160px; padding:0px 10px">
                            <select class=" form-control community_filter" id="comm_filter" onchange="filterkinderstud(this.value)">
					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '754') { echo $langlbl['title'] ; } } ?></option>
					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '755') { echo $langlbl['title'] ; } } ?></option>
					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '756') { echo $langlbl['title'] ; } } ?></option>
					            <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '757') { echo $langlbl['title'] ; } } ?></option>
					        </select>
                        </li>
                        <li><a href="<?= $baseurl ?>Kinderdashboard" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '759') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="body">
                <div class="row col-sm-12" id="viewdiscovery">
                        <?php
                        foreach($kinderlib as $content)
                        {
                        ?>
                        <div class="col-sm-3 col_img">
                            <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'audio'){ ?><i class="fa fa-headphones"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                                <a href="<?=$baseurl?>kinderdashboard/view/<?php echo md5($content['id']) ?>" class="viewknow" >
                                    <?php if(!empty($content->image )) { ?>
                                    <img src ="<?= $baseurl ?>img/<?= $content->image ?>" >
                                    <?php } else { ?>
                                    <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                    <?php } ?>
                                </a>
                                <p class="title" style="color:#000"><b><?= $titlelbl ?>:</b> <?php echo $content['title']?></p>
                                <p class="title" style="color:#000"><b><?= $clslbl ?>:</b> <?php echo $content['classname']?></p>
                                <p class="title" style="color:#000"><b><?= $sublbl ?>:</b> <?php echo $content['subject']?></p>
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
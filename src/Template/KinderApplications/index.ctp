<style>
.col {
  display: table-cell;
  padding: 16px;
}
.col_img img{
    height:100%;
    width:100%;
    max-height:200px;
    min-height:170px;
}
.col_img {
    margin-bottom:20px !important;
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
				<div class="row">
				    <h2 class="col-lg-6 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1824') { echo $langlbl['title'] ; } } ?></h2>
				    <!--<ul class="header-dropdown">
                        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#addknowledge" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '758') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>-->
                </div>
            </div>
            <div class="body">
                <div class="row col-sm-12" id="viewcommunity">
                        <?php
                        foreach($know_details as $content)
                        {
                        ?>
                        <div class="col-sm-3 col_img" style="height:215px !important">
                            <a href="<?=$baseurl?>KinderApplications/view/<?php echo md5($content['id']) ?>" class="viewknow" >
                            <?php if(!empty($content->image )) { ?>
                            <img src ="<?= $baseurl ?>applications_data/<?= $content->image ?>" >
                            <?php } else { ?>
                            <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 75px 0;border: 1px solid #cccccc;">
                            <?php } ?>
                            </a>
                            <p class="title" style="color:#000"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?>: <?= ucfirst($content->title) ?></p>
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

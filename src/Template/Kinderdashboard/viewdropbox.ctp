<style>
    #left-sidebar { left: -250px; }
    #main-content { width:100%;}
</style>
<link href="https://unpkg.com/nanogallery2/dist/css/nanogallery2.min.css" rel="stylesheet">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <?php if($knowledge_details[0]['file_type'] == "pdf")
                { ?>
                <div class="row">
                    <h2 class="col-lg-11 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                
                <?php }
                elseif($knowledge_details[0]['file_type'] == "word")
                { ?>
                <div class="row">
                    <h2 class="col-lg-8 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <h2 class="col-lg-2 align-right"><a href="<?=$baseurl?>img/<?php echo $knowledge_details[0]['links']?>" title="Download Document" download class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1493') { echo $langlbl['title'] ; } } ?></a></h2>
                    
                <?php } 
                else {?>    
                <div class="row">
                    <h2 class="col-lg-11 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                <?php }  ?>
                
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>kinderdashboard/kinderdropbox" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                
                <?php if(!empty($knowledge_details[0]['description'])) {?>
                <p class="mt-3"><b>Description - </b><?= ucfirst($knowledge_details[0]['description']) ?></p>
                <?php } ?>
            </div>
            <div class="body">
                <div class="row clearfix mb-4">
                    <?php if($knowledge_details[0]['file_type'] == "video")
                    { ?>
                        <div class="col-sm-12 text-center">
                            <?php
                            if($knowledge_details[0]['video_type']  == "youtube" )
                            {
                            ?>
                            <iframe width="860" height="350" src="<?= $knowledge_details[0]['links'] ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <?php
                            }
                            elseif($knowledge_details[0]['video_type']  == "vimeo" )
                            {
                            ?>
                            <iframe src="<?= $knowledge_details[0]['links'] ?>" width="860" height="350" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                            <?php
                            }elseif($knowledge_details[0]['video_type']  == "custom upload" )
                            {
                            ?>
                            <video width="100%" height="350" controls>
                              <source src="https://you-me-globaleducation.org/school/video_tchr_guide/<?php echo $knowledge_details[0]['links']?>" type="video/mp4">
                              <source src="movie.ogg" type="video/ogg">
                            </video>
                            <?php
                            }
                            else { ?>
                                <div width="860" height="350"><?= $knowledge_details[0]['links'] ?> </div>
                            <?php    }
                            ?>
                            
                        </div>
                    <?php
                    }
                    if(($knowledge_details[0]['file_type'] == "pdf") || ($knowledge_details[0]['file_type'] == "word"))
                    {
                         $valpag = $knowledge_details[0]['numpages'];
                        ?>
                        <div class="col-sm-12 text-center" style="margin: auto;height: 700px;overflow: scroll;">
                            <?php
                            for ($x = 0; $x < $valpag; $x++) {
                            ?>    
                            <img src="http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg" style="width: 100%;">
                            <?php  } ?>
                        </div>
                        <?php
                    }
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>    
<?php if(($knowledge_details[0]['file_type'] == "pdf")) { $valpag = $knowledge_details[0]['numpages']; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://unpkg.com/nanogallery2/dist/jquery.nanogallery2.js"></script>
<script>
/*    jQuery(document).ready(function () {
        
  jQuery("#my_nanogallery2").nanogallery2({
  	items:[
  	    <?php for ($x = 0; $x < $valpag; $x++) { ?> 
      { src: '<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg',      srct: '<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg' },
      <?php  } ?>
		],
    thumbnailWidth:  'auto',
  	thumbnailHeight: 170,
    itemsBaseURL:    'http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/',
		locationHash:    false
  });
});*/
</script>
<?php }else{ ?>
<script type="text/javascript" src="<?=$baseurl?>js_3rdparty/popper.min.js"></script>
<script src="<?=$baseurl?>js/libscripts.bundle.js"></script>
<script src="<?=$baseurl?>js/index.js"></script>
<?php } ?>
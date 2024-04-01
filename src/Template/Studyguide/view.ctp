<style>
    .comments_ht
    {
        max-height:315px;
        overflow-y:auto;
    }
    .reply { margin-left: 30px; }
    .reply_form {
    	margin-left: 40px;
    	display: none;
    }
    #comment_form { margin-top: 10px; }
    #left-sidebar { left: -250px; }
    #main-content { width:100%;}
   .nGY2 .nGY2GThumbnail {
        display: none !important;
    }
    .nGY2 .nGY2GThumbnail:first-child {
        display: block!important;
    }
</style>
<link href="https://unpkg.com/nanogallery2/dist/css/nanogallery2.min.css" rel="stylesheet">

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="col-lg-9 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>studyguide?classId=<?= $knowledge_details[0]['class_id'] ?>&subId=<?= $knowledge_details[0]['subject_id'] ?>" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                <?php if(!empty($knowledge_details[0]['description'])) {?>
                <p class="mt-3"><b>Description - </b><?= ucfirst($knowledge_details[0]['description']) ?></p>
                <?php } ?>
            </div>
            <div class="body">
                <div class="row clearfix mb-4">
                    <?php if($knowledge_details[0]['from_tab'] == 'exams'){ $valpag = $knowledge_details[0]['numpages']; ?>
                        <div class="col-sm-12 text-center" style="margin: auto;height: 500px;overflow: scroll;">
                            <?php for ($x = 0; $x < $valpag; $x++) { ?>    
                            <img src="http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/<?= $knowledge_details[0]['file_name'] ?><?=$x;?>.jpg" style="width: 100%;">
                            <?php  } ?>
                        </div>
                        <?php }else{ ?>
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
                    if($knowledge_details[0]['file_type'] == "pdf")
                    {
                         $valpag = $knowledge_details[0]['numpages'];
                        ?>
                       <!-- <div class="col-sm-12 text-center" style="margin: auto;height: 500px;overflow: scroll;">
                            <?php
                            for ($x = 0; $x < $valpag; $x++) {
                            ?>    
                            <?php  } ?>
                            <iframe src='http://docs.google.com/gview?embedded=true&url=https://you-me-globaleducation.org/school/img/<?php echo $knowledge_details[0]['links']?>' height='600px' width="100%"></iframe>
                          </div>-->
                          
                          <div class="col-sm-6  offset-4">
                            <div id="nanogallery2"
                                // gallery configuration
                                data-nanogallery2 = '{ 
                                  "thumbnailWidth":   "auto",
                              	  "thumbnailHeight":  600,
                                  "itemsBaseURL":     "http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/"
                                }'
                              >
                              <!-- content of the gallery -->
                              <?php
                            for ($x = 0; $x < $valpag; $x++) {
                            ?> 
                              <a href="<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg" data-ngthumb="<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg"></a>
                              <?php  } ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                        <?php } ?>
                    </div>
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
<script>
  $(document).ready(function(){
    // Toggle the dropdown on click
    $('.dropdown-toggle').click(function(){
      $('.dropdown-menu').toggle();
    });

    // Hide the dropdown when clicking outside of it
    $(document).click(function(event) {
      if (!$(event.target).closest('.dropdown').length) {
        $('.dropdown-menu').hide();
      }
    });
  });
</script>
<?php }else{ ?>
<script type="text/javascript" src="<?=$baseurl?>js_3rdparty/popper.min.js"></script>
<script src="<?=$baseurl?>js/libscripts.bundle.js"></script>
<script src="<?=$baseurl?>js/index.js"></script>
<?php } ?>
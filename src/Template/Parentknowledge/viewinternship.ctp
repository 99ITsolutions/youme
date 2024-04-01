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
</style>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <?php if($knowledge_details[0]['file_type'] == "pdf")
                { ?>
                <div class="row">
                    <h2 class="col-lg-11 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <!--<h2 class="col-lg-2 align-right"><a href="<?=$baseurl?>img/<?php echo $knowledge_details[0]['links']?>" title="Download PDF" download class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1492') { echo $langlbl['title'] ; } } ?></a></h2>-->
                    <!--<h2 class="col-lg-2 align-right"><a href="javascript:void(0);"  data-file="<?php echo $knowledge_details[0]['links']?>" title="View PDF" class="btn btn-sm btn-success viewpdffile"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1498') { echo $langlbl['title'] ; } } ?></a></h2>-->
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>parentknowledge/internship" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                <?php }
                elseif($knowledge_details[0]['file_type'] == "word")
                { ?>
                <div class="row">
                    <h2 class="col-lg-9 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <h2 class="col-lg-2 align-right"><a href="<?=$baseurl?>img/<?php echo $knowledge_details[0]['links']?>" title="Download Document" download class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1493') { echo $langlbl['title'] ; } } ?></a></h2>
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>parentknowledge/internship" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                <?php } 
                else {?>    
                <div class="row">
                    <h2 class="col-lg-11 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>parentknowledge/internship" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                <?php } 
                if(!empty($knowledge_details[0]['description'])) {?>
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
                        <div class="col-sm-12 text-center" style="margin: auto;height: 500px;overflow: scroll;">
                            <?php
                            for ($x = 0; $x < $valpag; $x++) {
                            ?>    
                            <img src="http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg" style="width: 100%;">
                            <?php  } ?>
                          
                        <!--<iframe src='http://docs.google.com/gview?embedded=true&url=https://you-me-globaleducation.org/school/img/<?php echo $knowledge_details[0]['links'] ?>' height='600px' width="100%" style="left:0px !important; position:inherit !important;"></iframe>-->
                        </div>
                        <?php
                    }
                    ?>
                  
                </div>
            </div>
        </div>
    </div>
</div>    


<div class="modal fade bd-example-modal-lg" id="viewpdffile" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">   
        <div class="modal-header header">
                <!--<h6 class="title" id="defaultModalLabel">Passcode</h6>-->
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
        <div class="modal-content">
            <div class="modal-body">
                <div id="viewfile"></div>
	        </div>
        </div>
    </div>
</div>    
<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
?>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2232') { $lbl2232 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2233') { $lbl2233 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2234') { $lbl2234 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2235') { $lbl2235 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2236') { $lbl2236 = $langlbl['title'] ; } 
    if($langlbl['id'] == '209') { $lbl209 = $langlbl['title'] ; } 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
} ?>
<style>
    .hide
    {
        display:none;
    }
    /*.uploaded-image img
    {
        width:150px;
        height:120px;
        padding:10px 0px;
    }*/
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?= $lbl2232 ?></h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0);" title="Back" class="btn btn-sm btn-success"><?= $lbl41 ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'banerimgs'] , 'id' => "banerimgsform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            
                            <div class="col-sm-8">
                                <label><?= $lbl2233 ?>*</label>
                                <div class="input-images-2" style="padding-top: .5rem;"></div>
                            </div>
                            <!--<div class="col-sm-6"></div>-->
                            <div class="col-sm-6 mt-2">
                                <label><?= $lbl2234 ?>*</label>
                                <div class="form-group">  
                                    <img src="canteen_banners/<?= $ci_details['meal_image1'] ?>" width="70px" height="45px" style="margin-bottom:15px;">
                                    <input type="file" class="form-control" name="image1"  >
                                    <input type="hidden" name="pimg1" value="<?= $ci_details['meal_image1'] ?>">
                                    <small id="fileHelp" class="form-text text-muted"><?= $lbl209 ?></small>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <label><?= $lbl2235 ?>*</label>
                                <div class="form-group">         
                                    <img src="canteen_banners/<?= $ci_details['meal_image2'] ?>" width="70px" height="45px" style="margin-bottom:15px;">
                                    <input type="file" class="form-control" name="image2"  >
                                    <input type="hidden" name="pimg2" value="<?= $ci_details['meal_image2'] ?>">
                                    <small id="fileHelp" class="form-text text-muted"><?= $lbl209 ?></small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label><?= $lbl2236 ?>*</label>
                                <div class="form-group">       
                                    <img src="canteen_banners/<?= $ci_details['meal_image3'] ?>" width="70px" height="45px" style="margin-bottom:15px;">
                                    <input type="file" class="form-control" name="image3"  >
                                    <input type="hidden" name="pimg3" value="<?= $ci_details['meal_image3'] ?>">
                                    <small id="fileHelp" class="form-text text-muted"><?= $lbl209 ?></small>
                                </div>
                            </div>
                            
                            <div class="col-sm-12">
                                <div class="error" id="tchrerror">
                                </div>
                                <div class="success" id="tchrsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="addtchrbtn" class="btn btn-primary"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '307') { echo $langlbl['title'] ; } } ?></button>
                   
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
            </div>
        </div>
    </div>
</div>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(document).ready(function() { 
    /*let preloaded = [
        {id: 1, src: 'https://picsum.photos/500/500?random=1'},
        {id: 2, src: 'https://picsum.photos/500/500?random=2'},
        {id: 3, src: 'https://picsum.photos/500/500?random=3'},
        {id: 4, src: 'https://picsum.photos/500/500?random=4'},
        {id: 5, src: 'https://picsum.photos/500/500?random=5'},
        {id: 6, src: 'https://picsum.photos/500/500?random=6'},
    ];
    
    $('.input-images-2').imageUploader({
        preloaded: preloaded,
        imagesInputName: 'photos',
        preloadedInputName: 'old'
    });*/


    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var images = "<?php echo $ci_details['slider_banner_image'] ?>";
    var imgs = images.split(",");
    var imgss = new Array();
    $('.input-images-2').html("");
    $.each(imgs, function(i, val) {
        var imgurl = baseurl + "/webroot/canteen_banners/"+val;
        var j = i+1;
        imgss.push({id: val, src: imgurl });
    });
    
   
    var preloaded = imgss;
    console.log(preloaded);
    $('.input-images-2').imageUploader({
        preloaded: preloaded,
        imagesInputName: 'photos',
        preloadedInputName: 'old'
    });
});
</script>
<style>
.gallery img 
{
    width: 20%;
    height: auto;
    cursor: pointer;
    transition: .3s;
}
.card .body {
    color: #444;
    padding: 0px 20px;
    font-weight: 400;
}
</style>
<style>

#Datepickk .d-calendar
{
    margin: 90px 0 0 0;
    max-width: 500px;
    max-height: 450px;
}

#Datepickk .d-table
{
    display:flex !important;
}
</style>

<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { $bcklbl = $langlbl['title'] ; } } ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card" style="height:100% !important;">
            <div class="header">
                <!--<h2 class="align-right"><a href="#" class="btn btn-success" onclick="closeCalendar()"><i class="fa fa-calendar"></i> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1185') { echo $langlbl['title'] ; } } ?></a></h2>-->
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $bcklbl ?></a></li>
                </ul>
            </div>
            <div class="gallery" id="gallery">
            <?php
                foreach($gallery_details as $gallery)
                { 
                    ?>
                    <div id="caldate_<?= date("m_d_Y", strtotime($gallery['event_date'])) ?>">
                    <div class="header" style="border:none !important;">
                        <h2 class="heading" style="font-size:20px !important; "><?= ucwords($gallery['title']) ?> ( <?=  date("d-m-Y", strtotime($gallery['event_date']))  ?>)</h2>
                        <p class="mt-3"><b>Description - </b><?= ucfirst($gallery['description']) ?></p>
                    </div>
                    <div class="body">
                        <?php
                        $images = explode(",", $gallery['images']);
                        //print_r($images);
                        foreach($images as $img)
                        {
                            $imageURL = 'img/'.$img;
                            ?>
                            <a href="/school/webroot/<?php echo $imageURL; ?>" data-fancybox="gallery" data-caption="<?php echo $img; ?>" >
                                <img src="/school/webroot/<?php echo $imageURL; ?>" alt="" style=" width: 300px !important; height:200px !important; border: 1px solid #242e3b; margin: 10px 5px; "/>
                            </a>
                            <?php 
                        } ?>
                    </div> 
                    </div>
                    <?php
                }
            ?>
            </div>
        </div>
    </div>
</div>


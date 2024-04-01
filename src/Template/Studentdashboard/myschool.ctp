<style>
h2.heading a
{
    color:#242E3B !important;
}
.card .body
{
    padding: 20px !important;
}
#ac-wrapper {
    position: absolute;
    top:0;
    left:0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,.6);
    z-index: 1001;
    margin: 0 auto !important;
}

#popup {
    width: 90% ;
    height: 500px;
    background: #FFFFFF;
    border: 4px solid #000;
    border-radius: 10px;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    box-shadow: #64686e 0px 0px 3px 3px;
    -moz-box-shadow: #64686e 0px 0px 3px 3px;
    -webkit-box-shadow: #64686e 0px 0px 3px 3px;
    position: relative;
    overflow-y:scroll;
    
}
</style>
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="header">
                    <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '35') { echo $langlbl['title'] ; } } ?></h2>
                    <ul class="header-dropdown">
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
	           </div>
	           <div class="body">
	               <div class="row clearfix">
	                    <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash ">
                                <div class="body" style="height:90px !important;">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF !important" href="<?=$baseurl?>viewGallery"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1218') { echo $langlbl['title'] ; } } ?></a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF" href="<?=$baseurl?>viewKnowledge"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '776') { echo $langlbl['title'] ; } } ?></a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash">
                                <div class="body">
                                    <div class="p-15 text-light">
                                        <span><b><a style="color:#FFFFFF" href="<?=$baseurl?>Codeconduct/view"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2143') { echo $langlbl['title'] ; } } ?></a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>  

	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<?php echo $this->Form->create(false , [ 'method' => "post"  ]); echo $this->Form->end(); ?>

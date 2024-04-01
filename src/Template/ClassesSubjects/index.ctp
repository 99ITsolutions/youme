<style>
    /*.bg-dash
    {
        background-color:#fca711 !important;
    }*/
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <!--<h2 class="heading">You-Me Academy</h2>-->
                        <ul class="header-dropdown">
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
				    </div>
                    <div class="body" id="knowledgecenter">
                        <div class="row clearfix">
				        <?php if(!empty($sclsub_details[0]))
                        { 
                            $privilages = explode(",", $sclsub_details[0]['privilages']); 
                            $roles = explode(",", $sclsub_details[0]['sub_privileges']);
                            if(in_array("1", $privilages)) { ?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <!-- <span><b><a style="color:#1d1d1d !important" href="javascript:void(0);" class="study_abroad" > Study Abroad </a></b></span>-->
                                           <span><b><a class="colorBtn" href="<?= $baseurl ?>classes"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '118') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        } else { ?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <!-- <span><b><a style="color:#1d1d1d !important" href="javascript:void(0);" class="study_abroad" > Study Abroad </a></b></span>-->
                                           <span><b><a class="colorBtn" href="<?= $baseurl ?>classes"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '118') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        if(!empty($sclsub_details[0]))
                        { 
                            $privilages = explode(",", $sclsub_details[0]['privilages']); 
                            $roles = explode(",", $sclsub_details[0]['sub_privileges']);
                            if(in_array("2", $privilages)) {?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a class="colorBtn" href="<?= $baseurl ?>subjects"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '10') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        } else { ?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a class="colorBtn" href="<?= $baseurl ?>subjects"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '10') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } 
                        if(!empty($sclsub_details[0]))
                        { 
                            $privilages = explode(",", $sclsub_details[0]['privilages']); 
                            $roles = explode(",", $sclsub_details[0]['sub_privileges']);
                            if(in_array("3", $privilages)) {?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a class="colorBtn" href="<?= $baseurl ?>classSubjects"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1562') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        } else { ?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a class="colorBtn" href="<?= $baseurl ?>classSubjects"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1562') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal classmodal animated zoomIn" id="abroad_study" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel" >Study Abroad</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'getuniv'] , 'id' => "getunivform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <label>Select Country</label>
                        <div class="form-group">                                    
                            <select class="form-control country" name="country" id="country">
                                <option value=""></option>
                                <?php foreach($countries_details as $country) { ?>
                                    <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="searcherror"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary searchbtn" id="searchbtn">Search Universities</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                   
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

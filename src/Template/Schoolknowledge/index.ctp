        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '593') { echo $langlbl['title'] ; } } ?></h2>
				        <!--<ul class="header-dropdown">
				            <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>-->
            			<!--<div class="row">
            				<h2 class="col-lg-12 align-right"><a href="javascript:void(0)" data-toggle="modal" data-target="#addknowledge" title="Add" class="btn btn-sm btn-success">Add New</a></h2>
                        </div>-->

		
                    </div>
                    <div class="body" id="knowledgecenter">
                        <div class="row clearfix">
				
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a  href="<?= $baseurl ?>Schoolknowledge/studyabroad"> 
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <!-- <span><b><a style="color:#1d1d1d !important" href="javascript:void(0);" class="study_abroad" > Study Abroad </a></b></span>-->
                                           <span class = "colorBtn"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '36') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a  href="<?= $baseurl ?>Schoolknowledge/localuniversity" >
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '37') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/community"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '938') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/howitworks"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1449') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/scholarship"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1450') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/mentorship"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1451') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/internship"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1452') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/intensive_english"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1453') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/leadership"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1454') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/newtechnologies"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1455') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/machinelearning"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2079') { echo $langlbl['title'] ; } } ?> </b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/stateexam"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1457') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
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


        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><span class="notranslate"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '733') { echo $langlbl['title'] ; } } ?></span></h2>
				
            			<!--<div class="row">
            				<h2 class="col-lg-12 align-right"><a href="javascript:void(0)" data-toggle="modal" data-target="#addknowledge" title="Add" class="btn btn-sm btn-success">Add New</a></h2>
                        </div>-->

		
                    </div>
                    <div class="body" id="knowledgecenter">
                        <div class="row clearfix">
				            
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span><b><a style="color:#ffffff" href="<?= $baseurl ?>Queries/studyabroad"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '777') { echo $langlbl['title'] ; } } ?> (<?= $abroad_details ?>)</a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a style="color:#ffffff" href="<?= $baseurl ?>Queries/localuniv"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '778') { echo $langlbl['title'] ; } } ?> (<?= $local_details ?>)</a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a style="color:#ffffff" href="<?= $baseurl ?>Queries/mentorship"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1451') { echo $langlbl['title'] ; } } ?> (<?= $mentor_details ?>)</a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a style="color:#ffffff" href="<?= $baseurl ?>Queries/scholarship"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1450') { echo $langlbl['title'] ; } } ?> (<?= $scholar_details ?>)</a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a style="color:#ffffff" href="<?= $baseurl ?>Queries/internship"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1452') { echo $langlbl['title'] ; } } ?> (<?= $intern_details ?>)</a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a style="color:#ffffff" href="<?= $baseurl ?>Queries/leadership"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1454') { echo $langlbl['title'] ; } } ?> (<?= $leader_details ?>)</a></b></span>
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


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
                        <h2 class="heading"> <span class="notranslate"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '593') { echo $langlbl['title'] ; } } ?></span></h2>
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
                                <a href="<?= $baseurl ?>Teacherknowledge/studyabroad">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <!-- <span><b><a style="color:#1d1d1d !important" href="javascript:void(0);" class="study_abroad" > Study Abroad </a></b></span>-->
                                           <span class="colorBtn" ><b> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '36') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/localuniversity" >
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class="colorBtn"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '37') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/community">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class="colorBtn"><b> <span class="notranslate"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '938') { echo $langlbl['title'] ; } } ?></span></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/howitworks"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1449') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/scholarship"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1450') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/mentorship"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1451') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/internship"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1452') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/intensive_english"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1453') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/leadership"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1454') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/newtechnologies"> 
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span class = "colorBtn" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1455') { echo $langlbl['title'] ; } } ?></a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/machinelearning"> 
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
                                <a href="<?= $baseurl ?>Teacherknowledge/stateexam"> 
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


<script>
    function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    



        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1449') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li><a href="<?= $baseurl ?>Schoolknowledge" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '759') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
				    </div>
                    <div class="body" id="knowledgecenter">
                        <div class="row clearfix">
				
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/scl_howitworks">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span style="color:#ffffff" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1564') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a  href="<?= $baseurl ?>Schoolknowledge/tchr_howitworks">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span style="color:#ffffff"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '15') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/stud_howitworks">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span style="color:#ffffff" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '240') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/parent_howitworks">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span style="color:#ffffff" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '449') { echo $langlbl['title'] ; } } ?></b></span>
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

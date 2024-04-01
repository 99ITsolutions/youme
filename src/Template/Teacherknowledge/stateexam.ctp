

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1457') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li><a href="<?= $baseurl ?>Teacherknowledge" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '759') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
				    </div>
                    <div class="body" id="knowledgecenter">
                        <div class="row clearfix">
				
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/latinphilo_stateexam">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span style="color:#ffffff" ><b>Latin-Philo</b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/mathphy_stateexam">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span style="color:#ffffff" ><b>Math-Physique</b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/chembio_stateexam">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span style="color:#ffffff" ><b>Chimie-Biologie</b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/general_stateexam">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span style="color:#ffffff" ><b>Pédagogie Générale</b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/commerciale_stateexam">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span style="color:#ffffff" ><b>Commerciale</b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="<?= $baseurl ?>Teacherknowledge/techniques_stateexam">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span style="color:#ffffff" ><b>Techniques et Autres</b></span>
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

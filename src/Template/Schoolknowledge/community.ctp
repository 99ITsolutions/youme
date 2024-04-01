        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '753') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li><a href="<?= $baseurl ?>Schoolknowledge" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '759') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
				    </div>
                    <div class="body" id="knowledgecenter">
                        <div class="row clearfix">
				
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/communityactivity/kinder">
                                <div class="card text-center bg-dash ">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                           <span style="color:#ffffff" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1519') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <a  href="<?= $baseurl ?>Schoolknowledge/communityactivity/primary">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span style="color:#ffffff"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1577') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <a href="<?= $baseurl ?>Schoolknowledge/communityactivity/highscl">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span style="color:#ffffff" ><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1578') { echo $langlbl['title'] ; } } ?></b></span>
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

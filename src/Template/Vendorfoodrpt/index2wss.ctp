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
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a class="colorBtn" href="<?= $baseurl ?>Canteenreport/vendorreport">Report by vendor</a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <span><b><a class="colorBtn" href="<?= $baseurl ?>Canteenreport/schoolreport">Report by school</a></b></span>
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


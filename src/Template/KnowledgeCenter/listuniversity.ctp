        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading">Universities List</h2>
                        <ul class="header-dropdown">
                            <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#contactform">Contact Form</a></li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem univ_table" id="univ_table">
                                <thead class="thead-dark">
                                    <tr>
                                        
                                        <th>Logo</th>
                                        <th>University Name </th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Website Link</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="univbody" class="modalrecdel">
                                    <?php foreach($univ_details as $uni) { ?>
                                        <tr>
                                            <td>
                                                <img src="<?=$baseurl?>univ_logos/<?= $uni['logo'] ?>" class="rounded-circle avatar" alt="">
                                            </td>
                                            <td>
                                                <b><?= $uni['univ_name'] ?></b>
                                            </td>
                                            <td>
                                                <span><?= $uni['email'] ?></span>
                                            </td>
                                            <td>
                                                <span><?= $uni['contact_number'] ?></span>
                                            </td>
                                            <td>
                                                
                                            </td>
                                            <td>
                                              
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

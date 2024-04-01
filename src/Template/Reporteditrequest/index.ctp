<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Subrecorder Max Marks Edit Request</h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem reqsts_table" id="reqsts_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <!--<th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>-->
                                <th>Request Status</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Trimestre</th>
                                <th>Periode</th>
                                <th>Teacher</th>
                            </tr>
                        </thead>
                        <tbody id="studentbody">
                        <?php $n=1; foreach($reportmmdtls as $value) { ?>
                            <tr>
                                <!--<td class="width45">
                                    <label class="fancy-checkbox">
                                        <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value['id'] ?>">
                                    </label>
                                </td>-->
                                <td>
                                    <?php if( $value['request_status'] == 0) { ?>
                                    <a href="javascript:void()" data-url="Reporteditrequest/status" data-id="<?=$value['id']?>" data-status="<?=$value['request_status']?>" data-str="Request Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>
                                    <?php 
                                    }
                                    else 
                                    { ?>
                                        <a href="javascript:void()" data-url="Reporteditrequest/status" data-id="<?=$value['id']?>" data-status="<?=$value['request_status']?>" data-str="Request Status" class="btn btn-sm js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>
                                    <?php 
                                    } ?>
                                </td>
                                <td>
                                    <span><?= $value['clsnam'] ?></span>
                                </td>
                                <td>
                                    <span><?=$value['subjnam'] ?></span>
                                </td>
                                <td>
                                    <span><?=$value['semester'] ?></span>
                                </td>
                                <td>
                                    <span><?=$value['periode'] ?></span>
                                </td>
                                <td>
                                    <span><?=$value['tchrnam'] ?></span>
                                </td>
                                
                            </tr>
                        <?php $n++; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
</div>
</div>
</div>


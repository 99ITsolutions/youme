<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <!--<h2 class="heading">Contact M</h2>-->
                <ul class="header-dropdown">
	                <li><a href="<?=$baseurl?>KinderMessages/add" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1380') { echo $langlbl['title'] ; } } ?></a></li>
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
                            
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="feestruc_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1381') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1382') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1383') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1384') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="strucbody" class="modalrecdel"> 
                        <?php
                                    $n=1;
                                    foreach($message_details as $value){
                                        if($value['read_count'] > 0){  $read ='class="font-weight-bold"'; }else{ $read =''; }
                                        ?>
                                        <tr>
                                           <td class="width45">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span <?= $read ?>><?=$value['sender']." - ". $value['student_no']. " (Class: ". $value['classname'] . ")"?></span>
                                            </td>
                                            <td>
                                                <?php if($value['read_count'] > 0){  ?><b>Re: </b><?php } ?><span <?= $read ?>><?= $value['subject']; ?></span>
                                            </td>
                                             <td>
                                                <span><?= date('M d, Y H:i', $value->created_date)?></span>
                                            </td>
                                            <td>
                                                <a href="<?=$baseurl?>KinderMessages/view/<?= base64_encode($value['id']) ?>"  class="btn btn-sm btn-outline-secondary" title="View"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                        $n++;
                                    }
                                    ?>
                                       
	                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


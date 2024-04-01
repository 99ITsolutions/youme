<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <!--<ul class="header-dropdown">
	                <li><a href="<?=$baseurl?>message/add" title="Add" class="btn btn-sm btn-success">New Message</a></li>
                </ul>-->
                <ul class="header-dropdown">
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
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '469') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '470') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2015') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '259') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '472') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="strucbody" class="modalrecdel"> 
                        <?php
                                    $n=1;
                                     foreach($message_details as $value){
                                        if($value['read_count'] > 0 || $value['read_msg'] == 0){  $read ='class="font-weight-bold"'; }else{ $read =''; }
                                        if(!empty($sclsub_details[0]))
                                        { 
                                            if(strtolower($value['sclsection']) == "creche" || strtolower($value['sclsection']) == "maternelle") {
                                                $clsmsg = "kindergarten";
                                            }
                                            elseif(strtolower($value['sclsection']) == "primaire") {
                                                $clsmsg = "primaire";
                                            }
                                            else
                                            {
                                                $clsmsg = "secondaire";
                                            }
                                            $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                            if(in_array($clsmsg, $subpriv)) { 
                                                $show = 1;
                                            }
                                            else
                                            {
                                                $show = 0;
                                            }
                                        } else { 
                                            $show = 1;
                                        }
                                        if($show == 1) {
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
                                                <?php if($value['attachment'] != "") { ?>
                                                <a href="<?=$baseurl?>messages/<?= $value['attachment'] ?>" download><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1745') { echo $langlbl['title'] ; } } ?></a>
                                                <?php } ?>
                                            
                                            </td>
                                             <td>
                                                <span><?= date('d M Y', $value->created_date)?></span>
                                            </td>
                                            <td>
                                                <?php if(!empty($sclsub_details[0]))
                                                { 
                                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                                    if(in_array("106", $roles)) { ?>
                                                        <a href="<?=$baseurl?>ParentCases/view/<?= base64_encode($value['id']) ?>"  class="btn btn-sm btn-outline-secondary" title="View"><i class="fa fa-eye"></i></a>
                                                    <?php }
                                                } else { ?>
                                                    <a href="<?=$baseurl?>ParentCases/view/<?= base64_encode($value['id']) ?>"  class="btn btn-sm btn-outline-secondary" title="View"><i class="fa fa-eye"></i></a>
                                                <?php } ?>
                                                
                                                
                                                
                                                
                                            </td>
                                        </tr>
                                        <?php
                                        $n++;
                                        }
                                    }
                                    ?>
	                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
        


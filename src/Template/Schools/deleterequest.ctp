            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <h2 class="heading col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '12') { echo $langlbl['title'] ; } } ?></h2>
            					<h2 class="col-lg-6 align-right">
            					    <a href="javascript:void(0)"  title="Delete All" data-str= "Students" data-url = "schools/deleteallstudents"  id="deleteallstud" class="btn btn-sm btn-success approve"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1532') { echo $langlbl['title'] ; } } ?></a>
            				    </h2>	
                            </div>
			
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem delstudent_table" id="delstudent_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?></th>
                                            <!--<th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '131') { echo $langlbl['title'] ; } } ?></th>-->
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '132') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '133') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '134') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '135') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?></th>
                                            <th>Delete Request Reason</th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '138') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentbody">
                                    <?php
                                    $n=1;
                                    foreach($studentdetails as $value){
                                        //print_r($value);
                                        ?>
                                        <tr>
                                            <td class="width45">
                                            <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value['id'] ?>">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span><?=$value['adm_no']?></span>
                                            </td>
                                            <!--<td>
                                                <span><?=$value['password']?></span>
                                            </td>-->
                                            <td>
                                                <span class="font-weight-bold"><?=$value['f_name']. " ".$value['l_name']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['s_f_name']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['s_m_name']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['mobile_for_sms']?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['class']['c_name']. "-".$value['class']['c_section']. " (". $value['class']['school_sections']. ")" ?></span>
                                            </td>
											<!-- <td>
                                                <span><?php //echo $value['class']['c_name']. "-". $value['class']['c_section']?></span>
                                            </td>-->
                                            <td>
                                                <span><?= $value['del_req_reason'] ?></span>
                                            </td>
                                            <td>
                                                <!--<a href="<?=$baseurl?>students/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>-->
                                                <?php /* $reqsent = "Delete Request Sent" ;
                                                $req = "Delete Request";
                                                if($value['delete_request'] == 0) { */ ?>
                                                <!--<a href="javascript:void()" data-url="student/deleterequest" data-id="<?=$value['id']?>" data-delreq="<?=$value['delete_request']?>" data-str="Student" class="btn btn-sm btn-outline-success deleterequeststu" title="Delete Request" data-type="status_change"><?=str_replace("0",$req,$value['delete_request']) ?> </a></span>-->
                                                <?php //} else { ?>
                                                <!--<a href="javascript:void()" data-url="student/deleterequest" data-id="<?=$value['id']?>" data-delreq="<?=$value['delete_request']?>" data-str="Student" class="btn btn-sm btn-outline-danger deleterequeststu" title="Delete Request" data-type="status_change"><?= str_replace("1",$reqsent,$value['delete_request'])?> </a></span>-->
                                                <?php // } ?>
                                                <button type="button" data-id="<?=$value['id']?>" data-url="../studentdelete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Student" data-type="confirm"><i class="fa fa-trash-o"></i></button>
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
            
            <div class="row clearfix">
             <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
 
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

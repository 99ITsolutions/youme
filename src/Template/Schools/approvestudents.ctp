



            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header row">
                            <h2 class="heading col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1442') { echo $langlbl['title'] ; } } ?></h2>
                            <h2 class="text-right col-md-6">
                                <a href="javascript:void(0)"  title="Approve All" data-str= "All Status" data-url = "schools/approveallstudents"  id="approvestudent" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1437') { echo $langlbl['title'] ; } } ?> </a> 
                                <a href="<?=$baseurl?>schools/approveStatus/<?= $sclid ?>" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '881') { echo $langlbl['title'] ; } } ?></a>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem student_table" id="approveTable" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '132') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '133') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '134') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '135') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '9') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '101') { echo $langlbl['title'] ; } } ?></th>
                                            <!--<th>Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody id="studentbody">
                                    <?php
                                    $n=1;
                                    foreach($student_details as $value){
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
                                                <span><?= $value['class']['c_name']. "-".$value['class']['c_section'] ?></span>
                                            </td>
											<!-- <td>
                                                <span><?php //echo $value['class']['c_name']. "-". $value['class']['c_section']?></span>
                                            </td>-->
                                            <td>
                                                <?php
                                                if(!empty($user_details[0]['id']))
                                                {
                                                    if( $value['status'] == 0)
                                                    {
                                                    ?>
                                                    <a href="javascript:void()" data-url="schools/studentapprovestatus" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Student Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>
                                                    <?php 
                                                    }
                                                    else 
                                                    { ?>
                                                        <a href="javascript:void()" data-url="schools/studentapprovestatus" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Student Status" class="btn btn-sm js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>
                                                    <?php 
                                                    }
                                                }
                                                else
                                                {
                                                    if( $value['status'] == 0)
                                                    {
                                                    ?>
                                                    <label class="switch">
                                                      <input type="checkbox" disabled >
                                                      <span class="slider round"></span>
                                                    </label>
                                                    <?php 
                                                    }
                                                    else 
                                                    { ?>
                                                        <label class="switch">
                                                          <input type="checkbox" checked disabled >
                                                          <span class="slider round"></span>
                                                        </label>
                                                    <?php 
                                                    }
                                                }
                                                ?>
                                                 
                                               
                                            </td>
                                            <!--<td>
                                                <a href="<?=$baseurl?>students/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                            </td>-->
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

<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
    .col-sm-2 {
        -ms-flex: 0 0 18%;
        flex: 0 0 18.2%;
        max-width: 18.2%;
        padding-right:10px !important;
        padding-left:10px !important;
    }
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1579') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
                    </div>
                    <div class="body" id="gen_pdf">
                        <div class="row clearfix col-md-12 ">
                            <div class="error" id="summryerror"><?= $error ?></div>
                        </div>
                        <div class="row clearfix">
	                        <!--<div class="col-md-3">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <select class="form-control class" id="schoolid" name="schoolid" required onchange="getschoolstudents(this.value)">
                                        <option value="">Choose School</option>
                                        <?php
                                        foreach($school_details as $key => $val){
                                            
                                            if($sclid != '' && ($val['id'] == $sclid))
                                            {
                                                $sel = "selected";
                                            }
                                            else
                                            {
                                                $sel = "";
                                            }
                                        ?>
                                          <option  value="<?=$val['id']?>" <?= $sel ?>><?php echo $val['comp_name'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select> 
                                </div>
    	                    </div>-->
    	                    <div class="col-md-3">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1003') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <select class="form-control session" id="session" name="session" required onchange="getsclsessionstudent(this.value)">
                                        <option value="">Choose Session</option>
                                        <?php 
                                        foreach($sessiondetails as $key => $val){
                                            if($sessionid != '' && ($val['id'] == $sessionid))
                                            {
                                                $sel = "selected";
                                            }
                                            else
                                            {
                                                $sel = "";
                                            }
                                        ?>
                                          <option  value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select> 
                                </div>
    	                    </div>
    	                    <div class="col-md-3">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <select class="form-control class" id="class" name="class" required onchange="getsclclassstudents(this.value)">
                                        <option value="">Choose Class</option>
                                        <?php foreach($cls_details as $key => $val) { 
                                            if(!empty($sclsub_details[0]))
                                            { 
                                                //echo "subadmin";
                                                if(strtolower($val['school_sections']) == "creche" || strtolower($val['school_sections']) == "maternelle") {
                                                    $clsmsg = "kindergarten";
                                                }
                                                elseif(strtolower($val['school_sections']) == "primaire") {
                                                    $clsmsg = "primaire";
                                                }
                                                else
                                                {
                                                    $clsmsg = "secondaire";
                                                }
                                                $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                                //print_r($subpriv);
                                                $clsmsg = trim($clsmsg);
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
                                            if($show == 1) {?>
                                            <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']." (".$val['school_sections']. ")";?> </option>
                                        <?php } } ?>
                                    </select> 
                                </div>
    	                    </div>
    	                    
    	                </div>
    	                
    	                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem libaccess_table" id="libaccess_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '131') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '132') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '133') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '134') { echo $langlbl['title'] ; } } ?></th>
                                        
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '135') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?></th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="libaccess_body" class="modalrecdel"> 
                                    <?php if(!empty($students_details)) {
                                    foreach($students_details as $value)
                                    {
                                        if(!empty($sclsub_details[0]))
                                        { 
                                            //echo "subadmin";
                                            
                                            if(strtolower($value['class']['school_sections']) == "creche" || strtolower($value['class']['school_sections']) == "maternelle") {
                                                $clsmsg = "kindergarten";
                                            }
                                            elseif(strtolower($value['class']['school_sections']) == "primaire") {
                                                $clsmsg = "primaire";
                                            }
                                            else
                                            {
                                                $clsmsg = "secondaire";
                                            }
                                            $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                            //print_r($subpriv);
                                            $clsmsg = trim($clsmsg);
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
                                            <td><span class="mb-0 font-weight-bold"><?= $value['adm_no'] ?></span></td>
                                            <td><span><?= $value['password'] ?></span></td>
                                            <td><span class="mb-0 font-weight-bold"><?= $value['l_name']." ".$value['f_name'] ?></span></td>
                                            <td><span><?= $value['s_f_name'] ?></span></td>
                                            <td><span><?= $value['s_m_name'] ?></span></td>
                                            <td><span><?= $value['emergency_number'] ?></span></td>
                                            <td><span><?= $value['class']['c_name'].'-'.$value['class']['c_section'].' ('.$value['class']['school_sections'].' )' ?></span></td>
                                            
                                        </tr>
                                        <?php }
                                    } }
                                    ?>
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>

<!------------------ End --------------------->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php
if(!empty($error))
{
    ?>
    <script>
        $("#summryerror").fadeIn().delay('5000').fadeOut('slow');
    </script>
    <?php
}
?>
<!------------------ End --------------------->

<script>


function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    



<style>
    .hide
    {
        display:none;
    }
    .input-group-text{
        font-size:0.8em;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<?php foreach($lang_label as $langlbl) 
{ 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '130') { $lbl130 = $langlbl['title'] ; }
    if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; }
    if($langlbl['id'] == '175') { $lbl175 = $langlbl['title'] ; }
    if($langlbl['id'] == '231') { $lbl231 = $langlbl['title'] ; }
    if($langlbl['id'] == '243') { $lbl243 = $langlbl['title'] ; }
    if($langlbl['id'] == '570') { $lbl570 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '571') { $lbl571 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '723') { $lbl723 = $langlbl['title'] ; }
    if($langlbl['id'] == '1160') { $lbl1160 = $langlbl['title'] ; }
    if($langlbl['id'] == '2144') { $lbl2144 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '2145') { $lbl2145 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '2147') { $lbl2147 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '2180') { $lbl2180 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '2181') { $lbl2181 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '2182') { $lbl2182 =  $langlbl['title'] ; } 
    
} //print_r($classes_details);
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header row">
                <h2 class="col-md-6 align-left heading"><?= $lbl2147 ?></h2>
                <h2 class="col-md-6 align-right">
                    <?php if(!empty($sclsub_details)){
                        if(in_array("115", $roles)) {?>
                    <a href="<?= $baseurl?>Studentdairyreport/createdairy" title="Create dairy note" class="btn btn-sm btn-success"><?= $lbl2180 ?></a>
                    <?php } } else { ?>
                    <a href="<?= $baseurl?>Studentdairyreport/createdairy" title="Create dairy note" class="btn btn-sm btn-success"><?= $lbl2180 ?></a>
                    <?php } ?>
                    <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()" ><?= $lbl41 ?> </a>
                </h2>
            </div>
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "report_sdform" , 'method' => "post"  ]);  ?>
            <div class="row container">
                
                <input type="hidden" id="sessionid" value="<?= $sessionid ?>">
                <div class="col-md-3">
                     <label><?= $lbl570 ?>*</label>
                    <select class="form-control class_s" name="class_sel" id="class_s" onchange=getclas_stud(this.value)>
                        <option value="">Choose Class</option>
                        <?php if(!empty($classes_details)) {
                            foreach($classes_details as $value) {
                                if($clsids == $value['id']) { $sel = 'selected'; } else { $sel = ''; } ?>
                                <option value="<?= $value['id'] ?>" <?= $sel ?> ><?=  $value['c_name']."-".$value['c_section'] ."( ".$value['school_sections']." )" ?></option>
                        <?php } } ?>
                    </select>
                </div>
                
                <div class="col-md-5">
                     <label><?= $lbl571 ?>*</label> <? //print_r($studentid); ?>
                    <select class="form-control" name="studentsel[]" id="liststudent" multiple>
                        <?php if($studentid != '') { 
                            foreach($reportdiaries as $stud)
            				{
            				    $stuids[] = $stud['id'];
            				}
            				$idstus = implode(",", $stuids); ?>
            				<option value="<?= $idstus ?>">All</option>
                            <?php foreach($reportdiaries as $stuval) {
                                if(in_array($stuval['id'], $studentid)) { $sel = 'selected'; } else { $sel = ''; } ?>
                                <option value="<?= $stuval['id'] ?>" <?= $sel ?> ><?=  $stuval['l_name']." ".$stuval['f_name'] ."( ".$stuval['email']." )" ?></option>
                        <?php } } ?>
                    </select>
                </div>
                
                <div class="col-sm-3">
                    <label><?= $lbl1160 ?>*</label>
                    <div class="form-group">
                        <input type="text" class="form-control dobirthdatepicker" id="enddate" value="<?= $diarydate ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="<?= $lbl1160 ?> *">
                    </div>
                </div>
                <div class="col-sm-1">
                    <button type="submit" class="btn btn-primary mt-4 diarysignedreport" id="dairysignedreport"><?= $lbl243 ?></button>
                </div>
               
                <div class="col-sm-12">
                    <div class="sderror"></div>
                    <div class="sdsuccess"></div>
                </div>
            </div>
             <?php echo $this->Form->end();?>
            <div class="body">
              
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem dairyrep_table" id="dairyrep_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <!--<th><input type="checkbox" name="allpublish" value="" id="allpublish" class="allpublish"></th>-->
                                        
                                        <th><?= $lbl723 ?></th>
                                        <th><?= $lbl147 ?></th>
                                        <th><?= $lbl231 ?></th>
                                        <th><?= $lbl175 ?></th>
                                        <th><?= $lbl2182 ?></th>
                                        <th><?= $lbl2181 ?></th>
                                    </tr>
                                </thead>
                                <tbody id="dairyrep_body" class="modalrecdel">  
                                <?php if(!empty($reportdiaries)) {
                                foreach($reportdiaries as $reportdiary) { 
                                 ?>
                                    <tr>
                                    <td><?= $reportdiary['adm_no'] ?></td>
                                    <td><?= $reportdiary['l_name']." ".$reportdiary['f_name'] ?></td>
                                    <td><?= $reportdiary['email'] ?></td>
                                    <td><?= $reportdiary['parent_logindetails']['parent_email'] ?></td>
                                    <td>
                                        <?php if($reportdiary['diaryid'] == "") { echo "Dairy Not updated"; }
                                        else { echo "Dairy Updated"; } ?>
                                    </td>
                                    <td>
                                        <?php if($reportdiary['parent_signature'] != "" && $reportdiary['diaryid'] != "") { echo "Signed"; }
                                        elseif($reportdiary['parent_signature'] == "" && $reportdiary['diaryid'] != "") { echo "Not Signed"; }
                                        else { echo ""; } ?>
                                    </td>
                                    </tr>
                                <?php } } ?>
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function getclas_stud(val)
    {
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var select_year = $("#sessionid").val();
        $("#liststudent").html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/Studentdairyreport/getstudent',
            data:'classId='+val+'&start_year='+select_year,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                $("#liststudent").html(html);
            }
        });
    }
    
</script>

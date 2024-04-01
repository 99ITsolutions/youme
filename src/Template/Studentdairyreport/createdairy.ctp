<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
#main-content {
    /*background: #fff;*/
}
.header {
    /*border: none !important;*/
}
.sidebar-main{
    background: -webkit-linear-gradient(right, #5be3db, #09bfcb);      
    padding: 0;  
}
.sidebar-inner{
    color: #fff;
    font-weight: 400;
    font-size: 15px;  
    border-bottom: 1px solid #00000014;
    padding: 52px 30px;
}
.sidebar-right{
    background: #fff;
    padding: 0;    
}
.sidebar-rightinner{
    padding: 21px 35px 9px 35px;     
    border-bottom: 1px solid #ececec;   
}
.sidebar-rightinner textarea {
    border: 1px solid #e4e4e4;
    border-radius: 5px;
    margin-bottom: 12px;
    padding: 8px 15px;
    font-size: 12px;
}
</style>
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
} 

if(!empty($classes_details)) {
    foreach($classes_details as $value) {
        $all[] = $value['id'];
} }
$allcls = implode(",", $all); ?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="col-md-6 align-left heading"><?= $lbl2180 ?></h2>
                    <h2 class="col-md-6 align-right">
                        <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()" ><?= $lbl41 ?> </a>
                    </h2>
                </div>
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'createdairy'] , 'method' => "post"  ]);  ?>
            <div class="row container mt-2">
                <input type="hidden" id="sessionid" name="sessionid" value="<?= $sessionid ?>">
                <input type="hidden" id="searchfilter" value="1" name="searchfilter">
                <div class="col-md-6">
                     <label><?= $lbl570 ?>*</label> 
                    <select class="form-control class_s" name="class_sel[]" id="class_s" multiple>
                        <option value="">Choose Class</option>
                        <?php if(!empty($classes_details)) {
                            $allcls = "all_".$allcls;
                            ?>
                            <option value="<?= $allcls ?>" <?php if($clsid[0] == $allcls) { echo 'selected'; } ?> >All</option>
                            <?php
                            foreach($classes_details as $value) {
                                if(in_array($value['id'], $clsid)) { $sel = 'selected'; } else { $sel = ''; } ?>
                                <option value="<?= $value['id'] ?>" <?= $sel ?> ><?=  $value['c_name']."-".$value['c_section'] ."( ".$value['school_sections']." )" ?></option>
                        <?php } } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label><?= $lbl1160 ?>*</label>
                    <div class="form-group">
                        <input type="text" class="form-control fordatepicker" id="enddate" value="<?= $diarydate ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="<?= $lbl1160 ?> *">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary mt-4 diarysignedreport" id="dairysignedreport"><?= $lbl243 ?></button>
                </div>
               
                <div class="col-md-12">
                    <div class="sderror"></div>
                    <div class="sdsuccess"></div>
                </div>
            </div>
             <?php echo $this->Form->end();?>
            
            <div class="body">
                <div class="row col-md-12" style="margin:0 auto !important; width:90% !important;">
                    <?php if($sf == 1) { ?> 
	                <h2 class="col-md-9 heading text-left mt-2" style="padding-top: 15px;padding-left: 3px;color: #595959 !important;"><?= $lbl2144 ?></h2>
	                <h2 class="col-md-3 heading text-right mt-2" style="padding-top: 15px;color: #595959 !important;padding-right: 20px;"> 
	                    <span>Date: </span><span id="dairydate"><?= $diarydate ?></span> 
                    </h2>
                    <?php } ?>
                </div>
                <?php if($sf == 1) { 
                $classids = implode(",",$clsid);
                
                    echo $this->Form->create(false , ['url' => ['action' => 'addstudentdairy'] , 'id' => "studentdairyform" , 'method' => "post"  ]);  ?>
        	            <div class="row clearfix m-1 main-diary">
        	                <input type="hidden" id="classid" name="classid" value="<?= $classids ?>">
                            <input type="hidden" id="date" value="<?= $diarydate ?>" name="date">
                            <input type="hidden" id="sfilter" value="<?= $sf ?>" name="sfilter">
        	                <div class="col-md-4 sidebar-main">
                                <div class="sidebar-inner">
                                    <input type="hidden" name="dairy_note" id="dairy_note" value="dairy_note"> 
                                    <span style="font-weight: bold;"><?= $lbl2145 ?>: </span> 
                                </div> 
                            </div>
                            <div class="col-md-8 sidebar-right">
                                <div class="sidebar-rightinner">
                                    <textarea class="form-control" name="note" id="note" rows="6" style="background:#c8930657;"><?= $note ?></textarea>
                                </div> 
                            </div>
                        </div>
                        <div class="row clearfix m-1">
                            <div class="col-sm-1">
    	                        <button type="submit" class="btn btn-primary mt-4 studdairybtn" id="studdairybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '95') { echo $langlbl['title'] ; } } ?></button>
    	                    </div>
    	                </div>
    	            <?php echo $this->Form->end(); ?>
                    
                <?php } ?>
                <div class="row container col-md-12">
                    <div class="error" id="sderror"></div>
                    <div class="success" id="sdsuccess"></div>
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
            url: baseurl + '/Tstudentdairy/getstudent',
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

<style>
    .col-sm-2, .col-sm-8 {
        padding-right: 0px !important;
        padding-left: 0px !important;
    }
    .col-sm-2 {
        border: 1px solid #ccc;
        border-radius: 5px;
        /*text-align: center;*/
        padding-top: 10px;
        padding-left: 15px !important;
        max-width:22%;
        flex: 0 0 22%;
    }
    .col-sm-8 {
        flex: 0 0 65%;
        max-width: 65%;
    }
    .col-sm-1 {
        flex: 0 0 6%;
        max-width: 6%;
    }
    .form-control {
        background-color: #ffffff;
        color: #000;
    }
    .form-control:focus{
        background-color: #ffffff;
        color: #000;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color:#ebebeb;
    }
    #main-content {
    background: #fff;
}
.header {
    border: none !important;
}
    .sidebar-main{
    background: -webkit-linear-gradient(right, #5be3db, #09bfcb);      padding: 0;  
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
        padding: 21px 35px 9px 35px;     border-bottom: 1px solid #ececec;   
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
    if($langlbl['id'] == '2144') { $lbl2144 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '2145') { $lbl2145 =  $langlbl['title'] ; } 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '243') { $lbl243 = $langlbl['title'] ; }
    if($langlbl['id'] == '1160') { $lbl1160 = $langlbl['title'] ; }
} ?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" style="background: url(https://you-me-globaleducation.org/school/img/background4.jpg);background-size: cover;background-attachment: fixed;">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading"><?= $lbl2144 ?></h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="body" id="gen_pdf">
	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'index'] , 'id' => "sdform" , 'method' => "post"  ]);  ?>
	                    <div class="row clearfix">
    	                    <div class="col-sm-3">
    	                        <label><?= $lbl1160 ?>*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control dobirthdatepicker" id="enddate" value="<?= $dairydate ?>" data-date-format="dd-mm-yyyy" name="enddate"  required placeholder="<?= $lbl1160 ?> *">
                                </div>
    	                    </div>
    	                    <div class="col-sm-1">
    	                        <button type="submit" class="btn btn-primary mt-4 clsummary_report" id="clsummary_report"><?= $lbl243 ?></button>
    	                    </div>
    	                </div>   
    	                <?php echo $this->Form->end();?>
        	        </div>
        	        
                    <?php 
                    $todaydt = date("d-m-Y", time());
                    $disablefield = 'disabled';
                    if($dairydate == $todaydt) { 
                        $disablefield = "";
                    }
                    ?>
    	            <div class="row" style="margin:0 auto !important; width:90% !important;">
    	                <h2 class="col-md-10 heading text-left mt-2" style="padding-top: 15px;padding-left: 3px;color: #595959 !important;"><?= $lbl2144 ?></h2>
    	                <h2 class="col-md-2 heading text-right mt-2" style="padding-top: 15px;color: #595959 !important;padding-right: 20px;"> 
    	                    <span>Date: </span><span id="dairydate"><?= $dairydate ?></span>
                        </h2>
        	            <?php   
        	            if(empty($dairydtl)) {
        	            echo $this->Form->create(false , ['url' => ['action' => 'addstudentdairy'] , 'id' => "studentdairyform" , 'method' => "post"  ]);  ?>
        	            <div class="row clearfix m-1 main-diary">
        	                <input type="hidden" name="clsid" id="clsid" value="<?= $clsid ?>">
        	                <input type="hidden" name="datedairy" id="datedairy" value="<?= $dairydate ?>"> 
        	                <?php foreach($subjectdtl as $subdtl) { ?>
            	                <div class="col-md-4 sidebar-main">
                                    <div class="sidebar-inner">
                                        <input type="hidden" name="subject_id[]" id="subject_id" value="<?= $subdtl['id'] ?>"> 
                                        <span style=""><?= $subdtl['subject_name'] ?>: </span>  
                                    </div> 
                                </div>
                                <div class="col-md-8 sidebar-right">
                                    <div class="sidebar-rightinner">
                                        <textarea class="form-control" name="subject_content[]" id="subject_content" rows="1" <?= $disablefield ?> placeholder="For Students..."></textarea>
                                        <textarea class="form-control" name="tsubject_content[]" id="subject_content" rows="1" disabled placeholder="For Teachers..."></textarea>
                                    </div> 
                                </div>
                            <?php } ?>  
                            <div class="col-md-4 sidebar-main">
                                <div class="sidebar-inner">
                                    <input type="hidden" name="dairy_note" id="dairy_note" value="dairy_note"> 
                                    <span style="font-weight: bold;"><?= $lbl2145 ?>: </span> 
                                </div> 
                            </div>
                            <div class="col-md-8 sidebar-right">
                                <div class="sidebar-rightinner">
                                    <textarea class="form-control" name="note" id="note" rows="5" style="background:#f3001685;" disabled></textarea>
                                </div> 
                            </div>
                        </div>
                        <?php if($disablefield == "") { ?>
                        <div class="row clearfix m-1">
                            <div class="col-sm-1">
    	                        <button type="submit" class="btn btn-primary mt-4 studdairybtn" id="studdairybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '95') { echo $langlbl['title'] ; } } ?></button>
    	                    </div>
    	                </div>
    	                <?php } echo $this->Form->end(); 
    	                } else { 
    	                echo $this->Form->create(false , ['url' => ['action' => 'addstudentdairy'] , 'id' => "studentdairyform" , 'method' => "post"  ]);  ?>
        	            <div class="row clearfix m-1 main-diary">
        	                <input type="hidden" name="clsid" id="clsid" value="<?= $clsid ?>">
        	                <input type="hidden" name="datedairy" id="datedairy" value="<?= $dairydate ?>"> 
        	                <?php if($dairycount != 1) {
        	                    foreach($dairydtl as $drydtl) {
            	                if($drydtl['subject_id'] != "") { ?>
                	                <div class="col-md-4 sidebar-main">
                                        <div class="sidebar-inner">
                                            <input type="hidden" name="subject_id[]" id="subject_id" value="<?= $drydtl['subject_id'] ?>"> 
                                            <span style="font-weight: bold;"><?= $drydtl['subject_name'] ?>: </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 sidebar-right">
                                        <div class="sidebar-rightinner">
                                            <textarea class="form-control" name="subject_content[]" id="subject_content" rows="1" <?= $disablefield ?> placeholder="For Students..."><?= $drydtl['subject_content'] ?></textarea>
                                            <textarea class="form-control" name="tsubject_content[]" id="subject_content" rows="1" disabled placeholder="For Teachers..."><?= $drydtl['tsubject_content'] ?></textarea>
                                        </div>
                                    </div>
                                    
                                <?php } }
        	                }
        	                else
        	                {
        	                    foreach($subjectdtl as $subdtl) { ?>
            	                <div class="col-md-4 sidebar-main">
                                    <div class="sidebar-inner">
                                        <input type="hidden" name="subject_id[]" id="subject_id" value="<?= $subdtl['id'] ?>"> 
                                        <span style=""><?= $subdtl['subject_name'] ?>: </span>  
                                    </div> 
                                </div>
                                <div class="col-md-8 sidebar-right">
                                    <div class="sidebar-rightinner">
                                        <textarea class="form-control" name="subject_content[]" id="subject_content" rows="1" <?= $disablefield ?> placeholder="For Students..."></textarea>
                                        <textarea class="form-control" name="tsubject_content[]" id="subject_content" rows="1" disabled placeholder="For Teachers..."></textarea>
                                    </div> 
                                </div>
                            <?php }   
        	                }
                            foreach($dairydtl as $drydtl) {
                                if($drydtl['note'] == "dairy_note") { ?>
                                    <div class="col-md-4 sidebar-main">
                                        <div class="sidebar-inner">
                                            <input type="hidden" name="dairy_note" id="dairy_note" value="dairy_note"> 
                                            <span style="font-weight: bold;"><?= $lbl2145 ?>: </span>
                                        </div>
                                    </div>
                                    <div class="col-md-8 sidebar-right">
                                        <div class="sidebar-rightinner">
                                            <textarea class="form-control" name="note" id="note" rows="5" style="background:#f3001685;" disabled><?= $drydtl['subject_content'] ?></textarea>
                                        </div>
                                    </div>
                                <?php } 
                            } ?>
                        </div>
                        <div class="row container col-md-12">
                            <div class="error" id="sderror"></div>
                            <div class="success" id="sdsuccess"></div>
                        </div>
                        <?php if($disablefield == "") { ?>
                        <div class="row clearfix m-1">
                            <div class="col-sm-1">
    	                        <button type="submit" class="btn btn-primary mt-4 studdairybtn" id="studdairybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '95') { echo $langlbl['title'] ; } } ?></button>
    	                    </div>
    	                </div>
    	                <?php } echo $this->Form->end(); 
    	                } ?>
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

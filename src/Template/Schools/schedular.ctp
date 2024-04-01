<?php 
foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
        if($langlbl['id'] == '669') { $lbl669 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1408') { $lbl1408 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1424') { $lbl1424 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1425') { $lbl1425 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1426') { $lbl1426 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1427') { $lbl1427 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1428') { $lbl1428 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1429') { $lbl1429 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1430') { $lbl1430 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2073') { $lbl2073 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2074') { $lbl2074 = $langlbl['title'] ; } 
        
        
        if($langlbl['id'] == '1556') { $lbl1556 = $langlbl['title'] ; }
        if($langlbl['id'] == '1830') { $mndylbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '1831') { $tuesdylbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '1832') { $wedlbl = $langlbl['title'] ; }
        if($langlbl['id'] == '1833') { $thurdylbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '1834') { $frilbl = $langlbl['title'] ; }
        if($langlbl['id'] == '1835') { $satlbl = $langlbl['title'] ; } 
        if($langlbl['id'] == '1999') { $lbl1999 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1998') { $lbl1998 = $langlbl['title'] ; } 
        
    } 
?>
<style> h5 { color: #007bff !important; } 
.fieldpaddings {
    padding: 0px 8px !important;
}
.marginrow {
    margin-left:0px !important;
    margin-right:0px !important;
}
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Schedular</h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>schools" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php	
                    echo $this->Form->create(false , ['url' => ['action' => 'scltimetable'] , 'id' => "scltimetblform" , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            <input type="hidden" value="<?= md5($school_details[0]['id']) ?>" id="sclid">
                            <input type="hidden" value="<?= $school_details[0]['id'] ?>" id="scl_id" name="scl_id">
                            <input type="hidden" value="<?= $school_details[0]['scl_privilages'] ?>" id="scl_priv" name="scl_priv">
                            <?php $exsclpriv =  explode(",", $school_details[0]['scl_privilages']); 
                            $checked = "";
                            $kinderpriv = 'style="display:none;"';
                            $checked1 =  ""; 
                            $primarypriv = 'style="display:none;"';
                            $checked2 = ""; 
                            $seniorpriv = 'style="display:none;"';
                            if(in_array("KinderGarten", $exsclpriv)) { 
                                $checked = "checked";
                                $kinderpriv = 'style="display:block;"';
                            }
                            if(in_array("Primary", $exsclpriv)) { 
                                $checked1 =  "checked"; 
                                $primarypriv = 'style="display:block;"';
                            }
                            if(in_array("Senior", $exsclpriv)) { 
                                $checked2 = "checked"; 
                                $seniorpriv = 'style="display:block;"';
                            } 
                            ?>
                            <div class="col-sm-12">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1408') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-2"><input type="checkbox" disabled name="scl_privilages[]" value="KinderGarten" <?php echo $checked;  ?> > KinderGarten</div>
                                        <div class="col-sm-2"><input type="checkbox" disabled name="scl_privilages[]" value="Primary" <?php echo $checked1;  ?> > Primary</div>
                                        <div class="col-sm-2"><input type="checkbox" disabled name="scl_privilages[]" value="Senior" <?php echo $checked2; ?> > Secondary</div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $kpn = ""; $kst = []; $ket = [];
                            $ppn = ""; $pst = []; $ket = [];
                            $spn = ""; $sst = []; $set = [];
                            foreach($timetbl_details as $tymtbl)
                            {
                                if($tymtbl['added_for'] == "KinderGarten")
                                {
                                    $kpn .= $tymtbl['period_name'].",";
                                    $kst[] = $tymtbl['start_time'];
                                    $ket[] = $tymtbl['end_time'];
                                }
                                
                                if($tymtbl['added_for'] == "Primary")
                                {
                                    $ppn .= $tymtbl['period_name'].",";
                                    $pst[] = $tymtbl['start_time'];
                                    $pet[] = $tymtbl['end_time'];
                                }
                                
                                if($tymtbl['added_for'] == "Senior")
                                {
                                    $spn .= $tymtbl['period_name'].",";
                                    $sst[] = $tymtbl['start_time'];
                                    $set[] = $tymtbl['end_time'];
                                }
                            }
                            ?>
                            <div id="kinderpriv" class="col-sm-12 row container" <?php echo $kinderpriv ?> >
                            <h6>Kindergarten School Timings (<?php echo date("h:i A", strtotime($school_details[0]['kinderscl_strttimings']))."-". date("h:i A", strtotime($school_details[0]['kinderscl_endtimings'])) ; ?>)</h6>
                            <div class="row container ">
                            <div class="col-sm-12 fieldpaddings"><div class="wrapper1">
                                <div class="row container marginrow">
                                    <div class="col-sm-3 fieldpaddings">
                                            <label><?= $lbl1429 ?> </label>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <label><?= $lbl1998 ?> </label>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <label><?= $lbl1999 ?> </label>
                                    </div>
                                </div>
                                <?php
                                $c_kst = count($kst);
                                if($c_kst == 0)
                                {
                                    ?>
                                    <div class="input-box row container mb-2 marginrow">
                                        <div class="col-sm-3 fieldpaddings">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="kname[]" id="kname" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 fieldpaddings">
                                            <div class="form-group">
                                                <input class="form-control" id="ktimepicker3" type="text" readonly value="<?= date("H:i", strtotime($school_details[0]['kinderscl_strttimings'])) ?>" name="kstart_time[]" placeholder="<?= $lbl1998 ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-4 fieldpaddings">
                                            <div class="form-group">
                                                <input class="form-control timepicker3" id="ktimepicker4_1" type="text" name="kend_time[]" placeholder="<?= $lbl1999 ?>" />
                                            </div>
                                        </div>
                                        <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <?php
                                }
                                foreach($kst as $key => $kb) {
                                    $kinder_pn = explode(",", $kpn);
                                    if($key != 0) { $readonly = "readonly"; } else { $readonly = ""; }
                                    $j = $key+1;
                                ?>
                                <div class="input-box row container mb-2 marginrow">
                                    <div class="col-sm-3 fieldpaddings">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="kname[]" value="<?= $kinder_pn[$key] ?>" id="kname" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <div class="form-group">
                                            <input class="form-control" id="ktimepicker3" type="text" <?= $readonly ?> value="<?= $kb ?>" name="kstart_time[]" placeholder="<?= $lbl1998 ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <div class="form-group">
                                            <input class="form-control timepicker3" id="ktimepicker4_<?= $j ?>" type="text" <?= $readonly ?> value="<?= $ket[$key] ?>" name="kend_time[]" placeholder="<?= $lbl1999 ?>" />
                                        </div>
                                    </div>
                                    <?php if($key == 0) { ?>
                                    <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>
                                    <?php } else { ?>
                                    <a href="#" class="col-sm-1 remove-lnk"><i class="fa fa-minus"></i></a>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div></div>
                            </div>
                            </div>
                            <div id="primarypriv" class="col-sm-12 row container"<?php echo $primarypriv ?> >
                            <h6>Primary School Timings (<?php echo date("h:i A", strtotime($school_details[0]['primaryscl_strttimings']))."-". date("h:i A", strtotime($school_details[0]['primaryscl_endtimings'])) ; ?>) </h6>
                            <div class="row container ">
                            
                            <div class="col-sm-12 fieldpaddings"><div class="wrapper2">
                                <div class="row container marginrow">
                                    <div class="col-sm-3 fieldpaddings">
                                            <label><?= $lbl1429 ?> </label>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <label><?= $lbl1998 ?> </label>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <label><?= $lbl1999 ?> </label>
                                    </div>
                                </div>
                                <?php
                                $c_pst = count($pst);
                                if($c_pst == 0)
                                {
                                    ?>
                                    <div class="input-box row container mb-2 marginrow">
                                        <div class="col-sm-3 fieldpaddings">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="pname[]" id="pname" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 fieldpaddings">
                                            <div class="form-group">
                                                <input class="form-control" id="ptimepicker3" readonly type="text" name="pstart_time[]" value="<?= date("H:i", strtotime($school_details[0]['primaryscl_strttimings'])) ?>" placeholder="<?= $lbl1998 ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-4 fieldpaddings">
                                            <div class="form-group">
                                                <input class="form-control timepicker3" id="ptimepicker4_1" type="text" name="pend_time[]" placeholder="<?= $lbl1999 ?>" />
                                            </div>
                                        </div>
                                        <button class="col-sm-1 btn add-btn1"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <?php
                                }
                                foreach($pst as $key => $pb) {
                                    $prim_pn = explode(",", $ppn);
                                    if($key != 0) { $readonly = "readonly"; } else { $readonly = ""; }
                                    $tymp = $key + 1;
                                ?>
                                <div class="input-box row container mb-2 marginrow">
                                    <div class="col-sm-3 fieldpaddings">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="pname[]" value="<?= $prim_pn[$key] ?>" id="pname" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <div class="form-group">
                                            <input class="form-control " id="ptimepicker3" <?= $readonly ?> type="text" value="<?= $pb ?>" name="pstart_time[]" placeholder="<?= $lbl1998 ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <div class="form-group">
                                            <input class="form-control timepicker3" id="ptimepicker4_<?= $tymp ?>" <?= $readonly ?> type="text" value="<?= $pet[$key] ?>" name="pend_time[]" placeholder="<?= $lbl1999 ?>" />
                                        </div>
                                    </div>
                                <?php if($key == 0) { ?>
                                <button class="col-sm-1 btn add-btn1"><i class="fa fa-plus"></i></button>
                                <?php } else { ?>
                                <a href="#" class="col-sm-1 remove-lnk1 form-control"><i class="fa fa-minus"></i></a>
                                <?php } ?>
                                </div>
                                <?php } ?>
                            </div></div>
                            </div></div>
                            <div id="secondarypriv" class="col-sm-12 row container" <?php echo $seniorpriv ?> >
                            <h6>Secondary School Timings (<?php echo date("h:i A", strtotime($school_details[0]['seniorscl_strttimings']))."-". date("h:i A", strtotime($school_details[0]['seniorscl_endtimings'])) ; ?>) </h6>
                            <div class="row container ">
                            <div class="col-sm-12 fieldpaddings"><div class="wrapper3">
                                <div class="row container marginrow">
                                    <div class="col-sm-3 fieldpaddings">
                                            <label><?= $lbl1429 ?> </label>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <label><?= $lbl1998 ?> </label>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <label><?= $lbl1999 ?> </label>
                                    </div>
                                </div>
                                <?php
                                $c_sst = count($sst);
                                if($c_sst == 0)
                                {
                                    ?>
                                    <div class="input-box row container mb-2 marginrow">
                                        <div class="col-sm-3 fieldpaddings">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="sname[]" id="sname" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 fieldpaddings">
                                            <div class="form-group">
                                                <input class="form-control" id="stimepicker3" readonly type="text" name="sstart_time[]" value="<?= date("H:i", strtotime($school_details[0]['seniorscl_strttimings'])) ?>" placeholder="<?= $lbl1998 ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-4 fieldpaddings">
                                            <div class="form-group">
                                                <input class="form-control timepicker3" id="stimepicker4_1" type="text" name="send_time[]" placeholder="<?= $lbl1999 ?>" />
                                            </div>
                                        </div>
                                        <button class="col-sm-1 btn add-btn2"><i class="fa fa-plus"></i></button>
                                     </div>
                                    <?php
                                }
                                foreach($sst as $key => $sb) {
                                    $senor_pn = explode(",", $spn);
                                    if($key != 0) { $readonly = "readonly"; } else { $readonly = ""; }
                                    $sty = $key+1;
                                ?>
                                <div class="input-box row container mb-2 marginrow">
                                    <div class="col-sm-3 fieldpaddings">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="sname[]" value="<?= $senor_pn[$key] ?>" id="sname" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <div class="form-group">
                                            <input class="form-control" id="stimepicker3" <?= $readonly ?> type="text"  value="<?= $sb ?>" name="sstart_time[]" placeholder="<?= $lbl1998 ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <div class="form-group">
                                            <input class="form-control timepicker3" id="stimepicker4_<?= $sty ?>" <?= $readonly ?> type="text" value="<?= $set[$key] ?>" name="send_time[]" placeholder="<?= $lbl1999 ?>" />
                                        </div>
                                    </div>
                                
                                <?php if($key == 0) { ?>
                                <button class="col-sm-1 btn add-btn2"><i class="fa fa-plus"></i></button>
                                <?php } else { ?>
                                <a href="#" class="col-sm-1 remove-lnk2 form-control"><i class="fa fa-minus"></i></a>
                                <?php } ?>
                             </div>
                             <?php } ?>
                             </div></div>
                            </div></div>
                            <div class="col-sm-12">
                                <div class="error" id="sclerror"></div>
                                <div class="success" id="sclsuccess"></div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <input type="hidden" name="sclid" value="<?= $id?>">   
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="updatesclbtn" class="btn btn-primary updatesclbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                   
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<script>
$(document).ready(function () {
      var max_input1 = 30;
      var y = "<?php echo $c_kst ?>";
      var x = "<?php echo $c_pst ?>";
      var z = "<?php echo $c_sst ?>";
      if(y == 0) { var y = 1; }
      if(x == 0) { var x = 1; }
      if(z == 0) { var z = 1; }
      $('.add-btn').click(function (e) {
        e.preventDefault();
        if (y < max_input1) {
            var st_kindr = y;
            var kid = "#ktimepicker4_"+st_kindr;
            var kindr_st = $(kid).val();
            y++;
            
            $(kid).mdtimepicker();
            $('.wrapper1').append(`
               <div class="input-box row container mb-2 marginrow">
                                <div class="col-sm-3 fieldpaddings">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="kname[]" id="kname" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <div class="form-group">
                                            <input class="form-control " id="ktimepicker3" value="`+kindr_st+`" readonly type="text" name="kstart_time[]" placeholder="<?php echo $lbl1998 ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4 fieldpaddings">
                                        <div class="form-group">
                                            <input class="form-control timepicker3" id="ktimepicker4_`+y+`" type="text"  name="kend_time[]" placeholder="<?php echo $lbl1999 ?>" />
                                        </div>
                                    </div>
                                <a href="#" class="col-sm-1 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                            </div>
                        
                </div>
         `); // add input field
        }
      });
      
      $('.add-btn1').click(function (e) {
        e.preventDefault();
        if (x < max_input1) {
            var st_prim = x;
            var pid = "#ptimepicker4_"+st_prim;
            var prim_st = $(pid).val();
            x++;
            $('.wrapper2').append(`
               <div class="input-box row container mb-2 marginrow">
                    <div class="col-sm-3 fieldpaddings">
                        <div class="form-group">
                            <input type="text" class="form-control" name="pname[]" id="pname" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-sm-4 fieldpaddings">                 
                        <div class="form-group">
                            <input class="form-control " id="ptimepicker3" readonly value="`+prim_st+`" type="text" name="pstart_time[]" placeholder="<?php echo $lbl1998 ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4 fieldpaddings">
                        <div class="form-group">
                            <input class="form-control timepicker3" id="ptimepicker4_`+x+`" type="text" name="pend_time[]" placeholder="<?php echo $lbl1999 ?>" />
                        </div>
                    </div>            
                    <a href="#" class="col-sm-1 remove-lnk1 form-control"><i class="fa fa-minus"></i></a>
                </div>
                        
                </div>
         `); // add input field
        }
      });
      
      $('.add-btn2').click(function (e) {
        e.preventDefault();
        
        if (z < max_input1) {
            var st_senr = z;
            var sid = "#stimepicker4_"+st_senr;
            var senr_st = $(sid).val();
            z++;
            $('.wrapper3').append(`
                <div class="input-box row container mb-2 marginrow">
                    <div class="col-sm-3 fieldpaddings">
                        <div class="form-group">
                            <input type="text" class="form-control" name="sname[]" id="sname" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-sm-4 fieldpaddings">
                        <div class="form-group">
                            <input class="form-control" id="stimepicker3" readonly value="`+senr_st+`" type="text" name="sstart_time[]" placeholder="<?php echo $lbl1998 ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4 fieldpaddings">
                        <div class="form-group">
                            <input class="form-control timepicker3" id="stimepicker4_`+z+`" type="text" name="send_time[]" placeholder="<?php echo $lbl1999 ?>" />
                        </div>
                    </div>            
                    <a href="#" class="col-sm-1 remove-lnk2 form-control"><i class="fa fa-minus"></i></a>
                </div>
                        
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper1').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        y--; // decrement the counter
      });
      $('.wrapper2').on("click", ".remove-lnk1", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      });
      $('.wrapper3').on("click", ".remove-lnk2", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        z--; // decrement the counter
      });
    });
</script>

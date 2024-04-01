<?php
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
        if($langlbl['id'] == '669') { $lbl669 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2216') { $lbl2216 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2217') { $lbl2217 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2218') { $lbl2218 = $langlbl['title'] ; }
        if($langlbl['id'] == '1556') { $lbl1556 = $langlbl['title'] ; }
        if($langlbl['id'] == '1412') { $lbl1412 = $langlbl['title'] ; }
        
        if($langlbl['id'] == '1519') { $lbl1519 = $langlbl['title'] ; }
        if($langlbl['id'] == '1577') { $lbl1577 = $langlbl['title'] ; }
        if($langlbl['id'] == '2189') { $lbl2189 = $langlbl['title'] ; }
        
        if($langlbl['id'] == '1830') { $lbl1830 = $langlbl['title'] ; }
        if($langlbl['id'] == '1831') { $lbl1831 = $langlbl['title'] ; }
        if($langlbl['id'] == '1832') { $lbl1832 = $langlbl['title'] ; }
        if($langlbl['id'] == '1833') { $lbl1833 = $langlbl['title'] ; }
        if($langlbl['id'] == '1834') { $lbl1834 = $langlbl['title'] ; }
        if($langlbl['id'] == '1835') { $lbl1835 = $langlbl['title'] ; }
        if($langlbl['id'] == '1836') { $lbl1836 = $langlbl['title'] ; }
        if($langlbl['id'] == '1837') { $lbl1837 = $langlbl['title'] ; }
    } 
?>
<style>
.fieldpaddings {
    padding: 0px 8px !important;
}
h5
{
	color: #007bff !important;
}
.wrapper 
{
    width:100% !important;
}
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Edit Serve Food to school</h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>Canteenvendors/assignfoodscl" title="Back" class="btn btn-sm btn-success"><?= $lbl41 ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php //print_r($serve_details);
                echo $this->Form->create(false , ['url' => ['action' => 'editservefood'] , 'id' => "editservefoodscl" , 'class' => "editservefoodscl", 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                        <div class="row clearfix col-md-12">
                            <input type="hidden" name="id" value="<?= $serve_details['id'] ?>" >
                            <div class="col-sm-3">
                                <label><?= $lbl2217 ?>*</label>
                                <div class="form-group">                                                
                                    <select class="form-control scllist" id="schllist1" name="school" required>
                                        <option value="">Choose School</option>
                                        <?php
                                        foreach($scl_details as $val){
                                            $sel = '';
                                            if($val['id'] == $serve_details['school_id'])
                                            {
                                                $sel = "selected";
                                            }
                                        ?>
                                          <option value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['comp_name'] ;?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>       
                                </div>
                            </div>
                    
                            <div class="col-sm-3">
                                <label>Class Section*</label>
                                <div class="form-group">                                                
                                    <select class="form-control sections" id="section1" name="section" required>
                                        <option value="">Choose Section</option>
                                        <option value="KinderGarten" <?php if($serve_details['class_section'] == "KinderGarten") { echo "selected"; } ?> ><?= $lbl1519 ?></option>
                                        <option value="Primary" <?php if($serve_details['class_section'] == "Primary") { echo "selected"; } ?> ><?= $lbl1577 ?></option>
                                        <option value="Senior" <?php if($serve_details['class_section'] == "Senior") { echo "selected"; } ?> ><?= $lbl2189 ?></option>
                                    </select>       
                                </div>
                            </div>
                            
                            <div id="withoptn1">
                                <?php if(!empty($stt_details))
                                { ?>
                                    <div class="col-sm-3" style="max-width:100%"><label>Timings to deliver*</label>
                                    <div class="form-group">                                                
                                        <select class="form-control" id="break`+y+`" name="break" >
                                            <option value="">Choose Timings</option>
                                            <?php foreach($stt_details as $stt) {
                                                $time = $stt['start_time'].'-'.$stt['end_time'];
                                                $sel = '';
                                                if($time == $serve_details['timings']) { $sel = "selected"; } 
                                            echo '<option value="'.$stt['start_time'].'-'.$stt['end_time'].'" '.$sel.'>'.$stt['start_time'].'-'.$stt['end_time'].'</option>';
                                            } ?>
                                        </select>       
                                    </div></div>
                                <?php }
                                else
                                { ?>
                                    <div class="col-sm-3" style="max-width:100%"><label>Timings to deliver*</label>
                                    <div class="form-group">
                                        <input class="form-control" type="time" id="break`+y+`" name="break" placeholder="Timings to deliver*" value="<?= $serve_details['timings'] ?>" />
                                    </div></div>
                                <?php } ?>
                            </div>
                            
                            <div class="col-sm-3">
                                <label>Order booking closed before*</label>
                                <div class="form-group">                                                
                                    <select class="form-control closed_booking" id="closedbooking1" name="closedbooking" required>
                                        <option value="">Choose Value</option>
                                        <option value="15 Minutes" <?php if($serve_details['order_booking_closed'] == "15 Minutes") { echo "selected"; } ?> >15 Minutes</option>
                                        <option value="30 Minutes" <?php if($serve_details['order_booking_closed'] == "30 Minutes") { echo "selected"; } ?> >30 Minutes</option>
                                        <option value="45 Minutes" <?php if($serve_details['order_booking_closed'] == "45 Minutes") { echo "selected"; } ?> >45 Minutes</option>
                                        <option value="60 Minutes" <?php if($serve_details['order_booking_closed'] == "60 Minutes") { echo "selected"; } ?> >60 Minutes</option>
                                        <option value="90 Minutes" <?php if($serve_details['order_booking_closed'] == "90 Minutes") { echo "selected"; } ?> >90 Minutes</option>
                                        <option value="120 Minutes" <?php if($serve_details['order_booking_closed'] == "120 Minutes") { echo "selected"; } ?> >120 Minutes</option>
                                    </select>       
                                </div>
                            </div>
                    
                            <div class="col-sm-3">
                                <label><?= $lbl1556 ?>*</label>
                                <div class="form-group">                                                
                                    <select class="form-control request_opt" id="weekday" name="weekday" required>
                                        <option value="">Choose Weekday</option>
                                        <option value="Monday" <?php if($serve_details['weekday'] == "Monday") { echo "selected"; } ?> >Monday</option>
                                        <option value="Tuesday" <?php if($serve_details['weekday'] == "Tuesday") { echo "selected"; } ?> >Tuesday</option>
                                        <option value="Wednesday" <?php if($serve_details['weekday'] == "Wednesday") { echo "selected"; } ?> >Wednesday</option>
                                        <option value="Thursday" <?php if($serve_details['weekday'] == "Thursday") { echo "selected"; } ?> >Thursday</option>
                                        <option value="Friday" <?php if($serve_details['weekday'] == "Friday") { echo "selected"; } ?> >Friday</option>
                                        <option value="Saturday" <?php if($serve_details['weekday'] == "Saturday") { echo "selected"; } ?> >Saturday</option>
                                        <option value="Sunday" <?php if($serve_details['weekday'] == "Sunday") { echo "selected"; } ?> >Sunday</option>
                                    </select>       
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <label>Select Vendors*</label>
                                <div class="form-group">                                                
                                    <select class="form-control community_filter" id="vendor1" name="vendor" required>
                                        <option value="">Choose Vendor</option>
                                        <?php foreach($vendr_details as $valu) {
                                            print_r($valu);
                                            $sel = '';
                                            if($valu['id'] == $serve_details['vendor_id'])
                                            {
                                                $sel = "selected";
                                            }
                                        ?>
                                          <option value="<?= $valu['id'] ?>" <?= $sel ?> ><?= $valu['l_name'].' '.$valu['f_name'].' ('.$valu['vendor_company'].')' ?></option>
                                        <?php } ?>
                                    </select>       
                                </div>
                            </div>
                    
                            <div class="col-sm-3">
                                <label>Food List*</label>
                                <div class="form-group">
                                    <select class="form-control select_food" id="fooditem1" name="foodlist[]" multiple required>
                                        <option value=""><?= $lbl2218 ?></option>
                                        <?php foreach($fooddata as $valu) {
                                            $foodids = explode(",", $serve_details['food_ids']);
                                        ?>
                                          <option value="<?= $valu['foodid'] ?>" <?php if(in_array($valu['foodid'], $foodids)) { echo "selected"; } ?> ><?= $valu['foodname']." ($".$valu['foodprice'].")" ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                                
                            <div class="col-sm-12">
                                <div class="error" id="eassignerror">
                                </div>
                                <div class="success" id="eassignsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="editassignbtn" class="btn btn-primary editassignbtn"><?= $lbl1412 ?></button>
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
    $('.wrapper').on("change", ".scllist", function (e) {
        var sclid = $(this).val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var id = this.id;
        var splitid = id.split("schllist");
        //alert(splitid[1]);
        $("#vendor"+splitid[1]).html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/Canteenvendors/getvendors',
            data:{'sclid':sclid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                if(html)
                {    
                    $("#vendor"+splitid[1]).html(html);
                }
          
            }
    
        });
    }) 
    
    $('.wrapper').on("change", ".sections", function (e) {
        var sctn = $(this).val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var id = this.id;
        var splitid = id.split("section");
        //alert(splitid[1]);
        $("#break"+splitid[1]).html("");
        var sclid = $("#schllist"+splitid[1]).val();
        
        $("#withoptn"+splitid[1]).html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/Canteenvendors/getbreaktimings',
            data:{'sclid':sclid,'sctn':sctn},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                
                if(html != '')
                {    
                    $("#withoptn"+splitid[1]).html(`<div class="col-sm-3" style="max-width:100%"><label>Timings to deliver*</label>
                    <div class="form-group">                                                
                        <select class="form-control" id="break`+y+`" name="break[]" >
                            `+html+`
                        </select>       
                    </div></div>`);
                    //$("#break"+splitid[1]).html(html);
                }
                else
                {    
                    $("#withoptn"+splitid[1]).html(`<div class="col-sm-3" style="max-width:100%"><label>Timings to deliver*</label>
                    <div class="form-group">
                        <input class="form-control" type="time" id="break`+y+`" name="break[]" placeholder="Timings to deliver*" />
                    </div></div>`);
                }
            }
    
        });
    }) 
    
    $('.wrapper').on("change", ".community_filter", function (e) {
        var vndrid = $(this).val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var id = this.id;
        var splitid = id.split("vendor");
        //alert(splitid[1]);
        $("#fooditem"+splitid[1]).html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/Canteenvendors/getvendorfood',
            data:{'vndrid':vndrid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                if(html)
                {    
                    $("#fooditem"+splitid[1]).html(html);
                }
          
            }
    
        });
    })
});
</script>
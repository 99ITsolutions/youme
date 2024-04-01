<?php
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; } 
        if($langlbl['id'] == '669') { $lbl669 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2216') { $lbl2216 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2217') { $lbl2217 = $langlbl['title'] ; } 
        if($langlbl['id'] == '2218') { $lbl2218 = $langlbl['title'] ; }
        if($langlbl['id'] == '1556') { $lbl1556 = $langlbl['title'] ; }
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
                <h2 class="heading">Serve Food to school</h2>
                <ul class="header-dropdown">
                    <li><a href="<?=$baseurl?>Canteenvendors/assignfoodscl" title="Back" class="btn btn-sm btn-success"><?= $lbl41 ?></a></li>
                </ul>
            </div>
            <div class="body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'addservefood'] , 'id' => "addservefoodscl" , 'class' => "addservefoodscl", 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                        <div class="row clearfix col-md-12">
                            <div class="wrapper">
                                <div class="input-box row container mb-2">
                                    <div class="col-sm-3">
                                        <label><?= $lbl2217 ?>*</label>
                                        <div class="form-group">                                                
                                            <select class="form-control scllist" id="schllist1" name="school[]" required>
                                                <option value="">Choose School</option>
                                                <?php
                                                foreach($scl_details as $val){
                                                ?>
                                                  <option  value="<?=$val['id']?>" ><?php echo $val['comp_name'] ;?> </option>
                                                <?php
                                                }
                                                ?>
                                            </select>       
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <label>Class Section*</label>
                                        <div class="form-group">                                                
                                            <select class="form-control sections" id="section1" name="section[]" required>
                                                <option value="">Choose Section</option>
                                                <option value="KinderGarten"><?= $lbl1519 ?></option>
                                                <option value="Primary"><?= $lbl1577 ?></option>
                                                <option value="Senior"><?= $lbl2189 ?></option>
                                            </select>       
                                        </div>
                                    </div>
                                    
                                    <div id="withoptn1"></div>
                                    
                                    <div class="col-sm-3">
                                        <label>Order booking closed before*</label>
                                        <div class="form-group">                                                
                                            <select class="form-control closed_booking" id="closedbooking1" name="closedbooking[]" required>
                                                <option value="">Choose Value</option>
                                                <option value="15 Minutes">15 Minutes</option>
                                                <option value="30 Minutes">30 Minutes</option>
                                                <option value="45 Minutes">45 Minutes</option>
                                                <option value="60 Minutes">60 Minutes</option>
                                                <option value="90 Minutes">90 Minutes</option>
                                                <option value="120 Minutes">120 Minutes</option>
                                            </select>       
                                        </div>
                                    </div>
                            
                                    <div class="col-sm-3">
                                        <label><?= $lbl1556 ?>*</label>
                                        <div class="form-group">                                                
                                            <select class="form-control request_opt" id="weekday1" name="weekday1[]" required multiple>
                                                <option value="">Choose Weekday</option>
                                                <option value="Monday"><?= $lbl1830 ?></option>
                                                <option value="Tuesday"><?= $lbl1831 ?></option>
                                                <option value="Wednesday"><?= $lbl1832 ?></option>
                                                <option value="Thursday"><?= $lbl1833 ?></option>
                                                <option value="Friday"><?= $lbl1834 ?></option>
                                                <option value="Saturday"><?= $lbl1835 ?></option>
                                                <option value="Sunday"><?= $lbl1837 ?></option>
                                            </select>       
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <label>Select Vendors*</label>
                                        <div class="form-group">                                                
                                            <select class="form-control community_filter" id="vendor1" name="vendor[]" required>
                                                <option value="">Choose Vendor</option>
                                            </select>       
                                        </div>
                                    </div>
                            
                                    <div class="col-sm-3">
                                        <label>Food List*</label>
                                        <div class="form-group">
                                            <select class="form-control select_food" id="fooditem1" name="foodlist1[]" multiple required>
                                                <option value=""><?= $lbl2218 ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>
                                </div>
                                <hr>
                            </div>
                            <div class="col-sm-12">
                                <div class="error" id="assignerror">
                                </div>
                                <div class="success" id="assignsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="addassignbtn" class="btn btn-primary addassignbtn"><?= $lbl669 ?></button>
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
    var max_input1 = 15;
    var y = 1;
    $('.add-btn').click(function (e) {
        e.preventDefault();
        if (y < max_input1) {
            y++;
            $('.wrapper').append(`
            
            <div class="input-box row container mb-2">
                <div class="col-sm-3">
                    <label><?= $lbl2217 ?>*</label>
                    <div class="form-group">                                                
                        <select class="form-control scllist" id="schllist`+y+`" name="school[]" required>
                            <option value="">Choose School</option>
                            <?php
                            foreach($scl_details as $val){
                            ?>
                              <option  value="<?=$val['id']?>" ><?php echo $val['comp_name'] ;?> </option>
                            <?php
                            }
                            ?>
                        </select>       
                    </div>
                </div>
                <div class="col-sm-3">
                    <label>Class Section*</label>
                    <div class="form-group">                                                
                        <select class="form-control sections" id="section`+y+`" name="section[]" required>
                            <option value="">Choose Section</option>
                            <option value="KinderGarten"><?= $lbl1519 ?></option>
                            <option value="Primary"><?= $lbl1577 ?></option>
                            <option value="Senior"><?= $lbl2189 ?></option>
                        </select>       
                    </div>
                </div>
                <div class="col-sm-3" id="withoptn`+y+`">
                </div>
                
                <div class="col-sm-3">
                    <label>Order booking closed before*</label>
                    <div class="form-group">                                                
                        <select class="form-control closed_booking" id="closedbooking`+y+`" name="closedbooking[]" required>
                            <option value="">Choose Value</option>
                            <option value="15 Minutes">15 Minutes</option>
                            <option value="30 Minutes">30 Minutes</option>
                            <option value="45 Minutes">45 Minutes</option>
                            <option value="60 Minutes">60 Minutes</option>
                            <option value="90 Minutes">90 Minutes</option>
                            <option value="120 Minutes">120 Minutes</option>
                        </select>       
                    </div>
                </div>
        
                <div class="col-sm-3">
                    <label><?= $lbl1556 ?>*</label>
                    <div class="form-group">                                                
                        <select class="form-control request_opt" id="weekday`+y+`" name="weekday`+y+`[]" required multiple>
                            <option value="">Choose Weekday</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>       
                    </div>
                </div>
                
                <div class="col-sm-3">
                    <label>Select Vendors*</label>
                    <div class="form-group">                                                
                        <select class="form-control community_filter" id="vendor`+y+`" name="vendor[]" required>
                            <option value="">Choose Vendor</option>
                        </select>       
                    </div>
                </div>
        
                <div class="col-sm-3">
                    <label>Food List*</label>
                    <div class="form-group">
                        <select class="form-control select_food" id="fooditem`+y+`" name="foodlist`+y+`[]" multiple required>
                            <option value=""><?= $lbl2218 ?></option>
                        </select>
                    </div>
                </div>
                
                <a href="#" class="col-sm-1 remove-lnk mt-2"><i class="fa fa-minus"></i></a>
            </div>
            <hr>
            `); // add input field
        }
    });
    
    // handle click event of the remove link
    $('.wrapper').on("click", ".remove-lnk", function (e) {
    e.preventDefault();
    
    $(this).parent('div.input-box').remove();  // remove input field
    y--; // decrement the counter
    });


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
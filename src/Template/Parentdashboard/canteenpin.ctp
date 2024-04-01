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
   .kbw-signature { width: 400px; height: 200px;}
    #defaultSignature canvas{
        width: 100% !important;
        height: auto;
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
    
    if($langlbl['id'] == '2332') { $lbl2332 = $langlbl['title'] ; }
    if($langlbl['id'] == '2333') { $lbl2333 = $langlbl['title'] ; }
    if($langlbl['id'] == '2334') { $lbl2334 = $langlbl['title'] ; }
    if($langlbl['id'] == '2335') { $lbl2335 = $langlbl['title'] ; }
} ?>
        <div class="row clearfix">
            <div class="col-lg-12">
               <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading"><?= $lbl2332 ?></h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="body">
	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'canpingent'] , 'id' => "canpin_genform" , 'method' => "post"  ]);  ?>
	                    <div class="row clearfix">
	                        <div class="col-md-3">
                                <label><?= $lbl2333 ?></label>
                                <select class="form-control studentchose" name="selstud" id="selstud" onchange="getsclfromstud(this.value)" >
                                    <option value="">Choose Student</option>
                                    <?php foreach($studlist as $stulis) { ?>
                                        <option value="<?= $stulis['id'] ?>"><?= $stulis['l_name']." ".$stulis['f_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label><?= $lbl2334 ?></label>
                                <input type="text" class="form-control" name="getscl" id="getscl" readonly>
                            </div>
                            <div class="col-md-3"> 
                                <label><?= $lbl2335 ?></label>
                                <input type="text" class="form-control" name="canteenpin" maxlength="4"  minlength="4" id="cpin" placeholder="<?= $lbl2335 ?>">
                            </div>
                            
    	                    <div class="col-sm-1">
    	                        <button type="submit" class="btn btn-primary mt-4 canpin_gen" id="canpin_gen"><?= $lbl243 ?></button>
    	                    </div>
    	                    <div class="col-md-12">
                                <div class="error" id="cpinerror"></div>
                                <div class="success" id="cpinsuccess"></div>
                            </div>
    	                </div>   
    	                <?php echo $this->Form->end();?>
        	        </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>

<!------------------ End --------------------->
<script>
function getsclfromstud(val)
{
    $("#getscl").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/parentdashboard/getscl',
            data:{'studid':val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                if(result)
                {    
                    $("#getscl").val(result.sclname);
                    $("#cpin").val(result.cpin);             
                }
          
            }

        });
    }
}
</script>
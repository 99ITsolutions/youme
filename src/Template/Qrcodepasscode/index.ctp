
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
                            <h2 class="col-md-11 heading">QR Code Passcode</h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="body">
	                    <?php   echo $this->Form->create(false , ['url' => ['action' => 'qrpasscode'] , 'id' => "qrpassform" , 'method' => "post"  ]);  ?>
	                    <div class="row clearfix">
	                        
                            <div class="col-md-3"> 
                                <label>QR Passcode</label>
                                <input type="text" class="form-control" value="<?= $scllist['qrcode_pin'] ?>" name="qrpass" maxlength="25"  minlength="4" id="qrpass" placeholder="Passcode">
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
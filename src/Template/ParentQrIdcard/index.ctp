<?php $statusarray = array('Inactive','Active' ); 
foreach($lang_label as $langlbl) { if($langlbl['id'] == '1380') { $addnewmsg = $langlbl['title'] ; } } ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
                <div class="row">
                    <div class="col-md-3">
                        <label>Select Student</label>
                        <select class="form-control studentchose" name="selstud" id="selstud" onchange="getsclfromstud(this.value)" >
                            <option value="">Choose Student</option>
                            <?php foreach($studlist as $stulis) { ?>
                                <option value="<?= $stulis['id'] ?>"><?= $stulis['l_name']." ".$stulis['f_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>School</label>
                        <input type="text" class="form-control" name="getscl" id="getscl" readonly>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="row">
                    <div id="downloadqrcode"  class="col-md-3"></div>
                    <div id="viewidqrcode" class="col-md-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getsclfromstud(val)
{
    $("#getscl").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $('#viewidqrcode').html("")
    $('#downloadqrcode').html("")
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/ParentQrIdcard/getscl',
            data:{'studid':val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                if(result)
                {    
                    $("#getscl").val(result.sclname);
                    $('#viewidqrcode').html(result.list);
                    $('#downloadqrcode').html(result.downloadimg);
                }
          
            }

        });
    }
}
</script>

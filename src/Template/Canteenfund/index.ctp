<?php
    $statusarray = array('Inactive','Active' );
    foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2027') { $yerlylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1019') { $hlfyrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1020') { $quatrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1021') { $mnthlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2028') { $chosesesslbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2121') { $lbl2121 = $langlbl['title'] ; }
    if($langlbl['id'] == '2175') { $lbl2175 = $langlbl['title'] ; }
    if($langlbl['id'] == '2219') { $lbl2219 = $langlbl['title'] ; }
    if($langlbl['id'] == '2220') { $lbl2220 = $langlbl['title'] ; }
    if($langlbl['id'] == '2237') { $lbl2237 = $langlbl['title'] ; }
    if($langlbl['id'] == '2238') { $lbl2238 = $langlbl['title'] ; }
    if($langlbl['id'] == '130') { $lbl130 = $langlbl['title'] ; } 
    if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; } 
    if($langlbl['id'] == '337') { $lbl337 = $langlbl['title'] ; }
    if($langlbl['id'] == '321') { $lbl321 = $langlbl['title'] ; }
    if($langlbl['id'] == '322') { $lbl322 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '2338') { $lbl2338 = $langlbl['title'] ; }
    if($langlbl['id'] == '2333') { $lbl2333 = $langlbl['title'] ; }
    if($langlbl['id'] == '2334') { $lbl2334 = $langlbl['title'] ; }
} 
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <!--<h2 class="heading">Contact M</h2>-->
                <ul class="header-dropdown">
	                <li id="dpdf"></li>
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
                
                <div class="row">
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
                </div>
                            
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="parntcntnfund_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th><?= $lbl130 ?></th>
                                <th><?= $lbl147 ?></th>
                                <th><?= $lbl337 ?></th>
                                <th><?= $lbl321 ?></th>
                                <th><?= $lbl322?></th>
                                <th><?= $lbl2237?></th>
                                <th><?= $lbl2338?></th>
                                <th><?= $lbl2238 ?></th>
                            </tr>
                        </thead>
                        <tbody id="cfundbody" class="modalrecdel"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getsclfromstud(val)
{
    $("#getscl").val();
    //$("#newmessage").css("display", "none");
    $("#newmsg").html('');
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/Canteenfund/getscl',
            data:{'studid':val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                $("#dpdf").html("");
                if(result)
                {    
                    $("#getscl").val(result.sclname);
                    $("#dpdf").html(result.dpdf);
                    $("#parntcntnfund_table").DataTable().destroy();
                    $('#cfundbody').html(result.list);
                    $("#parntcntnfund_table").DataTable({
                        "language": {
                            "lengthMenu": show+" _MENU_"+entries,
                            "search": search+":",
                            "info": show+" _START_ to _END_ "+ ofentries +" _TOTAL_ "+ entries,
                            "infoEmpty": show+" 0 to _END_ "+ ofentries +" _TOTAL_ "+ entries,
                            "paginate": {
                              next: next,
                              previous: prev,
                            }
                        }
                    });               
                }
          
            }

        });
    }
}
</script>

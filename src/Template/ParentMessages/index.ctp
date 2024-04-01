<?php $statusarray = array('Inactive','Active' ); 
foreach($lang_label as $langlbl) { if($langlbl['id'] == '1380') { $addnewmsg = $langlbl['title'] ; } } ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <!--<h2 class="heading">Contact M</h2>-->
                <ul class="header-dropdown">
	                <li id="newmsg"></li>
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
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="parntmsg_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1381') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1382') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1383') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1384') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="msgparbody" class="modalrecdel"></tbody>
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
            url: baseurl + '/ParentMessages/getscl',
            data:{'studid':val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                if(result)
                {    
                    $("#getscl").val(result.sclname);
                    //$("#newmessage").css("display", "block");
                    $("#newmsg").html('<a href="<?php echo $baseurl?>ParentMessages/add/'+result.studid+'" title="Add" class="btn btn-sm btn-success"><?php echo $addnewmsg ?></a>');
                    $("#parntmsg_table").DataTable().destroy();
                    $('#msgparbody').html(result.list);
                    $("#parntmsg_table").DataTable({
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

<style>

#calendar {
    width: 700px;
    margin: 0 auto;
}

.response {
    height: 60px;
}

.success {
    background: #cdf3cd;
    padding: 10px 60px;
    border: #c3e6c3 1px solid;
    display: inline-block;
}

@media screen and (max-width: 444px) and (min-width: 200px) 
{
    .fc .fc-view-container .fc-view.fc-basic-view>table>thead tr th.fc-widget-header
    {
        padding:4px !important;
    }
    .fc-toolbar .fc-right
    {
        display:block;
        float: none;
    }
}
</style>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2 class="heading "><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '420') { echo $langlbl['title'] ; } } ?></h2>
                        <ul class="header-dropdown">
                            <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                        </ul>
                        
                    </div>
                    <div class="body">
                        <div class="row container">
                            <div class="response"></div>
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal classmodal animated zoomIn" id="event_add" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '429') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '430') { echo $langlbl['title'] ; } } ?></label>
                            <input name="title" id="title" class="form-control" type="text" >
                            <input type="hidden" value="" id="eve_start">
                            <input type="hidden" value="" id="eve_end">
                            <input type="hidden" value="" id="eve_add_edit">
                            <input type="hidden" value="" id="eve_id">
                        </div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="button" class="btn btn-primary addevebtn" id="addevebtn" onclick="addcalevent(this.id);" style="margin-right: 10px;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '431') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-primary addevebtn" id="editevebtn" onclick="addcalevent(this.id);" style="margin-right: 10px; display:none"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-danger addevebtn" id="deletevebtn" onclick="deletecalevent();" style=" margin-right: 10px; display:none;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '901') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                </div>
            </div>
             
        </div>
    </div>
</div>    



<div class="row clearfix">
    <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
</div>
<!------------------ End --------------------->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>



function deletecalevent(){
    var eve_id = $("#eve_id").val();
    var refscrf = $("input[name='_csrfToken']").val();
    swal({
        title: "Are you sure?",
        text: "You want to delete the event in Calendar !",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: "Yes, Change it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            type: "POST",
            url: baseurl +"/schoolCalendar/deleteevent",
            data: { id : eve_id , _csrfToken : refscrf },
            success: function (response) {
                if(response.result == "success") {
                    $('#calendar').fullCalendar('removeEvents', event.id);
                    swal({
                        title: "Success",
                        text: "Status! Event Calendar has been deleted.",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#007bff",
                        confirmButtonText: "Ok",
                        showLoaderOnConfirm: true
                    });
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else
                {
                    swal({
                        title: "Error",
                        text: "Error Occured! Please Try Again",
                        type: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#007bff",
                        confirmButtonText: "Ok",
                        showLoaderOnConfirm: true
                    });
                    
                }
            }
        });
    });
}
function addcalevent(get_id){
    var start = $("#eve_start").val();
    var end = $("#eve_end").val();
    var title = $("#title").val();
    var eve_add_edit = $("#eve_add_edit").val();
    var eve_id = $("#eve_id").val();
    
    var refscrf = $("input[name='_csrfToken']").val();
    if (title != '') {
        if(eve_add_edit == 'add_eve'){
            $.ajax({
                url: baseurl +"/schoolCalendar/addevent",
                data: {title : title, start : start , end : end, _csrfToken : refscrf },
                type: "POST",
                success: function (data) {
                    if(data.result == "success")
                    {
                        $("#event_add").modal("hide");
                        swal({
                            title: "Success",
                            text: "Event Added Successfully",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#007bff",
                            confirmButtonText: "Ok",
                            showLoaderOnConfirm: true
                        });
                        setTimeout(function(){ location.reload() ;  }, 1000);
                    }
                    else
                    {
                       swal({
                            title: "Error",
                            text: "Error Occured! Please Try Again",
                            type: "error",
                            showCancelButton: true,
                            confirmButtonColor: "#007bff",
                            confirmButtonText: "Ok",
                            showLoaderOnConfirm: true
                        });
                    }
                    
                }
            });
        }else if(eve_add_edit == 'edit_eve'){
            $.ajax({
                url: baseurl +"/schoolCalendar/editevent",
                data: {title : title, id : eve_id , _csrfToken : refscrf },
                type: "POST",
                success: function (data) {
                    if(data.result == "success")
                    {
                        $("#event_add").modal("hide");
                        swal({
                            title: "Success",
                            text: "Event Updated Successfully",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#007bff",
                            confirmButtonText: "Ok",
                            showLoaderOnConfirm: true
                        });
                        setTimeout(function(){ location.reload() ;  }, 1000);
                    }
                    else
                    {
                       swal({
                            title: "Error",
                            text: "Error Occured! Please Try Again",
                            type: "error",
                            showCancelButton: true,
                            confirmButtonColor: "#007bff",
                            confirmButtonText: "Ok",
                            showLoaderOnConfirm: true
                        });
                    }
                    
                }
            });
        }
    }else{
        swal({
            title: "Error",
            text: "Please Enter Title!",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            confirmButtonText: "Ok",
            showLoaderOnConfirm: true
        });
    }
}


function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    

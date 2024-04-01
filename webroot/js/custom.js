$(document).ready(function()
{
    var currentDate = new Date();
    var origin   = window.location.origin;
    var baseurl = window.location.pathname.split('/')[1];
    baseurl = "/" + baseurl;
    var controller = window.location.pathname.split('/')[2];
    var actionpage = window.location.pathname.split('/')[3];
    var actioneditid = window.location.pathname.split('/')[4]; 
  
$('#calendar').fullCalendar({
        editable: true,
        events: baseurl +"/schoolCalendar/getevents",
        displayEventTime: false,
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable: true,
        selectHelper: true,
        dayNamesShort: caldays,
        monthNames: calmonths,
        
        select: function (start, end, allDay) {
            start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
            end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
            $("#eve_start").val(start);
            $("#eve_end").val(end);
            $("#eve_add_edit").val('add_eve');
            $("#editevebtn").hide();
            $("#deletevebtn").hide();
            $("#addevebtn").show();
            $("#event_add").modal("show");
            
            var refscrf = $("input[name='_csrfToken']").val();
        },
    eventDrop: function (event, delta) {
              
            },
    eventClick: function (event) {
        $("#event_add").modal("show");
        $("#title").val(event.title);
        $("#eve_add_edit").val('edit_eve');
        $("#eve_id").val(event.id);
        $("#deletevebtn").show();
        $("#editevebtn").show();
        $("#addevebtn").hide();
    }
});

$('#studentcalendar').fullCalendar({
        editable: true,
        events: baseurl +"/calendar/getevents",
        displayEventTime: false,
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        dayNamesShort: caldays,
        monthNames: calmonths,
        selectable: true,
        selectHelper: true,
        
});

$('#examasstable').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'status_change') {
            var sts =  $(this).data("status") ;
            showstatusConfirmMessage(id,url,str,sts);
        }
     });
  
/******canteen********/
$("#addcanteenform").submit(function(e){
    e.preventDefault();
    $("#addcfundbtn").prop("disabled", false);
    $("#addcfundbtn").text(saving+"...");

    $(this).ajaxSubmit({
        error: function(){
            $("#addcfundbtn").text("Save");
            $("#cfunderror").html(errorocc) ;
            $("#cfunderror").fadeIn().delay('5000').fadeOut('slow');
            $("#addcfundbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addcfundbtn").text("Save");
            $("#addcfundbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#cfundsuccess").html("Student Canteen fee added successfully.") ;
                $("#cfundsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload() ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#cfunderror").html(filldetails) ;
                $("#cfunderror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#cfunderror").html(feesalready+"class -"+response.class) ;
                $("#cfunderror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#cfunderror").html(response.result) ;
                $("#cfunderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#editcanteenform").submit(function(e){
    e.preventDefault();
    $("#editcfundbtn").prop("disabled", false);
    $("#editcfundbtn").text(updating+"...");

    $(this).ajaxSubmit({
        error: function(){
            $("#editcfundbtn").text("Update");
            $("#ecfunderror").html(errorocc) ;
            $("#ecfunderror").fadeIn().delay('5000').fadeOut('slow');
            $("#editcfundbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addcfundbtn").text("Save");
            $("#addcfundbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#ecfundsuccess").html("Student Canteen fee updated successfully.") ;
                $("#ecfundsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload() ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#ecfunderror").html(filldetails) ;
                $("#ecfunderror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#ecfunderror").html(feesalready+"class -"+response.class) ;
                $("#ecfunderror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#ecfunderror").html(response.result) ;
                $("#ecfunderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('#canteenfee_table tbody').on("click",".editstruc",function()
{
    var id = $(this).data('id');
    $("#estudent").html("");
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/fees/updatecanteen", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (data) 
        {      
            $('#deposit_by').prop("disabled", false);
            var result = data.canteen;
            $("#select_year2").select2().val(result['session_id']).trigger('change.select2');
            $('#class').select2().val(result['class_id']).attr("selected","selected").trigger('change.select2');
            console.log(data);
            $('#eid').val(id);
            var diff = data.timediff-result.created_date;
            if(diff > 1800)
            {
                $('#deposit_by').prop("disabled", true);
            }
            
            $('#amount').val(result['amount']);  
            $('#daily_limit').val(result['daily_limit']); 
            $('#deposit_by').val(result['deposit_by']); 
            egetstud(result['class_id'], result['student_id'])
        }
    });
});  

$("#getfeaturevendors").submit(function(e){
    e.preventDefault();
    var time = $("#gettime").val();
    var sdt = $("#seldate").val();
    if(sdt == "" || time == "")
    {
        swal(selctfoodtimings);
    }
    else
    {
        location.href = baseurl +"/canteen/featurevendor?seldate="+sdt+"&gettime="+time ;
        /*$(this).ajaxSubmit({
            success: function(response)
            {
                if(response.result === "success" ){ 
                    setTimeout(function(){ location.href = baseurl +"canteen/featurevendor ;  }, 1000);
                }
                else {
                    swal("Some error occured. Please try again","error");
                }
            } 
        }); */ 
    }
    return false;
});

$('#foodhistory_table').on("click",".canclfood",function()
{   
    var str =  "Student Food Cancelled" ;
    var url = baseurl+"/canteen/cancel";
    var id = $(this).data("id") ;
    var refscrf = $("input[name='_csrfToken']").val();
    //alert(refscrf);
    swal({
        title: areyou,
        text: "You want to cancel the food item",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: "Yes Cancel it",
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : id ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                if(response.result == "success"){
                    swal("Food Items", str+" "+ hasbeen, "success");
                    setTimeout(function() { location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
});
$("#getfeaturevendors").submit(function(e){
    e.preventDefault();
    var time = $("#gettime").val();
    var sdt = $("#seldate").val();
    if(sdt == "" || time == "")
    {
        swal(selctfoodtimings);
    }
    else
    {
        location.href = baseurl +"/canteen/featurevendor?seldate="+sdt+"&gettime="+time ;
        /*$(this).ajaxSubmit({
            success: function(response)
            {
                if(response.result === "success" ){ 
                    setTimeout(function(){ location.href = baseurl +"canteen/featurevendor ;  }, 1000);
                }
                else {
                    swal("Some error occured. Please try again","error");
                }
            } 
        }); */ 
    }
    return false;
});   
$("#addfoodform").submit(function(e){
    e.preventDefault();
    $("#addfibtn").prop("disabled", false);
    $("#addfibtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#addfibtn").text("Save");
            $("#fierror").html(errorocc) ;
            $("#fierror").fadeIn().delay('5000').fadeOut('slow');
            $("#addfibtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addfibtn").text("Save");
            $("#addfibtn").prop("disabled", false);
            if(response.result === "success" )
            {
                $("#fisuccess").html("Food item added successfully") ;
                $("#fisuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Canteenvendors/fooditems" ;  }, 1000);
            }
            
            else{
                $("#fierror").html(response.result) ;
                $("#fierror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#editfoodform").submit(function(e){
    e.preventDefault();
    $("#editfibtn").prop("disabled", false);
    $("#editfibtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#editfibtn").text("Update");
            
            $("#efierror").html(errorocc) ;
            $("#efierror").fadeIn().delay('5000').fadeOut('slow');
            $("#editfibtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editfibtn").text("Update");
            
            $("#editfibtn").prop("disabled", false);
            if(response.result === "success" ){
                $("#efisuccess").html("Food item updated successfully") ;
                $("#efisuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Canteenvendors/fooditems" ;  }, 1000);
            }
            else{
                $("#efierror").html(response.result) ;
                $("#efierror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('#fitem_table tbody').on("click",".editfood",function()
{
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/Canteenvendors/geteditfood", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {       
         if (result) {
            console.log(result);
            $("#editfood").modal("show");
            $("#efood_name").val(result.food_name);
            $("#efood_detail").val(result.details);
            $('#foodimage').html("<img src='"+baseurl+"/webroot/c_food/"+result.food_img+"' width='40px' height='40px'>");
            $('#eid').val(id);
            $('#efimg').val(result.food_img);
        
          }
        }
    });
});

$("#addservefoodscl").submit(function(e){
    e.preventDefault();
    $("#addassignbtn").prop("disabled", false);
    $("#addassignbtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#addassignbtn").text("Save");
            $("#assignerror").html(errorocc) ;
            $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addassignbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addassignbtn").text("Save");
            $("#addassignbtn").prop("disabled", false);
            if(response.result === "success" )
            {
                $("#assignsuccess").html("Food assigned successfully") ;
                $("#assignsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Canteenvendors/assignfoodscl" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#assignerror").html(filldetails) ;
                $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#assignerror").html("error") ;
                $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#assignerror").html(response.result) ;
                $("#assignerror").fadeIn().delay('10000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#canpin_genform").submit(function(e){
    e.preventDefault();
    $("#canpin_gen").prop("disabled", false);
    $("#canpin_gen").text(updating+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#canpin_gen").text("Update");
            $("#cpinerror").html(errorocc) ;
            $("#cpinerror").fadeIn().delay('5000').fadeOut('slow');
            $("#canpin_gen").prop("disabled", false);
        },
        success: function(response)
        {
            $("#canpin_gen").text("Update");
            $("#canpin_gen").prop("disabled", false);
            if(response.result === "success" )
            {
                $("#cpinsuccess").html(pinupdtedsuc) ;
                $("#cpinsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else{
                $("#cpinerror").html(response.result) ;
                $("#cpinerror").fadeIn().delay('10000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#qrpassform").submit(function(e){
    e.preventDefault();
    $("#canpin_gen").prop("disabled", false);
    $("#canpin_gen").text(updating+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#canpin_gen").text("Update");
            $("#cpinerror").html(errorocc) ;
            $("#cpinerror").fadeIn().delay('5000').fadeOut('slow');
            $("#canpin_gen").prop("disabled", false);
        },
        success: function(response)
        {
            $("#canpin_gen").text("Update");
            $("#canpin_gen").prop("disabled", false);
            if(response.result === "success" )
            {
                $("#cpinsuccess").html("QR Code passcode updated successfully/") ;
                $("#cpinsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else{
                $("#cpinerror").html(response.result) ;
                $("#cpinerror").fadeIn().delay('10000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#editservefoodscl").submit(function(e){
    e.preventDefault();
    $("#editassignbtn").prop("disabled", false);
    $("#editassignbtn").text(updating+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#editassignbtn").text("Update");
            $("#eassignerror").html(errorocc) ;
            $("#eassignerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editassignbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editassignbtn").text("Update");
            $("#editassignbtn").prop("disabled", false);
            if(response.result === "success" )
            {
                $("#eassignsuccess").html("Food assigned updated successfully") ;
                $("#eassignsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Canteenvendors/assignfoodscl" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#eassignerror").html(filldetails) ;
                $("#eassignerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#eassignerror").html("error") ;
                $("#eassignerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#eassignerror").html(response.result) ;
                $("#eassignerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#addassignfoodform").submit(function(e){
    e.preventDefault();
    $("#addassignbtn").prop("disabled", false);
    $("#addassignbtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#addassignbtn").text("Save");
            $("#assignerror").html(errorocc) ;
            $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addassignbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addassignbtn").text("Save");
            $("#addassignbtn").prop("disabled", false);
            if(response.result === "success" )
            {
                $("#assignsuccess").html("Food assigned successfully") ;
                $("#assignsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Canteenvendors/assignfood" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#assignerror").html(filldetails) ;
                $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#assignerror").html("error") ;
                $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#assignerror").html(response.result) ;
                $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#editassignfoodform").submit(function(e){
    e.preventDefault();
    $("#editassignbtn").prop("disabled", false);
    $("#editassignbtn").text(updating+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#editassignbtn").text("Update");
            $("#assignerror").html(errorocc) ;
            $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editassignbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editassignbtn").text("Update");
            $("#editassignbtn").prop("disabled", false);
            if(response.result === "success" )
            {
                $("#assignsuccess").html("Food assigned updated successfully") ;
                $("#assignsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Canteenvendors/assignfood" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#assignerror").html(filldetails) ;
                $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#assignerror").html("error") ;
                $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#assignerror").html(response.result) ;
                $("#assignerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});
$('.foodserve_table').on("click",".js-sweetalert",function(){
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var str =  $(this).data("str") ;
    //alert(id);
    if (type === 'confirm') {
        showConfirmMessage(id,url,str);
    }
});

$("#orderinfo").submit(function(e){
    e.preventDefault();
    $("#addorderbtn").prop("disabled", true);
    $("#viewdtlordrbody").html("")
    $("#studinfo").html("")
    $("#downloadinvoice").html("")
    //alert("Cfdv");
    $(this).ajaxSubmit({
        error: function(){
            $("#addorderbtn").prop("disabled", false);
            $("#ordererror").html(errorocc) ;
            $("#ordererror").fadeIn().delay('5000').fadeOut('slow');
        },
        success: function(response)
        {
            console.log(response);
            $("#addorderbtn").prop("disabled", false);
            if(response.result === "success" )
            {
                $("#addclass").modal("hide");
                $("#orderdinfo").modal("show");
                $("#orderid").val(response.orderid);
                $("#studinfo").html(response.studinfo)
                $("#viewdtlordrbody").html(response.data)
                $("#downloadinvoice").html(response.invoice)
                $("#rmrk").html(response.remark)
                $("#delvrall").html(response.da)
            }
            else{
                $("#ordererror").html(response.result) ;
                $("#ordererror").fadeIn().delay('10000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#delvrall").on("click", ".deliverallfd", function(){
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var ids = $(this).data("ids") ;
    console.log(ids);
    var refscrf = $("input[name='_csrfToken']").val() ;
    var ordrno = $("#orderid").val();
    //alert(ordrno)
    swal({
        title: areyou,
        text: chngefdsts,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: markdelivr,
        cancelButtonText: cncl,  
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : ids ,
               _csrfToken : refscrf,
               order_no: ordrno,
            },
            type : "post",
			url: baseurl + '/Cvendordashboard/orderstsall',
            success: function(response){
                
                $(this).html("");
                //var foodids = ids.split(",");
                console.log(ids)
                swal(ordrsts, allmrkordrsts, "success");
                $("#viewdtlordrbody").html(response.data)
                
            }
        })
    });
});


$('.viewdtlorder_table').on("click",".changeosts",function(){
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var sts =  $(this).data("osts") ;
    var str =  $(this).data("str") ;
    /*alert(sts);
    alert(id);*/
    if (sts == 0) {
        //showConfirmMessage(id,url,str);
        var refscrf = $("input[name='_csrfToken']").val() ;
    
        swal({
            title: areyou,
            text: "You want to change the food status",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            confirmButtonText: "Yes, Mark deliver",
            cancelButtonText: cncl,  
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.ajax({
                data : {
                    val : id ,
                   _csrfToken : refscrf,
                   sts : sts
                },
                type : "post",
    			url : url,
    			
                success: function(response){
                    console.log(response.result)
                    //swal("Order Status", str+" "+ hasbeen, "success");
                        $(this).html("");
                    //if(response.result === "success"){
                        /*alert(this);
                        alert("dfvc")*/
                        swal("Order Status", str+" "+ hasbeen, "success");
                        $("#foodid"+id).html("Delivered");
                        
                        //setTimeout(function(){ location.reload() ;  }, 1000);
                    /*}
                    else{
                        swal(errorpop, response.result, "error");
                    }*/
                }
            })
        });
    }
    else {
        swal("You can't change the status of Undelivered / Cancelled / Delivered")
    }
});


/*************************Canteen*************/
     
$('#timetbl_table').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            var sts =  $(this).data("status") ;
            showConfirmMessage(id,url,str,sts);
        }
     });

$('.attendnce_dtl').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
            
        var geturl = $(this).data("get") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
        if (type === 'status_change') {
            var sts =  $(this).data("status") ;
            showstatusConfirmMessage(id,url,str,sts);
        }
     });
$('.quest_table').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
            
        var geturl = $(this).data("get") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
        if (type === 'status_change') {
            var sts =  $(this).data("status") ;
            showstatusConfirmMessage(id,url,str,sts);
        }
     });
     
$('.gallery_table').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
        if (type === 'status_change') {
            var sts =  $(this).data("status") ;
            showstatusConfirmMessage(id,url,str,sts);
        }
        
     });
     
$('#approveTable').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        var sts =  $(this).data("status") ;
        
        showstatusConfirmMessage1(id,url,str,sts);
        
     });
 $('.subjectsclass_table').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
        
     });  
     
 $('.viewdiscovery').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
        
     });  


$('.category_table').on("click",".js-sweetalert",function(){
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var str =  $(this).data("str") ;
    if (type === 'confirm') {
        showConfirmMessage(id,url,str);
    }
});
$('.feedet_table').on("click",".js-sweetalert",function(){
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var str =  $(this).data("str") ;
    if (type === 'confirm') {
        showConfirmMessage(id,url,str);
    }
});

$('#viewcommunity').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
     });  
$('.kinderactivities').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
     }); 
 
$('#delstudent_table').on("click",".js-sweetalert",function(){
    //alert("hj");
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
     });  
$('#teacher_table').on("click",".js-sweetalert",function(){
    //alert("hj");
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
        else if (type === 'status_change') {
			var sts =  $(this).data("status") ;
            showstatusConfirmMessage(id,url,str,sts);
        }
     }); 

$('.class_table').on("click",".js-sweetalert",function(){
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var str =  $(this).data("str") ;
    if (type === 'confirm') {
        showConfirmMessage(id,url,str);
    }
});

$('.subjects_table').on("click",".js-sweetalert",function(){
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var str =  $(this).data("str") ;
    if (type === 'confirm') {
        showConfirmMessage(id,url,str);
    }
});

$('.session_table').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
        
    }); 
$('.product_table').on("click",".js-sweetalert",function(){
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var str =  $(this).data("str") ;
    if (type === 'confirm') {
        showConfirmMessage(id,url,str);
    } 
        
}); 
     
$('.viewtutcontent').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
        
     }); 
$('#feehead_table').on("click",".js-sweetalert",function(){
        var type = $(this).data('type');        
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        } 
        
     }); 
     
$('#myModal').on('hidden.bs.modal', function () {
    $('#subjectdetails').modal('show')
})
     
$('#approve').click(function(){
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#approveTable input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
    var refscrf = $("input[name='_csrfToken']").val();
    console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: chngselsts,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yeschng,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal(statuschng, str+" "+haschng, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
});



$('#approveexamsass').click(function(){
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    $('#approveTable input[type=checkbox]').each(function() {
        if (jQuery(this).is(":checked")) {
            var id = this.id;
           // alert(id);
            post_arr.push(id);
        }
    });
    //alert(url);
    console.log(post_arr);
    var refscrf = $("input[name='_csrfToken']").val();
    if(post_arr.length > 0){
        swal({
            title: areyou,
            text: chngselsts,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            confirmButtonText: yeschng,
            cancelButtonText: cncl,  
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.ajax({
                data : {
                    val : post_arr ,
                   _csrfToken : refscrf,
                   str : str
                },
                type : "post",
                url : url,
                
                success: function(response){
                    console.log(response);
                   
                    if(response.result == "success"){
                        swal(statuschng, str+" "+ haschng, "success");
                        setTimeout(function(){ location.reload() ;  }, 1000);
                    }
                    else{
                        swal(errorpop, response.result, "error");
                    }
                }
            })
        });
    } 
});

$('#approvenotify').click(function(){
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    $('#notification_table input[type=checkbox]').each(function() {
        if (jQuery(this).is(":checked")) {
            var id = this.id;
            var postid = id;
            post_arr.push(postid);
        }
    });
     
    var refscrf = $("input[name='_csrfToken']").val();
    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: chngselsts,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yeschng,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal(statuschng, str+" "+ haschng, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
});


$('#approveclass').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#approveTable input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
     
    var refscrf = $("input[name='_csrfToken']").val();
    //console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: chngselsts,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yeschng,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal(statuschng, str+" "+ haschng, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
});

$("#sclpriv").on('click', '.schoolmenus', function(){
    var val = $(this).val();
    //alert(val);
    $("#subrolespriv"+val).html("");
    if ($("#subpriv"+val).is(':checked')) 
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/schoolSubadmin/getsubroles',
            data:'privid='+val,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                $("#subrolespriv"+val).html(result);
            }
        });
    }
    else
    {
        $("#subrolespriv"+val).html("");
    }
    
});

$('#approvesubject').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#approveTable input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
    var refscrf = $("input[name='_csrfToken']").val();
    //console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: chngselsts,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yeschng,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal(statuschng, str+" "+ haschng, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
});

$('#deleteallstud').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#delstudent_table input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
   var refscrf = $("input[name='_csrfToken']").val();  
    console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: selstud,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yesdelete,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal("Students!", str+" "+ hasbeen, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
    else
    {
        swal(errorpop, plstud, "error");
    }
});

$('#deleteallmarket').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    $('#marktqueriestable input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        var postid = id;
        post_arr.push(postid);
      }
    });
   var refscrf = $("input[name='_csrfToken']").val();  
    console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: selqueries,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yesdelete,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal("Queries!", str+" "+ hasbeen, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
    else
    {
        swal(errorpop, plqueries, "error");
    }
});
$('#deleteallqueries').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    $('#mentortable input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        var postid = id;
        post_arr.push(postid);
      }
    });
   var refscrf = $("input[name='_csrfToken']").val();  
    console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: selqueries,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yesdelete,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal("Queries!", str+" "+ hasbeen, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
    else
    {
        swal(errorpop, plqueries, "error");
    }
});

$('#deletealluniqueries').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    $('#univtable input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        var postid = id;
        post_arr.push(postid);
      }
    });
   var refscrf = $("input[name='_csrfToken']").val();  
    console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: selqueries,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yesdelete,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal("Queries!", str+" "+ hasbeen, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
    else
    {
        swal(errorpop, plqueries, "error");
    }
});

$('#deleteallschools').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#school_table input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
     var refscrf = $("input[name='_csrfToken']").val();
    console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: selscl,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yesdelete,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal("Schools!", str+" "+ hasbeen, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
    else
    {
        swal(errorpop, plscl, "error");
    }
});

$('#deletealltchrs').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#teacher_table input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
    var refscrf = $("input[name='_csrfToken']").val(); 
    console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: seltchr,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yesdelete,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal("Teachers!", str+" "+ hasbeen, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
    else
    {
        swal(errorpop, pltchr, "error");
    }
});

$('#deleteallmeetings').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#meetinglink_table input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
     var refscrf = $("input[name='_csrfToken']").val();
    console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: selmeet,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yesdelete,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal("Meetings!", str+" "+ hasbeen, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
    else
    {
        swal(errorpop, plval, "error");
    }
});


$('#deleteallnotify').click(function(){
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('.notification_table input[type=checkbox][class=checkbox-tick]').each(function() {
       
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        var postid = id;
        
        post_arr.push(postid);
      }
    });
    var refscrf = $("input[name='_csrfToken']").val();
    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: selnotf,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yesdelete,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal("Notifications!", str+" "+hasbeen, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
    else
    {
        swal(errorpop, "Please select Notifications", "error");
    }
});

$('#approveclasssub').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#approveTable input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
     
    //console.log(post_arr);
var refscrf = $("input[name='_csrfToken']").val();
    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: chngselsts,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yeschng,
        cancelButtonText: cncl,  
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal(statuschng, str+" "+ haschng, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
});

$('#approvestudent').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#approveTable input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
     var refscrf = $("input[name='_csrfToken']").val();
    //console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: chngselsts,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yeschng,
        cancelButtonText: cncl,  
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal(statuschng, str+" "+ haschng, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
});

$('#approveteacher').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#approveTable input[type=checkbox]').each(function() {
      if (jQuery(this).is(":checked")) {
        var id = this.id;
        //var splitid = id.split('_');
        var postid = id;

        post_arr.push(postid);
        
      }
    });
     var refscrf = $("input[name='_csrfToken']").val();
    //console.log(post_arr);

    if(post_arr.length > 0){

        swal({
        title: areyou,
        text: chngselsts,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yeschng,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : post_arr ,
               _csrfToken : refscrf,
               str : str
            },
            type : "post",
            url : url,
            
            success: function(response){
                //alert(response);
               
                if(response.result == "success"){
                    swal(statuschng, str+" "+ haschng, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
    } 
});

/* END */




$(".color_change_btn1").on('click', function(){
    var color_value = $(".color_change_btn1").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#buttoncolor").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    
});
$(".color_change_btn2").on('click', function(){
    var color_value = $(".color_change_btn2").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn3").on('click', function(){
    var color_value = $(".color_change_btn3").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn4").on('click', function(){
    var color_value = $(".color_change_btn4").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn5").on('click', function(){
    var color_value = $(".color_change_btn5").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn6").on('click', function(){
    var color_value = $(".color_change_btn6").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn7").on('click', function(){
    var color_value = $(".color_change_btn7").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn8").on('click', function(){
    var color_value = $(".color_change_btn8").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn9").on('click', function(){
    var color_value = $(".color_change_btn9").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn10").on('click', function(){
    var color_value = $(".color_change_btn10").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn11").on('click', function(){
    var color_value = $(".color_change_btn11").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn12").on('click', function(){
    var color_value = $(".color_change_btn12").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn13").on('click', function(){
    var color_value = $(".color_change_btn13").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});
$(".color_change_btn14").on('click', function(){
    var color_value = $(".color_change_btn14").val();
    //alert(color_value);
    $("#button_color").val(color_value);
    $("#button_color").css("color",color_value);
    $("#button_color").css("background", color_value);
    $("#buttoncolor").val(color_value);
});


$(".color_change_nav1").on('click', function(){
    var color_value = $(".color_change_nav1").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav2").on('click', function(){
    var color_value = $(".color_change_nav2").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav3").on('click', function(){
    var color_value = $(".color_change_nav3").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav4").on('click', function(){
    var color_value = $(".color_change_nav4").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav5").on('click', function(){
    var color_value = $(".color_change_nav5").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav6").on('click', function(){
    var color_value = $(".color_change_nav6").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav7").on('click', function(){
    var color_value = $(".color_change_nav7").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav8").on('click', function(){
    var color_value = $(".color_change_nav8").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav9").on('click', function(){
    var color_value = $(".color_change_nav9").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav10").on('click', function(){
    var color_value = $(".color_change_nav10").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav11").on('click', function(){
    var color_value = $(".color_change_nav11").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav12").on('click', function(){
    var color_value = $(".color_change_nav12").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav13").on('click', function(){
    var color_value = $(".color_change_nav13").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});
$(".color_change_nav14").on('click', function(){
    var color_value = $(".color_change_nav14").val();
    //alert(color_value);
    $("#nav_bar").val(color_value);
    $("#nav_bar").css("color",color_value);
    $("#nav_bar").css("background", color_value);
    $("#navbar").val(color_value);
});

/* Add user form submission */
$('.adduserbtn').click(function(){
     $(".adduserbtn").text(saving);        
 });

$("#adduserform").submit(function(e)
{
    e.preventDefault();
    $("#adduserbtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#usererror").html(errorocc) ;
            $("#usererror").fadeIn().delay('5000').fadeOut('slow');
            $("#adduserbtn").prop("disabled", false);
            $(".adduserbtn").text(savescript);  
        },
        success: function(response)
        {
            $("#adduserbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#usersuccess").html(usradd) ;
                $("#usersuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".adduserbtn").text(savescript);  
                setTimeout(function(){ location.href = baseurl +"/subadmin" ;  }, 1000);
            }
            else if(response.result === "exist" )
            {
                $("#usererror").html(useremailalrex) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".adduserbtn").text(savescript);
            }
            else if(response.result === "empty" )
            {
                $("#usererror").html(filldetails) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".adduserbtn").text(savescript);
            }
            else
            {
                $("#usererror").html(response.result) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".adduserbtn").text(savescript);
            }
        } 
    });     
    return false;
});

$("#subrecoderform").submit(function(e)
{
    e.preventDefault();
    $("#recorderbtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#usererror").html(errorocc) ;
            $("#usererror").fadeIn().delay('5000').fadeOut('slow');
            $("#recorderbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#recorderbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#recordersuccess").html("Marks of this subject recorder submitted successfully.") ;
                $("#recordersuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else
            {
                $("#recordererror").html(response.result) ;
                $("#recordererror").fadeIn().delay('5000').fadeOut('slow');
                
            }
        } 
    });     
    return false;
});


$("#addsubadminform").submit(function(e)
{
    //alert(subalready);
    e.preventDefault();
    $("#adduserbtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#usererror").html(errorocc) ;
            $("#usererror").fadeIn().delay('5000').fadeOut('slow');
            $("#adduserbtn").prop("disabled", false);
            $(".adduserbtn").text(savescript);  
        },
        success: function(response)
        {
            $("#adduserbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#usersuccess").html(subadmadd) ;
                $("#usersuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".adduserbtn").text(savescript);  
                setTimeout(function(){ location.href = baseurl +"/schoolSubadmin" ;  }, 1000);
            }
            else if(response.result === "exist" )
            {
                $("#usererror").html(subadalready) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".adduserbtn").text(savescript);
            }
            else if(response.result === "empty" )
            {
                $("#usererror").html(filldetails) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".adduserbtn").text(savescript);
            }
            else
            {
                $("#usererror").html(response.result) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".adduserbtn").text(savescript);
            }
        } 
    });     
    return false;
});

$("#tutoringloginform").submit(function(e)
{
    e.preventDefault();
    var email = $("#tutoremail").val();
    var password = $("#tutorpassword").val();
    var tutorid = $("#tutorid").val();
    if(email !='' && password !=''){
        $.ajax({
            type:'POST',
            url: baseurl + '/tutoringCenter/tutoringcenter',
            data:'email='+email+'&password='+password+'&tutorid='+tutorid,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            error: function(){
                $("#tutoringloginerror").html(errorocc) ;
                $("#tutoringloginerror").fadeIn().delay('5000').fadeOut('slow');
                $(".tutoringloginbtn").text('Login');
            },
            success: function(response)
            {
                $("#addfeebtn").prop("disabled", false);
                $("#addfeebtn").text(savescript);
                if(response.result === "success" )
                { 
                    $("#tutoringloginsuccess").html(loginsucc) ;
                    $("#tutoringloginsuccess").fadeIn().delay('5000').fadeOut('slow');
                    $(".tutoringloginbtn").text('Login');  
                    setTimeout(function(){ location.href = baseurl +"/tutoringCenter/subjects/"+response.data['teacher_id']+'/'+response.data['class_id']+'/'+response.data['subject_id'] ;  }, 1000);
                }
                else
                {
                    $("#tutoringloginerror").html(invalidlogin) ;
                    $("#tutoringloginerror").fadeIn().delay('5000').fadeOut('slow');
                    $(".tutoringloginbtn").text('Login');
                }
            } 
        });   
    }
    return false;
});

/* END */
/* Edit user form submission */
$('.edituserbtn').click(function(){
     $(".edituserbtn").text(updating);        
 });

$("#edituserform").submit(function(e){
    e.preventDefault();
    $("#edituserbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#usererror").html(errorocc) ;
            $("#usererror").fadeIn().delay('5000').fadeOut('slow');
            $("#edituserbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#edituserbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#usersuccess").html(usrup) ;
                $("#usersuccess").fadeIn();
                $(".edituserbtn").text(updatescript);  
                setTimeout(function(){ location.href = baseurl +"/subadmin" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#usererror").html(filldetails) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".edituserbtn").text(updatescript);
            }
            else if(response.result === "exist" ){
                $("#usererror").html(useremailalrex) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".edituserbtn").text(updatescript);
            }
            else{
                $("#usererror").html(response.result) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".edituserbtn").text(updatescript);
            }
        } 
    });     
    return false;

});

$("#scltimetblform").submit(function(e){
    e.preventDefault();
    $("#updatesclbtn").text('Updating..');    
    $("#updatesclbtn").prop("disabled", true);
    var sclid = $("#sclid").val();
    $(this).ajaxSubmit({
        error: function(){
            $("#sclerror").html(errorocc) ;
            $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
            $("#updatesclbtn").prop("disabled", false);
            $("#updatesclbtn").text(updatescript);   
        },
        success: function(response)
        {
            $("#updatesclbtn").prop("disabled", false);
            $("#updatesclbtn").text(updatescript);   
            if(response.result === "success" ){ 
                $("#sclsuccess").html("Time table setup successfully") ;
                $("#sclsuccess").fadeIn();
                setTimeout(function(){ location.href = baseurl +"/schools/schedular/"+sclid ;  }, 1000);
            }
            else{
                $("#sclerror").html(response.result) ;
                $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
            }   
        } 
    });     
    return false;
});

  /* END */

$("#editsubadminform").submit(function(e){
    e.preventDefault();
    $("#edituserbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#usererror").html(errorocc) ;
            $("#usererror").fadeIn().delay('5000').fadeOut('slow');
            $("#edituserbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#edituserbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#usersuccess").html(subadup) ;
                $("#usersuccess").fadeIn();
                $(".edituserbtn").text(updatescript);  
                setTimeout(function(){ location.href = baseurl +"/schoolSubadmin" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#usererror").html(filldetails) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".edituserbtn").text(updatescript);
            }
            else if(response.result === "exist" ){
                $("#usererror").html(subalready) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".edituserbtn").text(updatescript);
            }
            else{
                $("#usererror").html(response.result) ;
                $("#usererror").fadeIn().delay('5000').fadeOut('slow');
                $(".edituserbtn").text(updatescript);
            }
        } 
    });     
    return false;

});


/* END */ 

/* Get City Name in school controller */


$('.countries').change(function(){
    var id = $(this).children("option:selected").val();
    var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/schools/getstate", 
              data: {"id":id,_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result) 
                 {  
                    $('#state').val(id);
                    let dropdown = $('#state');
                    dropdown.empty();

                    dropdown.append('<option selected="true" value="" disabled >'+chosestate+'</option>');
                    dropdown.prop('selectedIndex', 0);

                     $.each(result, function (key, entry) {
                      dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name));
                    })
                 }
                }
              });
});


/*End*/

$("#send_message").submit(function(e){
    e.preventDefault();
    
    $("#msgsendbtn").prop("disabled", true);
    $("#msgsendbtn").text('Saving...');
    $(this).ajaxSubmit({
        error: function(){
            $("#msgsenderror").html("Some error occured. Please try again.") ;
            $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
            $("#msgsendbtn").prop("disabled", false);
            $("#msgsendbtn").text('Save');
        },
        success: function(response){
            $("#msgsendbtn").prop("disabled", false);
            $("#msgsendbtn").text('Save');
            if(response.result === "success" ){ 
                $("#msgsendsuccess").html(msgsent) ;
                $("#msgsendsuccess").fadeIn();
                setTimeout(function(){ location.href = baseurl +"/StudentMessages/" ;  }, 1000);
                      
            }else if(response.result === "empty" ){
                $("#msgsenderror").html(filldetails) ;
                $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
                
            }else if(response.result === "activity" ){
                $("#msgsenderror").html("Activity not added.") ;
                $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
                
            }else{
                $("#msgsenderror").html(response.result) ;
                $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#tsend_message").submit(function(e){
    e.preventDefault();
    
    $("#msgsendbtn").prop("disabled", true);
    $("#msgsendbtn").text('Saving...');
    $(this).ajaxSubmit({
        error: function(){
            $("#msgsenderror").html("Some error occured. Please try again.") ;
            $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
            $("#msgsendbtn").prop("disabled", false);
            $("#msgsendbtn").text('Save');
        },
        success: function(response){
            $("#msgsendbtn").prop("disabled", false);
            $("#msgsendbtn").text('Save');
            if(response.result === "success" ){ 
                $("#msgsendsuccess").html(msgsent) ;
                $("#msgsendsuccess").fadeIn();
                setTimeout(function(){ location.href = baseurl +"/TeacherMessages/" ;  }, 1000);
                      
            }else if(response.result === "empty" ){
                $("#msgsenderror").html(filldetails) ;
                $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
                
            }else if(response.result === "activity" ){
                $("#msgsenderror").html("Activity not added.") ;
                $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
                
            }else{
                $("#msgsenderror").html(response.result) ;
                $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

/* Add School Form */


$('.addsclbtn').click(function(){
    $(".addsclbtn").text(saving);        
});

$("#addsclform").submit(function(e){
    e.preventDefault();
    $("#addsclbtn").prop("disabled", true);
    
    $(this).ajaxSubmit({
        error: function(){
            $("#sclerror").html(errorocc) ;
            $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addsclbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addsclbtn").prop("disabled", false);
            
            if(response.result === "success" )
            { 
                $("#sclsuccess").html(scladd) ;
                $("#sclsuccess").fadeIn().delay('5000').fadeOut('slow');
                $('#addsclform').trigger("reset");
                
                $(".state").val(null).trigger("change");
                $(".city").val(null).trigger("change"); 
                
                $(".addsclbtn").text(savescript);
                setTimeout(function(){ location.href = baseurl +"/schools/" ;  }, 1000);
            }
            else if(response.result === "error" ){ 
                $("#sclerror").html(recordntsave) ;
                $("#sclerror").fadeIn();
                $(".addsclbtn").text(savescript);
            } 
            else if(response.result === "empty" ){
                $("#sclerror").html(filldetails) ;
                $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
                $(".addsclbtn").text(savescript);
            }
            else if(response.result === "exist" ){
                $("#sclerror").html(sclalready) ;
                $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
                $(".addsclbtn").text(savescript);
            }
            else if(response.result === "activity" ){
                $("#sclerror").html(activityadd) ;
                $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
                $(".addsclbtn").text(savescript);
            }
            else if(response.result === "failed" ){
                $("#sclerror").html(sclntsave) ;
                $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
                $(".addsclbtn").text(savescript);
            }
            else{
                $("#sclerror").html(response.result) ;
                $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
                $(".addsclbtn").text(savescript);
            }
        } 
    });     
    return false;
});

/* END */

$("#addvendorform").submit(function(e){
    e.preventDefault();
    $("#addvendrbtn").prop("disabled", true);
    
    $(this).ajaxSubmit({
        error: function(){
            $("#vendrerror").html(errorocc) ;
            $("#vendrerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addvendrbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addvendrbtn").prop("disabled", false);
            $("#addvendrbtn").text(savescript);
            if(response.result === "success" )
            { 
                $("#vendrsuccess").html("Canteen Vendor added successfully.") ;
                $("#vendrsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Canteenvendors/" ;  }, 1000);
            }
            else if(response.result === "error" ){ 
                $("#vendrerror").html(recordntsave) ;
                $("#vendrerror").fadeIn();
            } 
            else if(response.result === "empty" ){
                $("#vendrerror").html(filldetails) ;
                $("#vendrerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#vendrerror").html(sclalready) ;
                $("#vendrerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#vendrerror").html(response.result) ;
                $("#vendrerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#editvendorform").submit(function(e){
    e.preventDefault();
    $("#editvendrbtn").prop("disabled", true);
    
    $(this).ajaxSubmit({
        error: function(){
            $("#evendrerror").html(errorocc) ;
            $("#evendrerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editvendrbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editvendrbtn").prop("disabled", false);
            $("#editvendrbtn").text(savescript);
            if(response.result === "success" )
            { 
                $("#evendrsuccess").html("Canteen Vendor added successfully.") ;
                $("#evendrsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Canteenvendors/" ;  }, 1000);
            }
            else if(response.result === "error" ){ 
                $("#evendrerror").html(recordntsave) ;
                $("#evendrerror").fadeIn();
            } 
            else if(response.result === "empty" ){
                $("#evendrerror").html(filldetails) ;
                $("#evendrerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#evendrerror").html(sclalready) ;
                $("#evendrerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#evendrerror").html(response.result) ;
                $("#evendrerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});
/* Update School Form */


$('.updatesclbtn').click(function(){
     $(".updatesclbtn").text(updating);        
 });



$("#updatesclform").submit(function(e){
    e.preventDefault();
    
  $("#updatesclbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#sclerror").html(errorocc) ;
        $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
  $("#updatesclbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#updatesclbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#sclsuccess").html(sclupd) ;
        $("#sclsuccess").fadeIn();
    $(".updatesclbtn").text(updatescript);  
        setTimeout(function(){ location.href = baseurl +"/schools/" ;  }, 1000);
                  
            }
        
        else if(response.result === "exist" ){
          $("#sclerror").html(sclalready) ;
          $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
    $(".updatesclbtn").text(updatescript);
  }
      else if(response.result === "empty" ){
          $("#sclerror").html(filldetails) ;
          $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
    $(".updatesclbtn").text(updatescript);
  }
  else if(response.result === "activity" ){
          $("#sclerror").html(activityadd) ;
          $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
    $(".updatesclbtn").text(updatescript);
  }
         else if(response.result === "failed" ){
          $("#sclerror").html(sclntsave) ;
          $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
    $(".updatesclbtn").text(updatescript);
  }
  else{
    $("#sclerror").html(response.result) ;
    $("#sclerror").fadeIn().delay('5000').fadeOut('slow');
    $(".updatesclbtn").text(updatescript);
  }
    } 
  });     
  return false;
  
  });

  /* END */
  

/* Add Class form submission */

$("#reportmaxrequestform").submit(function(e){
    e.preventDefault();
    
    $("#addclsbtn").prop("disabled", true);
    
    $(this).ajaxSubmit({
        error: function(){
            $("#clserror").html(errorocc) ;
            $("#clserror").fadeIn().delay('5000').fadeOut('slow');
            $("#addclsbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addclsbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                swal({
        			title: 'Rquest Sent',
        			text: 'Request sent successfully',
        			type: 'success'
        		});

                /*swal("Request sent successfully");
                
                setTimeout(function(){ location.reload() ;  }, 1000);*/
            }
            else{
                /*swal("response.result");*/
                Swal({
                  title: "Rquest Not Sent",
                  text: response.result,
                  icon: "error"
                });
                /*$("#clserror").html(response.result) ;
                $("#clserror").fadeIn().delay('5000').fadeOut('slow');*/
            }
        } 
    });     
    return false;
});

/* END */
$("#studentdairyform").submit(function(e){
    e.preventDefault();
    $("#studdairybtn").prop("disabled", true);
    $(this).ajaxSubmit({
        error: function(){
            $("#sderror").html(errorocc) ;
            $("#sderror").fadeIn().delay('5000').fadeOut('slow');
            $("#studdairybtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#studdairybtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#sdsuccess").html("Dairy Content updated successfully.") ;
                $("#sdsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload() ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#sderror").html(filldetails) ;
                $("#sderror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#sderror").html(response.result) ;
                $("#sderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#uploaddairysignform").submit(function(e){
    e.preventDefault();
    $("#studdairybtn").prop("disabled", true);
    $(this).ajaxSubmit({
        error: function(){
            $("#sderror").html(errorocc) ;
            $("#sderror").fadeIn().delay('5000').fadeOut('slow');
            $("#studdairybtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#studdairybtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#sdsuccess").html("Signature uploaded successfully.") ;
                $("#sdsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload() ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#sderror").html(filldetails) ;
                $("#sderror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#sderror").html(response.result) ;
                $("#sderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});
/* Add kinderdash image upload submission */

$("#addimageform").submit(function(e){
    e.preventDefault();
    $("#addimagebtn").prop("disabled", true);
    $(this).ajaxSubmit({
        error: function(){
            $("#addimageerror").html(errorocc) ;
            $("#addimageerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addimagebtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addimagebtn").prop("disabled", false);
            if(response.result === "success" ) { 
                $("#addimagesuccess").html(actup) ;
                $("#addimagesuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Kindergarten" ;  }, 1000);
            }
            else if(response.result === "empty" ) {
                $("#addimageerror").html(actimg) ;
                $("#addimageerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else {
                $("#addimageerror").html(response.result) ;
                $("#addimageerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#addnewactivityform").submit(function(e){
    e.preventDefault();
    $("#addactivitybtn").prop("disabled", true);
    $(this).ajaxSubmit({
        error: function(){
            $("#addactivityerror").html(errorocc) ;
            $("#addactivityerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addactivitybtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addactivitybtn").prop("disabled", false);
            if(response.result === "success" ) { 
                $("#addactivitysuccess").html(actadd) ;
                $("#addactivitysuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Kindergarten" ;  }, 1000);
            }
            else if(response.result === "empty" ) {
                $("#addactivityerror").html(actimg) ;
                $("#addactivityerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else {
                $("#addactivityerror").html(response.result) ;
                $("#addactivityerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

/* END */

$("#addtimetblform").submit(function(e){
    e.preventDefault();
    $("#addtimetblbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function()
        {
            $("#tymtblerror").html(errorocc) ;
            $("#tymtblerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addtimetblbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addtimetblbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#tymtblsuccess").html(ttadd) ;
                $("#tymtblsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Timetable" ;  }, 1000);
            }
            else if(response.result === "empty" )
            {
                $("#tymtblerror").html(filldetails) ;
                $("#tymtblerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" )
            {
                $("#tymtblerror").html(ttalready) ;
                $("#tymtblerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else
            {
                $("#tymtblerror").html(response.result) ;
                $("#tymtblerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#edittimetblform").submit(function(e){
    e.preventDefault();
    $("#edittimetblbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function()
        {
            $("#etymtblerror").html(errorocc) ;
            $("#etymtblerror").fadeIn().delay('5000').fadeOut('slow');
            $("#edittimetblbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#edittimetblbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#etymtblsuccess").html(ttupd) ;
                $("#etymtblsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Timetable" ;  }, 1000);
            }
            else if(response.result === "empty" )
            {
                $("#etymtblerror").html(filldetails) ;
                $("#etymtblerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" )
            {
                $("#etymtblerror").html(ttalready) ;
                $("#etymtblerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else
            {
                $("#etymtblerror").html(response.result) ;
                $("#etymtblerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('#timetbl_table tbody').on("click",".edittimetbl",function(){
    $("#esubj").html('');
    $("#esclst").css("display","none");
    $("#escltymgs").html("");
    $("#esclslot").html("");
    var ttid = $(this).data('id');
   
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/timetable/edit", 
        data: {"id":ttid,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {    
            var sclinfo = result.sclinfo;
            console.log(result.getdata);
            if(result) 
            {
                
                $("#edittable").modal("show");
                //$('#class_'+ result[0]['class_id']).attr("selected","selected").trigger('change.select2');
                $("#eclass").select2().val(result.getdata['class_id']).trigger('change.select2');
                $("#eweekday").select2().val(result.getdata['week_day']).trigger('change.select2');
                $("#etchr_id").val(result.getdata['teacher_id']);
                //$("#etimepicker").select2().val(result.getdata['start_time']).trigger('change.select2');
                $("#etchr_name").val(result.getdata['tchr_name']);
                
                $("#esclst").css("display","inline");
                $("#escltymgs").html(sclinfo['school_stym']+ "-"+sclinfo['school_etym']);
                $("#esclslot").html(sclinfo['school_slot']);
                $("#editttid").val(result.getdata['id']);
                egetsub(result.getdata['class_id'] , result.getdata['subject_id'], result.getdata['start_time']);
            }
        }
    });
});



$('#attendancereport_table tbody').on("click",".attendupdate",function(){
    //alert("hi");
    var datesel = $(this).data('date');
    var attend = $(this).data('attend');
    var stid = $(this).data('studid');
    var studname = $(this).data('studname');
    var monthname =  $(this).data('monthname');
    var clsid = $(this).data('clsid');
    
    $("#studname").html("");
    $("#studname").html(studname);
    $("#monthname").html("");
    $("#monthname").html(monthname);
    $("#date").html("");
    $("#date").html(datesel);
    $('#attendnc_filter').select2().val(attend).trigger('change.select2');
    $("#studid").val(stid);
    $("#seldate").val(datesel);
    $("#classid").val(clsid);
    $("#editsclattend").modal("show");
});

$('#subjattendancereport_table tbody').on("click",".attendupdate",function(){
    //alert("hi");
    var datesel = $(this).data('date');
    var attend = $(this).data('attend');
    var stid = $(this).data('studid');
    var studname = $(this).data('studname');
    var monthname =  $(this).data('monthname');
    var clsid = $(this).data('clsid');
    var subid = $(this).data('subid');
    
    $("#studname").html("");
    $("#studname").html(studname);
    $("#monthname").html("");
    $("#monthname").html(monthname);
    $("#date").html("");
    $("#date").html(datesel);
    $('#attendnc_filter').select2().val(attend).trigger('change.select2');
    $("#studid").val(stid);
    $("#seldate").val(datesel);
    $("#classid").val(clsid);
    $("#subid").val(subid);
    $("#editsubattend").modal("show");
});

/* Get class details data and append in index page */


function classesadd(){
var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/classes/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $('#class_table').DataTable().destroy();
                    $('#classbody').html(result.html); 
                    $( "#class_table" ).DataTable({
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

if(controller=="classes"){
var grades = $('#grades').val() ;
var sections = $('#sections').val() ;
var classes = $('#aclass').val();

var refscrf = $("input[name='_csrfToken']").val();
 window.onload = function (){

     $.ajax({ 
              url: baseurl +"/classes/getdata", 
              data: {_csrfToken : refscrf, grades: grades, sections: sections, classes: classes}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $('#class_table').DataTable().destroy();
                    $('#classbody').html(result.html); 
                    $( "#class_table" ).DataTable({
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
    }();

}

if(controller=="Reportcards"){
    var grades = $('#grades').val() ;
    var sections = $('#sections').val() ;
    var classes = $('#aclass').val();

var refscrf = $("input[name='_csrfToken']").val();
 window.onload = function (){

     $.ajax({ 
              url: baseurl +"/classes/getdata", 
              data: {_csrfToken : refscrf, grades: grades, sections: sections, classes: classes}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $('#class_table').DataTable().destroy();
                    $('#classbody').html(result.html); 
                    $( "#class_table" ).DataTable({
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
    }();

}

/* End */ 
if(controller=="AttendanceReport"){
var grades = $('#grades').val() ;
var sections = $('#sections').val() ;
var classes = $('#aclass').val();

var refscrf = $("input[name='_csrfToken']").val();
 window.onload = function (){

     $.ajax({ 
              url: baseurl +"/AttendanceReport/getdata", 
              data: {_csrfToken : refscrf, grades: grades, sections: sections, classes: classes}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $('#class_table').DataTable().destroy();
                    $('#classbody').html(result.html); 
                    $( "#class_table" ).DataTable({
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
    }();

} 



/* Edit Class form submission */

$("#addclsform").submit(function(e){
  e.preventDefault();
  
$("#editclsbtn").prop("disabled", true);

 $(this).ajaxSubmit({
  error: function(){
      $("#clserror").html(errorocc) ;
      $("#clserror").fadeIn().delay('5000').fadeOut('slow');
      $("#addclsbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#addclsbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#clssuccess").html(clsadd) ;
        $("#clssuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/classes" ;  }, 1000);
          }
          else if(response.result === "exist" ){
        $("#clserror").html(clsalready) ;
        $("#clserror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "empty" ){
        $("#clserror").html(filldetails) ;
        $("#clserror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#clserror").html(response.result) ;
        $("#clserror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

$("#editclsform").submit(function(e){
  e.preventDefault();
  
$("#editclsbtn").prop("disabled", true);

 $(this).ajaxSubmit({
  error: function(){
      $("#editclserror").html(errorocc) ;
      $("#editclserror").fadeIn().delay('5000').fadeOut('slow');
      $("#editclsbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#editclsbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#editclssuccess").html(clsupd) ;
        $("#editclssuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/classes" ;  }, 1000);
          }
          else if(response.result === "exist" ){
        $("#editclserror").html(clsalready) ;
        $("#editclserror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "empty" ){
        $("#editclserror").html(filldetails) ;
        $("#editclserror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#editclserror").html(response.result) ;
        $("#editclserror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});


$("#updatesclatendform").submit(function(e){
    e.preventDefault();
    $("#updateattend").prop("disabled", true);
    
    $(this).ajaxSubmit({
        error: function(){
            $("#reportsclatterror").html(errorocc) ;
            $("#reportsclatterror").fadeIn().delay('5000').fadeOut('slow');
            $("#updateattend").prop("disabled", false);
        },
        success: function(response)
        {
            $("#updateattend").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#reportsclattsuccess").html("Student school attendance updated successfully.") ;
                $("#reportsclattsuccess").fadeIn();
                setTimeout(function(){ location.reload();   }, 1000);
            }
            else{
                $("#reportsclatterror").html("Not updated! Please try again. ") ;
                $("#reportsclatterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#updatesubatendform").submit(function(e){
    e.preventDefault();
    $("#updateattend").prop("disabled", true);
    
    $(this).ajaxSubmit({
        error: function(){
            $("#reportsclatterror").html(errorocc) ;
            $("#reportsclatterror").fadeIn().delay('5000').fadeOut('slow');
            $("#updateattend").prop("disabled", false);
        },
        success: function(response)
        {
            $("#updateattend").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#reportsclattsuccess").html("Student subject attendance updated successfully.") ;
                $("#reportsclattsuccess").fadeIn();
                setTimeout(function(){ location.reload();   }, 1000);
            }
            else{
                $("#reportsclatterror").html("Not updated! Please try again. ") ;
                $("#reportsclatterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('.tutoriallogin').on("click",".tutorlogin",function(){

    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    //alert(id);
    $("#tutoringlogin").modal("show");
    $("#tutorid").val(id);
});

/* Add Subjects form submission */

$("#addsubform").submit(function(e){
    e.preventDefault();
    $("#addsubbtn").prop("disabled", false);
    $("#addsubbtn").text(saving+"...");

 $(this).ajaxSubmit({
  error: function(){
      $("#addsubbtn").text("Save");
      $("#suberror").html(errorocc) ;
      $("#suberror").fadeIn().delay('5000').fadeOut('slow');
      $("#addsubbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#addsubbtn").text("Save");
      $("#addsubbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#subsuccess").html(subadd) ;
        $("#subsuccess").fadeIn().delay('5000').fadeOut('slow');
        //classesadd();
        //$('#addsubform').trigger("reset");
        setTimeout(function(){ location.href = baseurl +"/subjects" ;  }, 1000);
          }
      else if(response.result === "empty" ){
        $("#suberror").html(filldetails) ;
        $("#suberror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "exist" ){
        $("#suberror").html(subalready) ;
        $("#suberror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#suberror").html(response.result) ;
        $("#suberror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});
/* END */


/* Add Edit fee structure */

$("#add_fee_structure").submit(function(e){
    e.preventDefault();
    $("#addstrucbtn").prop("disabled", false);
    $("#addstrucbtn").text(saving+"...");

 $(this).ajaxSubmit({
  error: function(){
      $("#addstrucbtn").text("Save");
      $("#strucerror").html(errorocc) ;
      $("#strucerror").fadeIn().delay('5000').fadeOut('slow');
      $("#addstrucbtn").prop("disabled", false);
  },
  success: function(response)
  {
      console.log(response);
      $("#addstrucbtn").text("Save");
      $("#addstrucbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#strucsuccess").html(feestadd) ;
        $("#strucsuccess").fadeIn().delay('5000').fadeOut('slow');
        setTimeout(function(){ location.href = baseurl +"/feedetail?id="+response.feesid ;  }, 1000);
      }
      else if(response.result === "empty" ){
        $("#strucerror").html(filldetails) ;
        $("#strucerror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "exist" ){
        $("#strucerror").html(feesalready+"class -"+response.class) ;
        $("#strucerror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#strucerror").html(response.result) ;
        $("#strucerror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

$("#editstrucform").submit(function(e){
  e.preventDefault();
  
$("#editstrucbtn").prop("disabled", true);
$("#editstrucbtn").text(updating+"...");

 $(this).ajaxSubmit({
  error: function(){
      $("#editstrucbtn").text("Update");
      $("#editstrucerror").html(errorocc) ;
      $("#editstrucerror").fadeIn().delay('5000').fadeOut('slow');
      $("#editstrucbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#editstrucbtn").text("Update");
      $("#editstrucbtn").prop("disabled", false);
      if(response.result === "success" ){ 
            $("#editstrucsuccess").html(feestup) ;
            $("#editstrucsuccess").fadeIn();
            setTimeout(function(){ location.href = baseurl +"/feedetail?id="+response.feesid ;  }, 1000);
        }
        else if(response.result === "exist" ){
            $("#editstrucerror").html(feesalready) ;
            $("#editstrucerror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "empty" ){
        $("#editstrucerror").html(filldetails) ;
        $("#editstrucerror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#editstrucerror").html(response.result) ;
        $("#editstrucerror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

$('#feestruc_table tbody').on("click",".editstruc",function()
{
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/fees/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
             $('#editstrucform').trigger("reset");
             var frequen = result[0]['frequency'];
             //alert(frequen);
             var frequency = frequen.replace(/ /g,"_");
            $('#class_'+ result[0]['class_id']).attr("selected","selected").trigger('change.select2');
            $('#f_'+ frequency).attr("selected","selected").trigger('change.select2');
            $('#eid').val(id);
            $('#sy_'+result[0]['start_year']).attr("selected","selected").trigger('change.select2');
            $('#amount').val(result[0]['amount']);  
          }
        }
    });
});

$('#schoolsubtable tbody').on("click",".privilegess",function()
{
    var id = $(this).data('id');
    $("#viewpri").modal("show");
    $("#all_priviledges").html("");
    $("#all_priviledges").html(id);
});

$('#subjectsclass_table tbody').on("click",".subjectsdetl",function()
{
    var id = $(this).data('id').split(",");
    var cls = $(this).data('cls');
    $("#viewsub").modal("show");
    $("#all_subjects").html("");
    var ids = id.join(", ");
    $("#all_subjects").html(ids);
    $("#clsname").html("");
    $("#clsname").html(cls);
});

$(document).on("change","#class",function(){
    dateChanged();
});

function dateChanged() {
 
}
/* END */
 

/* Get class details data and append in index page */


function subjectsadd(){
var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/subjects/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $('#subjects_table').DataTable().destroy();
                    $('#subjectsbody').html(result.html); 
                    $( "#subjects_table" ).DataTable({
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

if(controller=="subjects")
{
    var refscrf = $("input[name='_csrfToken']").val();
    window.onload = function ()
    {
        $.ajax({ 
              url: baseurl +"/subjects/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $('#subjects_table').DataTable().destroy();
                    $('#subjectsbody').html(result.html); 
                    $( "#subjects_table" ).DataTable({
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
    }();

}

/* End */ 
 


/* Edit Subject form submission */

$("#editsubform").submit(function(e){
  e.preventDefault();
  
$("#editsubbtn").prop("disabled", true);
$("#editsubbtn").text(updating+"...");

 $(this).ajaxSubmit({
  error: function(){
      $("#editsubbtn").text("Update");
      $("#editsuberror").html(errorocc) ;
      $("#editsuberror").fadeIn().delay('5000').fadeOut('slow');
      $("#editsubbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#editsubbtn").text("Update");
      $("#editsubbtn").prop("disabled", false);
      if(response.result === "success" ){ 
            $("#editsubsuccess").html(subupd) ;
            $("#editsubsuccess").fadeIn();
            setTimeout(function(){ location.href = baseurl +"/subjects" ;  }, 1000);
        }
        else if(response.result === "exist" ){
            $("#editsuberror").html(subalready) ;
            $("#editsuberror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "empty" ){
        $("#editsuberror").html(filldetails) ;
        $("#editsuberror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#editsuberror").html(response.result) ;
        $("#editsuberror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});


$('#notification_table tbody').on("click",".viewnotify",function()
{
    $('#title').html("");
    $('#sento').html("");
    $('#schedule_date').html("");
    $('#schedule_time').html("");
    $('#attchmnt').html("");
    $('#description').html("");
    
    var id = $(this).data('id');
    var title = $(this).data('title');
    var desc = $(this).data('desc');
    var sentto = $(this).data('sentto');
    var scdate = $(this).data('scdate');
    var sctime = $(this).data('sctime');
    var attch = $(this).data('attch');
    var studid = $(this).data('studid');
    var announce = $(this).data('announce');
    //alert(announce);
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/notification/view", 
        data: {"id":id, _csrfToken : refscrf, 'sentto':announce, 'studid' :studid}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#viewnotify").modal("show");
            $('#title').html(title);
            $('#sento').html(sentto);
            $('#description').html(result[0]['description']);
            $('#schedule_date').html(scdate);
            $('#schedule_time').html(sctime);
            if(attch !="")
            {
                $("#attach").css("display", "block");
                $('#attchmnt').html("<a href='../webroot/notifyattachmnt/"+attch+"' target='_blank' >"+attch+"</a>");
            }
          }
        }
    });
    
});

$('#notification_table tbody').on("click",".viewnotify1",function()
{
    $('#title').html("");
    $('#sento').html("");
    $('#schedule_date').html("");
    $('#schedule_time').html("");
    $('#attchmnt').html("");
    $('#description').html("");
    
    var id = $(this).data('id');
    var title = $(this).data('title');
    var desc = $(this).data('desc');
    var sentto = $(this).data('sentto');
    
    var scdate = $(this).data('scdate');
    var sctime = $(this).data('sctime');
    var attch = $(this).data('attch');
    var studid = $(this).data('studid');
    var announce = $(this).data('announce');
    //alert(announce);
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/notification/view", 
        data: {"id":id, _csrfToken : refscrf, 'sentto':announce, 'studid' :studid}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
            console.log(result);
            $("#viewnotify").modal("show");
            $('#title').html(title);
            $('#sento').html(sentto);
            $('#description').html(result[0]['description']);
            $('#schedule_date').html(scdate);
            $('#schedule_time').html(sctime);
            if(attch !="")
            {
                $("#attach").css("display", "block");
                $('#attchmnt').html("<a href='webroot/notifyattachmnt/"+attch+"' target='_blank' >"+attch+"</a>");
            }
          }
        }
    });
    
});

$("#schoolnotifycount").click(function(){
    //alert("hi");
    $("#sclannouncemnts").html("");
    $("#sclannouncemnts").html("Notification (0)");
    $("#sclrcvnotify").html("");
    $("#sclrcvnotify").html("0");
});

$('#subjects_table tbody').on("click",".editsubject",function()
{
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/subjects/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
            $('#esubject_name').val(result[0]['subject_name']);
            $('#eid').val(id);
            $('#estatus').val(result[0]['active']); 
        
          }
        }
    });
});

$(".js-example-tokenizer").select2({
    tags: true,
    placeholder: " Add Values",
    tokenSeparators: ['^'^ '~']
})

$(".class_s").select2({
      placeholder: choseclass,
      allowClear: true
  });
  
 $(".stdnt_s").select2({
      placeholder: chosestud,
      allowClear: true
  });
 $(".exam_type").select2({
      placeholder: chosevalu,
      allowClear: true
  });
  $(".mentor_title").select2({
      placeholder: chosetitle,
      allowClear: true
  });
   $(".scholar_title").select2({
      placeholder: chosetitle,
      allowClear: true
  });
  $(".listparent").select2({
      placeholder: chsparnt,
      allowClear: true
  });
  $(".listsclsub").select2({
      placeholder: chssclsub,
      allowClear: true
  });
   $(".intern_title").select2({
      placeholder: chosetitle,
      allowClear: true
  });
   $(".leader_title").select2({
      placeholder: chosetitle,
      allowClear: true
  });
   $(".language_sel").select2({
      placeholder: " Choose Language",
      allowClear: true
  });
$(".slott").select2({
      placeholder: choseslotss,
      allowClear: true
  });
$(".eslott").select2({
      placeholder: choseslotss,
      allowClear: true
  });

$("#notify_to").select2({
  placeholder: chosevalu,
  allowClear: true
});
$("#classoptn").select2({
  placeholder: chosevalu,
  allowClear: true
});

$("#schoollist").select2({
  placeholder: chosescl,
  allowClear: true
});

$("#listschool").select2({
  placeholder: chosescl,
  allowClear: true
});
$(".listteacher").select2({
  placeholder: chosetchrs,
  allowClear: true
});
$("#listteacher").select2({
  placeholder: chosetchrs,
  allowClear: true
});
$("#liststudent").select2({
  placeholder: chosestud,
  allowClear: true
});
$("#listparent").select2({
  placeholder: choseparent,
  allowClear: true
});
$("#bloodgroup").select2({
  placeholder: chosebloodgrp,
  allowClear: true
});
$("#m_listclass").select2({
  placeholder: choseclass,
  allowClear: true
});
$("#s_listclass").select2({
  placeholder: choseclass,
  allowClear: true
});
$("#productsfilter").select2({
  placeholder: product,
  allowClear: true
});

$(".subj_s").select2({
      placeholder: chosesubject,
      allowClear: true
  });

 $("#dealerfilter").select2({
      placeholder: seller,
      allowClear: true
  });
 $("#categ").select2({
      placeholder: categ,
      allowClear: true
  });  
$(".request_opt").select2({
      placeholder: choseoptn,
      allowClear: true
  });
$(".request_for").select2({
    placeholder: choseoptn,  
    initSelection: function(element, callback) {                   
    }
});

$(".eclass_s").select2({
      placeholder: choseclass,
      allowClear: true
  });
 
$(".esubj_s").select2({
      placeholder: chosesubject,
      allowClear: true
  });
  

$("#addclssubform").submit(function(e){
  e.preventDefault();
  
$("#addsubclsbtn").prop("disabled", true);

 $(this).ajaxSubmit({
  error: function(){
      $("#subclserror").html(errorocc) ;
      $("#subclserror").fadeIn().delay('5000').fadeOut('slow');
      $("#addsubclsbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#addsubclsbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#subclssuccess").html(clssubadd) ;
        $("#subclssuccess").fadeIn().delay('5000').fadeOut('slow');
    //classesadd();
        //$('#addclsform').trigger("reset");
        setTimeout(function(){ location.href = baseurl +"/classSubjects" ;  }, 1000);
          }
      else if(response.result === "empty" ){
        $("#subclserror").html(filldetails) ;
        $("#subclserror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "exist" ){
        $("#subclserror").html(clssubalready) ;
        $("#subclserror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#subclserror").html(response.result) ;
        $("#subclserror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

$('#sclproduct_table tbody').on("click",".contactform",function(){

    var id = $(this).data('id');
    var logo =  $(this).data('logo');
    var name= $(this).data('prodname');
    //alert(id);
    $("#marketplacecontact").modal("show");
    $("#logoproduct").html("");
    $("#proddname").html("");
    $("#logoproduct").html('<img src="webroot/productimages/'+logo+'" width="50px" height="35px" style="border:1px solid #fff; box-shadow: 0px 5px 25px 0px rgba(0,0,0,0.2);">');
    $("#proddname").html(name);
    $("#prodcid").val(id);
});

$('#univtable tbody').on("click",".youmecontct",function(){

    var id = $(this).data('id');
    var logo =  $(this).data('logo');
    var name= $(this).data('uniname');
    
    $("#youmecontactus").modal("show");
    $("#logouni").html("");
    $("#univname").html("");
    $("#logouni").html('<img src="../webroot/univ_logos/'+logo+'" width="50px" height="35px" style="border:1px solid #fff; box-shadow: 0px 5px 25px 0px rgba(0,0,0,0.2);">');
    $("#univname").html(name);
    $("#univid").val(id);
}); 
$('#localunivtable tbody').on("click",".localyoumecontactus",function(){

    var id = $(this).data('id');
    var logo =  $(this).data('logo');
    var name= $(this).data('uniname');
    
    $("#localyoumecontactus").modal("show");
    $("#llogouni").html("");
    $("#lunivname").html("");
    $("#llogouni").html('<img src="../webroot/univ_logos/'+logo+'" width="50px" height="35px" style="border:1px solid #fff; box-shadow: 0px 5px 25px 0px rgba(0,0,0,0.2);">');
    $("#lunivname").html(name);
    $("#lunivid").val(id);
}); 

if(controller=="classSubjects")
{
    var refscrf = $("input[name='_csrfToken']").val();
    window.onload = function ()
    {
        $.ajax({ 
              url: baseurl +"/classSubjects/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',
              success: function (result) 
              {       
                  
                 if (result.html) {
                    $('#subjectsclass_table').DataTable().destroy();
                    $('#subjectsclassbody').html(result.html); 
                    $("#subjectsclass_table" ).DataTable({
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
    }();

} 


$('#subjectsclass_table tbody').on("click",".editsubjectclass",function()
{
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/classSubjects/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
            var cls = result['class_id'].split(",");
            $("#eclass").select2().val(cls).trigger('change.select2');
            var sid = new Array();
            var sub = result['subject_id'].split(",");
            $('#esubjects').select2().val(sub).trigger('change.select2');
            $('#eid').val(id);
            $('#estatus').val(result['status']); 
        
          }
        }
    });
});

$('#fstdent_data_table tbody').on("click",".studntfee",function()
{
    var amt = $(this).data('amount');
    var studid = $(this).data('student');
    var clsid = $(this).data('class');
    var session = $(this).data('session');
    var fee_h_id = $(this).data('fee_h_id');
    var fee_s_id = $(this).data('fee_s_id');
    var fee_d_id = $(this).data('fee_d_id');
    var coupon = $(this).data('availcoupn');
    var dueamt = $(this).data('dueamt');
    //alert(dueamt);
    
    $("#session_amount").html("");
    $("#session_amount").html(": $"+amt);
    $("#totalamt").val(amt);
    $("#feehead").val(fee_h_id);
    $("#feestructure").val(fee_s_id);
    $("#feedetail").val(fee_d_id);
    $("#totalamt").val(amt);
    $("#studentid").val(studid);
    $("#classid").val(clsid);
    $("#sessionid").val(session);
    $("#dueamt").val(dueamt);
    $("#amtdue").html("");
    $("#amtdue").html(": $"+dueamt);
    $("#amount").val("");
    
    if(coupon == 1)
    {
        $("#hadcoupn").css("display", "inline-flex");
        var refscrf = $("input[name='_csrfToken']").val();
        $.ajax({ 
            url: baseurl +"/fees/getcoupon", 
            data: {"amt":amt,"studid":studid, "clsid":clsid, "session":session ,_csrfToken : refscrf}, 
            type: 'post',success: function (result) 
            {       
                if(result) {
                    console.log(result);
                    $("#couponid").html("");
                    $("#couponamt").html("");
                    $("#couponid").html(result.coupn);
                    $("#couponamt").html(result.coupnamt);
                    $("#discountamt").val(result.disamt)
                }
            }
        });
    }
    else
    {
        $('#hadcoupn').css("display", "none");
    }
});

$('#fstdent_data_table tbody').on("click",".estudntfee",function()
{
    var amt = $(this).data('amount');
    var studid = $(this).data('student');
    var clsid = $(this).data('class');
    var session = $(this).data('session');
    var fee_h_id = $(this).data('fee_h_id');
    var fee_s_id = $(this).data('fee_s_id');
    var fee_d_id = $(this).data('fee_d_id');
    var coupon = $(this).data('availcoupn');
    var id = $(this).data('id');
    var dueamt = $(this).data('dueamt');
    //alert(dueamt);
    
    $("#esession_amount").html("");
    $("#esession_amount").html(": $"+amt);
    $("#etotalamt").val(amt);
    $("#efeehead").val(fee_h_id);
    $("#efeestructure").val(fee_s_id);
    $("#efeedetail").val(fee_d_id);
    $("#etotalamt").val(amt);
    $("#estudentid").val(studid);
    $("#eclassid").val(clsid);
    $("#esessionid").val(session);
    $("#edueamt").val(dueamt);
    $("#id").val(id);
    
    if(coupon == 1)
    {
        $("#ehadcoupn").css("display", "inline-flex");
        
    }
    else
    {
        $('#ehadcoupn').css("display", "none");
    }
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/fees/getcoupon1", 
        data: {"amt":amt,"studid":studid, "clsid":clsid, "session":session ,_csrfToken : refscrf, 'id':id}, 
        type: 'post',success: function (result) 
        {       
            if(result) {
                console.log(result);
                $("#ecouponid").html("");
                $("#ecouponamt").html("");
                $("#ecouponid").html(result.coupn);
                $("#ecouponamt").html(result.coupnamt);
                $("#ediscountamt").val(result.disamt);
                var feestud = result.studfee;
                console.log(feestud);
                $("#eamount").val(feestud.amount);
                $("#etransid").val(feestud.trans_id);
                $("#etransdate").val(feestud.transaction_date);
                $("#ecashmemo").val(feestud.cashmemo);
                $("#emonth").val(feestud.frequency);
                $("#epaymode").select2().val(feestud.payment_mode).trigger('change.select2');
                $("#ecouponid").select2().val(feestud.coupon_id).trigger('change.select2');
                
                $("#ediscountamt").val(feestud.coupon_amount);
            }
        }
    });
});

/* Edit Class Subjects form submission */

$("#editclssubform").submit(function(e){
  e.preventDefault();
  
$("#editclssubbtn").prop("disabled", true);
$("#editclssubbtn").text(updating+"...");

 $(this).ajaxSubmit({
  error: function(){
      $("#editclssubbtn").text("Update");
      $("#editclssuberror").html(errorocc) ;
      $("#editclssuberror").fadeIn().delay('5000').fadeOut('slow');
      $("#editclssubbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#editclssubbtn").text("Update");
      $("#editclssubbtn").prop("disabled", false);
      if(response.result === "success" ){ 
            $("#editclssubsuccess").html(clssubupd) ;
            $("#editclssubsuccess").fadeIn();
            setTimeout(function(){ location.href = baseurl +"/classSubjects" ;  }, 1000);
        }
        else if(response.result === "exist" ){
            $("#editclssuberror").html(clssubalready) ;
            $("#editclssuberror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "empty" ){
        $("#editclssuberror").html(filldetails) ;
        $("#editclssuberror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#editclssuberror").html(response.result) ;
        $("#editclssuberror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

/* Add knowledge submission */

$("#addknowledgeform").submit(function(e)
{
    //alert("sadf");
    $(".addknowledgebtn").text(saving);        
    e.preventDefault();
    $("#addknowledgebtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#addknowldgeerror").html(errorocc) ;
            $("#addknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addknowledgebtn").prop("disabled", false);
            $(".addknowledgebtn").text(savescript);  
        },
        success: function(response)
        {
            $("#addknowledgebtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#addknowldgesuccess").html(knowadd) ;
                $("#addknowldgesuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".addknowledgebtn").text(savescript);  
                setTimeout(function(){ location.href = baseurl +"/knowledge" ;  }, 1000);
            }
            else
            {
                $("#addknowldgeerror").html(response.result) ;
                $("#addknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
                $(".addknowledgebtn").text(savescript);
            }
        } 
    });     
    return false;
});

 
 
/* Add knowledge submission */


$("#editknowledgeform").submit(function(e)
{
    //alert("sadf");
    $(".editknowledgebtn").text(updating);        
    e.preventDefault();
    $("#editknowledgebtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#editknowldgeerror").html(errorocc) ;
            $("#editknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editknowledgebtn").prop("disabled", false);
            $(".editknowledgebtn").text(updatescript);  
        },
        success: function(response)
        {
            $("#editknowledgebtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#editknowldgesuccess").html(knowupd) ;
                $("#editknowldgesuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".editknowledgebtn").text(updatescript);  
                setTimeout(function(){ location.href = baseurl +"/knowledge" ;  }, 1000);
            }
            else
            {
                $("#editknowldgeerror").html(response.result) ;
                $("#editknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
                $(".editknowledgebtn").text(updatescript);
            }
        } 
    });     
    return false;
});

  
$('.editknow').on("click",function()
{
   // alert("hi");
    $('#cover').html("");
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledge/edit", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
             
             
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $('#etitle').val(result[0]['file_title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['file_link_name']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['file_link_name']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            
            $('#edesc').val(result[0]['file_description']); 
            if(result[0]['image'] != "" && result[0]['image'] != null)
         {
              $('#cover').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='45px' height='45px'>");  
         }
           
            $('#cover_image').val(result[0]['image']); 
        
          }
        }
    });
});
  
 $('#checkid').click(function(){
    
    var str =  $(this).data("str") ;
    var url = baseurl+"/"+$(this).data("url") ;
    var contnt = $(this).data("contnt") ;
    var post_arr = [];
    // Get checked checkboxes
    $('#viewcommunity input[type=checkbox]').each(function() {
        if (jQuery(this).is(":checked")) {
            var id = this.id;
            post_arr.push(id);
        }
    });
    var refscrf = $("input[name='_csrfToken']").val();  
    console.log(post_arr);

    if(post_arr.length > 0){
        swal({
            title: areyou,
            text: "You want to delete the selected data",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            confirmButtonText: yesdelete,
            closeOnConfirm: false,
            cancelButtonText: cncl,  
            showLoaderOnConfirm: true
        }, function () {
            $.ajax({
                data : {
                    val : post_arr ,
                   _csrfToken : refscrf,
                   str : str
                },
                type : "post",
                url : url,
                
                success: function(response){
                    if(response.result == "success"){
                        swal(contnt, str+" "+ hasbeen, "success");
                        setTimeout(function(){ location.reload() ;  }, 1000);
                    }
                    else{
                        swal(errorpop, response.result, "error");
                    }
                }
            })
        });
    } 
    else
    {
        swal(errorpop, "Please select data first to delete", "error");
    }
});
//$('.editstateexam').on("click",function()
$('#viewcommunity').on("click",".editstateexam",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/editstate", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});

//$('.editmachinelearning').on("click",function()
$('#viewcommunity').on("click",".editmachinelearning",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/editmachine", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             
             var cls = result[0]['classname'].split(",");
            $("#eguid_class").select2().val(cls).trigger('change.select2');
            

            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            /*.val(result[0]['classname'])*/
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                    $("#etypevideo").css("display", "block");
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            if(result[0]['video_type'] == 'custom upload'){
               var video_type = 'cupload';
            }else{
                var video_type = result[0]['video_type'];
            }
            $("#evideotypes").select2().val(video_type).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});

$('#viewcommunity').on("click",".editintensive",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/editintense", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});

/**************** Kindergarten *******************/

$('#kinderactivityimg').on("click",".editimage",function()
{
    $("#activity_name").prop("readonly", false);
    var name = $(this).data('name');
    var id = $(this).data('id');
    var img = $(this).data('img');
    if(name == "Virtual Class")
    {
        $("#activity_name").prop("readonly", true);
    }
    $("#activity_name").val(name);
    $("#activity_id").val(id);
    $("#activity_img").val(img);
    $('#imgactivity').html("<img src='"+baseurl+"/webroot/img/"+img+"' width='35px' height='30px'>"); 
    $("#activitynam").html(name);
});

$('#viewcommunity').on("click",".editinternship",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/editintern", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});
$('#viewcommunity').on("click",".editmentorship",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/editmentor", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});

$('#viewcommunity').on("click",".editscholarship",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/editscholar", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});



$('#viewcommunity').on("click",".editleadership",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/editleader", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});

$('#viewcommunity').on("click",".edithowitworks",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/edithowworks", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});

//$('.editnewtechnologies').on("click",function()
$('#viewcommunity').on("click",".editnewtechnologies",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/edittechnology", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});
 
$('.editknowcenter').on("click",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/edit", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
            console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $("#eguid_class").select2().val(result[0]['classname']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});

$('.editdatapps').on("click",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/SchoolkinderApplication/edit", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
            console.log(result);
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            
            $('#efileupload').val(result[0]['links']);
            $('#file_name').html(result[0]['links']);
            
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/applications_data/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
          }
        }
    });
});
  
 /* Add cooments */


$("#comment_form").submit(function(e)
{
    //alert("sadf");
    $(".submit_comment").text('Post Comment...');        
    e.preventDefault();
    $("#submit_comment").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#submitCommenterror").html(errorocc) ;
            $("#submitCommenterror").fadeIn().delay('5000').fadeOut('slow');
            $("#submit_comment").prop("disabled", false);
            $(".submit_comment").text('subcomm');  
        },
        success: function(response)
        {
            $("#submit_comment").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#submitCommentsuccess").html(commposted) ;
                $("#submitCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".submit_comment").text('subcomm');  
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else
            {
                $("#submitCommenterror").html(response.result) ;
                $("#submitCommenterror").fadeIn().delay('5000').fadeOut('slow');
                $(".submit_comment").text('subcomm');
            }
        } 
    });     
    return false;
});


$("#editholiform").submit(function(e)
{
    e.preventDefault();
    $("#editholibtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#eholierror").html(errorocc) ;
            $("#eholierror").fadeIn().delay('5000').fadeOut('slow');
            $("#editholibtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editholibtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#eholisuccess").html("Holiday updated succesfully.") ;
                $("#eholisuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else
            {
                $("#eholierror").html(response.result) ;
                $("#eholierror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});
$("#addholiform").submit(function(e)
{
    e.preventDefault();
    $("#addholibtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#holierror").html(errorocc) ;
            $("#holierror").fadeIn().delay('5000').fadeOut('slow');
            $("#addholibtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addholibtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#holisuccess").html(holaddsucc) ;
                $("#holisuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else if(response.result === "exist" )
            { 
                $("#holierror").html(holexist) ;
                $("#holierror").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else
            {
                $("#holierror").html(response.result) ;
                $("#holierror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('#holiday_table tbody').on("click",".editholidy",function(){

    var id = $(this).data('id');
    //alert(id);
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/holiday/edit", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {    
            console.log(result);
            if(result) 
            {
                $("#editholiday").modal("show");
                $("#holi_type").val(result['holi_type']);
                $("#hid").val(result['id']);
                $("#date").val(result['date']);
                $("#descs").val(result['descs']);
            }
        }
    });
});

if(controller=="ExamListing" && actionpage == "viewexam"){
    
    var timer;
    var timeout = 5000; // Timout duration

    $('.submitanswer').click(function()
    {
        if(timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(saveData, timeout); 
        
    });
    var timenow = $("#timenow").val();
    var enddate = $("#enddate").val();
    var timediff = enddate-timenow;
    var classId = $("#classId").val();
    var subId = $("#subId").val();
    var fiveMinutes = timediff,
    display = $('#counter');
    startTimer(fiveMinutes, display, classId, subId);
}

function startTimer(duration, display, classId, subId) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        if(minutes == 5 && seconds == 0)
        {
            swal({
                title:  minleftfive,
                customClass: {
                	confirmButton: 'example-class' //insert class here
                }
            });
            
        }
        else if(minutes == 0 && seconds == 0)
        {
            var formdetails = $('#customexamsubmitform').serialize();
            $.ajax({
                url: baseurl +"/ExamListing/customexamsubmitauto",
                data:formdetails,
                method:"POST",
                success:function(result){
                    console.log(result);
                    swal(examsubmt);
                    location.href = baseurl +"/SubjectGrade?classId="+classId+"&subId="+subId ;
                },
                error:function(error){
                    console.log(error);
                }
            })
            
        }
        display.text(minutes + ":" + seconds);

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}



function saveData()  
{  
    var formdetails = $('#customexamsubmitform').serialize();
    
    $.ajax({
        url: baseurl +"/ExamListing/customexamsubmitform",
        data:formdetails,
        method:"POST",
        success:function(result){
            if(response.result === "success" )
            { 
                setTimeout(function(){ location.href = baseurl +"/ExamListing?classId="+classId+"&subId="+subId ;  }, 1000);
            }
            else
            {
                $("#subexamerror").html(response.result) ;
                $("#subexamerror").fadeIn().delay('5000').fadeOut('slow');
            }
        },
        error:function(error){
            console.log(error);
        }
    })
    return false;
}  

$("#customexamsubmitform").submit(function(e)
{
    $(".submit_exam").text('Submitting...');        
    e.preventDefault();
    $("#submit_exam").prop("disabled", true);
    var classId = $("#classId").val();
    var subId = $("#subId").val();
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#subexamerror").html(errorocc) ;
            $("#subexamerror").fadeIn().delay('5000').fadeOut('slow');
            $("#submit_exam").prop("disabled", false);
            $(".submit_exam").text('Submit');  
        },
        success: function(response)
        {
            console.log(response);
            $("#submit_exam").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#subexamsuccess").html(examsubmt) ;
                $("#subexamsuccess").fadeIn().delay('5000').fadeOut('slow');
                $(".submit_exam").text('Submit');  
                setTimeout(function(){ location.href = baseurl +"/ExamListing?classId="+classId+"&subId="+subId ;  }, 1000);
            }
            else
            {
                $("#subexamerror").html(response.result) ;
                $("#subexamerror").fadeIn().delay('5000').fadeOut('slow');
                $(".submit_exam").text('Submit');
            }
        } 
    });     
    return false;
});


$(document).on('click', '.answercount', function(){
    var getid = this.id;
    var wordlimit = getid.split("_");
    
    $("#"+getid).on('keyup', function() {
        var words = 0;
        if ((this.value.match(/\S+/g)) != null) {
            words = this.value.match(/\S+/g).length;
        }
        
        if (words > wordlimit[1]) {
            var trimmed = $(this).val().split(/\s+/, wordlimit[1]).join(" ");
            
            $(this).val(trimmed + " ");
        }
        else {
            $('#displaycount_'+wordlimit[1]).text(words);
            $('#wordleft_'+wordlimit[1]).text(wordlimit[1]-words);
        }
        //alert(words);
    });
});


$(document).on('click', '.reply-btn', function(){
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
     $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        var skul_id = $("#skulid").val();
        $.ajax({
            url: baseurl +"/knowledge/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                skul_id: skul_id
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});


$(document).on('click', '.user_reply-btn', function(){
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
     $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        var ruser_id = $("#ruser_id").val();
        $.ajax({
            url: baseurl +"/viewKnowledge/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                ruser_id: ruser_id
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(".schooldash").click( function()
{
    var type = $(this).data('type');        
    var school_id =  $(this).data("id") ;
    var email = $(this).data('email');  
    var password = $(this).data('password');
    var langu = $(this).data('language');
    var genactvity = $(this).data('genactvity');
    var refscrf = $("input[name='_csrfToken']").val(); 
    $.ajax({
        url: baseurl +"/login/logincheck",
        type: "POST",
        data: {
            type: type,
            email: email,
            password: password,
            _csrfToken : refscrf,
            school_id : school_id,
            langu: langu,
            genactvity:genactvity
        },
        success: function(response){
            console.log(response);
            if(response.result === "success" ){ 
                 window.open(origin+baseurl +"/schooldashboard", '_blank');
            }
        }   
    });
});
  
$(".studentdash").click( function()
{
    var type = $(this).data('type');        
    var school_id =  $(this).data("id") ;
    var email = $(this).data('email');  
    var password = $(this).data('password');
    var langu = $(this).data('language');
    var refscrf = $("input[name='_csrfToken']").val(); 
    
    $.ajax({
        url: baseurl +"/login/logincheck",
        type: "POST",
        data: {
            type: type,
            email: email,
            password: password,
            _csrfToken : refscrf,
            school_id : school_id,
            langu: langu
        },
        success: function(response){
            console.log(response);
            if(response.result === "success_student" ){ 
                if(response.dash == "kinder" )
                { 
                     window.open(origin+baseurl +"/kinderdashboard", 'studentdashboard' );
                }
                else
                {
                    window.open(origin+baseurl +"/studentdashboard", 'studentdashboard');
                }
            }
            
        }   
    });
});
  
 
/***********get school status *********/ 
  
$('#school_table tbody').on("click",".notifystatus",function(){

//alert(id);
    var id = $(this).data('id');
    //alert(id);
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/schools/notifysts", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {
            console.log(result);
            if (result) 
            {   
                $('#scl_id').val(id);
                $('#knowledge_sts').html("("+result.knowledge_sts+")");
                $('#class_sts').html("("+result.class_sts+")");
                $('#student_sts').html("("+result.student_sts+")");
                $('#subjcls_sts').html("("+result.subjcls_sts+")");
                $('#teacher_sts').html("("+result.teacher_sts+")");
                $('#subject_sts').html("("+result.subject_sts+")");
            }
        }
    });
});

if(controller=="gallery"){

var refscrf = $("input[name='_csrfToken']").val();
 window.onload = function (){

     $.ajax({ 
              url: baseurl +"/gallery/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $('#gallery_table').DataTable().destroy();
                    $('#gallerybody').html(result.html); 
                    $( "#gallery_table" ).DataTable({
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
    }();

}  
  

$("#addgalleryform").submit(function(e)
{
    //$(".addgallerybtn").text('Uploading Images...');        
    e.preventDefault();
    $("#addgallerybtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#galleryerror").html(errorocc) ;
            $("#galleryerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addgallerybtn").prop("disabled", false);
            //$(".addgallerybtn").text('Next');  
        },
        success: function(response)
        {
            $("#addgallerybtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                /*$("#gallerysuccess").html(gallryadd) ;
                $("#gallerysuccess").fadeIn().delay('5000').fadeOut('slow');*/
                // $(".addgallerybtn").text('Next');  
                $('#addgallery').modal('hide');
                $('#addgalleryimages').modal('show');
                $('#galleryId').val(response.galleryId);
               
                //setTimeout(function(){ location.href = baseurl +"/gallery" ;  }, 1000);
            }
            else
            {
                $("#galleryerror").html(response.result) ;
                $("#galleryerror").fadeIn().delay('5000').fadeOut('slow');
               // $(".addgallerybtn").text('Next');
            }
        } 
    });     
    return false;
});

$("#addgalleryimagesform").submit(function(e)
{
    $(".addgalleryimgbtn").text('Uploading Images...');        
    e.preventDefault();
    $("#addgalleryimgbtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#imggalleryerror").html(errorocc) ;
            $("#imggalleryerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addgalleryimgbtn").prop("disabled", false);
            $(".addgalleryimgbtn").text(savescript);  
        },
        success: function(response)
        {
            $("#addgalleryimgbtn").prop("disabled", false);
            $(".addgalleryimgbtn").text(savescript);  
            if(response.result === "success" )
            { 
                $("#imggallerysuccess").html(gallryadd) ;
                $("#imggallerysuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/gallery" ;  }, 1000);
            }
            else
            {
                $("#imggalleryerror").html(response.result) ;
                $("#imggalleryerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$('#gallery_table tbody').on("click",".editgallery",function(){

    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/gallery/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {   //alert(result.gallery_data[0]['event_date']);
        console.log(result);
            var eventdate  = result.gallery_data[0]['event_date'];
            var arr = eventdate.split('T');
            var datefrmt = arr[0].split('-');
            var date = datefrmt[2]+"-"+datefrmt[1]+"-"+datefrmt[0];
            if (result) 
            {
                $('#etitle').val(result.gallery_data[0]['title']);
                $('#edesc').val(result.gallery_data[0]['description']); 
                $('#edeventDate').val(date);    
                $('#eid').val(id);
                //$('#images').html(result.gallery_data[0]['images']);
            }
        }
    });
});

$('.subjctsdata').on("click",".subjectdtl",function(){    
    $("#subjectName b").html("");
    $("#teacherpic").html("");
    $("#teachername").html("");
    $("#teacheremail").html("");
    $("#teachermobile").html("");
    $("#teacherqual").html("");
    var subjectid =  $(this).data("subid") ;
    var classid = $(this).data("clsid") ;
    var refscrf = $("input[name='_csrfToken']").val();
    
    var row = document.getElementById("subrow");
    row.classList.add("loader");
    
    $.ajax({
        url: baseurl +"/Studentsubjects/subjectdtl", 
        data: {"classid":classid,"subjectid":subjectid,_csrfToken : refscrf}, 
        type: 'post',
        success: function(response){
            row.classList.remove("loader");
            
            console.log(response.emp_dtl);
            if(response.emp_dtl != "") {
                $("#subjectdetails").modal("show");
                
                $("#subjectName b").html(response.subname);
                if((response.emp_dtl[0]['pict']) != "") {
                    $("#teacherpic").html("<img src='"+origin+"/school/webroot/img/"+response.emp_dtl[0]['pict']+"' width='150px'>");
                }
                else
                {
                    $("#teacherpic").html("<img src= 'https://you-me-globaleducation.org/school/webroot/img/male.jpg' width='150px'>");
                }
                $("#teachername").html(response.emp_dtl[0]['f_name']+" "+response.emp_dtl[0]['l_name']);
                $("#teacheremail").html(response.emp_dtl[0]['email']);
                $("#teachermobile").html(response.emp_dtl[0]['mobile_no']);
                $("#teacherqual").html(response.emp_dtl[0]['quali']);
                $("#assessments_subId").val(response.subId);
                $("#assessments_classId").val(classid);
                $("#discussion_subId").val(response.subId);
                $("#discussion_classId").val(classid);
                $("#exams_subId").val(response.subId);
                $("#exams_classId").val(classid);
                $("#grade_subId").val(response.subId);
                $("#grade_classId").val(classid);
                $("#library_subId").val(response.subId);
                $("#library_classId").val(classid);
                $("#attendnce_subId").val(response.subId);
                $("#attendnce_classId").val(classid);
                $("#study_subId").val(response.subId);
                $("#study_classId").val(classid);
                $("#quiz_subId").val(response.subId);
                $("#quiz_classId").val(classid);
            }
            else
            {
                swal(errorpop, notchrass, "error");
            }
        }
    });
    
});

$('.generatemeeting').on("click",function()
{  
    
    var subjectid =  $(this).data("subject") ;
    var classid = $(this).data("class") ;
    var subjectname =  $(this).data("subname") ;
    var classname = $(this).data("classname") ;
    //alert(subjectname);
    $("#generatemeeting").modal("show");
    $("#class").html("");
    $("#subject").html("");
    $("#subject").html(subjectname);
    $("#class").html(classname);
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var stringLength = 13;
  
    function pickRandom() 
    {
        return possible[Math.floor(Math.random() * possible.length)];
    }
  
    var randomString = Array.apply(null, Array(stringLength)).map(pickRandom).join('');
    var link = subjectname+"-"+randomString;
    
    $("#subjectid").val(subjectid);
    $("#classid").val(classid);
});

$("#generatemeetingform").submit(function(e)
{   
    e.preventDefault();
    var refscrf = $("input[name='_csrfToken']").val();
    $("#submitreqbtn").prop("disabled", true);
    var cls = $("#classid").val();
    var sub = $("#subjectid").val();
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#submitreqerror").html(errorocc) ;
            $("#submitreqerror").fadeIn().delay('5000').fadeOut('slow');
            $("#submitreqbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#submitreqbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#submitreqsuccess").html(linkgen) ;
                $("#submitreqsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else
            {
                $("#submitreqerror").html(response.result) ;
                $("#submitreqerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('#assbackbutton').on("click", function()
{   
    var subjectid = $("#subid").val();
    var classid = $("#clsid").val();
    $("#assessments_subId").val(subjectid);
    $("#assessments_classId").val(classid);
    $("#discussion_subId").val(subjectid);
    $("#discussion_classId").val(classid);
    $("#exams_subId").val(subjectid);
    $("#exams_classId").val(classid);
    $("#grade_subId").val(subjectid);
    $("#grade_classId").val(classid);
    $("#library_subId").val(subjectid);
    $("#library_classId").val(classid);
    $("#attendnce_subId").val(subjectid);
    $("#attendnce_classId").val(classid);
    $("#study_subId").val(subjectid);
    $("#study_classId").val(classid);
    $("#quiz_subId").val(subjectid);
    $("#quiz_classId").val(classid);
    window.location = baseurl +"/Studentsubjects" + "?openmodal=1&subjectid="+subjectid + "&classid="  +classid;
});

$('.attendance_std').on("click", function()
{   
    $("#attendnce_dtl").modal("show");
});

$("#editgalleryform").submit(function(e)
{
    //$(".editgallerybtn").text('Uploading Images...');        
    e.preventDefault();
    var refscrf = $("input[name='_csrfToken']").val();
    $("#editgallerybtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#editgalleryerror").html(errorocc) ;
            $("#editgalleryerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editgallerybtn").prop("disabled", false);
            //$(".editgallerybtn").text(updatescript);  
        },
        success: function(response)
        {
            //console.log(response);
            $("#editgallerybtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $('#editgal').modal('hide');
                galleryimages(response.galleryId, refscrf);
                $('#editgalleryimages').modal('show');
                $('#egalleryId').val(response.galleryId);
            }
            else
            {
                $("#editgalleryerror").html(response.result) ;
                $("#editgalleryerror").fadeIn().delay('5000').fadeOut('slow');
                //$(".editgallerybtn").text(updatescript);
            }
        } 
    });     
    return false;
});
  
function galleryimages(id, refscrf)
{
    $('.input-images-2').html("");
    //console.log(baseurl);
    $.ajax({ 
        url: baseurl +"/gallery/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {  
            var images = result.gallery_data[0]['images'];
            var imgs = images.split(",");
            var imgss = new Array();
                
            $.each(imgs, function(i, val) {
                var imgurl = origin + "/school/webroot/img/"+val;
                var j = i+1;
                imgss.push({id: val, src: imgurl });
            });
            var preloaded = imgss;
            console.log(preloaded);
            $('.input-images-2').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'photos',
                preloadedInputName: 'old'
            });
            
        }
    });
}

$("#editgalleryimagesform").submit(function(e)
{
    $(".editgalleryimgbtn").text('Uploading Images...');        
    e.preventDefault();
    $("#editgalleryimgbtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#eimggalleryerror").html(errorocc) ;
            $("#eimggalleryerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editgalleryimgbtn").prop("disabled", false);
            $(".editgalleryimgbtn").text(updatescript);  
        },
        success: function(response)
        {
            $("#editgalleryimgbtn").prop("disabled", false);
            $(".editgalleryimgbtn").text(updatescript);  
            if(response.result === "success" )
            { 
                $("#eimggallerysuccess").html(gallryupd) ;
                $("#eimggallerysuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/gallery" ;  }, 1000);
            }
            else
            {
                $("#eimggalleryerror").html(response.result) ;
                $("#eimggalleryerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#sharerequestform").submit(function(e)
{
    e.preventDefault();
    $("#sharereqbtn").prop("disabled", true);
    
    $(this).ajaxSubmit({
        error: function(){
            $("#sharereqerror").html(errorocc) ;
            $("#sharereqerror").fadeIn().delay('5000').fadeOut('slow');
            $("#sharereqbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#sharereqbtn").prop("disabled", false);
            if(response.result == "success" ){ 
                $("#sharereqsuccess").html("Teacher Guide content has been shared successfully to students") ;
                $("#sharereqsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload() ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#sharereqerror").html(filldetails) ;
                $("#sharereqerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#sharereqerror").html(response.result) ;
                $("#sharereqerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#submitrequestform").submit(function(e)
{
    e.preventDefault();
    $("#submitreqbtn").prop("disabled", true);
    $("#submitreqbtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#submitreqerror").html(errorocc) ;
            $("#submitreqerror").fadeIn().delay('5000').fadeOut('slow');
            $("#submitreqbtn").prop("disabled", false);
            $("#submitreqbtn").text("Save");
        },
        success: function(response)
        {
            $("#submitreqbtn").prop("disabled", false);
            $("#submitreqbtn").text("Save");
            if(response.result == "success" ){ 
                if(response.type == "pdf")
                {
                    $("#submitreqsuccess").html(requestadd) ;
                    $("#submitreqsuccess").fadeIn().delay('5000').fadeOut('slow');
            
                    setTimeout(function(){ location.href = baseurl +"/teacherdashboard" ;  }, 1000);
                }
                else if(response.type == "customize")
                {
                    //alert(response.type);
                    if(response.submitId != "")
                    {
                        $("#submitId").val(response.submitId);
                        setTimeout(function(){ location.href = baseurl +"/teacherdashboard/add_question/"+response.submitId ;  }, 1000);
                    }
                }
            }
            else if(response.result === "empty" ){
                $("#submitreqerror").html(filldetails) ;
                $("#submitreqerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#submitreqerror").html(response.result) ;
                $("#submitreqerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#addexamassform").submit(function(e)
{
    e.preventDefault();
    $("#addexamassbtn").prop("disabled", true);
    $("#addexamassbtn").text(saving+"...");

    $(this).ajaxSubmit({
        error: function(){
            $("#examasserror").html(errorocc) ;
            $("#examasserror").fadeIn().delay('5000').fadeOut('slow');
            $("#addexamassbtn").prop("disabled", false);
             $("#addexamassbtn").text("Save");
        },
        success: function(response)
        {
            $("#addexamassbtn").prop("disabled", false);
            
             $("#addexamassbtn").text("Save");
            if(response.result === "success" ){ 
                if(response.type == "pdf")
                {
                    $("#examasssuccess").html(exmassadd) ;
                    $("#examasssuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.href = baseurl +"/examAssessment" ;  }, 1000);
                }
                else if(response.type == "customize")
                {
                    if(response.submitId != "")
                    {
                        $("#submitId").val(response.submitId);
                        setTimeout(function(){ location.href = baseurl +"/examAssessment/add_question/"+response.submitId ;  }, 1000);
                    }
                }
            }
            else if(response.result === "empty" ){
                $("#examasserror").html(filldetails) ;
                $("#examasserror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#examasserror").html(response.result) ;
                $("#examasserror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#addquesform").submit(function(e)
{
    e.preventDefault();
    $("#exmcustmbtn").prop("disabled", true);
    $("#exmcustmbtn").text(saving+"...");
    var examId = $("#submitId").val();
    $("#marksAllocated").html("");
    $(this).ajaxSubmit({
        error: function(){
            $("#examcustmerror").html(errorocc) ;
            $("#examcustmerror").fadeIn().delay('5000').fadeOut('slow');
            $("#exmcustmbtn").prop("disabled", false);
            $("#exmcustmbtn").text(savecontnue);
        },
        success: function(response)
        {
            $("#marksAllocated").html(response.allocate+"/"+response.max_marks);
            $("#exmcustmbtn").prop("disabled", false);
            $("#exmcustmbtn").text(savecontnue);
            if(response.result === "success" ){ 
                $("#examcustmsuccess").html(quesadd) ;
                $("#examcustmsuccess").fadeIn().delay('3000').fadeOut('slow');
                
                 
                $("#cutsomize_exam").modal("show");
                $('#addcutsomizeexmform').trigger("reset");
                $('#question').val("");
                $("#optionques").val("subjective").trigger("change");
                $("#valueques").val(null).trigger("change");
                $("#submitId").val(examId);
               
                $("#marksAllocated").html(response.allocate+"/"+response.max_marks);
                
                 getquestion(examId);
  
            }
            else if(response.result === "empty" ){
                $("#examcustmerror").html(filldetails) ;
                $("#examcustmerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#examcustmerror").html(response.result) ;
                $("#examcustmerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#addcutsomizeexmform").submit(function(e)
{
    e.preventDefault();
    $("#exmcustmbtn").prop("disabled", true);
    $("#exmcustmbtn").text(saving+"...");
    var examId = $("#submitId").val();
    
    $("#marksAllocated").html("");
    $(this).ajaxSubmit({
        error: function(){
            $("#examcustmerror").html(errorocc) ;
            $("#examcustmerror").fadeIn().delay('5000').fadeOut('slow');
            $("#exmcustmbtn").prop("disabled", false);
            $("#exmcustmbtn").text(savecontnue);
        },
        success: function(response)
        {
            $("#marksAllocated").html(response.allocate+"/"+response.max_marks);
            $("#exmcustmbtn").prop("disabled", false);
            $("#exmcustmbtn").text(savecontnue);
            if(response.result === "success" ){ 
                $("#examcustmsuccess").html(quesadd) ;
                $("#examcustmsuccess").fadeIn().delay('3000').fadeOut('slow');
                $("#cutsomize_exam").modal("show");
                
                $('#addcutsomizeexmform').trigger("reset");
                $('#question').val("");
                $("#optionques").val("subjective").trigger("change");
                $("#valueques").val(null).trigger("change");
                $("#submitId").val(examId);
                //alert(response.allocate);
                  
                $("#marksAllocated").html(response.allocate+"/"+response.max_marks);
                getquestion(examId);
  
            }
            else if(response.result === "empty" ){
                $("#examcustmerror").html(filldetails) ;
                $("#examcustmerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#examcustmerror").html(response.result) ;
                $("#examcustmerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#adduniversityform").submit(function(e)
{
    e.preventDefault();
    $("#addunivbtn").prop("disabled", true);
    $("#addunivbtn").text(saving+"...");
    $(this).ajaxSubmit({
        error: function(){
            $("#univerror").html(errorocc) ;
            $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addunivbtn").prop("disabled", false);
            $("#addunivbtn").text(savecontnue);
        },
        success: function(response)
        {
            $("#addunivbtn").prop("disabled", false);
            $("#addunivbtn").text("Save");
            if(response.result === "success" ){ 
                $("#univsuccess").html(univadd) ;
                $("#univsuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/knowledgeCenter/studyabroad" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#univerror").html(filldetails) ;
                $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#univerror").html(response.result) ;
                $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#addlocaluniversityform").submit(function(e)
{
    e.preventDefault();
    $("#addunivbtn").prop("disabled", true);
    $("#addunivbtn").text(saving+"...");
    $(this).ajaxSubmit({
        error: function(){
            $("#univerror").html(errorocc) ;
            $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addunivbtn").prop("disabled", false);
            $("#addunivbtn").text(savecontnue);
        },
        success: function(response)
        {
            $("#addunivbtn").prop("disabled", false);
            $("#addunivbtn").text("Save");
            if(response.result === "success" ){ 
                $("#univsuccess").html(univadd) ;
                $("#univsuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/knowledgeCenter/localuniversity" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#univerror").html(filldetails) ;
                $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#univerror").html(response.result) ;
                $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#edituniversityform").submit(function(e)
{
    e.preventDefault();
    $("#editunivbtn").prop("disabled", true);
    $("#editunivbtn").text(updating+"...");
    $(this).ajaxSubmit({
        error: function(){
            $("#univerror").html(errorocc) ;
            $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editunivbtn").prop("disabled", false);
            $("#editunivbtn").text("Update");
        },
        success: function(response)
        {
            $("#editunivbtn").prop("disabled", false);
            $("#editunivbtn").text("Update");
            if(response.result === "success" ){ 
                $("#univsuccess").html(univupd) ;
                $("#univsuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/knowledgeCenter/studyabroad" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#univerror").html(filldetails) ;
                $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#univerror").html(response.result) ;
                $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#editlocaluniversityform").submit(function(e)
{
    e.preventDefault();
    $("#editunivbtn").prop("disabled", true);
    $("#editunivbtn").text(updating+"...");
    $(this).ajaxSubmit({
        error: function(){
            $("#univerror").html(errorocc) ;
            $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editunivbtn").prop("disabled", false);
            $("#editunivbtn").text("Update");
        },
        success: function(response)
        {
            $("#editunivbtn").prop("disabled", false);
            $("#editunivbtn").text("Update");
            if(response.result === "success" ){ 
                $("#univsuccess").html(univupd) ;
                $("#univsuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/knowledgeCenter/localuniversity" ;  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#univerror").html(filldetails) ;
                $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#univerror").html(response.result) ;
                $("#univerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

function getquestion(examId){
    console.log(examId);
    $("#allo").html("");
    //$("#marksAllocated").html("");
    var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/examAssessment/getquestion", 
              data: {_csrfToken : refscrf, examId : examId}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                   $('#quest_table').DataTable().destroy();
                    $('#questnbody').html(result.html); 
                    $( "#quest_table" ).DataTable({
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
                    $("#allo").html(result.allocated);
                    //$("#marksAllocated").html(response.allocated);
                 }
              }
          });
          
}


/*****************************/


$("#editexamassform").submit(function(e)
{
    e.preventDefault();
    $('#queid').val("");
    $('#examid').val("");
    $("#editexamassbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#editexamasserror").html(errorocc) ;
            $("#editexamasserror").fadeIn().delay('5000').fadeOut('slow');
            $("#editexamassbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editexamassbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                if(response.type == "pdf")
                {
                    $("#editexamasssuccess").html(exmassupd) ;
                    $("#editexamasssuccess").fadeIn().delay('5000').fadeOut('slow');
        
                    setTimeout(function(){ location.href = baseurl +"/examAssessment" ;  }, 1000);
                }
                else if(response.type == "customize")
                {
                    if(response.submtID != "")
                    {
                        //alert(response.submtID);
                        
                        $("#submitId").val(response.submitId);
                        setTimeout(function(){ location.href = baseurl +"/examAssessment/add_question/"+response.submtID ;  }, 1000);
                    }
                }
                
            }
            else if(response.result === "empty" ){
                $("#editexamasserror").html(filldetails) ;
                $("#editexamasserror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#editexamasserror").html(response.result) ;
                $("#editexamasserror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('.quest_table').on("click",".ecutsomize_exam",function(){    
    $('#emarksAllocated').html("");
    var queid =  $(this).data("id") ;
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({
        url: baseurl +"/examAssessment/editquestion", 
        data: {"queid":queid, _csrfToken : refscrf}, 
        type: 'post',
        success: function(response){
            //alert(response.alloc);
            $('#emarksAllocated').html(response.alloc);
            $.each(response.getdata, function(i, item) {
                console.log(item.max_words);
                $('#equestion').val(item.question);
                $('#emarks').val(item.marks);
                $("#eoptionques").val(item.ques_type).trigger("change");
                if (item.options != null) {
                    var options = item.options;
                    var valuess = options.split("~^");
                    
                    $.each(valuess, function(j, k) {
                        $("#evalueques").append("<option value='"+k+"'>"+k+"</option>")
                    });
                
                    $("#evalueques").val(valuess).trigger("change");
                }
                $('#queid').val(item.id);
                $('#examid').val(item.exam_id);
                $('#emax_words').val(item.max_words);
            });
           
        }
    });
    
}); 

 


$("#edcustmqueform").submit(function(e)
{
    e.preventDefault();
    $("#edcustmquebtn").prop("disabled", true);
    $("#edcustmquebtn").text(updating+"...");
    var examId = $("#examid").val();

    $(this).ajaxSubmit({
        error: function(){
            $("#edcustmqueerror").html(errorocc) ;
            $("#edcustmqueerror").fadeIn().delay('5000').fadeOut('slow');
            $("#edcustmquebtn").prop("disabled", false);
            $("#edcustmquebtn").text("Update");
        },
        success: function(response)
        {
            $("#edcustmquebtn").prop("disabled", false);
            $("#edcustmquebtn").text("Update");
            if(response.result === "success" ){ 
                $("#edcustmquesuccess").html(quesupd) ;
                $("#edcustmquesuccess").fadeIn().delay('3000').fadeOut('slow');
                
                setTimeout(function(){ location.href = baseurl +"/examAssessment/add_question/"+ examId ;  }, 1000);
                        
            }
            else if(response.result === "empty" ){
                $("#edcustmqueerror").html(filldetails) ;
                $("#edcustmqueerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#edcustmqueerror").html(response.result) ;
                $("#edcustmqueerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#editquesform").submit(function(e)
{
    e.preventDefault();
    $("#edcustmquebtn").prop("disabled", true);
    $("#edcustmquebtn").text(updating+"...");
    var examId = $("#examid").val();

    $(this).ajaxSubmit({
        error: function(){
            $("#edcustmqueerror").html(errorocc) ;
            $("#edcustmqueerror").fadeIn().delay('5000').fadeOut('slow');
            $("#edcustmquebtn").prop("disabled", false);
            $("#edcustmquebtn").text("Update");
        },
        success: function(response)
        {
            $("#edcustmquebtn").prop("disabled", false);
            $("#edcustmquebtn").text("Update");
            if(response.result === "success" ){ 
                $("#edcustmquesuccess").html(quesupd) ;
                $("#edcustmquesuccess").fadeIn().delay('3000').fadeOut('slow');
                
                setTimeout(function(){ location.href = baseurl +"/teacherdashboard/add_question/"+ examId ;  }, 1000);
                        
            }
            else if(response.result === "empty" ){
                $("#edcustmqueerror").html(filldetails) ;
                $("#edcustmqueerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#edcustmqueerror").html(response.result) ;
                $("#edcustmqueerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#updateattendanceform").submit(function(e)
{
    //alert("hi");
    e.preventDefault();
    $("#submitattndnbtn").prop("disabled", true);
    var examId = $("#examid").val();

    $(this).ajaxSubmit({
        error: function(){
            $("#attendnerror").html(errorocc) ;
            $("#attendnerror").fadeIn().delay('5000').fadeOut('slow');
            $("#submitattndnbtn").prop("disabled", false);
           
        },
        success: function(response)
        {
            $("#submitattndnbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#attendnsuccess").html("Attendance added successfully.") ;
                $("#attendnsuccess").fadeIn().delay('3000').fadeOut('slow');
                
                setTimeout(function(){  location.reload(); }, 1000);
                        
            }
            else if(response.result === "seldate" ){
                $("#attendnerror").html("Please select Class , Student and Date.") ;
                $("#attendnerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "notsaved" ){
                $("#attendnerror").html("Attendance not saved") ;
                $("#attendnerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "notupdated" ){
                $("#attendnerror").html("Attendance not updated") ;
                $("#attendnerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#attendnerror").html(response.result) ;
                $("#attendnerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#teditquesform").submit(function(e)
{
    e.preventDefault();
    $("#edcustmquebtn").prop("disabled", true);
    $("#edcustmquebtn").text(updating+"...");
    var examId = $("#examid").val();

    $(this).ajaxSubmit({
        error: function(){
            $("#edcustmqueerror").html(errorocc) ;
            $("#edcustmqueerror").fadeIn().delay('5000').fadeOut('slow');
            $("#edcustmquebtn").prop("disabled", false);
            $("#edcustmquebtn").text("Update");
        },
        success: function(response)
        {
            $("#edcustmquebtn").prop("disabled", false);
            $("#edcustmquebtn").text("Update");
            if(response.result === "success" ){ 
                $("#edcustmquesuccess").html(quesupd) ;
                $("#edcustmquesuccess").fadeIn().delay('3000').fadeOut('slow');
                
                setTimeout(function(){ location.href = baseurl +"/teacherexamAssessment/add_question/"+ examId ;  }, 1000);
                        
            }
            else if(response.result === "empty" ){
                $("#edcustmqueerror").html(filldetails) ;
                $("#edcustmqueerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#edcustmqueerror").html(response.result) ;
                $("#edcustmqueerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#teditexamassform").submit(function(e)
{
    e.preventDefault();
    $("#editexamassbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#editexamasserror").html(errorocc) ;
            $("#editexamasserror").fadeIn().delay('5000').fadeOut('slow');
            $("#editexamassbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editexamassbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                
                
                if(response.type == "pdf")
                {
                    $("#editexamasssuccess").html(exmassupd) ;
                    $("#editexamasssuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.href = baseurl +"/teacherexamAssessment" ;  }, 1000);
                }
                else if(response.type == "customize")
                {
                    if(response.submtID != "")
                    {
                        $("#submitId").val(response.submitId);
                        setTimeout(function(){ location.href = baseurl +"/teacherexamAssessment/add_question/"+response.submtID ;  }, 1000);
                    }
                }
                
               
            }
            else if(response.result === "empty" ){
                $("#editexamasserror").html(filldetails) ;
                $("#editexamasserror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#editexamasserror").html(response.result) ;
                $("#editexamasserror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

/***********************/

$('#univtable tbody').on("click",".see_more",function()
{  
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/edituni", 
        data: {"id":id,_csrfToken : refscrf,"see":"more"}, 
        type: 'post',
        success: function (result) 
        {  
            var uni = "#unidesc_"+id;
            var see = "#see_more"+id;
            $(uni).html("");
            $(uni).html(result);
            $(see).html();
            $(see).html("See Less");
            $(see).removeClass("see_more");
            $(see).addClass("see_less");
        }
    });
});

$('#univtable tbody').on("click",".see_less",function()
{  
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/edituni", 
        data: {"id":id,_csrfToken : refscrf,"see":"less"}, 
        type: 'post',
        success: function (result) 
        {  
            var uni = "#unidesc_"+id;
            var see = "#see_more"+id;
            $(uni).html("");
            $(uni).html(result);
            $(see).html();
            $(see).html("See More");
            $(see).addClass("see_more");
            $(see).removeClass("see_less");
        }
    });
});


$('#localunivtable tbody').on("click",".see_more",function()
{  
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/editlouni", 
        data: {"id":id,_csrfToken : refscrf,"see":"more"}, 
        type: 'post',
        success: function (result) 
        {  
            var uni = "#unidesc_"+id;
            var see = "#see_more"+id;
            $(uni).html("");
            $(uni).html(result);
            $(see).html();
            $(see).html("See Less");
            $(see).removeClass("see_more");
            $(see).addClass("see_less");
        }
    });
});

$('#localunivtable tbody').on("click",".see_less",function()
{  
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/knowledgeCenter/editlouni", 
        data: {"id":id,_csrfToken : refscrf,"see":"less"}, 
        type: 'post',
        success: function (result) 
        {  
            var uni = "#unidesc_"+id;
            var see = "#see_more"+id;
            $(uni).html("");
            $(uni).html(result);
            $(see).html();
            $(see).html("See More");
            $(see).addClass("see_more");
            $(see).removeClass("see_less");
        }
    });
});



/*********************/

$('#examasstable tbody').on("click",".editexamass",function(){
    $("#fileName").html("");
    var examassid = $(this).data('id');
    //alert(examassid);
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/examAssessment/edit", 
        data: {"id":examassid,_csrfToken : refscrf}, 
        type: 'post',
        success: function (dataresult) 
        {       
            var result = dataresult.exams;
            $("#eexam_period").html("");
            $("#eexam_period").html(dataresult.exmprd);
            $("#eexam_type").html("");
            $("#eexam_type").html(dataresult.mestr);
            $("#eexamselclssctn").html(dataresult.classess);
            console.log(dataresult);
            if(result) 
            {
                
                $("#editsubmitreq").modal("show");
                $("#exam_assid").val(examassid);
                $("#einstruction").val(result[0].special_instruction);
                $("#etitle").val(result[0].title);
                $("#eend_date").val(result[0].end_date);
                $("#estart_date").val(result[0].start_date);
                $("#pre_file").val(result[0].file_name);
                $("#max_marks").val(result[0].max_marks);
                $("#emax_marks").val(result[0].max_marks);
                if(result[0].file_name != null)
                {
                    $("#fileName").html("<a href='webroot/img/"+result[0].file_name+"' target='_blank'>"+result[0].file_name+"</a>");
                }
                $("#eclass").select2().val(result[0].class_id).trigger('change.select2');
                $("#m_listclass").select2().val(dataresult.clsname).trigger('change.select2');
                
                
                //$("#eclass").select2().val(result[0].class_id).trigger('change.select2');
                var selsctns = dataresult.selsctns;
                getgradecls(dataresult.clsname, selsctns);
                
                $("#econtentupload").select2().val(result[0].exam_format).trigger('change.select2');
                
                $("#esubjects").select2().val(result[0].subject_id).trigger('change.select2');
                $("#ecls_sub").select2().val(result[0].subject_id).trigger('change.select2');
                $("#erequest_for").select2().val(result[0].type).trigger('change.select2');
                $("#eexam_type").select2().val(result[0].exam_type).trigger('change.select2');
                $("#eexam_period").select2().val(result[0].exam_period).trigger('change.select2');
                $("#eexamselclssstdnt").html(dataresult.studnt);
                
                
                if(result[0].type == "Exams")
                {
                    $("#eexamformat").css("display", "block");
                }
                if(result[0].type != "Study Guide")
                {
                    $("#eexamperiod").css("display", "block");
                }
                if(result[0].show_exmfrmt == "custom")
                {
                    $("#editable").prop("checked", true);
                }
                else if(result[0].show_exmfrmt == "pdf")
                {
                    $("#pdfex").prop("checked", true);
                }
                egetperiod(result[0].exam_type, result[0].exam_period)
                
            }
        }
    });
});



$('#texamasstable tbody').on("click",".teditsubmitreq",function(){
    $("#fileName").html("");
    var examassid = $(this).data('id');
    //alert(examassid);
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/examAssessment/edit", 
        data: {"id":examassid,_csrfToken : refscrf}, 
        type: 'post',
        success: function (dataresult) 
        {   
            var result = dataresult.exams;
            $("#eexam_period").html("");
            $("#eexam_period").html(dataresult.exmprd);
            $("#eexam_type").html("");
            $("#eexam_type").html(dataresult.mestr);
            $("#examselclssctn").html(dataresult.classess);
            console.log(dataresult);
            if(result) 
            {
                //var result = data.resultss;
                console.log(result);
                $("#editsubmitreq").modal("show");
                $("#exam_assid").val(examassid);
                $("#einstruction").val(result[0].special_instruction);
                $("#etitle").val(result[0].title);
                $("#eend_date").val(result[0].end_date);
                $("#estart_date").val(result[0].start_date);
                $("#pre_file").val(result[0].file_name);
                $("#max_marks").val(result[0].max_marks);
                $("#emax_marks").val(result[0].max_marks);
                if(result[0].file_name != null)
                {
                    $("#fileName").html("<a href='webroot/img/"+result[0].file_name+"' target='_blank'>"+result[0].file_name+"</a>");
                }
                $("#s_listclass").select2().val(dataresult.clsname).trigger('change.select2');
                
                
                //$("#eclass").select2().val(result[0].class_id).trigger('change.select2');
                var selsctns = dataresult.selsctns;
                getgradecls(dataresult.clsname, selsctns);
                
                $("#econtentupload").select2().val(result[0].exam_format).trigger('change.select2');
                
                $("#esubjects option:selected").val(result[0].subject_id);
                $("#esubjectname").val(result[0].subjname);
                $("#erequest_for").select2().val(result[0].type).trigger('change.select2');
                $("#eexam_type").select2().val(result[0].exam_type).trigger('change.select2');
                getsubcls(result[0].class_id, result[0].subject_id);
                
                egetexamtype(result[0].type,result[0].exam_type);
               
                $("#eexam_period").select2().val(result[0].exam_period).trigger('change.select2');
                if(result[0].type == "Exams")
                {
                    $("#eexamformat").css("display", "block");
                }
                if(result[0].exam_format != "study_guide")
                {
                    $("#eexamperiod").css("display", "block");
                }
                if(result[0].show_exmfrmt == "custom")
                {
                    $("#editable").prop("checked", true);
                }
                else if(result[0].show_exmfrmt == "pdf")
                {
                    $("#pdfex").prop("checked", true);
                }
                
            }
        }
    });
});


$('#schoolexamstable tbody').on("click",".viewinstruction",function(){
    $("#instructions").html("");
    var examassid = $(this).data('id');
   
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/schools/instruction", 
        data: {"id":examassid,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {       
            if(result) 
            {
                $("#viewinstruction").modal("show");
                $("#instructions").html(result[0].special_instruction);
            }
        }
    });
});


$('#assessment_table tbody').on("click",".viewinstruction",function(){
    $("#instructions").html("");
    var examassid = $(this).data('id');
   
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/schools/instruction", 
        data: {"id":examassid,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {       
            if(result) 
            {
                
                $("#viewinstruction").modal("show");
                $("#instructions").html(result[0].special_instruction);
            }
        }
    });
});



$('#examlisting_table tbody').on("click",".viewinstruction",function(){
    $("#instructions").html("");
    var examassid = $(this).data('id');
   
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/schools/instruction", 
        data: {"id":examassid,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {       
            if(result) 
            {
                
                $("#viewinstruction").modal("show");
                $("#instructions").html(result[0].special_instruction);
            }
        }
    });
});



$('#examlisting_table tbody').on("click",".viewpasscode",function(){
    $("#instructions").html("");
    var passcode = $(this).data('passcode');
    var fileexam = $(this).data('examfile');
    var idexam = $(this).data('examid');
    var examformt = $(this).data('examformt');
    var submitexamid = $(this).data('submitexamid');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/ExamListing/viewexamformat", 
        data: { _csrfToken : refscrf, "SubexamID": submitexamid }, 
        type: 'post',
        success: function (result) 
        {    
            console.log(result);
            if(result == null || result == "")
            {
                $("#examformat").css("display", "block");
            }
            else
            {
                $("#examformat").css("display", "none");
            }
            $("#viewpasscode").modal("show");
            $("#gen_passcode").val(passcode);
            $("#exam_file").val(fileexam);
            $("#id_exam").val(idexam);
            $("#id_submitexam").val(submitexamid);
            $("#formatexam").val(result);
        }
    })
    
    
});

$('#school_table tbody').on("click",".schoolstatusreview",function(){

    var sclid = $(this).data('id');
    var sclsts = $(this).data('status');
    $("#sclapprovests").modal("show");
    $("#sclid").val(sclid);
    $("#sclsts").val(sclsts);
});

$('#student_table tbody').on("click",".deleterequeststu",function(){

    var stuid = $(this).data('id');
    var studelreq = $(this).data('delreq');
    $("#delreqpopup").modal("show");
    $("#stuid").val(stuid);
    $("#studelreq").val(studelreq);
});

$('#student_table tbody').on("click",".js-sweetalert",function(){
    //alert("hj");
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var str =  $(this).data("str") ;
    if (type === 'status_change') {
        var sts =  $(this).data("status") ;
        showstatusConfirmMessage(id,url,str,sts);
    } 
}); 

$('#reqsts_table tbody').on("click",".js-sweetalert",function(){
    //alert("hj");
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var str =  $(this).data("str") ;
    if (type === 'status_change') {
        var sts =  $(this).data("status") ;
        showstatusConfirmMessage(id,url,str,sts);
    } 
}); 

$('#vendor_table tbody').on("click",".schoolstatusreview",function(){
    //alert("hj");
    var type = $(this).data('type');        
    var id =  $(this).data("id") ;
    var url =  $(this).data("url") ;
    var str =  $(this).data("str") ;
    if (type === 'status_change') {
        var sts =  $(this).data("status") ;
        showstatusConfirmMessage(id,url,str,sts);
    } 
}); 

$('#class_table tbody').on("click",".editclass",function(){

    var classid = $(this).data('id');
  
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/classes/update", 
        data: {"id":classid,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {    
            console.log(result);
            if(result) 
            {
                $("#editclass").modal("show");
                $("#c_name").val(result[0]['c_name']);
                $("#cid").val(result[0]['id']);
                $("#addsectns").append("<option value='"+result[0]['c_section']+"'>"+result[0]['c_section']+"</option>");
                $("#addsectns").val(result[0]['c_section']).trigger("change");
                $("#c_section").val(result[0]['school_sections']);
            }
        }
    });
});


$('#examlisting_table tbody').on("click",".examUpload",function(){

    var subexamid = $(this).data('id');
    var studentid = $("#stdid").val();
    var refscrf = $("input[name='_csrfToken']").val();
    
    $("#uploadExams").modal("show");
    $("#student_id").val(studentid);
    $("#exam_id").val(subexamid);
           
});

$('#viewtdairy').on("click",".tdairydtl",function()
{
    var full = $(this).data('str');
    var title = $(this).data('title');
    $("#viewtd").modal("show");
    $("#title").html("");
    $("#title").html(title);
    $("#detailfull").html("");
    $("#detailfull").html(full);
});
$('#viewtdairy').on("click",".tdairydtl2",function()
{
    var full = $(this).data('str');
    var title = $(this).data('title');
    $("#viewtd").modal("show");
    $("#title").html("");
    $("#title").html(title);
    $("#detailfull").html("");
    $("#detailfull").html(full);
});
$('#viewtdairy').on("click",".tdairydtl3",function()
{
    var full = $(this).data('str');
    var title = $(this).data('title');
    $("#viewtd").modal("show");
    $("#title").html("");
    $("#title").html(title);
    $("#detailfull").html("");
    $("#detailfull").html(full);
});

$('#assessment_table tbody').on("click",".assUpload",function(){

    var subexamid = $(this).data('id');
    var studentid = $(this).data('stuid');
    $("#AssessmentUpload").modal("show");
    $("#student_id").val(studentid);
    $("#exam_id").val(subexamid);
});

$("#passcodesubmit").submit(function(e)
{
    $("#viewexams").html("");
    var passcode =  $("#passcode").val();
    var gen_pass =  $("#gen_passcode").val();
    var examID = $("#id_exam").val();
    var SubexamID = $("#id_submitexam").val();
    
    var formatexam = $("#formatexam").val();
    var examformt = $("input[name='exam_format']:checked", "#passcodesubmit").val();
    var refscrf = $("input[name='_csrfToken']").val();
    //alert(formatexam);
    if(formatexam != "")
    {
        if(passcode == gen_pass)
        {
            var examformat ="";
            var examfile = $("#exam_file").val();
            if(examfile != "")
            {
                $("#viewpasscode").modal("hide");
                $("#viewexam").modal("show");
                $("#viewexams").html("<iframe src='"+origin+"/school/webroot/img/"+examfile+"' width='780' height='550'></iframe>");
            }
            else
            {
                $.ajax({ 
                    url: baseurl +"/ExamListing/viewcustomexam", 
                    data: {"id":examID, _csrfToken : refscrf, "examformt":examformat}, 
                    type: 'post',
                    success: function (result) 
                    {    
                        console.log(result);
                        if(formatexam == "pdf")
                        {
                            console.log(result);
                            $('#viewexam').css('overflow-y', 'auto');
                            $("#viewpasscode").modal("hide");
                            $("#viewexam").modal("show");
                            $("#viewexams").html("<div style='width:780pxx;'>"+result+"</div>");
                        }
                        else
                        {
                            setTimeout(function(){ location.href = baseurl +"/ExamListing/viewexam/"+SubexamID ;  }, 1000);
                        }
                    }
                })   
            }
        }
        else
        {
            $("#passcodeerror").html("Enter Correct Passcode. Please try again.") ;
            $("#passcodeerror").fadeIn().delay('5000').fadeOut('slow');
        }
    }
    return false;
});

$("#classexam_table tbody").on("click",".viewsubmittedexam",function()
{
    $("#viewfile").html("");
    var file =  $(this).data('id');
    $("#viewsubmittedexam").modal("show");
    var files = file.split(",");
    $.each(files, function(i, item) {
        $("#viewfile").append("<iframe src='"+origin+"/school/webroot/uploadExams/"+item+"' width='780' height='550'></iframe>");
    });
    
    
});
$("#classexam_table tbody").on("click",".viewclaim",function()
{
    $("#viewclaimrais").html("");
    var id =  $(this).data('id');
    var claim =  $(this).data('claim');
    //var gen_pass =  $("#gen_passcode").val();
   
    $("#viewclaim").modal("show");
    $("#viewclaimrais").html(claim);
    $("#subexid").val(id);
    
});
$("#classasstable tbody").on("click",".viewclaim",function()
{
    $("#viewclaimrais").html("");
    var id =  $(this).data('id');
    var claim =  $(this).data('claim');
    //var gen_pass =  $("#gen_passcode").val();
   
    $("#viewclaim").modal("show");
    $("#viewclaimrais").html(claim);
    $("#subexid").val(id);
    
});

$("#classexam_table tbody").on("click",".viewquestionexam",function()
{
    $("#viewquesfile").html("");
    var file =  $(this).data('id');
    var examID = $(this).data('examid');
    //alert(file);
    var refscrf = $("input[name='_csrfToken']").val();
    if(file != "")
    {
        $("#viewquestionexam").modal("show");
        $("#viewquesfile").html("<iframe src='"+origin+"/school/webroot/img/"+file+"' width='780' height='550'></iframe>");
    }
    else
    {
        $.ajax({ 
            url: baseurl +"/ExamListing/viewcustomexam", 
            data: {"id":examID, _csrfToken : refscrf}, 
            type: 'post',
            success: function (result) 
            {    
                console.log(result);
                $('#viewquesfile').css('overflow-y', 'auto');
                $("#viewquestionexam").modal("show");
                $("#viewquesfile").html("<div style='width:780pxx; height:750px;'>"+result+"</div>");
            }
        });
    }
    
});

$("#subjectgrade_table  tbody").on("click",".viewsubmittedexam",function()
{
    $("#viewfile").html("");
    var file =  $(this).data('id');
    var files = file.split(",");
    $("#viewsubmittedexam").modal("show");
    $.each(files, function(i, item) {
        $("#viewfile").append("<iframe src='"+origin+"/school/webroot/uploadExams/"+item+"' width='780' height='550'></iframe>");
    });
    
    
});

$("#subjectgrade_table  tbody").on("click",".viewquestionexam",function()
{
    $("#viewquesfile").html("");
    var file =  $(this).data('id');
    var examID = $(this).data('examid');
    var refscrf = $("input[name='_csrfToken']").val();
    if(file != "")
    {
        $("#viewquestionexam").modal("show");
        $("#viewquesfile").html("<iframe src='"+origin+"/school/webroot/img/"+file+"' width='780' height='550'></iframe>");
    }
    else
    {
        $.ajax({ 
            url: baseurl +"/ExamListing/viewcustomexam", 
            data: {"id":examID, _csrfToken : refscrf}, 
            type: 'post',
            success: function (result) 
            {    
                console.log(result);
                $('#viewquesfile').css('overflow-y', 'auto');
                $("#viewquestionexam").modal("show");
                $("#viewquesfile").html("<div style='width:780pxx; height:750px;'>"+result+"</div>");
            }
        });
    }
    
    
});

$("#subjectgrade_table  tbody").on("click",".raiseclaim",function()
{
    var id =  $(this).data('id');
    $("#raiseClaim").modal("show");
    $("#sub_exm_id").val(id);
    var subid = $("#subid").val();
    var clsid = $("#clsid").val();
    $("#classid").val(clsid);
    $("#subjectid").val(subid);
    
});

$("#classasstable  tbody").on("click",".viewsubmittedass",function()
{
    $("#viewfile").html("");
    var file =  $(this).data('id');
    var files = file.split(",");
    $("#viewsubmittedass").modal("show");
    $.each(files, function(i, item) {
        $("#viewfile").append("<iframe src='"+origin+"/school/webroot/uploadExams/"+item+"' width='780' height='550'></iframe>");
    });
    
});

$("#classasstable  tbody").on("click",".viewquestionass",function()
{
    $("#viewquesfile").html("");
    var file =  $(this).data('id');
    var examID = $(this).data('examid');
    //alert(file);
    var refscrf = $("input[name='_csrfToken']").val();
    if(file != "")
    {
        $("#viewquestionass").modal("show");
        $("#viewquesfile").html("<iframe src='"+origin+"/school/webroot/img/"+file+"' width='780' height='550'></iframe>");
    }
    else
    {
        $.ajax({ 
            url: baseurl +"/ExamListing/viewcustomexam", 
            data: {"id":examID, _csrfToken : refscrf}, 
            type: 'post',
            success: function (result) 
            {    
                console.log(result);
                $('#viewquesfile').css('overflow-y', 'auto');
                $("#viewquestionass").modal("show");
                $("#viewquesfile").html("<div style='width:780pxx; height:750px;'>"+result+"</div>");
            }
        });
    }
    
});

$("#classexam_table tbody").on("click",".viewevaluatedexam",function()
{
    $("#viewcheckedfile").html("");
    var file =  $(this).data('id');
    var files = file.split(",");
    $("#viewevaluatedexam").modal("show");
    $.each(files, function(i, item) {
        $("#viewcheckedfile").append("<iframe src='"+origin+"/school/webroot/uploadevaluatedanswersheet/"+item+"' width='780' height='550'></iframe>");
    });
    
});

$("#classasstable tbody").on("click",".viewevaluatedass",function()
{
    $("#viewcheckedfile").html("");
    var file =  $(this).data('id');
    var files = file.split(",");
    $("#viewevaluatedass").modal("show");
    $.each(files, function(i, item) {
        $("#viewcheckedfile").append("<iframe src='"+origin+"/school/webroot/uploadevaluatedanswersheet/"+item+"' width='780' height='550'></iframe>");
    });
    console.log(files);
});

$("#assessment_table tbody").on("click",".viewsubmitdass",function()
{
    $("#viewcheckedfile").html("");
    var file =  $(this).data('id');
    var files = file.split(",");
    $("#viewevaluatedass").modal("show");
    $.each(files, function(i, item) {
        console.log(item);
        $("#viewcheckedfile").append("<iframe src='"+baseurl+"/uploadExams/"+item+"' width='780' height='550'></iframe>");
    });
    console.log(files);
});

$("#examlisting_table tbody").on("click",".viewsubmitdass",function()
{
    $("#viewcheckedfile").html("");
    var file =  $(this).data('id');
    var files = file.split(",");
    $("#viewevaluatedass").modal("show");
    $.each(files, function(i, item) {
        $("#viewcheckedfile").append("<iframe src='"+origin+"/school/webroot/uploadExams/"+item+"' width='780' height='550'></iframe>");
    });
    console.log(files);
});

$("#subjectgrade_table tbody").on("click",".viewevaluatedexam",function()
{
    $("#viewcheckedfile").html("");
    var file =  $(this).data('id');
    var files = file.split(",");
    $("#viewevaluatedexam").modal("show");
    $.each(files, function(i, item) {
        $("#viewcheckedfile").append("<iframe src='"+origin+"/school/webroot/uploadevaluatedanswersheet/"+item+"' width='780' height='550'></iframe>");
    });
    
});

$("#classexam_table tbody").on("click",".updateexamreviews",function()
{
    var id =  $(this).data('id');
    var claim_sts = $(this).data('claimsts');
    var claim = $(this).data('claimraise');
    var marks = $(this).data('maxmarks');
    $("#updateexamreviews").modal("show");
    $("#sub_exm_id").val(id);
    $("#maxmarks").val(marks);
    
    if(claim != "" && claim_sts == 0)
    {
        $("#claim_sts").css("display", "block");
    }
    else
    {
        $("#claim_sts").css("display", "none");
    }
    
    
});

$("#classasstable tbody").on("click",".updateassreviews",function()
{
    var id =  $(this).data('id'); 
    var id =  $(this).data('id'); 
    var claim_sts = $(this).data('claimsts');
    var claim = $(this).data('claimraise');
    var studid =  $(this).data('studentid');
    $("#updateassreviews").modal("show");
    $("#sub_exm_id").val(id);
    $("#studen_id").val(studid);
    if(claim != "" && claim_sts == 0)
    {
        $("#claim_sts").css("display", "block");
    }
    else
    {
        $("#claim_sts").css("display", "none");
    }
    
    
});

$("#updateexamreviewsform").submit(function(e)
{
    e.preventDefault();
    $("#updateexamreviewsbtn").prop("disabled", true);
    $("#updateexamreviewsbtn").text("Updating");
    var subid = $("#subjectid").val();
    var clasid = $("#classid").val();
    $(this).ajaxSubmit({
        
        error: function(){
            $("#examreviewerror").html(errorocc) ;
            $("#examreviewerror").fadeIn().delay('5000').fadeOut('slow');
            $("#updateexamreviewsbtn").prop("disabled", false);
            $("#updateexamreviewsbtn").text(updatedremrks);
        },
        success: function(response)
        {
            $("#updateexamreviewsbtn").prop("disabled", false);
            $("#updateexamreviewsbtn").text(updatedremrks);
            if(response.result === "success" ){ 
                $("#examreviewsuccess").html(remarkupd) ;
                $("#examreviewsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    //location.href = baseurl +"/classExams?studentdetails=0&gradeid=" + clasid + "&subid=" + subid ;  
                    location.reload();
                }, 1000);
            }
            else if(response.result === "empty" ){
                $("#examreviewerror").html(filldetails) ;
                $("#examreviewerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#examreviewerror").html(response.result) ;
                $("#examreviewerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#stsrsnform").submit(function(e)
{
    e.preventDefault();
    $("#sclstsbtn").prop("disabled", true);
    $(this).ajaxSubmit({
        
        error: function(){
            $("#sclstserror").html(errorocc) ;
            $("#sclstserror").fadeIn().delay('5000').fadeOut('slow');
            $("#sclstsbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#sclstsbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#sclstssuccess").html(stsrsnupd) ;
                $("#sclstssuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.href = baseurl +"/schools" ;  
                }, 1000);
            }
            else{
                $("#sclstserror").html(response.result) ;
                $("#sclstserror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#delrsnform").submit(function(e)
{
    e.preventDefault();
    $("#sclstsbtn").prop("disabled", true);
    $(this).ajaxSubmit({
        
        error: function(){
            $("#sclstserror").html(errorocc) ;
            $("#sclstserror").fadeIn().delay('5000').fadeOut('slow');
            $("#sclstsbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#sclstsbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#sclstssuccess").html(stsrsnupd) ;
                $("#sclstssuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.href = baseurl +"/students" ;  
                }, 1000);
            }
            else{
                $("#sclstserror").html(response.result) ;
                $("#sclstserror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#updateclaimform").submit(function(e)
{
    e.preventDefault();
    $("#updateclaimbtn").prop("disabled", true);
    $("#updateclaimbtn").text("Sending...");
    var subid = $("#subjectid").val();
    var clasid = $("#classid").val();
    $(this).ajaxSubmit({
        
        error: function(){
            $("#claimerror").html(errorocc) ;
            $("#claimerror").fadeIn().delay('5000').fadeOut('slow');
            $("#updateclaimbtn").prop("disabled", false);
            $("#updateclaimbtn").text("Send");
        },
        success: function(response)
        {
            $("#updateclaimbtn").prop("disabled", false);
            $("#updateclaimbtn").text("Send");
            if(response.result === "success" ){ 
                $("#claimsuccess").html(claimraise) ;
                $("#claimsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.href = baseurl +"/subjectGrade?studentdetails=0&classId=" + clasid + "&subId=" + subid ;  
                }, 1000);
            }
            else if(response.result === "empty" ){
                $("#claimerror").html(filldetails) ;
                $("#claimerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#claimerror").html(response.result) ;
                $("#claimerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#updateassreviewsform").submit(function(e)
{
    e.preventDefault();
    $("#updateassreviewsbtn").prop("disabled", true);
    $("#updateassreviewsbtn").text("Updating");
    var subid = $("#subjectid").val();
    var clasid = $("#classid").val();
    $(this).ajaxSubmit({
        
        error: function(){
            $("#assreviewerror").html(errorocc) ;
            $("#assreviewerror").fadeIn().delay('5000').fadeOut('slow');
            $("#updateassreviewsbtn").prop("disabled", false);
            $("#updateassreviewsbtn").text(updatedremrks);
        },
        success: function(response)
        {
            $("#updateassreviewsbtn").prop("disabled", false);
            $("#updateassreviewsbtn").text(updatedremrks);
            if(response.result === "success" ){ 
                $("#assreviewsuccess").html(remarkupd) ;
                $("#assreviewsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    //location.href = baseurl +"/classAssessment?studentdetails=0&gradeid=" + clasid + "&subid=" + subid ;  
                    location.reload();
                }, 1000);
            }
            else if(response.result === "empty" ){
                $("#assreviewerror").html(filldetails) ;
                $("#assreviewerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#assreviewerror").html(response.result) ;
                $("#assreviewerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#updatequizreviewsform").submit(function(e)
{
    e.preventDefault();
    $("#updateassreviewsbtn").prop("disabled", true);
    $("#updateassreviewsbtn").text("Updating");
    var subid = $("#subjectid").val();
    var clasid = $("#classid").val();
    $(this).ajaxSubmit({
        
        error: function(){
            $("#assreviewerror").html(errorocc) ;
            $("#assreviewerror").fadeIn().delay('5000').fadeOut('slow');
            $("#updateassreviewsbtn").prop("disabled", false);
            $("#updateassreviewsbtn").text(updatedremrks);
        },
        success: function(response)
        {
            $("#updateassreviewsbtn").prop("disabled", false);
            $("#updateassreviewsbtn").text(updatedremrks);
            if(response.result === "success" ){ 
                $("#assreviewsuccess").html(remarkupd) ;
                $("#assreviewsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    //location.href = baseurl +"/classQuiz?studentdetails=0&gradeid=" + clasid + "&subid=" + subid ;  
                    location.reload();
                }, 1000);
            }
            else if(response.result === "empty" ){
                $("#assreviewerror").html(filldetails) ;
                $("#assreviewerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#assreviewerror").html(response.result) ;
                $("#assreviewerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#examuploadform").submit(function(e)
{
    e.preventDefault();
    $("#uploadexambtn").prop("disabled", true);
    $("#uploadexambtn").text("Uploading");
    var subid = $("#subid").val();
    var clasid = $("#clsid").val();
    $(this).ajaxSubmit({
        
        error: function(){
            $("#examuploaderror").html(errorocc) ;
            $("#examuploaderror").fadeIn().delay('5000').fadeOut('slow');
            $("#uploadexambtn").prop("disabled", false);
            $("#uploadexambtn").text("Upload");
        },
        success: function(response)
        {
            $("#uploadexambtn").prop("disabled", false);
            $("#uploadexambtn").text("Upload");
            if(response.result === "success" ){ 
                $("#examuploadsuccess").html(examuplod) ;
                $("#examuploadsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.href = baseurl +"/examListing?openmodal=0&classId=" + clasid + "&subId=" + subid ;  
                }, 1000);
            }
            else if(response.result === "empty" ){
                $("#examuploaderror").html(filldetails) ;
                $("#examuploaderror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#examuploaderror").html(response.result) ;
                $("#examuploaderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#assuploadform").submit(function(e)
{
    e.preventDefault();
    $("#uploadassbtn").prop("disabled", true);
    $("#uploadassbtn").text("Uploading");
    var subid = $("#subid").val();
    var clasid = $("#clsid").val();
    $(this).ajaxSubmit({
        
        error: function(){
            $("#assuploaderror").html(errorocc) ;
            $("#assuploaderror").fadeIn().delay('5000').fadeOut('slow');
            $("#uploadassbtn").prop("disabled", false);
            $("#uploadassbtn").text("Upload");
        },
        success: function(response)
        {
            $("#uploadassbtn").prop("disabled", false);
            $("#uploadassbtn").text("Upload");
            if(response.result === "success" ){ 
                $("#assuploadsuccess").html(assignuplod) ;
                $("#assuploadsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.href = baseurl +"/assessments?openmodal=0&classId=" + clasid + "&subId=" + subid ;  
                }, 1000);
            }
            else if(response.result === "empty" ){
                $("#assuploaderror").html(filldetails) ;
                $("#assuploaderror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#assuploaderror").html(response.result) ;
                $("#assuploaderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#quizuploadform").submit(function(e)
{
    e.preventDefault();
    $("#uploadassbtn").prop("disabled", true);
    $("#uploadassbtn").text("Uploading");
    var subid = $("#subid").val();
    var clasid = $("#clsid").val();
    $(this).ajaxSubmit({
        
        error: function(){
            $("#assuploaderror").html(errorocc) ;
            $("#assuploaderror").fadeIn().delay('5000').fadeOut('slow');
            $("#uploadassbtn").prop("disabled", false);
            $("#uploadassbtn").text("Upload");
        },
        success: function(response)
        {
            $("#uploadassbtn").prop("disabled", false);
            $("#uploadassbtn").text("Upload");
            if(response.result === "success" ){ 
                $("#assuploadsuccess").html(quizupld) ;
                $("#assuploadsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.href = baseurl +"/quiz?openmodal=0&classId=" + clasid + "&subId=" + subid ;  
                }, 1000);
            }
            else if(response.result === "empty" ){
                $("#assuploaderror").html(filldetails) ;
                $("#assuploaderror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#assuploaderror").html(response.result) ;
                $("#assuploaderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#codeconductform").submit(function(e)
{
    e.preventDefault();
    $("#codeconductbtn").prop("disabled", true);
    $("#codeconductbtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#codeerror").html(errorocc) ;
            $("#codeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#codeconductbtn").prop("disabled", false);
            $("#codeconductbtn").text("Save");
        },
        success: function(response)
        {
            $("#codeconductbtn").prop("disabled", false);
            $("#codeconductbtn").text("Save");
            if(response.result === "success" ){ 
                $("#codesuccess").html("Code of Conduct content has been updated.") ;
                $("#codesuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/Codeconduct";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#codeerror").html(filldetails) ;
                $("#codeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#codeerror").html(response.result) ;
                $("#codeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#addnotifyform").submit(function(e)
{
    e.preventDefault();
    $("#addnotifybtn").prop("disabled", true);
    $("#addnotifybtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#notifyerror").html(errorocc) ;
            $("#notifyerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addnotifybtn").prop("disabled", false);
            $("#addnotifybtn").text("Save");
        },
        success: function(response)
        {
            $("#addnotifybtn").prop("disabled", false);
            $("#addnotifybtn").text("Save");
            if(response.result === "success" ){ 
                $("#notifysuccess").html(notifyadd) ;
                $("#notifysuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/notification";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#notifyerror").html(filldetails) ;
                $("#notifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#notifyerror").html(response.result) ;
                $("#notifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#saddnotifyform").submit(function(e)
{
    e.preventDefault();
    $("#addnotifybtn").prop("disabled", true);
    $("#addnotifybtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#notifyerror").html(errorocc) ;
            $("#notifyerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addnotifybtn").prop("disabled", false);
            $("#addnotifybtn").text("Save");
        },
        success: function(response)
        {
            $("#addnotifybtn").prop("disabled", false);
            $("#addnotifybtn").text("Save");
            if(response.result === "success" ){ 
                $("#notifysuccess").html(notifctnadd) ;
                $("#notifysuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/schoolNotification";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#notifyerror").html(filldetails) ;
                $("#notifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#notifyerror").html(response.result) ;
                $("#notifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#seditnotifyform").submit(function(e)
{
    e.preventDefault();
    $("#editnotifybtn").prop("disabled", true);
    $("#editnotifybtn").text(updating+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#enotifyerror").html(errorocc) ;
            $("#enotifyerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editnotifybtn").prop("disabled", false);
            $("#editnotifybtn").text("Update");
        },
        success: function(response)
        {
            $("#editnotifybtn").prop("disabled", false);
            $("#editnotifybtn").text("Update");
            if(response.result === "success" ){ 
                $("#enotifysuccess").html(notifctnupd) ;
                $("#enotifysuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/schoolNotification";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#enotifyerror").html(filldetails) ;
                $("#enotifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#enotifyerror").html(response.result) ;
                $("#enotifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#taddnotifyform").submit(function(e)
{
    e.preventDefault();
    $("#addnotifybtn").prop("disabled", true);
    $("#addnotifybtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#notifyerror").html(errorocc) ;
            $("#notifyerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addnotifybtn").prop("disabled", false);
            $("#addnotifybtn").text("Save");
        },
        success: function(response)
        {
            $("#addnotifybtn").prop("disabled", false);
            $("#addnotifybtn").text("Save");
            if(response.result === "success" ){ 
                $("#notifysuccess").html(notifctnadd) ;
                $("#notifysuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/teacherNotifications";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#notifyerror").html(filldetails) ;
                $("#notifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#notifyerror").html(response.result) ;
                $("#notifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#teditnotifyform").submit(function(e)
{
    e.preventDefault();
    $("#editnotifybtn").prop("disabled", true);
    $("#editnotifybtn").text(updating+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#enotifyerror").html(errorocc) ;
            $("#enotifyerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editnotifybtn").prop("disabled", false);
            $("#editnotifybtn").text("Update");
        },
        success: function(response)
        {
            $("#editnotifybtn").prop("disabled", false);
            $("#editnotifybtn").text("Update");
            if(response.result === "success" ){ 
                $("#enotifysuccess").html(notifctnupd) ;
                $("#enotifysuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/teacherNotifications";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#enotifyerror").html(filldetails) ;
                $("#enotifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#enotifyerror").html(response.result) ;
                $("#enotifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#editnotifyform").submit(function(e)
{
    e.preventDefault();
    $("#editnotifybtn").prop("disabled", true);
    $("#editnotifybtn").text(updating+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#enotifyerror").html(errorocc) ;
            $("#enotifyerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editnotifybtn").prop("disabled", false);
            $("#editnotifybtn").text("Update");
        },
        success: function(response)
        {
            $("#editnotifybtn").prop("disabled", false);
            $("#editnotifybtn").text("Update");
            if(response.result === "success" ){ 
                $("#enotifysuccess").html(notifctnupd) ;
                $("#enotifysuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/notification";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#enotifyerror").html(filldetails) ;
                $("#enotifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#enotifyerror").html(response.result) ;
                $("#enotifyerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$('#feestruc_table tbody').on("click",".viewstruc",function(){
    $("#sessn").html("");
    $("#classname").html("");
    $("#frequencyview").html("");
    $("#dollar").html("");
    
    var id = $(this).data('id');
    var amt = $(this).data('amt');
    var freq = $(this).data('frequency');
    var classname = $(this).data('cname');
    var session = $(this).data('sess');
    
    
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/feedetail/view", 
        data: {"id":id, _csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {       
            if (result) 
            {
                $("#viewstruc").modal('show');
                
                $("#sessn").html(session);
                $("#classname").html(classname);
                $("#frequencyview").html(freq);
                $("#dollar").html(amt);
                
                $('#viewfeestruc_table').DataTable().destroy();
                $('#viewstrucbody').html(result.html); 
                $("#viewfeestruc_table" ).DataTable({
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
});

$("#addattndncecsvform").submit(function(e)
{
    e.preventDefault();
    $("#csvbtn").prop("disabled", true);
    $("#csvbtn").text("Uploading...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#csverror").html(errorocc) ;
            $("#csverror").fadeIn().delay('5000').fadeOut('slow');
            $("#csvbtn").prop("disabled", false);
            $("#csvbtn").text("Upload");
        },
        success: function(response)
        {
            console.log(response);
            $("#csvbtn").prop("disabled", false);
            $("#csvbtn").text("Upload");
            if(response.result === "success" ){ 
                $("#csvsuccess").html(attdncuplod) ;
                $("#csvsuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/teacherattendance";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#csverror").html(filldetails) ;
                $("#csverror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#csverror").html(response.result) ;
                $("#csverror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#scladdattndncecsvform").submit(function(e)
{
    e.preventDefault();
    $("#csvbtn").prop("disabled", true);
    $("#csvbtn").text("Uploading...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#csverror").html(errorocc) ;
            $("#csverror").fadeIn().delay('5000').fadeOut('slow');
            $("#csvbtn").prop("disabled", false);
            $("#csvbtn").text("Upload");
        },
        success: function(response)
        {
            console.log(response);
            $("#csvbtn").prop("disabled", false);
            $("#csvbtn").text("Upload");
            if(response.result === "success" ){ 
                $("#csvsuccess").html(attdncuplod) ;
                $("#csvsuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/schoolattendance";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#csverror").html(filldetails) ;
                $("#csverror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#csverror").html(response.result) ;
                $("#csverror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});


$("#addstuattndncform").submit(function(e)
{
    e.preventDefault();
    $("#addstdntattndncbtn").prop("disabled", true);
    $("#addstdntattndncbtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#addstudatterror").html(errorocc) ;
            $("#addstudatterror").fadeIn().delay('5000').fadeOut('slow');
            $("#addstdntattndncbtn").prop("disabled", false);
            $("#addstdntattndncbtn").text("Add");
        },
        success: function(response)
        {
            console.log(response);
            $("#addstdntattndncbtn").prop("disabled", false);
            $("#addstdntattndncbtn").text("Add");
            if(response.result === "success" ){ 
                $("#addstudattsuccess").html(attdncadd) ;
                $("#addstudattsuccess").fadeIn().delay('3000').fadeOut('slow');
                
                $('#addstuattndncform').trigger("reset");
                $("#class").val(null).trigger("change");
                $("#student_cls").val(null).trigger("change"); 
                $("#attdate").val("");
                $("#reason").val("");
                $("#attdan").val(null).trigger("change"); 
                //setTimeout(function(){location.href = baseurl +"/teacherattendance";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#addstudatterror").html(filldetails) ;
                $("#addstudatterror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#addstudatterror").html(response.result) ;
                $("#addstudatterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#addsclattndncform").submit(function(e)
{
    e.preventDefault();
    $("#addstdntattndncbtn").prop("disabled", true);
    $("#addstdntattndncbtn").text(saving+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#addstudatterror").html(errorocc) ;
            $("#addstudatterror").fadeIn().delay('5000').fadeOut('slow');
            $("#addstdntattndncbtn").prop("disabled", false);
            $("#addstdntattndncbtn").text("Add");
        },
        success: function(response)
        {
            console.log(response);
            $("#addstdntattndncbtn").prop("disabled", false);
            $("#addstdntattndncbtn").text("Add");
            if(response.result === "success" ){ 
                $("#addstudattsuccess").html(attdncadd) ;
                $("#addstudattsuccess").fadeIn().delay('3000').fadeOut('slow');
                
                $('#addsclattndncform').trigger("reset");
                $("#class").val(null).trigger("change");
                $("#student_cls").val(null).trigger("change"); 
                $("#attdate").val("");
                $("#reason").val("");
                $("#attdan").val(null).trigger("change"); 
                //setTimeout(function(){location.href = baseurl +"/teacherattendance";}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#addstudatterror").html(filldetails) ;
                $("#addstudatterror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#addstudatterror").html(response.result) ;
                $("#addstudatterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('#clsattndnc_table tbody').on("click",".editattendance",function()
{
    //alert("dsk");
    var id = $(this).data('id');
    var attnc = $(this).data('attnc');
    var attdate = $(this).data('gattdate');
    var clssub = $(this).data('clssub');
    var stuid = $(this).data('stuid');
   
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/classAttendance/edit", 
        data: {"id":id, _csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {       
            console.log(result);
            
            $("#atid").val(id);
            $("#eattdate").val(attdate);
            $("#class_sub").select2().val(clssub).trigger('change.select2');
            //$("#studentid").select2().val(stuid).trigger('change.select2');
            $("#attendance").select2().val(attnc).trigger('change.select2');
            var student = "student";
            $("#editattendance").modal('show');
            getstudentlist(stuid, student);
        }
    });
       
    
            
        
});

$("#editstuattndncform").submit(function(e)
{
    e.preventDefault();
    $("#editstdntattndncbtn").prop("disabled", true);
    $("#editstdntattndncbtn").text(updating+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#editstudatterror").html(errorocc) ;
            $("#editstudatterror").fadeIn().delay('5000').fadeOut('slow');
            $("#editstdntattndncbtn").prop("disabled", false);
            $("#editstdntattndncbtn").text("Update");
        },
        success: function(response)
        {
            console.log(response);
            $("#editstdntattndncbtn").prop("disabled", false);
            $("#editstdntattndncbtn").text("Update");
            if(response.result === "success" ){ 
                $("#editstudattsuccess").html(attdncupd) ;
                $("#editstudattsuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/classAttendance?studentdetails=0&subid="+response.subjectid+"&gradeid="+response.classid;}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#editstudatterror").html(filldetails) ;
                $("#editstudatterror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#editstudatterror").html(response.result) ;
                $("#editstudatterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('#sclattndnc_table tbody').on("click",".editattendance",function()
{
    //alert("dsk");
    var id = $(this).data('id');
    var attnc = $(this).data('attnc');
    var attdate = $(this).data('gattdate');
    var clssub = $(this).data('clssub');
    var stuid = $(this).data('stuid');
    $("#studentid").html("");
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/schoolattendance/edit", 
        data: {"id":id, _csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {       
            console.log(result);
            $("#ereason").val(result.attendance.reason);
            $("#atid").val(id);
            $("#eattdate").val(attdate);
            //$(".class_sub").select2().val(clssub).trigger('change.select2');
            //$("#studentid").select2().val(result.student).trigger('change.select2');
            $("#attendance").select2().val(attnc).trigger('change.select2');
            var student = "student";
            $("#editattendance").modal('show');
            $(".class_sub").html("");
            $(".class_sub").html(result.class);
           // getstudentlistattendance(stuid, student);
            $(".studentlisting").html("");
            $(".studentlisting").html(result.student);
        }
    });
});

$("#editsclattndncform").submit(function(e)
{
    e.preventDefault();
    $("#editstdntattndncbtn").prop("disabled", true);
    $("#editstdntattndncbtn").text(updating+"...");
    
    $(this).ajaxSubmit({
        error: function(){
            $("#editstudatterror").html(errorocc) ;
            $("#editstudatterror").fadeIn().delay('5000').fadeOut('slow');
            $("#editstdntattndncbtn").prop("disabled", false);
            $("#editstdntattndncbtn").text("Update");
        },
        success: function(response)
        {
            console.log(response);
            $("#editstdntattndncbtn").prop("disabled", false);
            $("#editstdntattndncbtn").text("Update");
            if(response.result === "success" ){ 
                $("#editstudattsuccess").html(attdncupd) ;
                $("#editstudattsuccess").fadeIn().delay('3000').fadeOut('slow');
                setTimeout(function(){location.href = baseurl +"/schoolattendance?examAssmodal=0&date="+response.date+"&class="+response.classid;}, 1000);
  
            }
            else if(response.result === "empty" ){
                $("#editstudatterror").html(filldetails) ;
                $("#editstudatterror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#editstudatterror").html(response.result) ;
                $("#editstudatterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$('#attendancereport_table tbody').on("click",".attendancereport",function(){
    $("#subjectnames").html("");
    $("#subjectattendance").html("");
    $("#attendancdate").html("");
    $("#studname").html("");
    $("#clsname").html("");
    var date = $(this).data('attendancedate');
    var dateat = $(this).data('attenddate');
    var classid = $("#chooseclass").val();
    var studentid = $(this).data('stdid');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/AttendanceReport/subject", 
        data: {"classid":classid, "studentid":studentid, "atdate":date, _csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
            if (result) 
            {
                console.log(result);
                var subjects = result.subjects;
                var subjectnames = subjects.split(",");
                var subattendnc = result.subattendnc;
                var attendance = subattendnc.split(",");
                $.each(subjectnames, function(i, item) {
                    //console.log(item);
                    $("#subjectnames").append("<th>"+item+"</th>");
                    $("#subjectattendance").append("<td>"+attendance[i]+"</td>");
                });
                $("#subattendance").modal("show");
                $("#attndncdate").html("("+dateat+")");
                $("#studname").html(result.studentname);
                $("#clsname").html("("+result.classname+")");
                
            }
        }
        });
});

 
 
/* Add knowledge community */


$("#addcommunityform").submit(function(e)
{
    $(".addknowledgebtn").text(saving);        
    e.preventDefault();
    $("#addknowledgebtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#addknowldgeerror").html(errorocc) ;
            $("#addknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addknowledgebtn").prop("disabled", false);
            $(".addknowledgebtn").text(savescript);  
        },
        success: function(response)
        {
            $("#addknowledgebtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#addknowldgesuccess").html(contentadd) ;
                $("#addknowldgesuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".addknowledgebtn").text(savescript);  
                //setTimeout(function(){ location.href = baseurl +"/knowledgeCenter/community" ;  }, 1000);
                setTimeout(function(){ location.reload(); }, 1000);
            }
            else
            {
                $("#addknowldgeerror").html(response.result) ;
                $("#addknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
                $(".addknowledgebtn").text(savescript);
            }
        } 
    });     
    return false;
});

$("#adddataform").submit(function(e)
{
    $(".adddatabtn").text(saving);        
    e.preventDefault();
    $("#adddatabtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#adddataerror").html(errorocc) ;
            $("#adddataerror").fadeIn().delay('5000').fadeOut('slow');
            $("#adddatabtn").prop("disabled", false);
            $(".adddatabtn").text(savescript);  
        },
        success: function(response)
        {
            $("#adddatabtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#adddatasuccess").html(contentadd) ;
                $("#adddatasuccess").fadeIn().delay('5000').fadeOut('slow');
                $(".adddatabtn").text(savescript);  
                setTimeout(function(){ location.reload(); }, 1000);
            }
            else
            {
                $("#adddataerror").html(response.result) ;
                $("#adddataerror").fadeIn().delay('5000').fadeOut('slow');
                $(".adddatabtn").text(savescript);
            }
        } 
    });     
    return false;
});

$("#editdataform").submit(function(e)
{
    $(".editdatabtn").text(updating);        
    e.preventDefault();
    $("#editdatabtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#editdataerror").html(errorocc) ;
            $("#editdataerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editdatabtn").prop("disabled", false);
            $(".editdatabtn").text(updatescript);  
        },
        success: function(response)
        {
            $("#editdatabtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#editdatasuccess").html(contentupd) ;
                $("#editdatasuccess").fadeIn().delay('5000').fadeOut('slow');
                $(".editdatabtn").text(updatescript);  
                setTimeout(function(){ location.reload(); }, 1000);
            }
            else
            {
                $("#editdataerror").html(response.result) ;
                $("#editdataerror").fadeIn().delay('5000').fadeOut('slow');
                $(".editdatabtn").text(updatescript);
            }
        } 
    });     
    return false;
});


$("#editcommunityform").submit(function(e)
{
    //alert("hello");
    $(".editknowledgebtn").text(updating);        
    e.preventDefault();
    $("#editknowledgebtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#editknowldgeerror").html(errorocc) ;
            $("#editknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editknowledgebtn").prop("disabled", false);
            $(".editknowledgebtn").text(updatescript);  
        },
        success: function(response)
        {
            $("#editknowledgebtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#editknowldgesuccess").html(knowcommupd) ;
                $("#editknowldgesuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".editknowledgebtn").text(updatescript);  
                setTimeout(function(){ location.reload() ;  }, 1000);
            }
            else
            {
                $("#editknowldgeerror").html(response.result) ;
                $("#editknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
                $(".editknowledgebtn").text(updatescript);
            }
        } 
    });     
    return false;
});

/*******************kinder activities ************/

$("#addactivityform").submit(function(e)
{
    $(".addactivitybtn").text(saving);        
    e.preventDefault();
    $("#addactivitybtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#addactivityerror").html(errorocc) ;
            $("#addactivityerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addactivitybtn").prop("disabled", false);
            $(".addactivitybtn").text(savescript);  
        },
        success: function(response)
        {
            $("#addactivitybtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#addactivitysuccess").html(dataadd) ;
                $("#addactivitysuccess").fadeIn().delay('5000').fadeOut('slow');
                $(".addactivitybtn").text(savescript);  
                setTimeout(function(){ location.reload(); }, 1000);
            }
            else
            {
                $("#addactivityerror").html(response.result) ;
                $("#addactivityerror").fadeIn().delay('5000').fadeOut('slow');
                $(".addactivitybtn").text(savescript);
            }
        } 
    });     
    return false;
});

$('#viewdiscovery').on("click",".editdiscovery",function()
{
    $('#coverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/Kindergarten/editdiscover", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $("#efile_type").select2().val(result[0]['file_type']).trigger('change.select2');
            $("#eclass").select2().val(result[0]['class_id']).trigger('change.select2');
            $('#etitle').val(result[0]['title']);  
            $('#ekid').val(result[0]['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }else if(result[0]['video_type'] == "custom upload")
                {
                    $('#efileupload').val(result[0]['links']);
                    $('#file_name').html(result[0]['links']);
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result[0]['image']);
            $('#edesc').val(result[0]['description']); 
            if(result[0]['image'] != null && result[0]['image'] != "")
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result[0]['image']+"' width='35px' height='30px'>"); 
            }
            getsubcls(result[0]['class_id'], result[0]['subject_id']);
            kindersubjctcls(result[0]['class_id'], result[0]['subject_id']);
          }
        }
    });
});

function kindersubjctcls(val, subid)
{
    $("#ecls_sub").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/SchoolLibrary/getsubjectskinder',
            data:{'classId':val, 'subid' : subid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html)
                {    
                    $("#ecls_sub").html(html);
                }
          
            }

        });
    }
}

$("#editactivityform").submit(function(e)
{
    $(".editactivitybtn").text(saving);        
    e.preventDefault();
    $("#editactivitybtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#editactivityerror").html(errorocc) ;
            $("#editactivityerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editactivitybtn").prop("disabled", false);
            $(".editactivitybtn").text(savescript);  
        },
        success: function(response)
        {
            $("#editactivitybtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#editactivitysuccess").html(dataupd) ;
                $("#editactivitysuccess").fadeIn().delay('5000').fadeOut('slow');
                $(".editactivitybtn").text(savescript);  
                setTimeout(function(){ location.reload(); }, 1000);
            }
            else
            {
                $("#editactivityerror").html(response.result) ;
                $("#editactivityerror").fadeIn().delay('5000').fadeOut('slow');
                $(".editactivitybtn").text(savescript);
            }
        } 
    });     
    return false;
});

$("#editstateexamform").submit(function(e)
{
    $(".editknowledgebtn").text(updating);        
    e.preventDefault();
    $("#editknowledgebtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#editknowldgeerror").html(errorocc) ;
            $("#editknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editknowledgebtn").prop("disabled", false);
            $(".editknowledgebtn").text(updatescript);  
        },
        success: function(response)
        {
            $("#editknowledgebtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#editknowldgesuccess").html(dataupd) ;
                $("#editknowldgesuccess").fadeIn().delay('5000').fadeOut('slow');
                $(".editknowledgebtn").text(updatescript);  
               // setTimeout(function(){ location.reload(); }, 1000);
            }
            else
            {
                $("#editknowldgeerror").html(response.result) ;
                $("#editknowldgeerror").fadeIn().delay('5000').fadeOut('slow');
                $(".editknowledgebtn").text(updatescript);
            }
        } 
    });     
    return false;
});


/*************Community Reply************/

$(document).on('click', '.appcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#appcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/SchoolkinderApplication/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tchrappcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrappcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/teacherkinderApplication/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studappcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studappcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/KinderApplications/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

/*************Community Reply************/

$(document).on('click', '.comm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.secomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#stexmcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/stateexmreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.sclsecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#sclstexmcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Schoolknowledge/stateexmreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studsecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studstexmcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Studentknowledge/stateexmreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tchrsecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrstexmcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherknowledge/stateexmreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});


$(document).on('click', '.ntcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#newtechnocomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/technologiesreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});
$(document).on('click', '.sclntcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#sclnewtechnocomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Schoolknowledge/technologiesreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});
$(document).on('click', '.studntcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studnewtechnocomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Studentknowledge/technologiesreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tchrntcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrnewtechnocomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherknowledge/technologiesreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.mlcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#machinelearncomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/machinelearnreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.sclmlcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#sclmachinelearncomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Schoolknowledge/machinelearnreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tchrmlcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrmachinelearncomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherknowledge/machinelearnreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studmlcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studmachinelearncomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Studentknowledge/machinelearnreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.sccomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#scholarcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/scholarreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.sclsccomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#sclscholarcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Schoolknowledge/scholarreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tchrsccomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrscholarcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherknowledge/scholarreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studsccomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studscholarcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Studentknowledge/scholarreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});


$(document).on('click', '.mencomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#mentorshipcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/mentorreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.sclmencomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#sclmentorshipcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Schoolknowledge/mentorreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tchrmencomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrmentorshipcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherknowledge/mentorreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studmencomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studmentorshipcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Studentknowledge/mentorreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});


$(document).on('click', '.incomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#internshipcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/internreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.sclincomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#sclinternshipcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Schoolknowledge/internreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tchrincomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrinternshipcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherknowledge/internreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studincomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studinternshipcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Studentknowledge/internreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});



$(document).on('click', '.iecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#intensivecomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/intensereplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});
$(document).on('click', '.scliecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#sclintensivecomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Schoolknowledge/intensereplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tchriecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrintensivecomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherknowledge/intensereplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studiecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studintensivecomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Studentknowledge/intensereplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.discovercomm_reply-btn', function(){
   
   //alert("hi");
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#discovercomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Kindergarten/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(repcommpost) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.discoverstudcomm_reply-btn', function(){
   
   //alert("hi");
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#discoverstudcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Kinderdashboard/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(repcommpost) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.discovertchrcomm_reply-btn', function(){
   
   //alert("hi");
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#discovertchrcomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherkindergarten/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(repcommpost) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.sclhiwcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#sclhowworkscomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Schoolknowledge/howworksreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                console.log(response);
            } 
        });
    });
});

$(document).on('click', '.tchrhiwcomm_reply-btn', function(){
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrhowworkscomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherknowledge/howworksreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studhiwcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studhowworkscomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Studentknowledge/howworksreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});
$(document).on('click', '.hiwcomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#howworkscomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/howworksreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.lecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#leadercomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/knowledgeCenter/leadershipreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.scllecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#sclleadercomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Schoolknowledge/leadershipreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tchrlecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#tchrleadercomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Teacherknowledge/leadershipreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studlecomm_reply-btn', function(){
   
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#studleadercomment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        $.ajax({
            url: baseurl +"/Studentknowledge/leadershipreplycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload();  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});


$(document).on('click', '.schoollib_reply-btn', function(){
   // alert("hi");
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        var skul_id = $("#sclid").val();
        //alert(skul_id);
        var mdkid = $("#mdkid").val();
        $.ajax({
            url: baseurl +"/schoolLibrary/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                skul_id : skul_id
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
		    error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                console.log(response);
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload(true);  }, 1000);
                }
                
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.studentlib_reply-btn', function(){
   // alert("hi");
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        var skul_id = $("#sclid").val();
        //alert(skul_id);
        var mdkid = $("#mdkid").val();
        $.ajax({
            url: baseurl +"/classLibrary/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid, 
                skul_id : skul_id
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                console.log(response);
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload(true);  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.teacherlib_reply-btn', function(){
   // alert("hi");
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        var skul_id = $("#sclid").val();
        //alert(skul_id);
        var mdkid = $("#mdkid").val();
        $.ajax({
            url: baseurl +"/teacherLibrary/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                skul_id : skul_id
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                console.log(response);
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload(true);  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.teacherpost_reply-btn', function(){
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.tpsubmit-reply', function()
    {
        
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var gradeid = $("#gradeid").val();
        var subid = $("#subid").val();
        //alert("hie");
        $.ajax({
            url: baseurl +"/teacherPost/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                gradeid : gradeid, 
                subid : subid
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                //alert("hwdqwdie");
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('15000').fadeOut('slow');
            },
            success: function(response)
            {
                //alert("hiewdwe");
                console.log(response);
                if(response.result == "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ window.location.reload();  }, 1000);
                    //setTimeout(function(){ location.href = baseurl +"/teacherPost?subid="+subid+"&gradeid="+gradeid ;  }, 1000);
                }
                else
                {
                    //alert("hwdwefie");
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('15000').fadeOut('slow');
                }
            } 
        });
    });
});




$(document).on('click', '.school_reply-btn', function(){
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        var skul_id = $("#skul_id").val();
        var mdkid = $("#mdkid").val();
        $.ajax({
            url: baseurl +"/schoolknowledge/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
               // _csrfToken : refscrf,
                r_kid : r_kid, 
                skul_id : skul_id
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                console.log(response);
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload(true);  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});


$(document).on('click', '.student_reply-btn', function(){
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        //var skul_id = $("#skul_id").val();
        var mdkid = $("#mdkid").val();
        //alert(mdkid);
        $.ajax({
            url: baseurl +"/studentknowledge/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    location.reload(true);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});


$(document).on('click', '.teacher_reply-btn', function(){
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    $(document).on('click', '.submit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        //var skul_id = $("#skul_id").val();
        var mdkid = $("#mdkid").val();
        $.ajax({
            url: baseurl +"/teacherknowledge/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                //_csrfToken : refscrf,
                r_kid : r_kid
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                console.log(response);
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload(true);  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$("#youmecontactusfom").submit(function(e)
{
    e.preventDefault();
    $("#youmecontactbtn").prop("disabled", true);
    $("#youmecontactbtn").text("Sending...");
    $(this).ajaxSubmit({
        
        error: function(){
            $("#youmecontcterror1").html(errorocc) ;
            $("#youmecontcterror1").fadeIn().delay('5000').fadeOut('slow');
            $("#youmecontactbtn").prop("disabled", false);
            $("#youmecontactbtn").text("Send");
        },
        success: function(response)
        {
            $("#youmecontactbtn").prop("disabled", false);
            $("#youmecontactbtn").text("Send");
            if(response.result === "success" ){ 
                $("#youmecontctsuccess1").html(msgsent) ;
                $("#youmecontctsuccess1").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.reload(true)
                }, 1000);
            }
            else{
                $("#youmecontcterror1").html(response.result) ;
                $("#youmecontcterror1").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});
$("#marketprocontactform").submit(function(e)
{
    e.preventDefault();
    $("#marketplcecontctbtn").prop("disabled", true);
    $("#marketplcecontctbtn").text("Sending...");
    $(this).ajaxSubmit({
        
        error: function(){
            $("#marketplcecontcterror").html(errorocc) ;
            $("#marketplcecontcterror").fadeIn().delay('5000').fadeOut('slow');
            $("#marketplcecontctbtn").prop("disabled", false);
            $("#marketplcecontctbtn").text("Send");
        },
        success: function(response)
        {
            $("#marketplcecontctbtn").prop("disabled", false);
            $("#marketplcecontctbtn").text("Send");
            if(response.result === "success" ){ 
                $("#marketplcecontctsuccess").html(thankumarket) ;
                $("#marketplcecontctsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.reload(true)
                }, 1000);
            }
            else{
                $("#marketplcecontcterror").html(response.result) ;
                $("#marketplcecontcterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#mentorcontactform").submit(function(e)
{
    e.preventDefault();
    $("#mentorcontctbtn").prop("disabled", true);
    $("#mentorcontctbtn").text("Sending...");
    $(this).ajaxSubmit({
        
        error: function(){
            $("#mentorcontcterror").html(errorocc) ;
            $("#mentorcontcterror").fadeIn().delay('5000').fadeOut('slow');
            $("#mentorcontctbtn").prop("disabled", false);
            $("#mentorcontctbtn").text("Send");
        },
        success: function(response)
        {
            $("#mentorcontctbtn").prop("disabled", false);
            $("#mentorcontctbtn").text("Send");
            if(response.result === "success" ){ 
                $("#mentorcontctsuccess").html(thankumarket) ;
                $("#mentorcontctsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.reload(true)
                }, 1000);
            }
            else{
                $("#mentorcontcterror").html(response.result) ;
                $("#mentorcontcterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#scholarcontactform").submit(function(e)
{
    e.preventDefault();
    $("#scholarcontctbtn").prop("disabled", true);
    $("#scholarcontctbtn").text("Sending...");
    $(this).ajaxSubmit({
        
        error: function(){
            $("#scholarcontcterror").html(errorocc) ;
            $("#scholarcontcterror").fadeIn().delay('5000').fadeOut('slow');
            $("#scholarcontctbtn").prop("disabled", false);
            $("#scholarcontctbtn").text("Send");
        },
        success: function(response)
        {
            $("#scholarcontctbtn").prop("disabled", false);
            $("#scholarcontctbtn").text("Send");
            if(response.result === "success" ){ 
                $("#scholarcontctsuccess").html(thankumarket) ;
                $("#scholarcontctsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.reload(true)
                }, 1000);
            }
            else{
                $("#scholarcontcterror").html(response.result) ;
                $("#scholarcontcterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#interncontactform").submit(function(e)
{
    e.preventDefault();
    $("#interncontctbtn").prop("disabled", true);
    $("#interncontctbtn").text("Sending...");
    $(this).ajaxSubmit({
        
        error: function(){
            $("#interncontcterror").html(errorocc) ;
            $("#interncontcterror").fadeIn().delay('5000').fadeOut('slow');
            $("#interncontctbtn").prop("disabled", false);
            $("#interncontctbtn").text("Send");
        },
        success: function(response)
        {
            $("#interncontctbtn").prop("disabled", false);
            $("#interncontctbtn").text("Send");
            if(response.result === "success" ){ 
                $("#interncontctsuccess").html(thankumarket) ;
                $("#interncontctsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.reload(true)
                }, 1000);
            }
            else{
                $("#interncontcterror").html(response.result) ;
                $("#interncontcterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#leadercontactform").submit(function(e)
{
    e.preventDefault();
    $("#leadercontctbtn").prop("disabled", true);
    $("#leadercontctbtn").text("Sending...");
    $(this).ajaxSubmit({
        
        error: function(){
            $("#leadercontcterror").html(errorocc) ;
            $("#leadercontcterror").fadeIn().delay('5000').fadeOut('slow');
            $("#leadercontctbtn").prop("disabled", false);
            $("#leadercontctbtn").text("Send");
        },
        success: function(response)
        {
            $("#leadercontctbtn").prop("disabled", false);
            $("#leadercontctbtn").text("Send");
            if(response.result === "success" ){ 
                $("#leadercontctsuccess").html(thankumarket) ;
                $("#leadercontctsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.reload(true)
                }, 1000);
            }
            else{
                $("#leadercontcterror").html(response.result) ;
                $("#leadercontcterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#localyoumecontactusfom").submit(function(e)
{
    e.preventDefault();
    $("#lyoumecontactbtn").prop("disabled", true);
    $("#lyoumecontactbtn").text("Sending...");
    $(this).ajaxSubmit({
        
        error: function(){
            $("#youmecontcterror").html(errorocc) ;
            $("#youmecontcterror").fadeIn().delay('5000').fadeOut('slow');
            $("#lyoumecontactbtn").prop("disabled", false);
            $("#lyoumecontactbtn").text("Sent");
        },
        success: function(response)
        {
            $("#lyoumecontactbtn").prop("disabled", false);
            $("#lyoumecontactbtn").text("Sent");
            if(response.result === "success" ){ 
                $("#youmecontctsuccess").html(msgsent) ;
                $("#youmecontctsuccess").fadeIn().delay('5000').fadeOut('slow');
        
                setTimeout(function(){ 
                    location.reload(true)
                }, 1000);
            }
            else{
                $("#youmecontcterror").html(response.result) ;
                $("#youmecontcterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});



  $(function() {
    $('.daterange').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear'
      }
    });
  });

    $('.timepicker').mdtimepicker();
    $('.timepicker2').mdtimepicker();
    $('.timepicker3').mdtimepicker();
    $('.timepicker4').mdtimepicker();
    // $('.logouttime').timepicker();
 
$('#multiselect3-all').multiselect({
    includeSelectAllOption: true,
});

$('[data-toggle="tooltip"]').tooltip();


$(document).ready(function(){
     $.fn.datepicker.defaults.language = 'fr';
});

$(".datepicker").datepicker(
{
  autoclose: true,
  dateFormat: 'dd-mm-YYYY',
  startDate : new Date(),
   language:datecalndr
});


 $('#datetimepicker1').datetimepicker({
     //format: 'DD-MM-YYYY hh:mm A',
        format: 'DD-MM-YYYY HH:mm',
        minDate:new Date(),
        locale: datecalndr,
 });
 
$('#datetimepicker2').datetimepicker({
    format: 'DD-MM-YYYY HH:mm',
    minDate:new Date()
});
 
 $('#datetimepicker_year').datetimepicker({
     format: 'YYYY'
 });
 
  $('#datetimepicker_year2').datetimepicker({
     format: 'YYYY'
 });
 
$(document).ready(function() {

    $('#datetimepicker_year3').datetimepicker({
        format: 'YYYY'
    }).on('dp.change', function (event) {
        
    });
});

 $('#edatetimepicker1').datetimepicker({
     format: 'DD-MM-YYYY HH:mm',
     minDate:new Date()
 });
 $('#edatetimepicker2').datetimepicker({
    format: 'DD-MM-YYYY HH:mm',
    minDate:new Date()
 });

 $('#sfdatetimepicker1').datetimepicker({
     format: 'DD-MM-YYYY HH:mm',
     minDate:new Date()
 });
 
  $('#stdatetimepicker2').datetimepicker({
    format: 'DD-MM-YYYY HH:mm',
    minDate:new Date()
 });
 
$(".backdatepicker").datepicker({
  autoclose: true,
  minDate: 0 ,
  dateFormat: 'dd-mm-YYYY',
   language:datecalndr
  });
  
$(".fordatepicker").datepicker({
    autoclose: true,
    dateFormat: 'dd-mm-YYYY',
    minDate: new Date(),
    startDate: new Date(),
     language:datecalndr
    
});
  
$(".dobirthdatepicker").datepicker({
  
  autoclose: true,
  dateFormat: 'dd-mm-YYYY',
  endDate: "currentDate",
    maxDate: currentDate,
    language:datecalndr
  });

$(".commondatepicker").datepicker({
  autoclose: true,
   language:datecalndr
});


/* Edit profile Form */


$('.editbtn').click(function(){
     $(".editbtn").text(updating);        
 });


$("#editprofileform").submit(function(e){
    e.preventDefault();
    
  $("#editbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#usererror").html(errorocc) ;
        $("#usererror").fadeIn().delay('5000').fadeOut('slow');
  $("#editbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#editbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#usersuccess").html(adminup) ;
        $("#usersuccess").fadeIn();
    $(".editbtn").text(updatescript);
        setTimeout(function(){ location.reload() ;  }, 1000);
                
            }

      else if(response.result === "empty" ){
          $("#usererror").html(filldetails) ;
          $("#usererror").fadeIn().delay('5000').fadeOut('slow');
    $(".editbtn").text(updatescript);   
  }
  else if(response.result === "exist" ){
          $("#usererror").html(useremailalrex) ;
          $("#usererror").fadeIn().delay('5000').fadeOut('slow');
    $(".editbtn").text(updatescript);
  }
   
  else{
    $("#usererror").html(response.result) ;
    $("#usererror").fadeIn().delay('5000').fadeOut('slow');
    $(".editbtn").text(updatescript);
  }
    } 
  });     
  return false;
  
  });

  /* END */
/* Edit School admin User Form */


$('.userseditbtn').click(function(){
     $(".userseditbtn").text(updating);        
 });


$("#edituserprofileform").submit(function(e){
    e.preventDefault();
    
  $("#userseditbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#userserror").html(errorocc) ;
        $("#userserror").fadeIn().delay('5000').fadeOut('slow');
  $("#userseditbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#userseditbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#userssuccess").html(scladmup) ;
        $("#userssuccess").fadeIn();
      $(".userseditbtn").text(updatescript);  
        setTimeout(function(){ location.reload() ;  }, 1000);
                   
            }
            else if(response.result === "exist" ){
        $("#usererror").html(useremailalrex) ;
        $("#usererror").fadeIn().delay('5000').fadeOut('slow');
    $(".userseditbtn").text(updatescript);
      }
      else if(response.result === "empty" ){
          $("#userserror").html(filldetails) ;
          $("#userserror").fadeIn().delay('5000').fadeOut('slow');
    $(".userseditbtn").text(updatescript);
  }
   
  else{
    $("#userserror").html(response.result) ;
    $("#userserror").fadeIn().delay('5000').fadeOut('slow');
    $(".userseditbtn").text(updatescript);
  }
    } 
  });     
  return false;
  
  });

  /* END */
/* Add Teacher Form */


$("#addtchrform").submit(function(e)
{
    e.preventDefault();
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var stringLength = 30;
  
    function pickRandom() 
    {
        return possible[Math.floor(Math.random() * possible.length)];
    }
  
    var randomString = Array.apply(null, Array(stringLength)).map(pickRandom).join('');
    var link = "meet.jit.si/"+randomString;
    
    $("#meeting_link").val(link);
    $("#addtchrbtn").prop("disabled", true);
    $("#addtchrbtn").text(saving);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#tchrerror").html(errorocc) ;
            $("#tchrerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addtchrbtn").prop("disabled", false);
            $("#addtchrbtn").text(savescript);
        },
        success: function(response)
        {
            $("#addtchrbtn").prop("disabled", false);
            $("#addtchrbtn").text(savescript);
            if(response.result === "success" )
            { 
                $("#tchrsuccess").html(tchradd) ;
                $("#tchrsuccess").fadeIn();
                setTimeout(function(){ location.href = baseurl +"/teachers/" ;  }, 1000);
            }
            else if(response.result === "empty" )
            {
                $("#tchrerror").html(filldetails) ;
                $("#tchrerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "activity" )
            {
                $("#tchrerror").html(activityadd) ;
                $("#tchrerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#tchrerror").html(response.result) ;
                $("#tchrerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;

});

  /* END */
/* Edit/Update Teacher Form */


$("#edittchrform").submit(function(e){
    e.preventDefault();
  $("#edittchrbtn").text('Updating..');    
  $("#edittchrbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#tchrerror").html(errorocc) ;
        $("#tchrerror").fadeIn().delay('5000').fadeOut('slow');
  $("#edittchrbtn").prop("disabled", false);
 $("#edittchrbtn").text(updatescript);   
    },
    success: function(response)
    {
  $("#edittchrbtn").prop("disabled", false);
 $("#edittchrbtn").text(updatescript);   
      if(response.result === "success" ){ 
        $("#tchrsuccess").html(tchrupd) ;
        $("#tchrsuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/teachers/" ;  }, 1000);
                  
            }
        
      else if(response.result === "empty" ){
          $("#tchrerror").html(filldetails) ;
          $("#tchrerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#tchrerror").html(activityadd) ;
          $("#tchrerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#tchrerror").html(response.result) ;
    $("#tchrerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

  /* END */ 
/* Multiselect Role */

 $("#emprolename").select2({
      placeholder: " Choose Role",
      allowClear: true
  });
  $("#status").select2({
      placeholder: " Choose Status",
      allowClear: true
  });
  $("#couponamt").select2({
      placeholder: " Choose Amount",
      allowClear: true
  });
  $("#couponid").select2({
      placeholder: " Choose Coupon",
      allowClear: true
  });
  $(".chosetitle").select2({
      placeholder: " Choose Title",
      allowClear: true
  });
  $("#grades").select2({
      placeholder: chosegrade,
      allowClear: true
  });
  $(".chgrades").select2({
      placeholder: chsgrades,
      allowClear: true
  });
  $(".chsections").select2({
      placeholder: chssectn,
      allowClear: true
  });
  $("#subjects").select2({
      placeholder: chosesubject,
      allowClear: true
  });
  $("#gender").select2({
      placeholder: chosegender,
      allowClear: true
  });
  $("#feehead_rec").select2({
      placeholder: " Choose Role",
      allowClear: true
  });
   $("#classdef").select2({
      placeholder: choseclass,
      allowClear: true
  });
  $("#studentdef").select2({
      placeholder: chosestud,
      allowClear: true
  });
  $("#attclass").select2({
      placeholder: choseclass,
      allowClear: true
  });
   $("#proclass").select2({
      placeholder: choseclass,
      allowClear: true
  });
   $("#studentProm").select2({
      placeholder: chosestud,
      allowClear: true
  });
  $(".studentchose").select2({
      placeholder: chosestud,
      allowClear: true
  });
   $("#promclass").select2({
      placeholder: choseclass,
      allowClear: true
  });
   $("#selclass").select2({
      placeholder: choseclass,
      allowClear: true
  });
  $("#sibl_in_scl").select2({
      placeholder: chosestud,
      allowClear: true
  });
   $("#sibl_in_scl1").select2({
      placeholder: chosestud,
      allowClear: true
  });
   $("#sibl_in_scl2").select2({
      placeholder: chosestud,
      allowClear: true
  });
  $("#stopage_route").select2({
      placeholder: " Choose Route",
      allowClear: true
  });
  $(".community_filter").select2({
      placeholder: chosevalu,
      allowClear: true
  });
  $(".attend_filter").select2({
      placeholder: chosevalu,
      allowClear: true
  });
   $(".sections").select2({
      placeholder: chosesectn,
      allowClear: true
  });
  $("#session").select2({
      placeholder: chosesess,
      allowClear: true
  });
  $("#afrequency").select2({
      placeholder: chosefreq,
      allowClear: true
  });
  $("#aclass").select2({
      placeholder: choseclass,
      allowClear: true
  });
  $("#frequency").select2({
      placeholder: chosefreq,
      allowClear: true
  });
  $(".file_type").select2({
      placeholder: " Choose File Type",
      allowClear: true
  });
   $("#estopage_route").select2({
      placeholder: " Choose Route",
      allowClear: true
  });
   $("#in_name").select2({
      placeholder: " Choose Income Head",
      allowClear: true
  });
  $("#ein_name").select2({
      placeholder: " Choose Income Head",
      allowClear: true
  });
  $("#class_list").select2({
      placeholder: choseclass
  });
  $(".class_list").select2({
      placeholder: choseclass
  });
  
    $("#subject_list").select2({
      placeholder: chosesubject
  });
  $("#class_whole").select2({
      placeholder: choseclass,
      allowClear: true
  });
  $("#prefix").select2({
     // placeholder: " Choose Prefix",
      allowClear: true
  });
   $(".student_prefix").select2({
      placeholder: " Choose Prefix",
      allowClear: true
  });
 
/* End */
/* Import Teacher Form */

$("#addtchrcsvform").submit(function(e){
    e.preventDefault();
    
  $("#addtchrcsvbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#tchrcsverror").html(errorocc) ;
        $("#tchrcsverror").fadeIn().delay('5000').fadeOut('slow');
  $("#addtchrcsvbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#addtchrcsvbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#tchrcsvsuccess").html(tchrimport) ;
        $("#tchrcsvsuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/teachers/" ;  }, 1000);
                  
            }
        
      else if(response.result === "empty" ){
          $("#tchrcsverror").html(filldetails) ;
          $("#tchrcsverror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#tchrcsverror").html(activityadd) ;
          $("#tchrcsverror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#tchrcsverror").html(response.result) ;
    $("#tchrcsverror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

  /* END */


/* Employee status change and get listing  */


$(".chngstatus").select2({
      placeholder: choseone,
      allowClear: true
  });
/* END */
/* Student status change and get listing  */
$(".studentfilter").select2({
      placeholder: choseone,
      allowClear: true
  });

/* END */
/* Add Employee Form */
$('.addempbtn').click(function(){
     $(".addempbtn").text(saving);        
 });
/* Edit/Update Employee Form */
$('.editempbtn').click(function(){
     $(".editempbtn").text(updating);        
 });
$("#editempform").submit(function(e){
    e.preventDefault();
     $("#editempbtn").text('Updating..');  
  $("#editempbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#emperror").html(errorocc) ;
        $("#emperror").fadeIn().delay('5000').fadeOut('slow');
  $("#editempbtn").prop("disabled", false);
$("#editempbtn").text(updatescript);  
    },
    success: function(response)
    {
  $("#editempbtn").prop("disabled", false);
$("#editempbtn").text(updatescript);  
      if(response.result === "success" ){ 
        $("#empsuccess").html(tchrupd) ;
        $("#empsuccess").fadeIn();
    $(".editempbtn").text(updatescript);
        setTimeout(function(){ location.href = baseurl +"/teachers/" ;  }, 1000);
                  
            }
        
      else if(response.result === "empty" ){
          $("#emperror").html(filldetails) ;
          $("#emperror").fadeIn().delay('5000').fadeOut('slow');
    $(".editempbtn").text(updatescript);
  }
  else if(response.result === "exist" ){
          $("#emperror").html("A employee with this mobile number is already exist.") ;
          $("#emperror").fadeIn().delay('5000').fadeOut('slow');
    $(".editempbtn").text(updatescript);
  }
  else if(response.result === "mlength" ){
          $("#emperror").html(kindlyinsert) ;
          $("#phone").css({"border-color": "#ff0000", 
             "border-width":"2px", 
             "border-style":"solid"});
          $("#emperror").fadeIn().delay('5000').fadeOut('slow');
    $(".editempbtn").text(updatescript);
          
  }
else if(response.result === "number" ){
          $("#emperror").html(onlynumeric) ;
          $("#phone").css({"border-color": "#ff0000", 
             "border-width":"2px", 
             "border-style":"solid"});
          $("#emperror").fadeIn().delay('5000').fadeOut('slow');
    $(".editempbtn").text(updatescript); 
          
  }
    
  else if(response.result === "activity" ){
          $("#emperror").html(activityadd) ;
          $("#emperror").fadeIn().delay('5000').fadeOut('slow');
    $(".editempbtn").text(updatescript);
  }
  else{
    $("#emperror").html(response.result) ;
    $("#emperror").fadeIn().delay('5000').fadeOut('slow');  
    $(".editempbtn").text(updatescript);
  }
    } 
  });     
  return false;
  
  });

  /* END */ 




/* Import Employee Form */


$('.addempcsvbtn').click(function(){
     $(".addempcsvbtn").text('Importing...');        
 });


$("#addempcsvform").submit(function(e){
    e.preventDefault();
    
  $("#addempcsvbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#empcsverror").html(errorocc) ;
        $("#empcsverror").fadeIn().delay('5000').fadeOut('slow');
  $("#addempcsvbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#addempcsvbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#empcsvsuccess").html(tchrimport) ;
        $("#empcsvsuccess").fadeIn();
    $(".addempcsvbtn").text('Import');
        setTimeout(function(){ location.href = baseurl +"/employee/" ;  }, 1000);
                  
            }
        
      else if(response.result === "empty" ){
          $("#empcsverror").html(filldetails) ;
          $("#empcsverror").fadeIn().delay('5000').fadeOut('slow');
    $(".addempcsvbtn").text('Import');
  }
  else if(response.result === "activity" ){
          $("#empcsverror").html(activityadd) ;
          $("#empcsverror").fadeIn().delay('5000').fadeOut('slow');
    $(".addempcsvbtn").text('Import');
  }
  else{
    $("#empcsverror").html(response.result) ;
    $("#empcsverror").fadeIn().delay('5000').fadeOut('slow');
    $(".addempcsvbtn").text('Import');
  }
    } 
  });     
  return false;
  
  });

  /* END */



/*  Update Employee profile form sbumission */
  

$('.editempprfbtn').click(function(){
     $(".editempprfbtn").text(updating);        
 });


$("#editempprofileform").submit(function(e){
  e.preventDefault();
  
$("#editempprfbtn").prop("disabled", true);

 $(this).ajaxSubmit({
  error: function(){
      $("#emperror").html(errorocc) ;
      $("#emperror").fadeIn().delay('5000').fadeOut('slow');
      $("#editempprfbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#editempprfbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#empsuccess").html("Employee profile updated successfully.") ;
        $("#empsuccess").fadeIn();
    $(".editempprfbtn").text(updatescript);
        setTimeout(function(){ location.reload() ;  }, 1000);
          }
      else if(response.result === "empty" ){
        $("#emperror").html(filldetails) ;
        $("#emperror").fadeIn().delay('5000').fadeOut('slow');
    $(".editempprfbtn").text(updatescript);

      }
      else if(response.result === "exist" ){
        $("#emperror").html(tchremailal) ;
        $("#emperror").fadeIn().delay('5000').fadeOut('slow');
    $(".editempprfbtn").text(updatescript);

      }
      else{
        $("#emperror").html(response.result) ;
        $("#emperror").fadeIn().delay('5000').fadeOut('slow');
    $(".editempprfbtn").text(updatescript);

}
  } 
});     
return false;

});


$('.stateids').change(function(){
    
    var id = $(this).children("option:selected").val();
    var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/students/getcity", 
              data: {"id":id,_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result) 
                 {  
                    $('#state').val(id);
                    let dropdown = $('#city');
                    dropdown.empty();

                    dropdown.append('<option selected="true" disabled>Choose City</option>');
                    dropdown.prop('selectedIndex', 0);

                     $.each(result, function (key, entry) {
                      dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name));
                    })
                 }
                }
              });
});

/*  End */

/* Auto calculate age */
$(function() 
{
    
    $("#dobdatepicker").on("change",function()
    {
        var dob = $(this).val();
        //var newdate = dob.split("-").reverse().join("-");
        var newdate = dob.split("-");
        var ndate = newdate[1]+"/"+newdate[0]+"/"+newdate[2];
        //alert(ndate);
        var dat = getAge(ndate);
        $('#s_age').val(); 
        $('#s_age').val(dat);
    
        function getAge(dateString)
        {
            var now = new Date();
            var today = new Date(now.getYear(),now.getMonth(),now.getDate());

            var yearNow = now.getYear();
            var monthNow = now.getMonth();
            var dateNow = now.getDate();
            
            var dob = new Date(dateString.substring(6,10),dateString.substring(0,2)-1,dateString.substring(3,5));

            var yearDob = dob.getYear();
            var monthDob = dob.getMonth();
            var dateDob = dob.getDate();
            var age = {};
            var ageString = "";
            var yearString = "";
            var monthString = "";
            var dayString = "";

            yearAge = yearNow - yearDob;

            if (monthNow >= monthDob)
                var monthAge = monthNow - monthDob;
            else {
                yearAge--;
                var monthAge = 12 + monthNow -monthDob;
            }

            if (dateNow >= dateDob)
                var dateAge = dateNow - dateDob;
            else 
            {
                monthAge--;
                var dateAge = 31 + dateNow - dateDob;
                if (monthAge < 0) 
                {
                    monthAge = 11;
                    yearAge--;
                }
            }

            age = {
                years: yearAge,
                months: monthAge,
                days: dateAge
            };

            if ( age.years > 1 ) yearString = " years";
            else yearString = " year";
            if ( age.months> 1 ) monthString = " months";
            else monthString = " month";
            if ( age.days > 1 ) dayString = " days";
            else dayString = " day";

            if ( (age.years > 0) && (age.months > 0) && (age.days > 0) )
                ageString = age.years + yearString + " " + age.months + monthString + " " + age.days + dayString;
            else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
                ageString = age.years + yearString + " " + age.months + monthString +" " + age.days + dayString;
            else if ( (age.years > 0) && (age.months == 0) && (age.days == 0) )
                ageString = age.years + yearString + age.months + monthString + " " + age.days + dayString;
            else if ( (age.years > 0) && (age.months > 0) && (age.days == 0) )
                ageString = age.years + yearString + " " + age.months + monthString + " " + age.days + dayString;
            else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
                ageString = age.years + yearString + " " + age.months + monthString + " " + age.days + dayString;
            else if ( (age.years > 0) && (age.months == 0) && (age.days > 0) )
                ageString = age.years + yearString + " " + age.days + dayString;
            else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
                ageString = age.years + yearString + " " + age.months + monthString + " " + age.days + dayString;
            else ageString = "Oops! Could not calculate age!";
            
            return ageString;
        }
        
    });
});


/* End */








/* Get Sibling details in divs with live search */


// var refscrf = $("input[name='_csrfToken']").val();


function formatRepo (repo) {
  console.log(repo);
  if (repo.loading) {
    return repo.s_name;
  }

 var $container = $(
    "<div class='select2-result-repository clearfix'>" +
      "<div class='select2-result-repository__meta'>" +
        "<span class='select2-result-repository__s_name'></span>" +     
        "<span class='select2-result-repository__s_f_name'></span>" + 
        "<span class='select2-result-repository__s_m_name'></span>" + 
        "<span class='select2-result-repository__acc_no'></span>" + 
        "<span class='select2-result-repository__adm_no'></span>" + 
        "<span class='select2-result-repository__resi_add1'></span>" + 
        "<span class='select2-result-repository__class'></span>" + 
        "<span class='select2-result-repository__section'></span>" + 
        "<span class='select2-result-repository__session'></span>" + 
      "</div>" +
    "</div>"
  ); 



  $container.find(".select2-result-repository__s_name").text(repo.s_name);
 
 if(repo.s_f_name)
{
  $container.find(".select2-result-repository__s_f_name").text(" / " + repo.s_f_name);
}
  if(repo.s_m_name){

  $container.find(".select2-result-repository__s_m_name").text(" / " + repo.s_m_name);
}

  if(repo.acc_no){

  $container.find(".select2-result-repository__acc_no").text(" / " + repo.acc_no );
}

  if(repo.adm_no){

  $container.find(".select2-result-repository__adm_no").text(" / " + repo.adm_no );
}

  if(repo.resi_add1){

  $container.find(".select2-result-repository__resi_add1").text(" / " + repo.resi_add1 );
}

if(repo.class){

  if(repo.class.c_name){

  $container.find(".select2-result-repository__class").text(" / " + repo.class.c_name);
 }

  if(repo.class.c_section){

  $container.find(".select2-result-repository__section").text(" / " + repo.class.c_section);
 }
}

  if(repo.c_sess_name){

  $container.find(".select2-result-repository__session").text(" / " + repo.c_sess_name);
}

  return $container;
}

function selectrepo (repo) {

  if(repo.s_f_name)
  {
    $('#s_f_name').val(repo.s_f_name);
  }
  if(repo.s_m_name)
  {
    $('#s_m_name').val(repo.s_m_name);
  }
  if(repo.acc_no)
  {
    $('#stnd_acc_no').val(repo.acc_no);
  }
  return repo.s_name || repo.s_name;
}


/* End */




/* Get Student roll number from Students Controller */

if((controller=="students") && (actionpage=="add") || (controller=="students") && (actionpage=="edit") ) {

$('#class').change(function(){
    
    var id = $(this).children("option:selected").val();

    var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/students/getrollno", 
              data: {"id":id,_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result) 
                 {  
                    $('#roll_no').val(result.roll_no);
                   
                 }
                }
              });
});


}


/*End*/
$("#updateadmform").submit(function(e)
{
    e.preventDefault();
    $("#addstdntbtn").prop("disabled", true);
    $(this).ajaxSubmit({
        error: function(){
            $("#stdnterror").html(errorocc) ;
            $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
            $("#addstdntbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addstdntbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#stdntsuccess").html(admissadd) ;
                $("#stdntsuccess").fadeIn().delay('5000').fadeOut('slow');
                
                var stuid = response.studid;
                
                setTimeout(function(){ location.href = baseurl +"/readmissions/print/"+stuid ;  }, 1000);
            }
           
            else if(response.result === "exist" )
            {
                $("#stdnterror").html(studentalremail) ;
                $("#email").css({"border-color": "#ff0000", 
                 "border-width":"2px", 
                 "border-style":"solid"});
                $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addstdntbtn").text(savescript); 
            }
           
            else if(response.result === "mlength" ){
                $("#stdnterror").html(kindlyinsert) ;
                $("#mobile_for_sms").css({"border-color": "#ff0000", 
                 "border-width":"2px", 
                 "border-style":"solid"});
                $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addstdntbtn").text(savescript); 
            }
            else if(response.result === "number" ){
                $("#stdnterror").html(onlynumeric) ;
                $("#mobile_for_sms").css({"border-color": "#ff0000", 
                 "border-width":"2px", 
                 "border-style":"solid"});
                $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addstdntbtn").text(savescript); 
            }
            else
            {
                $("#stdnterror").html(response.result) ;
                $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');    
                $(".addstdntbtn").text(savescript); 
            }
        } 
    });     
    return false;
});
$("#addadmform").submit(function(e)
{
    e.preventDefault();
    $("#addstdntbtn").prop("disabled", true);
    $(this).ajaxSubmit({
        error: function(){
            $("#stdnterror").html(errorocc) ;
            $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
            $("#addstdntbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#addstdntbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#stdntsuccess").html(admissadd) ;
                $("#stdntsuccess").fadeIn().delay('5000').fadeOut('slow');
                
                var stuid = response.studid;
                
                setTimeout(function(){ location.href = baseurl +"/Admissions/print/"+stuid ;  }, 1000);
            }
            else if(response.result === "exist" )
            {
                $("#stdnterror").html(studentalremail) ;
                $("#email").css({"border-color": "#ff0000", 
                 "border-width":"2px", 
                 "border-style":"solid"});
                $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addstdntbtn").text(savescript); 
            }
            
            else if(response.result === "mlength" ){
                $("#stdnterror").html(kindlyinsert) ;
                $("#mobile_for_sms").css({"border-color": "#ff0000", 
                 "border-width":"2px", 
                 "border-style":"solid"});
                $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addstdntbtn").text(savescript); 
            }
            else if(response.result === "number" ){
                $("#stdnterror").html(onlynumeric) ;
                $("#mobile_for_sms").css({"border-color": "#ff0000", 
                 "border-width":"2px", 
                 "border-style":"solid"});
                $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addstdntbtn").text(savescript); 
            }
            else
            {
                $("#stdnterror").html(response.result) ;
                $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');    
                $(".addstdntbtn").text(savescript); 
            }
        } 
    });     
    return false;
});






/* Add Student Form */


$('.addstdntbtn').click(function(){
       
 });


$("#addstdntform").submit(function(e){
    e.preventDefault();
    $(".addstdntbtn").text(saving);      
  $("#addstdntbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#stdnterror").html(errorocc) ;
        $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
        $("#addstdntbtn").prop("disabled", false);
        
    },
    success: function(response)
    {
        $(".addstdntbtn").text(savescript);      
  $("#addstdntbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#stdntsuccess").html(studentadd) ;
        $("#stdntsuccess").fadeIn().delay('5000').fadeOut('slow');
    
    studentsadd();


        setTimeout(function(){ location.href = baseurl +"/students/" ;  }, 1000);
                  
            }
        
        else if(response.result === "exist" ){
          $("#stdnterror").html(studentalremail) ;
          $("#email").css({"border-color": "#ff0000", 
             "border-width":"2px", 
             "border-style":"solid"});
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
    $(".addstdntbtn").text(savescript); 
  }
  

  else if(response.result === "mlength" ){
          $("#stdnterror").html(kindlyinsert) ;
          $("#mobile_for_sms").css({"border-color": "#ff0000", 
             "border-width":"2px", 
             "border-style":"solid"});
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
    $(".addstdntbtn").text(savescript); 
          
  }
  else if(response.result === "number" ){
          $("#stdnterror").html(onlynumeric) ;
          $("#mobile_for_sms").css({"border-color": "#ff0000", 
             "border-width":"2px", 
             "border-style":"solid"});
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
    $(".addstdntbtn").text(savescript); 
  }
      else if(response.result === "empty" ){
          $("#stdnterror").html(filldetails) ;
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
    $(".addstdntbtn").text(savescript); 
  }
  else if(response.result === "activity" ){
          $("#stdnterror").html(activityadd) ;
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
    $(".addstdntbtn").text(savescript); 
  }
  else{
    $("#stdnterror").html(response.result) ;
    $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');    
    $(".addstdntbtn").text(savescript); 
  }
    } 
  });     
  return false;
  
  });



function studentsadd(){
var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/students/getadmno", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result) {
           $('#adm_no').val(); $('#adm_no').val(result.adm_no);
                   $('#stnd_acc_no').val(); $('#stnd_acc_no').val(result.adm_no);        
                 }
              }
          });
          
}




  /* END */





/* Select student  */

 $(".attstudent").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".attsection").select2({
      placeholder: choseone,
      allowClear: true
  });   

/* End */


  
 
 
  

/* Edit Student Form */


$("#editstdntform").submit(function(e){
    e.preventDefault();
    $("#editstdntbtn").text(updating);  
    $("#editstdntbtn").prop("disabled", true);

   
   $(this).ajaxSubmit({
    error: function(){
        $("#stdnterror").html(errorocc) ;
        $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
        $("#editstdntbtn").prop("disabled", false);
        $("#editstdntbtn").text(updatescript);  
    },
    success: function(response)
    {
  $("#editstdntbtn").prop("disabled", false);
    $("#editstdntbtn").text(updatescript);
  if(response.result === "success" ){ 
        $("#stdntsuccess").html(studentupd) ;
        $("#stdntsuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/students/" ;  }, 1000);
                  $("#editstdntbtn").text(updatescript);
          }
  else if(response.result === "exist" ){
          $("#stdnterror").html(studentalremail) ;
          $("#email").css({"border-color": "#ff0000", 
             "border-width":"2px", 
             "border-style":"solid"});
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
           $("#editstdntbtn").text(updatescript);
  }
  
  
  else if(response.result === "mlength" ){
          $("#stdnterror").html(kindlyinsert) ;
          $("#mobile_for_sms").css({"border-color": "#ff0000", 
             "border-width":"2px", 
             "border-style":"solid"});
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
           $("#editstdntbtn").text(updatescript);
          
  }
  else if(response.result === "number" ){
          $("#stdnterror").html(onlynumeric) ;
          $("#mobile_for_sms").css({"border-color": "#ff0000", 
             "border-width":"2px", 
             "border-style":"solid"});
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
  }
      else if(response.result === "empty" ){
          $("#stdnterror").html(filldetails) ;
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
           $("#editstdntbtn").text(updatescript);
  }
  else if(response.result === "activity" ){
          $("#stdnterror").html(activityadd) ;
          $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
           $("#editstdntbtn").text(updatescript);
  }
  else{
    $("#stdnterror").html(response.result) ;
    $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
     $("#editstdntbtn").text(updatescript);
  }
    } 
  });     
  return false;
  
  });

  /* END */




/* Import Student Form */


$("#addstdntcsvform").submit(function(e){
    e.preventDefault();
   $(".addstdntcsvbtn").text('Uploading...');       
  $("#addstdntcsvbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#stdntcsverror").html(errorocc) ;
        $("#stdntcsverror").fadeIn().delay('5000').fadeOut('slow');
  $("#addstdntcsvbtn").prop("disabled", false);
  $(".addstdntcsvbtn").text('Import');  

    },
    success: function(response)
    {
  $("#addstdntcsvbtn").prop("disabled", false);
$(".addstdntcsvbtn").text('Import');  
      if(response.result === "success" ){ 
        $("#stdntcsvsuccess").html(studentsimport) ;
        $("#stdntcsvsuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/students/" ;  }, 1000);
                  
            }
        
      else if(response.result === "empty" ){
          $("#stdntcsverror").html(filldetails) ;
          $("#stdntcsverror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#stdntcsverror").html(activityadd) ;
          $("#stdntcsverror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#stdntcsverror").html(response.result) ;
    $("#stdntcsverror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

  /* END */


/*  Update student profile form sbumission */
  

$("#editstdntprofileform").submit(function(e){
  e.preventDefault();
  
$("#editstdntprfbtn").prop("disabled", true);

 $(this).ajaxSubmit({
  error: function(){
      $("#stdnterror").html(errorocc) ;
      $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
      $("#editstdntprfbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#editstdntprfbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#stdntsuccess").html(profileupd) ;
        $("#stdntsuccess").fadeIn();
        setTimeout(function(){ location.reload() ;  }, 1000);
          }
      else if(response.result === "empty" ){
        $("#stdnterror").html(filldetails) ;
        $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "exist" ){
        $("#stdnterror").html(althisemail) ;
        $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#stdnterror").html(response.result) ;
        $("#stdnterror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

 $(".department").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".teacher").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".left").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".role").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".module").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".status").select2({
      placeholder: choseone,
      allowClear: true
  });
 $(".category").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".newcategory").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".class").select2({
      placeholder: choseclass,
      allowClear: true
  });

 $(".state").select2({
      placeholder: chosestate,
      allowClear: true
  });
$(".country").select2({
      placeholder: chosecountry,
      allowClear: true
  });

 $(".city").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".gender").select2({
      placeholder: chosegender,
      allowClear: true
  });
  
  $(".months").select2({
      placeholder: chosemnths,
      allowClear: true
  });
  
   $(".paymode").select2({
      placeholder: chosemodepay,
      allowClear: true
  });

 $(".discount").select2({
      placeholder: choseone,
      allowClear: true
  });

 $("#transport").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".boarder").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".a_route_no").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".d_route_no").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".stopage").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".stdntleft").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".sess_name").select2({
      placeholder: chosesess,
      allowClear: true
  });

 $(".acc_no").select2({
      placeholder: choseone,
      allowClear: true
  });

 $(".headname").select2({
      placeholder: chosefeedsc,
      allowClear: true
  });


/* Multiselect vehicle route */

 $("#route_no").select2({
      placeholder: " Select Route No.",
      allowClear: true
  });
  

/* End */



 



/* Edit School role form submission by school */

$('.editsorlbtn').click(function(){
     $(".editsorlbtn").text(updating);        
 });



/* Add Fees Form */


$("#addfeeform").submit(function(e){
    e.preventDefault();
    
  $("#addfeebtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#feeerror").html(errorocc) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  $("#addfeebtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#addfeebtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#feesuccess").html(feeadd) ;
        $("#feesuccess").fadeIn().delay('5000').fadeOut('slow');
        $('#addfeeform').trigger("reset");
       // setTimeout(function(){ location.href = baseurl +"/fees/" ;  }, 1000);
                  
            }
        
      else if(response.result === "empty" ){
          $("#feeerror").html(filldetails) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "exist" ){
          $("#feeerror").html(feesclsalredy) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#feeerror").html(activityadd) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#feeerror").html(response.result) ;
    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

  /* END */


/* Edit Fees Form */


$("#editfeeform").submit(function(e){
    e.preventDefault();
    
  $("#editfeebtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#feeerror").html(errorocc) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  $("#editfeebtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#editfeebtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#feesuccess").html(feeup) ;
        $("#feesuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/fees/" ;  }, 1000);
                  
            }
        
      else if(response.result === "empty" ){
          $("#feeerror").html(filldetails) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "exist" ){
          $("#feeerror").html(feesclsalredy) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#feeerror").html(activityadd) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#feeerror").html(response.result) ;
    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

  /* END */



/* Add Fee Head Form */


$("#addfeeheadform").submit(function(e){
    e.preventDefault();
    $("#addfeeheadbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#feeerror").html(errorocc) ;
            $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addfeeheadbtn").prop("disabled", false);
        
        },
        success: function(response)
        {
            $("#addfeeheadbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#feesuccess").html(feehadd) ;
                $("#feesuccess").fadeIn().delay('5000').fadeOut('slow');
                feeheadadd();
                $('#addfeeheadform').trigger("reset");
            }
            else if(response.result === "empty" ){
                $("#feeerror").html(filldetails) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#feeerror").html(feehalready) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "activity" ){
                $("#feeerror").html(activityadd) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#feeerror").html(response.result) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

  /* END */ 
$("#addfeediscountform").submit(function(e){
    e.preventDefault();
    $("#addfeeheadbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#feeerror").html(errorocc) ;
            $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addfeeheadbtn").prop("disabled", false);
        
        },
        success: function(response)
        {
            $("#addfeeheadbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#feesuccess").html("Fee Discount added successfully.") ;
                $("#feesuccess").fadeIn().delay('5000').fadeOut('slow');
                feediscountadd();
                $('#addfeediscountform').trigger("reset");
            }
            else if(response.result === "empty" ){
                $("#feeerror").html(filldetails) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "exist" ){
                $("#feeerror").html(feehalready) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "activity" ){
                $("#feeerror").html(activityadd) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#feeerror").html(response.result) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#addstuddiscountform").submit(function(e){
    e.preventDefault();
    $("#addstudisbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#feeerror").html(errorocc) ;
            $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addstudisbtn").prop("disabled", false);
        
        },
        success: function(response)
        {
            $("#addstudisbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#feesuccess").html("Coupon applied on Student fees added successfully.") ;
                $("#feesuccess").fadeIn().delay('5000').fadeOut('slow');
                location.reload();
            }
            else if(response.result === "exist" ){
                $("#feeerror").html(feehalready) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "activity" ){
                $("#feeerror").html(activityadd) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#feeerror").html(response.result) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

/* Select Frequency  */

 $(".head_frequency").select2({
      placeholder: "Select Frequency",
      allowClear: true
  });


/* End */





/* Multiselect Months */

 $(".months").select2({
      placeholder: chosemnths,
      dropdownParent: $('#addfeehead')
  });

$(".stud_fee").select2({
      placeholder: chosetete
  });

/* End */




/* Get Fee Head data and append in index page */


function feeheadadd(){
var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/feehead/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $("#feehead_table").DataTable().destroy();
                    $('#feeheadbody ').html(result.html);
                    $( "#feehead_table" ).DataTable({
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

if(controller=="feehead"){

var refscrf = $("input[name='_csrfToken']").val();
 window.onload = function (){

     $.ajax({ 
              url: baseurl +"/feehead/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $("#feehead_table").DataTable().destroy();
                    $('#feeheadbody ').html(result.html);
                    $( "#feehead_table" ).DataTable({
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
    }();

}

if(controller=="feediscount"){
//alert(controller);
var refscrf = $("input[name='_csrfToken']").val();
 window.onload = function (){

     $.ajax({ 
              url: baseurl +"/feediscount/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $("#feehead_table").DataTable().destroy();
                    $('#feeheadbody ').html(result.html);
                    $( "#feehead_table" ).DataTable({
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
    }();

}



function feediscountadd(){
var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/feediscount/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $("#feehead_table").DataTable().destroy();
                    $('#feeheadbody ').html(result.html);
                    $( "#feehead_table" ).DataTable({
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
/* End */


/*Get values from controller for feehead edit */


$('#feehead_table tbody').on("click",".editfeeheads",function(){

    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/feehead/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
            if (result) {
                $('#head_name').val(); $('#head_name').val(result.name);
                $('.head_frequency').val(); 
                $(".head_frequency").select2().val(result.frequency).trigger('change.select2');
                $('.months').val(); $('.months').val(result.months).change(); 
                $('.student_prefix').val(); $('.student_prefix').val(result.student_prefix).change(); 
                $('#id').val(id);
            }
        }
    });
});

$('#feehead_table tbody').on("click",".editfeediscount",function(){

    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/feediscount/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {
            console.log(result);
            if (result) {
                $('#head_name').val(); 
                $('#head_name').val(result.name);
                $('#amount').val(); 
                $('#amount').val(result.amount);
                $('#percentage_amount').val(); 
                $("#percentage_amount").select2().val(result.percentge).trigger('change.select2');
                $('#id').val(id);
                $('#coupon_code').val(); 
                $('#coupon_code').val(result.code);
                $('#valid_date').val(); 
                $('#valid_date').val(result.vdate);
            }
        }
    });
});

/*End*/

$('#feedis_table tbody').on("click",".editstudentcoupn",function() {
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/feediscount/coupnupdate", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {
            console.log(result);
            if (result) {
                $('#eclass').html(""); 
                $('#eclass').html(result.class);
                $('#eliststudent').html(""); 
                $('#eliststudent').html(result.student);
                $('#ecoupon').html(""); 
                $("#ecoupon").html(result.discount);
                $('#coupnid').val(id);
                
            }
        }
    });
});

/*End*/

$("#editdiscstudentform").submit(function(e){
    e.preventDefault();
    $("#editfeeheadbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#efeeerror").html(errorocc) ;
            $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editfeeheadbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editfeeheadbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#efeesuccess").html("Coupon of student updated successfully.") ;
                $("#efeesuccess").fadeIn();
                setTimeout(function(){ location.reload() ;  }, 1000);
            }
            else{
                $("#efeeerror").html(response.result) ;
                $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

/* Edit Fee Head Form */
$("#editfeediscform").submit(function(e){
    e.preventDefault();
    $("#editfeeheadbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#efeeerror").html(errorocc) ;
            $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editfeeheadbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editfeeheadbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#efeesuccess").html("Fee discount updated successfully.") ;
                $("#efeesuccess").fadeIn();
                setTimeout(function(){ location.href = baseurl +"/feediscount/" ;  }, 1000);
            }
            else if(response.result === "exist" ){
                $("#efeeerror").html(feehalready) ;
                $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#efeeerror").html(response.result) ;
                $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$("#editfeeheadform").submit(function(e){
    e.preventDefault();
    $("#editfeeheadbtn").prop("disabled", true);

    $(this).ajaxSubmit({
        error: function(){
            $("#efeeerror").html(errorocc) ;
            $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editfeeheadbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editfeeheadbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#efeesuccess").html(feehup) ;
                $("#efeesuccess").fadeIn();
                setTimeout(function(){ location.href = baseurl +"/feehead/" ;  }, 1000);
            }
            else if(response.result === "exist" ){
                $("#efeeerror").html(feehalready) ;
                $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#efeeerror").html(response.result) ;
                $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

  /* END */ 



/* Add Fee Setup Form */


$("#addfeesetform").submit(function(e){
    e.preventDefault();
    
  $("#addfeesetbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#feeerror").html(errorocc) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  $("#addfeesetbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#addfeesetbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        //$("#feesuccess").html("Fee setup added successfully.") ;
        //$("#feesuccess").fadeIn().delay('5000').fadeOut('slow');
        //$('#addfeesetform').trigger("reset");
       // setTimeout(function(){ location.href = baseurl +"/feesetup/" ;  }, 1000);
             location.href = baseurl +"/feedetail/" ;     
            }
        
      else if(response.result === "empty" ){
          $("#feeerror").html(filldetails) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "exist" ){
          $("#feeerror").html("A fee setup with this class is already exist.") ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#feeerror").html(activityadd) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#feeerror").html(response.result) ;
    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

  /* END */ 
  
  

/*Get values from controller for feehead edit */


$('#feesetup_table tbody').on("click",".editfeesets",function(){

    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/feesetup/update", 
              data: {"id":id,_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result) 
                 {
                  $('#sess_name').val();
          $("#sess_name").select2().val(result.name).trigger('change.select2');
                  $('#classid').val(); 
                  $("#classid").select2().val(result.class).trigger('change.select2');
          
                  $('#id').val(id);
                 }
                }
              });
});


/*End*/


/* Edit Fee setup Form */


$("#editfeesetform").submit(function(e){
    e.preventDefault();
    
  $("#editfeesetbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#ufeeerror").html(errorocc) ;
        $("#ufeeerror").fadeIn().delay('5000').fadeOut('slow');
  $("#editfeesetbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#editfeesetbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#ufeesuccess").html("Fee setup updated successfully.") ;
        $("#ufeesuccess").fadeIn();
        //setTimeout(function(){ location.href = baseurl +"/feesetup/" ;  }, 1000);
        setTimeout(function(){  window.location.reload();       }, 1000);  
            }
        
      else if(response.result === "empty" ){
          $("#ufeeerror").html(filldetails) ;
          $("#ufeeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "exist" ){
          $("#ufeeerror").html("A fee setup with this class is already exist.") ;
          $("#ufeeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#ufeeerror").html(activityadd) ;
          $("#ufeeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#ufeeerror").html(response.result) ;
    $("#ufeeerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });


  /* END */ 
  
  
/* Generate Fee receipt Form */


/*$("#genfeereceiptform").submit(function(e){
    e.preventDefault();
    
  $("#genfeereceiptbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#feeerror").html(errorocc) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  $("#genfeereceiptbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#genfeereceiptbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#feesuccess").html("Fees generated successfully.") ;
        $("#feesuccess").fadeIn().delay('5000').fadeOut('slow');
        $('#viewdetails').css("display", "inline-block");
        $('#pdfview').css("display", "inline-block");
        $('.header').css("display", "block");

        
            }
        
      else if(response.result === "empty" ){
          $("#feeerror").html(filldetails) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "exist" ){
          $("#feeerror").html("A fee receipt with this admission number is already generated.") ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#feeerror").html(activityadd) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#feeerror").html(response.result) ;
    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

*/
  /* END */ 
  
  
  /* Update Generate Fee receipt Form */

/*
$("#egenfeereceiptform").submit(function(e){
    e.preventDefault();
    
  $("#egenfeereceiptbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#feeerror").html(errorocc) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  $("#egenfeereceiptbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#egenfeereceiptbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#feesuccess").html("Fee receipt updated successfully.") ;
        $("#feesuccess").fadeIn();
         setTimeout(function(){ location.href = baseurl +"/feeGeneration/" ;  }, 1000);
            }
        
      else if(response.result === "empty" ){
          $("#feeerror").html(filldetails) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  
  else if(response.result === "activity" ){
          $("#feeerror").html(activityadd) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#feeerror").html(response.result) ;
    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });*/


  /* END

All student receipt
  */ 
  /*
  
$("#allstudentreceiptform").submit(function(e){
    e.preventDefault();
    
  $("#allstudentreceiptbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#feeerror").html(errorocc) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  $("#allstudentreceiptbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#allstudentreceiptbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#feesuccess").html("All Fee receipt generated successfully.") ;
        $("#feesuccess").fadeIn();
         setTimeout(function(){ location.href = baseurl +"/feeGeneration/" ;  }, 1000);
            }
        
      else if(response.result === "empty" ){
          $("#feeerror").html(filldetails) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  
  else if(response.result === "activity" ){
          $("#feeerror").html(activityadd) ;
          $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#feeerror").html(response.result) ;
    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });*/


  /* END */ 



/* Add Fee Setup Form */


$("#addfeedetform").submit(function(e)
{
    e.preventDefault();
    $("#addfeedetbtn").prop("disabled", true);
    var headamt = $("#addheadamt").val();
    var setupid = $("#setupid").val().split(",");
    $("#totalleft").html("");
    $(this).ajaxSubmit({
        error: function()
        {
            $("#feeerror").html(errorocc) ;
            $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addfeedetbtn").prop("disabled", false);
        },
        success: function(response)
        {
            console.log(response.amt_left);
            $("#addfeedetbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#feesuccess").html(feedadd) ;
                $("#feesuccess").fadeIn().delay('5000').fadeOut('slow');
                feedetailadd(setupid[0]);
                $(".headname").val(null).trigger("change");
                $("#totalleft").html(response.amt_left);
                $('#addfeedetform').trigger("reset");
            }
            else if(response.result === "exist" )
            {
                $("#feeerror").html(feedalready) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "activity" )
            {
                $("#feeerror").html(activityadd) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else
            {
                $("#feeerror").html(response.result) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

  /* END */ 


/* Get fee details data and append in index page */


function feedetailadd(id){
    //alert(id);
    $("#totalleft").html("");
    var refscrf = $("input[name='_csrfToken']").val();
    var fsetupid = $("#setupid").val();
     $.ajax({ 
              url: baseurl +"/feedetail/getdata", 
              data: {_csrfToken : refscrf, id:id, fid:fsetupid}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $( "#feedet_table" ).DataTable().destroy();
                    $('#feedetailbody').html(result.html);
                    $( "#feedet_table" ).DataTable({
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
                 $("#totalleft").html(result.amt_left);
              }
          });
          
}

if(controller=="feedetail"){
if($("#setupid").val() == "")
{
    var setupid = $("#getsetupid").val().split(",");
}
else
{
    var setupid = $("#setupid").val().split(",");
}

var refscrf = $("input[name='_csrfToken']").val();
$("#totalleft").html("");
var fsetupid = $("#setupid").val();
//alert(setupid[0]);
window.onload = function ()
{
     $.ajax({ 
              url: baseurl +"/feedetail/getdata", 
              data: {_csrfToken : refscrf, id:setupid[0], fid:fsetupid}, 
              type: 'post',success: function (result) 
              {    
                 console.log(result);
                 if (result.html) {
                    $( "#feedet_table" ).DataTable().destroy();
                    $('#feedetailbody').html(result.html);
                    $( "#feedet_table" ).DataTable({
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
                 $("#totalleft").html(result.amt_left);
                 
              }
          });
    }();

}

/* End */


/*Get values from controller for feehead edit */


$('#feedet_table tbody').on("click",".editfeedets",function(){

    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/feedetail/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
            if (result) 
            {
                $('#fee_s_id').val(); $('#fee_s_id').val(result.setup);
                $('#fee_h_id').val();
                $("#fee_h_id").select2().val(result.head).trigger('change.select2');  

                $('#amountno').val(); $('#amountno').val(result.amount);
                $('#id').val(id);
            }
        }
    });
});


/*End*/


/* Edit Fee setup Form */


$("#editfeedetform").submit(function(e){
    e.preventDefault();
    var strucid = $("#fee_s").val();
    //alert(strucid);
  $("#editfeedetbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#efeeerror").html(errorocc) ;
        $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
  $("#editfeedetbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#editfeedetbtn").prop("disabled", false);

      if(response.result === "success" )
      { 
        $("#efeesuccess").html(feedup) ;
        $("#efeesuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/feedetail?id="+ strucid;  }, 1000);
                  
    }
        
      else if(response.result === "empty" ){
          $("#efeeerror").html(filldetails) ;
          $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "exist" ){
          $("#efeeerror").html(feedalready) ;
          $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#efeeerror").html(activityadd) ;
          $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#efeeerror").html(response.result) ;
    $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });



/*
$("#adddscform").submit(function(e){
    e.preventDefault();
    
  $("#adddscbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#dscerror").html(errorocc) ;
        $("#dscerror").fadeIn().delay('5000').fadeOut('slow');
  $("#adddscbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#adddscbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#dscsuccess").html("Discount added successfully.") ;
        $("#dscsuccess").fadeIn().delay('5000').fadeOut('slow');
        $('#adddscform').trigger("reset");
        //setTimeout(function(){ location.href = baseurl +"/discount/" ;  }, 1000);
                  
            }
      else if(response.result === "empty" ){
          $("#dscerror").html(filldetails) ;
          $("#dscerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "exist" ){
          $("#dscerror").html("A discount with this code is already exist.") ;
          $("#vhclerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#dscerror").html(activityadd) ;
          $("#dscerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#dscerror").html(response.result) ;
    $("#dscerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

 


$("#editdscform").submit(function(e){
    e.preventDefault();
    
  $("#editdscbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#dscerror").html(errorocc) ;
        $("#dscerror").fadeIn().delay('5000').fadeOut('slow');
  $("#editdscbtn").prop("disabled", false);

    },
    success: function(response)
    {
  $("#editdscbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#dscsuccess").html("Discount updated successfully.") ;
        $("#dscsuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/discount/" ;  }, 1000);
                  
            }
      else if(response.result === "empty" ){
          $("#dscerror").html(filldetails) ;
          $("#dscerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "exist" ){
          $("#dscerror").html("A discount with this code is already exist.") ;
          $("#vhclerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else if(response.result === "activity" ){
          $("#dscerror").html(activityadd) ;
          $("#dscerror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#dscerror").html(response.result) ;
    $("#dscerror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

  

$("#addholiform").submit(function(e){
  e.preventDefault();
  
$("#addholibtn").prop("disabled", true);

 $(this).ajaxSubmit({
  error: function(){
      $("#holierror").html(errorocc) ;
      $("#holierror").fadeIn().delay('5000').fadeOut('slow');
      $("#addholibtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#addholibtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#holisuccess").html("Holiday added successfully.") ;
        $("#holisuccess").fadeIn().delay('5000').fadeOut('slow');
        $('#addholiform').trigger("reset");
        //setTimeout(function(){ location.href = baseurl +"/holiday" ;  }, 1000);
          }
      else if(response.result === "empty" ){
        $("#holierror").html(filldetails) ;
        $("#holierror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "exist" ){
        $("#holierror").html("A holiday with this name is already exist.") ;
        $("#holierror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#holierror").html(response.result) ;
        $("#holierror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});



$("#editholiform").submit(function(e){
  e.preventDefault();
  
$("#editholibtn").prop("disabled", true);

 $(this).ajaxSubmit({
  error: function(){
      $("#holierror").html(errorocc) ;
      $("#holierror").fadeIn().delay('5000').fadeOut('slow');
      $("#editholibtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#editholibtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#holisuccess").html("Holiday updated successfully.") ;
        $("#holisuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/holiday" ;  }, 1000);
          }
      else if(response.result === "empty" ){
        $("#holierror").html(filldetails) ;
        $("#holierror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "exist" ){
        $("#holierror").html("A holiday with this name is already exist.") ;
        $("#holierror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#holierror").html(response.result) ;
        $("#holierror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

$(".updatefeeform").submit(function(e){
  e.preventDefault();
  
$(".updatefeebtn").prop("disabled", true);

 $(this).ajaxSubmit({
  error: function(){
      $("#feeerror").html(errorocc) ;
      $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
      $(".updatefeebtn").prop("disabled", false);
  },
  success: function(response)
  {
      $(".updatefeebtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#feesuccess").html("Fees sumbit successfully.") ;
        $("#feesuccess").fadeIn();
        setTimeout(function(){ location.href = baseurl +"/payment" ;  }, 1000);
          }
          else if(response.result === "exist" ){
        $("#feeerror").html("A fee of this student is already sumbit.") ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "empty" ){
        $("#feeerror").html(filldetails) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#feeerror").html(response.result) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});
  */

 $("#col_type").select2({
      placeholder: " Select Column",
      allowClear: true
  });
  

/* End */
/* Select Template */

 $(".template").select2({
      placeholder: choseone,
      allowClear: true
  });
  

/* Select Session month and year  */

 $(".addsession").select2({
      placeholder: choseone,
      allowClear: true
  });

/* End */


/* Add Session Form */


$('.addsessbtn').click(function(){
     $(".addsessbtn").text(saving);        
 });


$("#addsessionform").submit(function(e){
    e.preventDefault();
    $("#addsessbtn").prop("disabled", true);
    $(this).ajaxSubmit({
        error: function()
        {
            $("#sesserror").html(errorocc) ;
            $("#sesserror").fadeIn().delay('5000').fadeOut('slow');
            $("#addsessbtn").prop("disabled", false);
            $(".addsessbtn").text(savescript);  
        },
        success: function(response)
        {
            $(".addsessbtn").text(savescript);  
            $("#addsessbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#sesssuccess").html(sessadd) ;
                $("#sesssuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload() ;  }, 1000);    
                //sessionadd();
                //$(".addsession").val(null).trigger("change"); 
                //$('#addsessionform').trigger("reset");
            }
            else if(response.result === "error" )
            { 
                $("#sesserror").html(sessntsave) ;
                $("#sesserror").fadeIn();
                $(".addsessbtn").text(savescript);
            } 
            else if(response.result === "exist" )
            {
                $("#sesserror").html(sessalready) ;
                $("#sesserror").fadeIn().delay('5000').fadeOut('slow');
                $(".addsessbtn").text(savescript);
            }
            else if(response.result === "activity" ){
                $("#sesserror").html(activityadd) ;
                $("#sesserror").fadeIn().delay('5000').fadeOut('slow');
                $(".addsessbtn").text(savescript);
            }
            else
            {
                $("#sesserror").html(response.result) ;
                $("#sesserror").fadeIn().delay('5000').fadeOut('slow');
                $(".addsessbtn").text(savescript);
            }
        } 
    });     
    return false;
});
 
  /* END */

$("#addcategoryform").submit(function(e){
    e.preventDefault();
    $("#addcategbtn").prop("disabled", true);
    $(".addcategbtn").text(saving);  
    $(this).ajaxSubmit({
        error: function()
        {
            $("#caterror").html(errorocc) ;
            $("#caterror").fadeIn().delay('5000').fadeOut('slow');
            $("#addcategbtn").prop("disabled", false);
            $(".addcategbtn").text(savescript);  
        },
        success: function(response)
        {
            $(".addcategbtn").text(savescript);  
            $("#addcategbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#catsuccess").html(catadd) ;
                $("#catsuccess").fadeIn().delay('5000').fadeOut('slow');
                categoryadd();
                $('#addcategoryform').trigger("reset");
            }
            else if(response.result === "error" )
            { 
                $("#caterror").html("Category not saved.") ;
                $("#caterror").fadeIn();
                $(".addcategbtn").text(savescript);
            } 
            else if(response.result === "exist" )
            {
                $("#caterror").html(catalready) ;
                $("#caterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addcategbtn").text(savescript);
            }
            else if(response.result === "activity" ){
                $("#caterror").html(activityadd) ;
                $("#caterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addcategbtn").text(savescript);
            }
            else
            {
                $("#caterror").html(response.result) ;
                $("#caterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addcategbtn").text(savescript);
            }
        } 
    });     
    return false;
});

$("#addproductform").submit(function(e){
    e.preventDefault();
    $("#addprodbtn").prop("disabled", true);
    $(".addprodbtn").text(saving);  
    $(this).ajaxSubmit({
        error: function()
        {
            $("#proderror").html(errorocc) ;
            $("#proderror").fadeIn().delay('5000').fadeOut('slow');
            $("#addprodbtn").prop("disabled", false);
            $(".addprodbtn").text(savescript);  
        },
        success: function(response)
        {
            $(".addprodbtn").text(savescript);  
            $("#addprodbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#prodsuccess").html(prodadd) ;
                $("#prodsuccess").fadeIn().delay('5000').fadeOut('slow');
                productadd();
                $('#addproductform').trigger("reset");
                $("#dealers").select2().val().trigger('change.select2');
                $("#dealrcat").select2().val().trigger('change.select2');
            }
            else
            {
                $("#proderror").html(response.result) ;
                $("#proderror").fadeIn().delay('5000').fadeOut('slow');
                $(".addprodbtn").text(savescript);
            }
        } 
    });     
    return false;
});

$("#editproductform").submit(function(e){
    e.preventDefault();
    $("#editprodbtn").prop("disabled", true);
    $(".editprodbtn").text(updating);  
    $(this).ajaxSubmit({
        error: function()
        {
            $("#eproderror").html(errorocc) ;
            $("#eproderror").fadeIn().delay('5000').fadeOut('slow');
            $("#editprodbtn").prop("disabled", false);
            $(".editprodbtn").text(updatescript);  
        },
        success: function(response)
        {
            $(".editprodbtn").text(updatescript);  
            $("#editprodbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#eprodsuccess").html(produpd) ;
                $("#eprodsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/Products" ;  }, 1000);   
            }
            else
            {
                $("#eproderror").html(response.result) ;
                $("#eproderror").fadeIn().delay('5000').fadeOut('slow');
                $(".editprodbtn").text(updatescript);
            }
        } 
    });     
    return false;
});

$("#adddealersform").submit(function(e){ 
    //alert("hi");
    e.preventDefault();
    $("#adddealerbtn").prop("disabled", true);
    $(".adddealerbtn").text(saving);  
    $(this).ajaxSubmit({
        error: function()
        {
            $("#dealererror").html(errorocc) ;
            $("#dealererror").fadeIn().delay('5000').fadeOut('slow');
            $("#adddealerbtn").prop("disabled", false);
            $(".adddealerbtn").text(savescript);  
        },
        success: function(response)
        {
            $(".adddealerbtn").text(savescript);  
            $("#adddealerbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#dealersuccess").html(sellradd) ;
                $("#dealersuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/dealers" ;  }, 1000);   
            }
            else
            {
                $("#dealererror").html(response.result) ;
                $("#dealererror").fadeIn().delay('5000').fadeOut('slow');
                $(".adddealerbtn").text(savescript);
            }
        } 
    });     
    return false;
});

$("#editdealerform").submit(function(e){
    e.preventDefault();
    $("#editdealerbtn").prop("disabled", true);
    $(".editdealerbtn").text(updating);  
    $(this).ajaxSubmit({
        error: function()
        {
            $("#dealererror").html(errorocc) ;
            $("#dealererror").fadeIn().delay('5000').fadeOut('slow');
            $("#editdealerbtn").prop("disabled", false);
            $(".editdealerbtn").text(updatescript);  
        },
        success: function(response)
        {
            $(".editdealerbtn").text(updatescript);  
            $("#editdealerbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#dealersuccess").html(sellrupd) ;
                $("#dealersuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/dealers" ;  }, 1000);   
            }
            else
            {
                $("#dealererror").html(response.result) ;
                $("#dealererror").fadeIn().delay('5000').fadeOut('slow');
                $(".editdealerbtn").text(updatescript);
            }
        } 
    });     
    return false;
});

$("#editcategoryform").submit(function(e){
    e.preventDefault();
    $("#editcatbtn").prop("disabled", true);
    $(".editcatbtn").text(updating);  
    $(this).ajaxSubmit({
        error: function()
        {
            $("#caterror").html(errorocc) ;
            $("#caterror").fadeIn().delay('5000').fadeOut('slow');
            $("#editcatbtn").prop("disabled", false);
            $(".editcatbtn").text(updatescript);  
        },
        success: function(response)
        {
            $(".editcatbtn").text(updatescript);  
            $("#editcatbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                $("#catsuccess").html(catupd) ;
                $("#catsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.reload() ;  }, 1000);    
            }
            else if(response.result === "error" )
            { 
                $("#caterror").html("Category not saved.") ;
                $("#caterror").fadeIn();
                $(".editcatbtn").text(updatescript);
            } 
            else if(response.result === "exist" )
            {
                $("#caterror").html(catalready) ;
                $("#caterror").fadeIn().delay('5000').fadeOut('slow');
                $(".editcatbtn").text(updatescript);
            }
            else if(response.result === "activity" ){
                $("#caterror").html(activityadd) ;
                $("#caterror").fadeIn().delay('5000').fadeOut('slow');
                $(".editcatbtn").text(updatescript);
            }
            else
            {
                $("#caterror").html(response.result) ;
                $("#caterror").fadeIn().delay('5000').fadeOut('slow');
                $(".editcatbtn").text(updatescript);
            }
        } 
    });     
    return false;
});

/* Get session details data and append in index page */


function categoryadd(){
var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/categories/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $("#category_table" ).DataTable().destroy();
                    $("#categorybody").html(result.html);   
                    $("#category_table" ).DataTable({
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

function sessionadd(){
var refscrf = $("input[name='_csrfToken']").val();
     $.ajax({ 
              url: baseurl +"/session/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $("#session_table" ).DataTable().destroy();
                    $("#sessionbody").html(result.html);   
                    $("#session_table" ).DataTable({
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

function productadd(){
var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/products/getdata", 
        data: {_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
            if (result.html) {
                $("#product_table" ).DataTable().destroy();
                $("#productbody").html(result.html);   
                $("#product_table" ).DataTable({
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
//alert(controller);

$('#tracklog_table').on("click",".acttracker",function()
{
    //alert("jhejw");
    var id = $(this).data('id');
    var d = $(this).data('d');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/Loginhistoryreport/activitytracker", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
            console.log(result);
            if (result) {
                var tableid = '#trackact_body_'+id;
                $('#trackact_table'+d).DataTable().destroy();
                $('#trackact_body'+d).html(result); 
                $('#trackact_body'+d).html(result); 
                //$('#trackact_body').append(result); 
                $("#trackact_table"+d).DataTable({
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
});

$('#scl_tchr_tracklog_table').on("click",".acttracker",function()
{
    //alert($(this).data('strtdate'));
    var d = $(this).data('d');
    $("#trackercontent"+d).html("");
    var id = $(this).data('id');
    var str = $(this).data('str');
    var tchr = $(this).data('tchr');
    var strtdate = $(this).data('strtdate');
    //alert(str);
    $("#tchrnm"+d).html("");
    $("#tchrnm"+d).append(" ( Name: "+ tchr+")");
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/Loginhistoryreport/activitytrackertchr", 
        data: {"id":id,_csrfToken : refscrf, 'strtdate':strtdate,'str':str}, 
        type: 'post',success: function (result) 
        {     
            console.log(result);
            if (result != "") {
                $("#trackercontent"+d).append(result);
            }
            else
            {
                $("#trackercontent"+d).append("<span style='text-align:center'>No Activity Found</span>");
            }
            
        }
    });
}); 

$('#ts_tracklog_table').on("click",".acttracker",function()
{
    //alert($(this).data('strtdate'));
    var d = $(this).data('d');
    $("#trackercontent"+d).html("");
    var stuid = $(this).data('id');
    var strtdate = $(this).data('strtdate');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/Teachertracking/activitytracker", 
        data: {"stuid":stuid,_csrfToken : refscrf, 'strtdate':strtdate}, 
        type: 'post',success: function (result) 
        {       
            if (result) {
                console.log(result);
                $("#trackercontent"+d).append(result);
            }
        }
    });
}); 

$('#ts_tracklog_table').on("click",".rcdetailing",function()
{
    //alert($(this).data('strtdate'));
    var d = $(this).data('d');
    $("#trackercontent"+d).html("");
    var stuid = $(this).data('id');
    var clss = $(this).data('class');
    //alert(clss);
    var session = $("#session").val();
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/ReportcardReport/activitytracker", 
        data: {"stuid":stuid,_csrfToken : refscrf, 'class':clss, 'session':session}, 
        type: 'post',success: function (result) 
        {       
            $("#trackercontent"+d).html("");
            if (result) {
                console.log(result);
                $("#trackercontent"+d).append(result);
            }
        }
    });
});

if(controller == "Products"){

var refscrf = $("input[name='_csrfToken']").val();
   window.onload = function ()
   {
        $.ajax({ 
            url: baseurl +"/products/getdata", 
            data: {_csrfToken : refscrf}, 
            type: 'post',success: function (result) 
            {       
                //console.log(result);
                if (result.html) 
                {
                    $("#product_table" ).DataTable().destroy();
                    $("#productbody").html(result.html);   
                    $("#product_table" ).DataTable({
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
    }();
}


if(controller=="session"){

var refscrf = $("input[name='_csrfToken']").val();
   window.onload = function (){

     $.ajax({ 
              url: baseurl +"/session/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $("#session_table" ).DataTable().destroy();
                    $("#sessionbody").html(result.html);   
                    $("#session_table" ).DataTable({
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
}();
}
if(controller=="Categories"){

var refscrf = $("input[name='_csrfToken']").val();
   window.onload = function (){

     $.ajax({ 
              url: baseurl +"/Categories/getdata", 
              data: {_csrfToken : refscrf}, 
              type: 'post',success: function (result) 
              {       
                 if (result.html) {
                    $("#category_table" ).DataTable().destroy();
                    $("#categorybody").html(result.html);   
                    $("#category_table" ).DataTable({
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
}();
}

/* End */

$('#category_table tbody').on("click",".editcategory",function()
{
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax
    ({ 
        url: baseurl +"/categories/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {  
            if (result)
            {
                console.log(result[0]['name']);
                $('#id').val(id);
                $('#category_name').val(result[0]['name']);
            }
        }
    });
});

$('#product_table tbody').on("click",".editproduct",function()
{
    var id = $(this).data('id');
    $("#pro_dealrcat").html("");
    $("#prodctimg").html("");
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax
    ({ 
        url: baseurl +"/products/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {
            console.log(result.categories);
            if (result)
            {
                $('#id').val(id);
                $("#pro_dealers").select2().val(result.products['dealer_id']).trigger('change.select2');
                //$("#pro_dealrcat").select2().val(result.products['category_id']).trigger('change.select2');
                $("#pro_name").val(result.products['product_name']);
                $("#pro_price").val(result.products['price']);
                $("#pro_quantity").val(result.products['quantity']);
                $("#pro_dealrcat").html(result.categories);
                //$("#pro_dealrcat").select2().val(result.products['category_id']).trigger('change.select2');
                $("#prodctimg").html("<img src='"+baseurl+"/webroot/productimages/"+result.products['product_image']+"'  width='50px' height='50px' >");
                $("#prodimg").val(result.products['product_image']);
                var abc = "cate";
                getprodealercate(result.products['category_id'], abc);
            }
        }
    });
});
/* Get details on click for session edit  */

$('#session_table tbody').on("click",".editsession",function(){

    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/session/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {  
            if (result) {
                $("#startmonth").select2().val(result.startmonth).trigger('change.select2');
                $("#startyear").select2().val(result.startyear).trigger('change.select2');
                $("#endmonth").select2().val(result.endmonth).trigger('change.select2');
                $("#endyear").select2().val(result.endyear).trigger('change.select2');
                $('#id').val(id);
          
                var selectedValues = result.category;
                $(document).ready(function() 
                {
                    $("#category").select2({
                      multiple: true,
                    });
                    $('#category').val(selectedValues).trigger('change');
                });
          
            }
        }
        });
});


/*End*/



/* Edit Session Form */


$('.editsessbtn').click(function(){
     $(".editsessbtn").text(updating);        
 });


$("#editsessionform").submit(function(e){
  e.preventDefault();
  
$("#editsessbtn").prop("disabled", true);

 $(this).ajaxSubmit({
  error: function(){
      $("#editsesserror").html(errorocc) ;
      $("#editsesserror").fadeIn().delay('5000').fadeOut('slow');
      $("#editsessbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#editsessbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#editsesssuccess").html(sessionup) ;
        $("#editsesssuccess").fadeIn();
    $(".editsessbtn").text(updatescript);
        setTimeout(function(){ location.href = baseurl +"/session" ;  }, 1000);
          }
        else if(response.result === "exist" ){
        $("#editsesserror").html(sessionalready) ;
        $("#editsesserror").fadeIn().delay('5000').fadeOut('slow');
    $(".editsessbtn").text(updatescript);
      }
      else if(response.result === "activity" ){
        $("#editsesserror").html(activnotsav) ;
        $("#editsesserror").fadeIn().delay('5000').fadeOut('slow');
    $(".editsessbtn").text(updatescript);
      }
    else if(response.result === "error" ){
        $("#editsesserror").html(sessionntup) ;
        $("#editsesserror").fadeIn().delay('5000').fadeOut('slow');
    $(".editsessbtn").text(updatescript);
      }
      else{
        $("#editsesserror").html(response.result) ;
        $("#editsesserror").fadeIn().delay('5000').fadeOut('slow');
    $(".editsessbtn").text(updatescript);
}
  } 
});     
return false;

});

 
  /* END */



/* Select Session year  */

 $(".currntsesssion").select2({
      placeholder: choseyear,
      allowClear: true
  });
  
   $("#dealers").select2({
      placeholder:  seller,
      allowClear: true
  });
  $("#pro_dealers").select2({
      placeholder:  seller,
      allowClear: true
  });
  
   $("#pro_dealrcat").select2({
      placeholder: categ,
      allowClear: true
  });
  $("#dealrcat").select2({
      placeholder: categ,
      allowClear: true
  });

/* End */


/* Select Studnet Name */

 $(".stud_name").select2({
      placeholder: choseone,
      allowClear: true
  });


/* END */

/* END */


/* Search in Dropdowns   */

$(".pay_to").select2({
      placeholder: choseone,
      allowClear: true,
      tags: true
});


$(".vendor").select2({
      placeholder: choseone,
      allowClear: true,
      tags: true
});


 $(".vehicles").select2({
      placeholder: choseone,
      allowClear: true
  });


 $(".subheadids").select2({
      placeholder: choseone,
      allowClear: true
  });

$(".session").select2({
      placeholder: chosesess,
      allowClear: true
  });

/* End */

/*Update Employee Form*/


$("#editempform").submit(function(e){
    e.preventDefault();
     $("#editempbtn").text('Updating..');   
  $("#editempbtn").prop("disabled", true);

   $(this).ajaxSubmit({
    error: function(){
        $("#editemperror").html(errorocc) ;
        $("#editemperror").fadeIn().delay('5000').fadeOut('slow');
  $("#editempbtn").prop("disabled", false);
 $("#editempbtn").text(updatescript);   
    },
    success: function(response)
    {
         $("#editempbtn").text(updatescript);   
  $("#editempbtn").prop("disabled", false);

      if(response.result === "success" ){ 
        $("#editempsuccess").html(tchrupd) ;
        $("#editempsuccess").fadeIn();
         setTimeout(function(){ location.href = baseurl +"/teachers/" ;  }, 1000);
                
            }
        else    if(response.result === "email" ){ 
                $("#editemperror").html(tchremexist) ;
                $("#editemperror").fadeIn();
                     
                        
                    }
      else if(response.result === "empty" ){
          $("#editemperror").html(filldetails) ;
          $("#editemperror").fadeIn().delay('5000').fadeOut('slow');
  }
  else{
    $("#editemperror").html(response.result) ;
    $("#editemperror").fadeIn().delay('5000').fadeOut('slow');
  }
    } 
  });     
  return false;
  
  });

/* Load Personal Task */
$('.datatable').dataTable({
  dom: 'Bfrtip',
  buttons: [{
    extend : 'excelHtml5',
    text: 'Export in Excel'

  }  ]
});  


$('#marktqueriestable tbody').on("click",".marketdesc",function(){
    $('#fulldescview').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/marketQueries/getdesc", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {      
            console.log(result);
            if (result) 
            {
                $("#fulldesc").modal("show");
                $('#fulldescview').html(result); 
            }
        }
    });
});


/*End*/
/*End*/



});



function updatefeeclass(val)
{
    var Id = $("#setupid").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/feesetup/updatesetupclass',
            data:{'classId':val, 'Id': Id},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html.result == "updated")
                {                   
                    $("#class").select2().val(val).trigger('change.select2');
                }
          
            }

        });
    }
}

function updatefeesession(val)
{
    var Id = $("#setupid").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/feesetup/updatesetupsess',
            data:{'sessName':val, 'Id': Id},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html.result == "updated")
                {                   
                    $("#sess_name").select2().val(val).trigger('change.select2');
                }
          
            }

        });
    }
}

function grades(val)
{
    //alert(val);
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
            type:'POST',
            url: baseurl + '/students/getsubjects',
            data:'classId='+val,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                if (html) {
                    console.log(html);
                    $("#subjec").val(html[0]['subject_id']);
                    var sub = html[0]['subject_id'].split(",");
                    console.log(sub);
                    $('#subjects').select2().val(sub).trigger('change.select2');
                
                }
            }

        });
    
}

function getsubjects(val)
{
    //alert(val);
    $("#subj_s").html("");
    var tut_filter = $("#tut_filter").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/schoolTutorialfee/getsubjectsss',
        data:{'filter':tut_filter, 'classId':val,},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            console.log(html);
            if (html.sep == "subjects")
            {
                $("#subj_s").html(html.abc); 
            }
            else
            {
                $("#viewtutcontent").html("");
                $("#viewtutcontent").html(html.abc); 
            }
        }
    });
}
function getcontent(val)
{
    //alert(val);
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var tut_filter = $("#tut_filter").val();
    var class_s = $("#class_s").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/schoolTutorialfee/getcontent',
        data:{'filter':tut_filter, 'class_s':class_s, 'subj':val,},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            if (html) {
                console.log(html);
                $("#viewtutcontent").html(html); 
            }
        }
    });
}

function scltutorial_filter(val)
{
    //alert(val);
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var subj = $("#subj_s").val();
    var class_s = $("#class_s").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/schoolTutorialfee/getfilter',
        data:{'filter':val, 'class_s':class_s, 'subj':subj,},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            if (html) {
                console.log(html);
                $("#viewtutcontent").html(html); 
            }
        }
    });
}


function getstudents(get_val, id)
{
    
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    if(id == 'class'){
        var select_year = $("#select_year").val();
        var new_val = get_val;
    }else if(id == 'select_year'){
        var new_val = $("#class").val();
        var select_year = get_val;
        
    }
   
    $.ajax({
        type:'POST',
        url: baseurl + '/fees/getstudent',
        data:'classId='+new_val+'&start_year='+select_year,
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            
            $("#student").html('');
            $("#student").append( "<option value=''></option>" );
            $.each(html[0], function(key,value) {
                console.log(html[0]);
                $("#student").append( "<option value='"+value.id+"'>"+value.l_name+" "+value.f_name+" ("+value.email+" )</option>" );
            }); 
            //$("#totalamt").val(html[2]);
            $( "#month" ).html('');
            $( "#month" ).html(html[1]);
            /*$( "#session_amount" ).html('');
            $( "#session_amount" ).html('($'+html[2]+')');*/
        }
    });
}


function getstudentsession(get_val)
{
    
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    var select_year = $("#select_year").val();
    var schoolid = $("#schoolid").val();
    var new_val = get_val;
    
   
    $.ajax({
            type:'POST',
            url: baseurl + '/summary/getstudent',
            data:'classId='+new_val+'&start_year='+select_year+'&schoolid='+schoolid,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                
                $("#student").html('');
                $("#student").append( "<option value=''></option>" );
                $.each(html[0], function(key,value) {
                    console.log(html[0]);
                    $("#student").append( "<option value='"+value.id+"'>"+value.l_name+" "+value.f_name+" ("+value.email+" )</option>" );
                }); 
            }

        });
    
}

function getstudentsessionscl(get_val)
{
    
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var select_year = $("#select_year").val();
    var new_val = get_val;
    //alert(select_year);
    $.ajax({
            type:'POST',
            url: baseurl + '/studentSummary/getstudent',
            data:'classId='+new_val+'&start_year='+select_year,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                /*var abc = $("ul#select2-student-results").text();
                alert(abc); */
                $("#student").html('');
                $("#student").append( "<option value=''></option>" );
                $.each(html[0], function(key,value) {
                    console.log(html[0]);
                    $("#student").append( "<option value='"+value.id+"'>"+value.l_name+" "+value.f_name+" ("+value.email+" )</option>" );
                }); 
            }

        });
    
}
/* Add Fees Form */


$("#addstudentfees").submit(function(e){
    e.preventDefault();
    $("#addfeebtn").prop("disabled", true);
    $("#addfeebtn").text(saving);
    var select_year = $("#select_year").val();
    var new_val = $("#class").val();
    var studentid = $("#student").val();
    var frequency = $("#month").val();
    var amount = $("#amount").val();
    var paymode = $("#paymode").val();
    var totalamt = $("#totalamt").val();
    var cashmemo = $("#cashmemo").val();
    /*if(select_year != "" && studentid != "" && new_val != "")
    {
        $.ajax
        ({
            type:'POST',
            url: baseurl + '/fees/addstudentfees',
            data:'class='+new_val+'&start_year='+select_year+'&student='+studentid+'&frequency='+frequency+'&amount='+amount+'&paymode='+paymode+'&totalamt='+totalamt+'&cashmemo='+cashmemo,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },*/
        $(this).ajaxSubmit({
            error: function(){
                $("#feeerror").html(errorocc) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
                $("#addfeebtn").prop("disabled", false);
                $("#addfeebtn").text(savescript);
            },
            success: function(response)
            {
                $("#addfeebtn").prop("disabled", false);
                $("#addfeebtn").text(savescript);
                if(response.result === "success" )
                { 
                    $("#feesuccess").html(feeadd) ;
                    $("#feesuccess").fadeIn().delay('1000').fadeOut('slow');;
                    
                    getstudents_data(studentid, student);
                     $('#addfee').modal('hide');
                    $('#addstudentfees').trigger("reset");
                    $("#paymode").val(null).trigger("change");
                    $("#amount").val();
                    $("#transid").val();
                    $("#cashmemo").val();
                   // setTimeout(function(){ location.reload();  }, 1000);
                }
                else if(response.result === "activity" ){
                    $("#feeerror").html(activityadd) ;
                    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
                }
                else
                {
                    $("#feeerror").html(response.result) ;
                    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });     
    /*}
    else
    {
        $("#addfeebtn").prop("disabled", false);
        $("#addfeebtn").text(savescript);
        $("#feeerror").html(feestudata) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
    }*/
    return false;
});

$("#editstudentfees").submit(function(e){
    e.preventDefault();
    $("#editfeebtn").prop("disabled", true);
    var studentid = $("#student").val();
    
    $(this).ajaxSubmit({
        error: function(){
            $("#efeeerror").html(errorocc) ;
            $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#editfeebtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#editfeebtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#efeesuccess").html(feeup) ;
                $("#efeesuccess").fadeIn().delay('1000').fadeOut('slow');
                getstudents_data(studentid, student);
                $('#editfee').modal('hide');
            }
            else if(response.result === "activity" ){
                $("#efeeerror").html(activityadd) ;
                $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else
            {
                $("#efeeerror").html(response.result) ;
                $("#efeeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });
    return false;
});

$("#addstudentonlinfees").submit(function(e){
    e.preventDefault();
    $("#addfeebtn").prop("disabled", true);
    $("#addfeebtn").text(saving);
    
    $(this).ajaxSubmit({
        error: function(){
            $("#feeerror").html(errorocc) ;
            $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            $("#addfeebtn").prop("disabled", false);
            $("#addfeebtn").text(savescript);
        },
        success: function(response)
        {
            console.log(response);
            $("#addfeebtn").prop("disabled", false);
            $("#addfeebtn").text(savescript);
            if(response.result === "success" )
            { 
                $("#feesuccess").html(feeadd) ;
                $("#feesuccess").fadeIn().delay('1000').fadeOut('slow');;
                /*
                $('#addstudentfees').trigger("reset");
                $("#month").val(null).trigger("change");
                $("#amount").val();
                $("#addfee").modal("hide");*/
                //setTimeout(function(){ location.href = baseurl +"/fees/add" ;  }, 1000);
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else if(response.result === "activity" ){
                $("#feeerror").html(activityadd) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
            else
            {
                $("#feeerror").html(response.result) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    
    return false;
});

  /* END */

function file_typess(val)
{
    $("#dtubevideo").css("display", "none");
    $("#typevideo").css("display", "none");
    var video_type = $("#videotypes").val();
    if(val == "pdf")
    {
        $("#link_file").css("display", "none");
        $("#upload_file").css("display", "block");
    }
    if(val == "word")
    {
        $("#link_file").css("display", "none");
        $("#upload_file").css("display", "block");
    }
    if(val == "video")
    {
        if(video_type == "d.tube")
        {
            $("#link_file").css("display", "none");
            $("#dtubevideo").css("display", "block");   
        }else if(result[0]['video_type'] == "custom upload")
        {
           $("#typevideo").css("display", "none");
            $("#upload_file").css("display", "block");
        }
        else
        {
            $("#link_file").css("display", "block");
            $("#dtubevideo").css("display", "none");
        }
        $("#typevideo").css("display", "block");
        $("#upload_file").css("display", "none");
    }
    if(val == "audio")
    {
        $("#link_file").css("display", "none");
        $("#upload_file").css("display", "block");
    }
}

function video_type(val)
{
    $("#dtubevideo").css("display", "none");
    var file_type = $("#file_type").val();
    
    if(file_type == "video")
    {
        if(val == "d.tube")
        {
            $("#link_file").css("display", "none");
            $("#dtubevideo").css("display", "block");
            $("#upload_file").css("display", "none");
        }
        else if(val == "cupload")
        {
            $('#file_size_up').html('');
            $('#file_size_up').html('(Note: File should be less than 15 MB)');
            $("#link_file").css("display", "none");
            $("#upload_file").css("display", "block");
            $("#dtubevideo").css("display", "none");
        }else
        {
            $("#upload_file").css("display", "none");
            $("#link_file").css("display", "block");
            $("#dtubevideo").css("display", "none");
        }
    }
}

function evideo_type(val)
{
    $("#edtubevideo").css("display", "none");
    var file_type = $("#efile_type").val();
    
    if(file_type == "video")
    {
        if(val == "d.tube")
        {
            $("#elink_file").css("display", "none");
            $("#edtubevideo").css("display", "block");   
            $("#eupload_file").css("display", "none");
        }else if(val == "cupload")
        {
            $('#file_size_up').html('');
                    $('#file_size_up').html('(Note: File should be less than 15 MB)');
            $("#elink_file").css("display", "none");
            $("#eupload_file").css("display", "block");
            $("#edtubevideo").css("display", "none");
        }
        else
        {
            $("#elink_file").css("display", "block");
            $("#edtubevideo").css("display", "none");
            $("#eupload_file").css("display", "none");
        }
        
    }
}

function efile_typess(val)
{
    $("#edtubevideo").css("display", "none");
    $("#etypevideo").css("display", "none");
    var video_type = $("#evideotypes").val();
    if(val == "pdf")
    {
        $("#elink_file").css("display", "none");
        $("#eupload_file").css("display", "block");
    }
    if(val == "word")
    {
        $("#elink_file").css("display", "none");
        $("#eupload_file").css("display", "block");
    }
    if(val == "video")
    {
        if(video_type == "d.tube")
        {
            $("#elink_file").css("display", "none");
            $("#edtubevideo").css("display", "block");   
        }else if(val == "cupload")
        {
            $("#etypevideo").css("display", "block");
            $("#eupload_file").css("display", "block");
        }
        else
        {
            $("#elink_file").css("display", "block");
            $("#edtubevideo").css("display", "none");
        }
        
    }
    if(val == "audio")
    {
        $("#elink_file").css("display", "none");
        $("#eupload_file").css("display", "block");
    }
}

function getexamtype(val)
{
    $('#exam_type').prop('required',false);
    $('#exam_period').prop('required',false);
    if(val == "Exams")
    {
        $("#examtypes").css("display", "block");
        $("#title").removeAttr('required');
        $("#maxmarks").css("display", "block");
        $("#max_marks").prop('required');
        $("#maxmrks").prop('required');
        $("#contentuplod").css("display", "block");
        $("#pdfupload").css("display", "none");
        $("#guidetitle").css("display", "none");
        $("#guideinstr").css("display", "block");
        $('#exam_type').prop('required',true);
        $('#exam_period').prop('required',false);
        
        if($("#contentupload").val() == "custom")
        {
            $("#examformat").css("display", "block");
        }
        else
        {
            $("#examformat").css("display", "none");
        }
        
    }
    else if( val == "Study Guide")
    {
        $("#examtypes").css("display", "none");
        $("#examperiod").css("display", "none");
        $("#maxmarks").css("display", "none");
        $("#contentuplod").css("display", "none");
        $("#pdfupload").css("display", "block");
        $("#max_marks").removeAttr('required');
        $("#contentupload").removeAttr('required');
        $("#guidetitle").css("display", "block");
        $("#title").prop('required',true);
        $("#instruction").removeAttr('required');
        $("#guideinstr").css("display", "none");
        $("#maxmrks").removeAttr('required');
        $('#exam_type').prop('required',false);
        $('#exam_period').prop('required',false);
        if($("#contentupload").val() == "custom")
        {
            $("#examformat").css("display", "block");
        }
        else
        {
            $("#examformat").css("display", "none");
        }
        
    }
    else
    {
        $("#examtypes").css("display", "block");
        $("#examperiod").css("display", "block");
        $("#title").removeAttr('required');
        $("#maxmarks").css("display", "block");
        $("#max_marks").prop('required');
        $("#maxmrks").prop('required');
        $("#contentuplod").css("display", "block");
        $("#pdfupload").css("display", "none");
        $("#guidetitle").css("display", "none");
        $("#guideinstr").css("display", "block");
        $('#exam_type').prop('required',true);
        $('#exam_period').prop('required',true);
        if($("#contentupload").val() == "custom")
        {
            $("#examformat").css("display", "block");
        }
        else
        {
            $("#examformat").css("display", "none");
        }
    }
    
    var clsid = $("#s_listclass1").val();
    //alert(clsid);
    if(clsid == "")
    {
        $("#submitreqerror").html("Please select class first.") ;
        $("#submitreqerror").fadeIn().delay('5000').fadeOut('slow');
        $("#examasserror").html("Please select class first.") ;
        $("#examasserror").fadeIn().delay('5000').fadeOut('slow');
    }
    else
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/examAssessment/getmestr',
            data:{'filter':'add', 'clsid':clsid },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                $("#exam_type").html(html.data);
            }
        });
    }
}

function contntupd(val)
{
    if(val == "pdf")
    {
        $("#pdfupload").css("display", "block");
        $("#fileupload").prop('required',true);
        $("#customize").css("display", "none");
        $("#examformat").css("display", "none");
    }
    else if(val == "custom")
    {
        var request = $("#request_for").val();
        //alert(request);
        /*if(request == "Exams")
        {*/
            $("#examformat").css("display", "block");
        /*}
        else
        {
            $("#examformat").css("display", "none");
        }*/
        $("#pdfupload").css("display", "none");
        $("#customize").css("display", "block");
        $("#fileupload").prop('required',false);
    }
    else
    {
        $("#pdfupload").css("display", "none");
        $("#customize").css("display", "none");
        $("#fileupload").prop('required',false);
        $("#examformat").css("display", "none");
    }
}

function attreport(val)
{
    //alert(val); 
    if(val == "class")
    {
        $("#studentsel").css("display", "none");
    }
    else
    {
        $("#studentsel").css("display", "block");
    }
}
function exam_assfilter(val)
{
    //alert(val);
    if(val == "Exams")
    {
        $("#examtype").css("display", "block");
    }
    else
    {
        $("#examtype").css("display", "none");
    }
}


function econtntupd(val)
{
    if(val == "pdf")
    {
        $("#epdfupload").css("display", "block");
        $("#efileupload").prop('required',false);
        $("#ecustomize").css("display", "none");
        $("#eexamformat").css("display", "none");
    }
    else if(val == "custom")
    {
        var request = $("#erequest_for").val();
        //alert(request);
        if(request == "Exams")
        {
            $("#eexamformat").css("display", "block");
        }
        else
        {
            $("#eexamformat").css("display", "none");
        }
        $("#epdfupload").css("display", "none");
        $("#efileupload").prop('required',false);
        $("#ecustomize").css("display", "block");
    }
    else
    {
        $("#epdfupload").css("display", "none");
        $("#efileupload").prop('required',false);
        $("#eustomize").css("display", "none");
        $("#eexamformat").css("display", "none");
    }
}

function egetexamtype(val,abc)
{
    if(val == "Exams")
    {
        $("#eexamtypes").css("display", "block");
        $("#title").removeAttr('required');
        $("#maxmarks").css("display", "block");
        $("#emaxmarks").css("display", "block");
        $("#max_marks").prop('required');
        $("#econtentuplod").css("display", "block");
        //$("#epdfupload").css("display", "none");
        $("#guidetitle").css("display", "none");
        $("#guideinstr").css("display", "block");
        $("#eguidetitle").css("display", "none");
        $("#eguideinstr").css("display", "block");
        var request = $("#erequest_for").val();
        //alert(request);
        if(request == "Exams")
        {
            $("#eexamformat").css("display", "block");
        }
        else
        {
            $("#eexamformat").css("display", "none");
        }
    }
    else if( val == "Study Guide")
    {
        $("#eexamtypes").css("display", "none");
        $("#maxmarks").css("display", "none");
        $("#emaxmarks").css("display", "none");
        $("#econtentuplod").css("display", "none");
        $("#ecustomize").css("display", "none");
        $("#epdfupload").css("display", "block");
        $("#max_marks").removeAttr('required');
        $("#econtentupload").removeAttr('required');
        $("#guidetitle").css("display", "block");
        $("#etitle").prop('required',true);
        $("#einstruction").removeAttr('required');
        $("#guideinstr").css("display", "none");
        $("#eguidetitle").css("display", "block");
        $("#eguideinstr").css("display", "none");
        $("#eexamformat").css("display", "none");
    }
    else
    {
        $("#eexamtypes").css("display", "block");
        $("#title").removeAttr('required');
        $("#maxmarks").css("display", "block");
        $("#max_marks").prop('required');
        $("#econtentuplod").css("display", "block");
        $("#emaxmarks").css("display", "block");
        //$("#epdfupload").css("display", "none");
        $("#guidetitle").css("display", "none");
        $("#guideinstr").css("display", "block");
        $("#eguidetitle").css("display", "none");
        $("#eguideinstr").css("display", "block");
        $("#eexamformat").css("display", "none");
    }
    
    var clsid = $("#s_listclass").val();
    var clsid2 = $("#m_listclass").val();
    
    if(clsid != "" && clsid2 == undefined)
    {
        /*if(abc == "direct")
        {*/
            $.ajax({
                type:'POST',
                url: baseurl + '/examAssessment/getmestr',
                data:{'filter':'edit', 'clsid':clsid, 'abc':abc },
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    console.log(html);
                    $("#eexam_type").html(html.data);
                }
            });
            
        /*}
        else
        {
            console.log(val);
            console.log(abc);
            $("#eexam_type").select2().val(abc).trigger('change.select2');
        }*/
    }
    else if(clsid == undefined && clsid2 != "")
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/examAssessment/getmestr',
            data:{'filter':'edit', 'clsid':clsid2, 'abc':abc },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                console.log(html);
                $("#eexam_type").html(html.data);
            }
        });
    }
    else
    {
        $("#editexamasserror").html("Please select class first.") ;
        $("#editexamasserror").fadeIn().delay('5000').fadeOut('slow');
    }
    


}

function optionobjective(val)
{
    if(val == "objective")
    {
        $("#quesVal").css("display", "block");
        $("#maxwords").css("display", "none");
        $("#valueques").prop('required',true);
    }
    else
    {
        $("#quesVal").css("display", "none");
        $("#maxwords").css("display", "block");
        $("#valueques").prop('required',false);
    }
}

function studentsearch()
{
    $("#studentsearch").css("display", "block");
    $("#searchstudent").css("display", "none");
    $("#closesearch").css("display", "block");
    $("#sumdownloadreport").css("display", "none");
}


function closesearch()
{
    $("#studentsearch").css("display", "none");
    $("#searchstudent").css("display", "block");
    $("#closesearch").css("display", "none");
    $("#sumdownloadreport").css("display", "block");
}

function eoptionobjective(val)
{
    if(val == "objective")
    {
        $("#equesVal").css("display", "block");
        $("#emaxwords").css("display", "none");
        $("#evalueques").prop('required',true);
    }
    else
    {
        $("#equesVal").css("display", "none");
        $("#emaxwords").css("display", "block");
        $("#evalueques").prop('required',false);
    }
}

function notify(val)
{
    if(val == "schools")
    {
        $("#scl_list").css("display", "block");
        $("#tchr_list").css("display", "none");
        $("#studnt_list").css("display", "none");
        $("#schl_list").css("display", "none");
        $("#cls_opt").css("display", "none");
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
        $("#parnt_list").css("display", "none");
    }
    else if(val == "teachers")
    {
        $("#schl_list").css("display", "block");
        $("#tchr_list").css("display", "none");
        $("#studnt_list").css("display", "none");
        $("#scl_list").css("display", "none");
        $("#cls_opt").css("display", "none");
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
        $("#parnt_list").css("display", "none");
    }
    
    else if(val == "students")
    {
        $("#schl_list").css("display", "block");
        $("#scl_list").css("display", "none");
        $("#tchr_list").css("display", "none");
        $("#studnt_list").css("display", "none");
        $("#cls_opt").css("display", "block");
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
        $("#parnt_list").css("display", "none");
    }
    else if(val == "parents")
    {
        $("#schl_list").css("display", "block");
        $("#scl_list").css("display", "none");
        $("#tchr_list").css("display", "none");
        $("#studnt_list").css("display", "none");
        $("#cls_opt").css("display", "none");
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
        $("#parnt_list").css("display", "none");
    }
    else
    {
        $("#scl_list").css("display", "none");
        $("#tchr_list").css("display", "none");
        $("#studnt_list").css("display", "none");
        $("#cls_opt").css("display", "none");
        $("#schl_list").css("display", "none");
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
        $("#parnt_list").css("display", "none");
    }
}

function schoolnotify(val)
{
    if(val == "teachers")
    {
        $("#tchr_list").css("display", "block");
        $("#studnt_list").css("display", "none");
        $("#cls_opt").css("display", "none");
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
        $("#parnt_list").css("display", "none");
    }
    
    else if(val == "students")
    {
        $("#tchr_list").css("display", "none");
        $("#studnt_list").css("display", "none");
        $("#cls_opt").css("display", "block");
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
        $("#parnt_list").css("display", "none");
    }
    else if(val == "parents")
    {
        $("#tchr_list").css("display", "none");
        $("#studnt_list").css("display", "none");
        $("#cls_opt").css("display", "block");
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
        $("#parnt_list").css("display", "none");
    }
    else
    {
        
        $("#tchr_list").css("display", "none");
        $("#studnt_list").css("display", "none");
        $("#cls_opt").css("display", "none");
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
        $("#parnt_list").css("display", "none");
    }
}

function classchnge(val)
{
    //alert(val);
    $("#multiple_cls_list").css("display", "none");
    var sclid = $("#listschool").val();
    if(val == "single")
    {
        
        var notify = $("#notify_to").val();
        $("#s_listclass").html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/notification/classlist',
            data:'schoolId='+sclid,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                $("#single_cls_list").css("display", "block");
                $("#s_listclass").html(html.data);
            }

        });
        
    }
    else if(val == "multiple")
    {
        
        $("#studnt_list").css("display", "none");
        $("#single_cls_list").css("display", "none");
        var notify = $("#notify_to").val();
        $("#m_listclass").html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/notification/classlist',
            data:'schoolId='+sclid,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                $("#multiple_cls_list").css("display", "block");
                $("#m_listclass").html(html.data);
            }

        });
        
    }
    else
    {
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
    }
}


function schoolclasschnge(val)
{
    //alert(val);
    $("#multiple_cls_list").css("display", "none");
    //var sclid = $("#listschool").val();
    if(val == "single")
    {
        
        var notify = $("#notify_to").val();
        $("#s_listclass").html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/schoolNotification/classlist',
            //data:'schoolId='+sclid,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                $("#single_cls_list").css("display", "block");
                $("#s_listclass").html(html.data);
            }

        });
        
    }
    else if(val == "multiple")
    {
        
        $("#studnt_list").css("display", "none");
        $("#single_cls_list").css("display", "none");
        var notify = $("#notify_to").val();
        $("#m_listclass").html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/schoolNotification/classlist',
            //data:'schoolId='+sclid,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                $("#multiple_cls_list").css("display", "block");
                $("#m_listclass").html(html.data);
            }

        });
        
    }
    else
    {
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
    }
}

function teacherclasschnge(val)
{
    //alert(val);
    $("#multiple_cls_list").css("display", "none");
    //var sclid = $("#listschool").val();
    if(val == "single")
    {
        
        var notify = $("#notify_to").val();
        $("#s_listclass").html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/teacherNotifications/classlist',
            //data:'schoolId='+sclid,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                $("#single_cls_list").css("display", "block");
                $("#s_listclass").html(html.data);
            }

        });
        
    }
    else if(val == "multiple")
    {
        
        $("#studnt_list").css("display", "none");
        $("#single_cls_list").css("display", "none");
        var notify = $("#notify_to").val();
        $("#m_listclass").html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/teacherNotifications/classlist',
            //data:'schoolId='+sclid,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                $("#multiple_cls_list").css("display", "block");
                $("#m_listclass").html(html.data);
            }

        });
        
    }
    else
    {
        $("#single_cls_list").css("display", "none");
        $("#multiple_cls_list").css("display", "none");
    }
}

function schoolchange(val)
{
    var notify = $("#notify_to").val();
    if(notify == "teachers")
    {
        $("#listteacher").html("");
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/notification/teacherlist',
                data:'schoolId='+val,
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    $("#tchr_list").css("display", "block");
                    $("#listteacher").html(html.data);
                    $("#studnt_list").css("display", "none");
                }
    
            });
        }
    }
    else if(notify == "parents")
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/notification/classlist',
            data:'schoolId='+val,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                $("#single_cls_list").css("display", "block");
                $("#s_listclass").html(html.data);
            }

        });
        
    }
}
function getstudentlist(val, classstudent)
{
    if(classstudent == "class")
    {
        $("#student_cls").html("");
        $("#studentid").html("");
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/teacherattendance/studentlist',
                data:{'classid' : val},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    console.log(html);
                    $("#student_cls").html(html);
                    $("#studentid").html(html);
                    
                }
    
            });
        }
    }
    else
    {
        $("#studentid").select2().val(val).trigger('change.select2');
    }
}

function getstudentlistattendance(val, classstudent)
{
    //alert(classstudent);
    if(classstudent == "class")
    {
        $("#student_cls").html("");
        $("#studentid").html("");
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/schoolattendance/studentlist',
                data:{'classid' : val},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    console.log(html);
                    $("#student_cls").html(html);
                    $("#studentid").html(html);
                    
                }
    
            });
        }
    }
    else
    {
        //alert(val);
        $("#studentid").select2().val(val).trigger('change.select2');
    }
}

function attendance_studentreport(val)
{
        $("#student_cls").html("");
        var sessionyear = $("#select_year").val();
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/attendanceReport/studentlist',
                data:{'classid' : val, 'start_year': sessionyear},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    console.log(html);
                    $("#studentdef").html(html);
                    
                }
    
            });
        }
    
}


function getstudent(val)
{
    var notify = $("#notify_to").val();
    var sclid = $("#listschool").val();
    if(notify == "students")
    {
        $("#parnt_list").css("display", "none");
        $("#liststudent").html("");
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/notification/studentlist',
                data:{ 'schoolId': sclid, 'classid' : val},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    $("#studnt_list").css("display", "block");
                    $("#liststudent").html(html.data);
                }
    
            });
        }
    }
    else if(notify == "parents")
    {
        
        $("#studnt_list").css("display", "none");
        $("#listparent").html("");
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/notification/parentlist',
                data:{ 'schoolId': sclid, 'classid' : val},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    $("#parnt_list").css("display", "block");
                    $("#listparent").html(html.data);
                }
    
            });
        }
    }
}

function getschoolstudent(val)
{
    var notify = $("#notify_to").val();
    var sclid = $("#listschool").val();
    if(notify == "students")
    {
        $("#parnt_list").css("display", "none");
        $("#liststudent").html("");
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/schoolNotification/studentlist',
                data:{ 'schoolId': sclid, 'classid' : val},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    $("#studnt_list").css("display", "block");
                    $("#liststudent").html(html.data);
                }
    
            });
        }
    }
    else if(notify == "parents")
    {
        
        $("#studnt_list").css("display", "none");
        $("#listparent").html("");
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/schoolNotification/parentlist',
                data:{ 'schoolId': sclid, 'classid' : val},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    $("#parnt_list").css("display", "block");
                    $("#listparent").html(html.data);
                }
    
            });
        }
    }
}

function gettchrstudent(val)
{
    var notify = $("#notify_to").val();
    var sclid = $("#listschool").val();
    if(notify == "students")
    {
        $("#parnt_list").css("display", "none");
        $("#liststudent").html("");
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/teacherNotifications/studentlist',
                data:{ 'schoolId': sclid, 'classid' : val},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    $("#studnt_list").css("display", "block");
                    $("#liststudent").html(html.data);
                }
    
            });
        }
    }
    else if(notify == "parents")
    {
        
        $("#studnt_list").css("display", "none");
        $("#listparent").html("");
        if(val != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/teacherNotifications/parentlist',
                data:{ 'schoolId': sclid, 'classid' : val},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    $("#parnt_list").css("display", "block");
                    $("#listparent").html(html.data);
                }
    
            });
        }
    }
}



function check_stu_pin()
{
    //alert("hi");
    var sp1 = $("#s_pin_1").val();
    var sp2 = $("#s_pin_2").val();
    var sp3 = $("#s_pin_3").val();
    var sp4 = $("#s_pin_4").val();
    if(sp1 == "")
    {
        $("#s_pin_1").focus();
    }
    else
    {
        if(sp2 == "")
        {
            $("#s_pin_2").focus();
        }
        else
        {
            if(sp3 == "")
            {
                $("#s_pin_3").focus();
            }
            else
            {
                if(sp4 == "")
                {
                    $("#s_pin_4").focus();
                }
                else
                {
                    if((sp1 == 1) && (sp2 == 2) && (sp3 == 3) && (sp4 == 4))
                    {
                        var refscrf = $("input[name='_csrfToken']").val();
                        $.ajax({ 
                            url: baseurl +"/Studentdashboard/getsidebar", 
                            data: {_csrfToken : refscrf}, 
                            type: 'post',
                            success: function (response) 
                            {       
                                if(response.result == "success") 
                                {
                                    var baseurl = window.location.pathname.split('/')[1];
                                    var baseurl = "/" + baseurl
                                    location.href = baseurl +"/studentdashboard/studentprofile/" ;
                                    
                                }
                            }
                        });
                        
                    }
                    else
                    {
                        swal("Enter Correct Pin");
                    }
                }
            }
        }
    }
}

var datepickercalendar = new Datepickk();  
function closeCalendar(){
    var date = "";
    datepickercalendar.unselectAll();
    datepickercalendar.lang = 'fr';
    datepickercalendar.closeOnSelect = true;
    datepickercalendar.onClose = function(){
        datepickercalendar.closeOnClick = false;
        datepickercalendar.onClose = null;
    }
    datepickercalendar.onSelect = function(checked){
        var seldate = this.toLocaleDateString();
        
        var arr = seldate.split('/');
        var date = arr.join("_");
        var caldate = "#caldate_"+date ;
        $('div#gallery').animate({
            scrollTop: $(caldate).offset().top
        }, 800, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = caldate;
      });
    };
    datepickercalendar.show();
    
}

function getsubjectattendance(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $("#subject_list").html("");
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/Schoolattendance/getsubjects", 
        data: {"id":val,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {       
            if(result) 
            {
                $("#subject_list").html(result.data);
            }
        }
    });
}


function getclass(val)
{
    $('#student').val('').trigger('change.select2');
    //alert(val);
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $("#class").html("");
    var refscrf = $("input[name='_csrfToken']").val(); 
    $.ajax({ 
        url: baseurl +"/Summary/getclass", 
        data: {"id":val,_csrfToken : refscrf}, 
        type: 'post',
        success: function (result) 
        {     
            console.log(result);
            if(result) 
            {
                $("#class").html(result);
                
            }
        }
    });
}



$("#add_fee_tut").submit(function(e){
    e.preventDefault();
    $("#addtutbtn").prop("disabled", false);
    $("#addtutbtn").text(saving+"...");

 $(this).ajaxSubmit({
  error: function(){
      $("#addtutbtn").text("Save");
      $("#tuterror").html(errorocc) ;
      $("#tuterror").fadeIn().delay('5000').fadeOut('slow');
      $("#addtutbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#addtutbtn").text("Save");
      $("#addtutbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#tutsuccess").html(feeadd) ;
        $("#tutsuccess").fadeIn().delay('5000').fadeOut('slow');
        //classesadd();
        //$('#addsubform').trigger("reset");
        setTimeout(function(){ location.href = baseurl +"/tutorialfee" ;  }, 1000);
          }
      else if(response.result === "empty" ){
        $("#tuterror").html(filldetails) ;
        $("#tuterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "exist" ){
        $("#tuterror").html("Fee already exist.") ;
        $("#tuterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#tuterror").html(response.result) ;
        $("#tuterror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});


$("#edittutform").submit(function(e){
  e.preventDefault();
  
$("#edittutbtn").prop("disabled", true);
$("#edittutbtn").text(updating+"...");

 $(this).ajaxSubmit({
  error: function(){
      $("#edittutbtn").text("Update");
      $("#edittuterror").html(errorocc) ;
      $("#edittuterror").fadeIn().delay('5000').fadeOut('slow');
      $("#edittutbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#edittutbtn").text("Update");
      $("#edittutbtn").prop("disabled", false);
      if(response.result === "success" ){ 
            $("#edittutsuccess").html(feeup) ;
            $("#edittutsuccess").fadeIn();
            setTimeout(function(){ location.href = baseurl +"/tutorialfee" ;  }, 1000);
        }
        else if(response.result === "exist" ){
            $("#edittuterror").html("Fee already exist.") ;
            $("#edittuterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "empty" ){
        $("#edittuterror").html(filldetails) ;
        $("#edittuterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#edittuterror").html(response.result) ;
        $("#edittuterror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

$('#tut_table tbody').on("click",".editstruc",function()
{
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/tutorialfee/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
             console.log(result);
            $('#class').val(result[0]['class_id']).trigger('change.select2');
            $('#subjects').val(result[0]['subject_id']).trigger('change.select2');
            $('#efrequency').val(result[0]['frequency']).trigger('change.select2');
            $('#newselect_year').val(result[0]['session_id']).trigger('change.select2');
            $('#eid').val(id);
            $('#eamount').val(result[0]['fee']);  
          }
        }
    });
});


$('#viewstudyguidecontent').on("click",".studyguide",function()
{
    var file = $(this).data('file');
    $('#studyguidepdf').modal("show");  
    
    $("#viewstudypdf").html("<iframe src='"+origin+"/school/webroot/img/"+file+"#toolbar=0&navpanes=0&statusbar=0&view=Fit;readonly=true; disableprint=true;' width='780' height='550'></iframe>");
   
});


$("#add_fee_tut_school").submit(function(e){
    e.preventDefault();
    $("#addtutbtn").prop("disabled", false);
    $("#addtutbtn").text(saving+"...");

 $(this).ajaxSubmit({
  error: function(){
      $("#addtutbtn").text("Save");
      $("#tuterror").html(errorocc) ;
      $("#tuterror").fadeIn().delay('5000').fadeOut('slow');
      $("#addtutbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#addtutbtn").text("Save");
      $("#addtutbtn").prop("disabled", false);
      if(response.result === "success" ){ 
        $("#tutsuccess").html(feeadd) ;
        $("#tutsuccess").fadeIn().delay('5000').fadeOut('slow');
        //classesadd();
        //$('#addsubform').trigger("reset");
        setTimeout(function(){ location.href = baseurl +"/SchoolTutorialfee" ;  }, 1000);
          }
      else if(response.result === "empty" ){
        $("#tuterror").html(filldetails) ;
        $("#tuterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "exist" ){
        $("#tuterror").html("Fee already exist.") ;
        $("#tuterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#tuterror").html(response.result) ;
        $("#tuterror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});


$("#edittutform_school").submit(function(e){
  e.preventDefault();
  
$("#edittutbtn").prop("disabled", true);
$("#edittutbtn").text(updating+"...");

 $(this).ajaxSubmit({
  error: function(){
      $("#edittutbtn").text("Update");
      $("#edittuterror").html(errorocc) ;
      $("#edittuterror").fadeIn().delay('5000').fadeOut('slow');
      $("#edittutbtn").prop("disabled", false);
  },
  success: function(response)
  {
      $("#edittutbtn").text("Update");
      $("#edittutbtn").prop("disabled", false);
      if(response.result === "success" ){ 
            $("#edittutsuccess").html(feeup) ;
            $("#edittutsuccess").fadeIn();
            setTimeout(function(){ location.href = baseurl +"/SchoolTutorialfee" ;  }, 1000);
        }
        else if(response.result === "exist" ){
            $("#edittuterror").html("Fee already exist.") ;
            $("#edittuterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else if(response.result === "empty" ){
        $("#edittuterror").html(filldetails) ;
        $("#edittuterror").fadeIn().delay('5000').fadeOut('slow');
      }
      else{
        $("#edittuterror").html(response.result) ;
        $("#edittuterror").fadeIn().delay('5000').fadeOut('slow');
}
  } 
});     
return false;

});

$('#tut_table_school tbody').on("click",".editstruc_school",function()
{
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/SchoolTutorialfee/update", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
            $('#eclass').val(result[0]['class_id']);
            $('#esubjects').html('');
            $('#esubjects').html(result[1]);
            $('#eteacher').html('');
            $('#eteacher').html(result[2]);
            $('#efrequency').val(result[0]['frequency']).trigger('change.select2');
            $('#newselect_year').val(result[0]['session_id']).trigger('change.select2');
            $('#eid').val(id);
            $('#eamount').val(result[0]['fee']);  
          }
        }
    });
});

$('#knowledgecenter').on("click",".study_abroad",function()
{
    $("#abroad_study").modal('show');
});



$("#getunivform").submit(function(e)
{
    e.preventDefault();
    $("#searchbtn").prop("disabled", true);
    $("#searchbtn").text("Searching...");

    $(this).ajaxSubmit
    ({
        error: function()
        {
            $("#searchbtn").text("Update");
            $("#searcherror").html(errorocc) ;
            $("#searcherror").fadeIn().delay('5000').fadeOut('slow');
            $("#searchbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#searchbtn").text("Search Univesity");
            $("#searchbtn").prop("disabled", false);
            if(response.result === "success" ){ 
                setTimeout(function(){ location.href = baseurl +"/knowledgeCenter/listuniversity/"+response.country_id ;  }, 1000);
            }
            else if(response.result === "failed" ){
                $("#searcherror").html(nounifound) ;
                $("#searcherror").fadeIn().delay('5000').fadeOut('slow');
            }
            else{
                $("#searcherror").html(response.result) ;
                $("#searcherror").fadeIn().delay('5000').fadeOut('slow');
            }
    } 
});     
return false;

});

function getstudents_subject(get_val, id)
{
    
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    if(id == 'class'){
        var select_year = $("#select_year").val();
        var new_val = get_val;
    }else if(id == 'select_year'){
        var new_val = $("#class").val();
        var select_year = get_val;
        
    }
    
    $.ajax({
            type:'POST',
            url: baseurl + '/SchoolTutorialfee/getstudent',
            data:'classId='+new_val+'&start_year='+select_year,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                $( "#student" ).html('');
                $( "#student" ).append( "<option value=''></option>" );
                $.each(html[0], function(key,value) {
                    $( "#student" ).append( "<option value='"+value.id+"'>"+value.l_name+" "+value.f_name+" ("+value.email+" )</option>" );
                     // alert(value.f_name);
                }); 
                $('#subject').html('');
                $('#subject').html(html[3]).trigger('change.select2');
                $( "#month" ).html('');
                $( "#month" ).html(html[1]);
                /*$( "#session_amount" ).html('');
                $( "#session_amount" ).html('($'+html[2]+')');*/
                
                
            }

        });
    
}

$("#addstudenttutfees").submit(function(e){
    e.preventDefault();
    $("#addfeebtn").prop("disabled", true);
    $("#addfeebtn").text(saving);
    var select_year = $("#select_year").val();
    var new_val = $("#class").val();
    var studentid = $("#student").val();
    var frequency = $("#month").val();
    var amount = $("#amount").val();
    var subject = $("#subject").val();
    var teacher = $("#teacher").val();
    if(select_year != "" && studentid != "" && new_val != ""){
        $.ajax
        ({
            type:'POST',
            url: baseurl + '/SchoolTutorialfee/addstudentfees',
            data:'class='+new_val+'&start_year='+select_year+'&student='+studentid+'&frequency='+frequency+'&teacher='+teacher+'&subject='+subject+'&amount='+amount,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            error: function(){
                $("#feeerror").html(errorocc) ;
                $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
                $("#addfeebtn").prop("disabled", false);
                $("#addfeebtn").text(savescript);
            },
            success: function(response)
            {
                $("#addfeebtn").prop("disabled", false);
                $("#addfeebtn").text(savescript);
                if(response.result === "success" )
                { 
                    $("#feesuccess").html(feeadd) ;
                    $("#feesuccess").fadeIn().delay('5000').fadeOut('slow');;
                    
                    getstudents_data(studentid, student);
                    $('#addstudentfees').trigger("reset");
                    $("#month").val(null).trigger("change");
                    $("#amount").val();
                    $("#addfee").modal("hide");
                    //setTimeout(function(){ location.href = baseurl +"/fees/add" ;  }, 1000);
                }
                else if(response.result === "activity" ){
                    $("#feeerror").html(activityadd) ;
                    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
                }
                else
                {
                    $("#feeerror").html(response.result) ;
                    $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });     
    }
    else
    {
        $("#addfeebtn").prop("disabled", false);
        $("#addfeebtn").text(savescript);
        $("#feeerror").html(feestudata) ;
        $("#feeerror").fadeIn().delay('5000').fadeOut('slow');
    }
    return false;
});

$("#addlibcontentform").submit(function(e)
{
    $(".addtutcontbtn").text(saving);        
    e.preventDefault();
    $("#addtutcontbtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#addtutconterror").html(errorocc) ;
            $("#addtutconterror").fadeIn().delay('5000').fadeOut('slow');
            $("#addtutcontbtn").prop("disabled", false);
            $(".addtutcontbtn").text(savescript);  
        },
        success: function(response)
        {
            $("#addtutcontbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#addtutcontsuccess").html(libcontadd) ;
                $("#addtutcontsuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".addtutcontbtn").text(savescript);  
                setTimeout(function(){ location.href = baseurl +"/SchoolLibrary" ;  }, 1000);
            }
            else
            {
                $("#addtutconterror").html(response.result) ;
                $("#addtutconterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addtutcontbtn").text(savescript);
            }
        } 
    });     
    return false;
});

$("#saddtutcontentform").submit(function(e)
{
    $(".addtutcontbtn").text(saving);        
    e.preventDefault();
    $("#addtutcontbtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#addtutconterror").html(errorocc) ;
            $("#addtutconterror").fadeIn().delay('5000').fadeOut('slow');
            $("#addtutcontbtn").prop("disabled", false);
            $(".addtutcontbtn").text(savescript);  
        },
        success: function(response)
        {
            $("#addtutcontbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#addtutcontsuccess").html(tutcontadd) ;
                $("#addtutcontsuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".addtutcontbtn").text(savescript);  
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else
            {
                $("#addtutconterror").html(response.result) ;
                $("#addtutconterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addtutcontbtn").text(savescript);
            }
        } 
    });     
    return false;
});


$("#addtutcontentform").submit(function(e)
{
    $(".addtutcontbtn").text(saving);        
    e.preventDefault();
    $("#addtutcontbtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#addtutconterror").html(errorocc) ;
            $("#addtutconterror").fadeIn().delay('5000').fadeOut('slow');
            $("#addtutcontbtn").prop("disabled", false);
            $(".addtutcontbtn").text(savescript);  
        },
        success: function(response)
        {
            $("#addtutcontbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#addtutcontsuccess").html(tutcontadd) ;
                $("#addtutcontsuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".addtutcontbtn").text(savescript);  
                setTimeout(function(){ location.href = baseurl +"/tutorialfee/add/"+response.class+"/"+response.subject ;  }, 1000);
            }
            else
            {
                $("#addtutconterror").html(response.result) ;
                $("#addtutconterror").fadeIn().delay('5000').fadeOut('slow');
                $(".addtutcontbtn").text(savescript);
            }
        } 
    });     
    return false;
});


$('.edittutcontent').on("click",function()
{
    var controller = window.location.pathname.split('/')[2];
    //alert(controller);
    $('#eecoverimg').html(""); 
    var id = $(this).data('id');
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/tutorialfee/editcontent", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',success: function (html) 
        {       
         if (html) {
            console.log(html);
            var result = html.getdata;
            $("#efile_type").select2().val(result['file_type']).trigger('change.select2');
            var stuid = result['student_id'].split(",");
            $("#estudid").select2().val(stuid).trigger('change.select2');
            $('#etitle').val(result['title']);  
            $('#ekid').val(result['id']);    
        
            if(result['file_type'] == "video")
            {
                if(result['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result['links']);  
                }
                else
                {
                    $('#efile_link').val(result['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result['links']);
                $('#file_name').html(result['links']);
            }
             $("#evideotypes").select2().val(result['video_type']).trigger('change.select2'); 
            $('#ecoverimage').val(result['image']);
            $('#edesc').val(result['description']); 
            if(result['image'] != ''){
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result['image']+"' width='35px' height='30px'>"); 
            }else{
               $('#coverimg').html("<img src='http://www.you-me-globaleducation.org/youme-logo.png' width='35px' height='30px'>"); 
            }
            
            if(controller == "SchoolTutorialfee")
            {
                $("#ecls_sub").html(html.subjects);
                //var result = html.getdata;
                $("#eclassID").select2().val(result['class_id']).trigger('change.select2');
                $("#ecls_sub").select2().val(result['subject_id']).trigger('change.select2');
            }
            
          }
        }
    });
});

$("#sedittutcontentform").submit(function(e)
{
    //alert("hello");
    $(".edittutcontentgebtn").text(updating);        
    e.preventDefault();
    $("#edittutcontentgebtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#edittutcontenterror").html(errorocc) ;
            $("#edittutcontenterror").fadeIn().delay('5000').fadeOut('slow');
            $("#edittutcontentgebtn").prop("disabled", false);
            $(".edittutcontentgebtn").text(updatescript);  
        },
        success: function(response)
        {
            $("#edittutcontentgebtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#edittutcontentsuccess").html(tutcontup) ;
                $("#edittutcontentsuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".edittutcontentgebtn").text(updatescript);  
                setTimeout(function(){ location.reload() ;  }, 1000);
            }
            else
            {
                $("#edittutcontenterror").html(response.result) ;
                $("#edittutcontenterror").fadeIn().delay('5000').fadeOut('slow');
                $(".edittutcontentgebtn").text(updatescript);
            }
        } 
    });     
    return false;
});


$("#edittutcontentform").submit(function(e)
{
    //alert("hello");
    $(".edittutcontentgebtn").text(updating);        
    e.preventDefault();
    $("#edittutcontentgebtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#edittutcontenterror").html(errorocc) ;
            $("#edittutcontenterror").fadeIn().delay('5000').fadeOut('slow');
            $("#edittutcontentgebtn").prop("disabled", false);
            $(".edittutcontentgebtn").text(updatescript);  
        },
        success: function(response)
        {
            $("#edittutcontentgebtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#edittutcontentsuccess").html(tutcontup) ;
                $("#edittutcontentsuccess").fadeIn().delay('5000').fadeOut('slow');
                //$('#adduserform').trigger("reset");
                $(".edittutcontentgebtn").text(updatescript);  
                setTimeout(function(){ location.href = baseurl +"/tutorialfee/add/"+response.class+"/"+response.subject ;  }, 1000);
            }
            else
            {
                $("#edittutcontenterror").html(response.result) ;
                $("#edittutcontenterror").fadeIn().delay('5000').fadeOut('slow');
                $(".edittutcontentgebtn").text(updatescript);
            }
        } 
    });     
    return false;
});


$('.editlibcontent').on("click",function()
{
    
    $("#ecls_sub").html("");
    $('#eecoverimg').html(""); 
    var id = $(this).data('id');
   
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/schoolLibrary/editcontent", 
        data: {"id":id,_csrfToken : refscrf}, 
        type: 'post',
        success: function (html) 
        {       
         if (html) {
            console.log(html);
            $("#ecls_sub").html(html.subjects);
            var result = html.getdata;
            $("#efile_type").select2().val(result['file_type']).trigger('change.select2');
            $("#eclassID").select2().val(result['class_id']).trigger('change.select2');
            $("#ecls_sub").select2().val(result['subject_id']).trigger('change.select2');
            $('#etitle').val(result['title']);  
            $('#ekid').val(result['id']);    
            if(result[0]['file_type'] == "video")
            {
                if(result[0]['video_type'] == "d.tube")
                {
                    $('#edtube_video').val(result[0]['links']);  
                }
                else
                {
                    $('#efile_link').val(result[0]['links']);  
                }
            }
            else
            {
                $('#efileupload').val(result[0]['links']);
                $('#file_name').html(result[0]['links']);
            }
            $("#evideotypes").select2().val(result[0]['video_type']).trigger('change.select2');
            $('#ecoverimage').val(result['image']);
            $('#edesc').val(result['description']); 
            if(result['image'] != '')
            {
                $('#coverimg').html("<img src='"+baseurl+"/webroot/img/"+result['image']+"' width='35px' height='30px'>"); 
            }
            else
            {
               $('#coverimg').html("<img src='http://www.you-me-globaleducation.org/youme-logo.png' width='35px' height='30px'>"); 
            }
            
          }
        }
    });
});

$("#editlibcontentform").submit(function(e)
{
    //alert("hello");
    $(".editlibcontentgebtn").text(updating);        
    e.preventDefault();
    $("#editlibcontentgebtn").prop("disabled", true);
    $(this).ajaxSubmit(
    {
        error: function(){
            $("#editlibcontenterror").html(errorocc) ;
            $("#editlibcontenterror").fadeIn().delay('5000').fadeOut('slow');
            $("#editlibcontentgebtn").prop("disabled", false);
            $(".editlibcontentgebtn").text(updatescript);  
        },
        success: function(response)
        {
            $("#editlibcontentgebtn").prop("disabled", false);
            $(".editlibcontentgebtn").text(updatescript);
            if(response.result === "success" )
            { 
                $("#editlibcontentsuccess").html(libcontup) ;
                $("#editlibcontentsuccess").fadeIn().delay('5000').fadeOut('slow');
                setTimeout(function(){ location.href = baseurl +"/schoolLibrary" ;  }, 1000);
            }
            else
            {
                $("#editlibcontenterror").html(response.result) ;
                $("#editlibcontenterror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

$(document).on('click', '.spcomm_reply-btn', function(){
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
    var refscrf = $("input[name='_csrfToken']").val();
    $(document).on('click', '.spsubmit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var clsid = $("#clsid").val();
        var subid = $("#subid").val();
        $.ajax({
            url: baseurl +"/studentDiscussion/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                clsid : clsid,
                subid : subid
                
            },
            
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
                console.log(response);
                if(response.result == "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    //setTimeout(function(){ location.reload(true);  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.tcomm_reply-btn', function(){
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
   // var refscrf = $("input[name='_csrfToken']").val();
    $(document).on('click', '.tsubmit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        var skul_id = $("#school_id").val();
        var teacher_id = $("#teachr1id").val();
        //var mdkid = $("#mdkid").val();
        $.ajax({
            url: baseurl +"/tutorialfee/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                r_kid : r_kid,
                skul_id : skul_id,
                teacher_id : teacher_id
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
               
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload(true);  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.sclcomm_reply-btn', function(){
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
   // var refscrf = $("input[name='_csrfToken']").val();
    $(document).on('click', '.tsubmit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        var skul_id = $("#school_id").val();
        var teacher_id = $("#teachr1id").val();
        //var mdkid = $("#mdkid").val();
        $.ajax({
            url: baseurl +"/SchoolTutorialfee/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                r_kid : r_kid,
                skul_id : skul_id,
                teacher_id : teacher_id
                
            },
            beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		    },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
               
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload(true);  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

$(document).on('click', '.scomm_reply-btn', function(){
   
    var comment_id = $(this).data('id');
    $(this).parent().siblings('#comment_reply_form_' + comment_id).toggle(500);
    $("#reply_text").focus();
   // var refscrf = $("input[name='_csrfToken']").val();
    $(document).on('click', '.ssubmit-reply', function()
    {
        //alert("hi");
        var reply_textarea = $(this).siblings('textarea'); 
        var reply_text = $(this).siblings('textarea').val();
        var r_kid = $("#r_kid").val();
        var skul_id = $("#school_id").val();
        var teacher_id = $("#teachr1id").val();
        //var mdkid = $("#mdkid").val();
        $.ajax({
            url: baseurl +"/tutoringCenter/replycomments",
            type: "POST",
            data: {
                comment_id: comment_id,
                reply_text: reply_text,
                reply_posted: 1,
                r_kid : r_kid,
                skul_id : skul_id,
                teacher_id : teacher_id
                
            },
            beforeSend: function (xhr) { // Add this line
      xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
            error: function(){
                $("#replyCommenterror").html(errorocc) ;
                $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
            },
            success: function(response)
            {
               
                if(response.result === "success" )
                { 
                    $("#replyCommentsuccess").html(commposted) ;
                    $("#replyCommentsuccess").fadeIn().delay('5000').fadeOut('slow');
                    setTimeout(function(){ location.reload(true);  }, 1000);
                }
                else
                {
                    $("#replyCommenterror").html(response.result) ;
                    $("#replyCommenterror").fadeIn().delay('5000').fadeOut('slow');
                }
            } 
        });
    });
});

 /* Add cooments */


function community_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var clsfilter = $("#clsfilter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':clsfilter, 'filter':val, 'title': title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function kccls_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var comm_filter = $("#comm_filter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':val, 'filter':comm_filter, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function scl_kccls_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var comm_filter = $("#comm_filter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':val, 'filter':comm_filter, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function tchr_kccls_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var comm_filter = $("#comm_filter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':val, 'filter':comm_filter, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function stud_kccls_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var comm_filter = $("#comm_filter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':val, 'filter':comm_filter, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function parnt_kccls_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var comm_filter = $("#comm_filter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Parentknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':val, 'filter':comm_filter, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function kinder_kccls_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var comm_filter = $("#comm_filter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':val, 'filter':comm_filter, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function stateexam_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewstateexam',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function machinelearning_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var clsguide = $("#clsfilter").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewmachine',
        data:{'filter':val, 'added_for':added_for, 'title':title,  'clsguide':clsguide },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function guidecls_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    //alert(val);
    var title = $("#title_filter").val();
    var filter = $("#comm_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewmachine',
        data:{'filter':filter, 'added_for':added_for, 'title':title, 'clsguide':val },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function resttitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var filter = $("#comm_filter").val();
    var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledge/titlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl, 'added_for':added_for, 'clsfilter':clsfilter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function title_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var filter = $("#comm_filter").val();
    var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/titlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl, 'added_for':added_for, 'clsfilter':clsfilter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function etitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filter = $("#comm_filter").val();
    //var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/etitlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function scltitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var filter = $("#comm_filter").val();
    var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/titlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl, 'added_for':added_for, 'clsfilter':clsfilter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function scletitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filter = $("#comm_filter").val();
    //var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/etitlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function droptitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    //var added_for = $("#added_for").val();
    var filter = $("#comm_filter").val();
    var subjfilter = $("#subjfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Dropbox/titlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl, 'subjfilter':subjfilter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function tchrtitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var filter = $("#comm_filter").val();
    var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/titlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl, 'added_for':added_for, 'clsfilter':clsfilter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function tchretitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filter = $("#comm_filter").val();
    //var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/etitlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function studtitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var filter = $("#comm_filter").val();
    var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/studentknowledge/titlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl, 'added_for':added_for, 'clsfilter':clsfilter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function studetitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filter = $("#comm_filter").val();
    //var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/studentknowledge/etitlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function prnttitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var filter = $("#comm_filter").val();
    var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Parentknowledge/titlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl, 'added_for':added_for, 'clsfilter':clsfilter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function prntetitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filter = $("#comm_filter").val();
    //var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Parentknowledge/etitlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function kindrtitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var filter = $("#comm_filter").val();
    var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/titlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl, 'added_for':added_for, 'clsfilter':clsfilter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function kindretitle_filter(val,tbl)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filter = $("#comm_filter").val();
    //var clsfilter = $("#clsfilter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/etitlefilter',
        data:{'filter':filter, 'title':val, 'tbl':tbl },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function leadership_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewleader',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function howitworks_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewhowworks',
        data:{ 'filterfor':filterfor, 'filter':val, 'title': title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function intensive_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewintensive',
        data:{ 'filterfor':filterfor, 'filter':val,  'title': title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function internship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewintern',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function mentorship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewmentor',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function scholarship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewscholar',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function newtechnologies_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/viewtechnologies',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function school_community_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var clsfilter = $("#clsfilter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/schoolknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':clsfilter, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function teacher_community_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var clsfilter = $("#clsfilter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/teacherknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':clsfilter, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function student_community_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var clsfilter = $("#clsfilter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/studentknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':clsfilter, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function parent_community_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var clsfilter = $("#clsfilter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/parentknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':clsfilter, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function kinder_community_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var addedfor = $("#addedfor").val();
    var clsfilter = $("#clsfilter").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewcommunity',
        data:{ 'addedfor':addedfor, 'clsfilter':clsfilter, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function country_filter(val)
{
    $("#univbody").html("");
    //alert(val);
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/countryfilter',
        data:'country='+val,
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            //$("#univbody").html(result);
            //console.log(result);
            $('#univtable').DataTable().destroy();
            $('#univbody').html(result); 
            $( "#univtable" ).DataTable({
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

    });
    
}
function scountry_filter(val)
{
    $("#univbody").html("");
    //alert(val);
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/schoolknowledge/countryfilter',
        data:'country='+val,
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            //$("#univbody").html(result);
            //console.log(result);
            $('#univtable').DataTable().destroy();
            $('#univbody').html(result); 
            $( "#univtable" ).DataTable({
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

    });
    
}
function state_filter(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/statefilter',
        data:'state='+val,
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#city").html("");
            $("#city").html(result);
        }

    });
    
}
function city_filter(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var state = $("#state").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledgeCenter/cityfilter',
        data:{ city: val,state: state },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $('#localunivtable').DataTable().destroy();
            $('#localunivbody').html(result); 
            $( "#localunivtable" ).DataTable({
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

    }); 
    
}
function scity_filter(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var state = $("#state").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/schoolknowledge/cityfilter',
        data:{ city: val,state: state },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $('#localunivtable').DataTable().destroy();
            $('#localunivbody').html(result); 
            $( "#localunivtable" ).DataTable({
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

    });
    
}
function library_filter(val){
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsd = $("#class").val();
    var subd = $("#subject").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/schoolLibrary/viewtutcontent',
        data:{ 'filter': val, 'class_id':clsd, 'subject_id' : subd},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewtutcontent").html(result);
        }

    });
}

function viewtchrlibrary_filter(val){
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsd = $("#class").val();
    var subd = $("#subject").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/teacherLibrary/viewlibcontent',
        data:{ 'filter': val, 'class_id':clsd, 'subject_id' : subd},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewtutcontent").html(result);
        }

    });
}

function viewlibrary_filter(val){
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsd = $("#class").val();
    var subd = $("#subject").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/classLibrary/viewlibcontent',
        data:{ 'filter': val, 'class_id':clsd, 'subject_id' : subd},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewtutcontent").html(result);
        }

    });
}

function kviewlibrary_filter(val){
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsd = $("#class").val();
    var subd = $("#subject").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/kinderLibrary/viewlibcontent',
        data:{ 'filter': val, 'class_id':clsd, 'subject_id' : subd},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewtutcontent").html(result);
        }

    });
}

function knowledgebase_filter(val){
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledge/viewknowcontent',
        data:{ 'filter': val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewtutcontent").html(result);
        }

    });
}


function knowledge_filter(val){
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    //alert(title_filter);
    $.ajax({
        type:'POST',
        url: baseurl + '/knowledge/knowcontent',
        data:{ 'filter': val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewtutcontent").html(result);
        }

    });
}

function filterkinder(val){
    $("#viewdiscovery").html("");
    var dashid = $("#dashid").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsid = $("#classkinder").val();
    var subid = $("#subjectkinder").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/kindergarten/filterkinder',
        data:{ 'filter': val, 'dashid':dashid, 'clsid':clsid, 'subid': subid},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewdiscovery").html(result);
        }

    });
}

function filterkinderstud(val){
    $("#viewdiscovery").html("");
    var dashid = $("#dashid").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/kinderdashboard/filteractivities',
        data:{ 'filter': val, 'dashid':dashid},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewdiscovery").html(result);
        }

    });
} 

function filterkindertchr(val){
    $("#viewdiscovery").html("");
    var dashid = $("#dashid").val();
    
    var clsid = $("#classkinder").val();
    var subid = $("#subjectkinder").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherkindergarten/filteractivities',
        data:{ 'filter': val, 'dashid':dashid, 'clsid':clsid, 'subid': subid},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewdiscovery").html(result);
        }

    });
} 


function filterkindersubj(val){
    $("#viewdiscovery").html("");
    var dashid = $("#dashid").val();
    var filter = $("#comm_filter").val();
    var clsid = $("#classkinder").val();
    var subid = $("#subjectkinder").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherkindergarten/filteractivitiessub',
        data:{ 'subid': val, 'dashid':dashid, 'clsid':clsid, 'filter': filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewdiscovery").html(result);
        }

    });
} 

function filterkindersclsubj(val){
    $("#viewdiscovery").html("");
    var dashid = $("#dashid").val();
    var filter = $("#comm_filter").val();
    var clsid = $("#classkinder").val();
    var subid = $("#subjectkinder").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/kindergarten/filteractivitiessub',
        data:{ 'subid': val, 'dashid':dashid, 'clsid':clsid, 'filter': filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewdiscovery").html(result);
        }

    });
} 


function getlibrary_content(val){
    //alert(val);
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsd = $("#class").val();
    var filter = $("#tut_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/schoolLibrary/viewfiltercontent',
        data:{'filter': filter, 'class_id':clsd, 'subject_id' : val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewtutcontent").html(result);
        }

    });
    
}

function tutorial_filter(val){
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var class_s = $("#clsid").val();
    var subj = $("#subsid").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/tutorialfee/viewtutcontent',
        data:{'filter':val, 'class_s':class_s, 'subj':subj,},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewtutcontent").html(result);
        }

    });
    
}

function stututorial_filter(val){
    $("#viewtutcontent").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/tutoringCenter/viewtutcontent',
        data:'filter='+val,
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewtutcontent").html(result);
        }

    });
    
}


function getclass_subject(get_val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(get_val == "all")
    {
        $("#viewtutcontent").html("");
        var filter = $("#tut_filter").val();
        $.ajax({
            type:'POST',
            url: baseurl + '/schoolLibrary/getcontent',
            data:'filter='+filter,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                $("#viewtutcontent").html(html);
            }
    
        });
    }
    else
    {
        $('#subject').html('');
        $.ajax({
            type:'POST',
            url: baseurl + '/schoolLibrary/getstudent',
            data:'classId='+get_val,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                $('#subject').html(html);
            }
    
        });
    }
    
}

function subjctcls(val)
{
    $("#cls_sub").html("");
    $("#ecls_sub").html("");
    $('#request_for').val('').trigger('change') ;
    $('#erequest_for').val('').trigger('change') ;
    $("#eexamtypes").css("display", "none");
    $("#eexamperiod").css("display", "none");
    $("#examtypes").css("display", "none");
    $("#examperiod").css("display", "none");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/SchoolLibrary/getsubjects',
            data:{'classId':val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html)
                {    
                    $("#cls_sub").html(html);
                    $("#ecls_sub").html(html);
                }
          
            }

        });
    }
}


function subjctclssuperadmin(val)
{
    $("#cls_sub").html("");
    var sclid = $("#schoolid").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/SchoolMeetingReport/getsubjects',
            data:{'classId':val, 'schoolid':sclid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html)
                {    
                    $("#cls_sub").html(html);
                }
          
            }

        });
    }
}

function getteachers(val)
{
    $("#listteacher").html("");
    
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/SchoolMeetingReport/gettchrs',
            data:{'sclid':val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html)
                {    
                    $("#listteacher").html(html);
                }
          
            }

        });
    }
}

function getteachers_tut(val)
{
    $("#teacher").html("");
    var clsid = $("#class").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/SchoolTutorialfee/gettchrs',
            data:{'subid':val, 'clsid' : clsid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html)
                {    
                    $("#teacher").html(html);
                }
          
            }

        });
    }
}


function getdealercate(val)
{
    $("#dealrcat").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/products/getcategory',
            data:{'dealerid':val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html)
                {    
                    $("#dealrcat").html(html);
                }
          
            }

        });
    }
}
function getprodealercate(val, vari)
{
    if(vari == "dealers")
    {
        $("#pro_dealrcat").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        if(val)
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/products/getcategory',
                data:{'dealerid':val},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){
                    if(html)
                    {    
                        $("#pro_dealrcat").html(html);
                    }
              
                }
    
            });
        }
    }
    else
    {
        $("#pro_dealrcat").select2().val(val).trigger('change.select2');
    }
}

function getcategfilter(val)
{
    $("#dealerfilter").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/products/getdealers',
            data:{'catid':val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                if(html.catss == "noall")
                {    
                    $("#dealerfilter").html(html.dataa);
                }
                else
                {
                    $("#product_table" ).DataTable().destroy();
                    $("#productbody").html(html.dataa);   
                    $("#product_table" ).DataTable({
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

function viewcategfilter(val)
{
    $("#dealerfilter").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/schoolmarketplace/getdealers',
            data:{'catid':val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                if(html.catss == "noall")
                {    
                    $("#dealerfilter").html(html.dealer);
                    $("#sclproduct_table" ).DataTable().destroy();
                    $("#sclproductbody").html(html.dataa);   
                    $("#sclproduct_table" ).DataTable({
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
                else
                {
                    $("#sclproduct_table" ).DataTable().destroy();
                    $("#sclproductbody").html(html.dataa);   
                    $("#sclproduct_table" ).DataTable({
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
function dealerfilter(val)
{
    $("#productsfilter").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var catid = $("#categ").val();
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/products/getproducts',
            data:{'dealerid':val, 'categoryid':catid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                if(html)
                {    
                    $("#productsfilter").html(html.list);
                    
                    $("#product_table" ).DataTable().destroy();
                    $("#productbody").html(html.products);   
                    $("#product_table" ).DataTable({
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
function viewdealerfilter(val)
{
    $("#productsfilter").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var catid = $("#categ").val();
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/schoolmarketplace/getproducts',
            data:{'dealerid':val, 'categoryid':catid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                if(html)
                {    
                    $("#productsfilter").html(html.list);
                    
                    $("#sclproduct_table" ).DataTable().destroy();
                    $("#sclproductbody").html(html.products);   
                    $("#sclproduct_table" ).DataTable({
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

function productsfilter(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var catid = $("#categ").val();
    var dealerid = $("#dealerfilter").val();
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/products/getproductslist',
            data:{'dealerid':dealerid, 'categoryid':catid, 'productid':val,},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                if(result)
                {    
                    $("#product_table" ).DataTable().destroy();
                    $("#productbody").html(result.html);   
                    $("#product_table" ).DataTable({
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

function viewproductsfilter(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var catid = $("#categ").val();
    var dealerid = $("#dealerfilter").val();
    if(val)
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/schoolmarketplace/getproductslist',
            data:{'dealerid':dealerid, 'categoryid':catid, 'productid':val,},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                if(result)
                {    
                    $("#sclproduct_table" ).DataTable().destroy();
                    $("#sclproductbody").html(result.html);   
                    $("#sclproduct_table" ).DataTable({
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

$('#teacherclsstd').on("click",".studentdtldata",function()
{
    var id = $(this).data('id');
    var stdname = $(this).data('stdname');
    $("#studentdata").modal("show");
    $("#std_name").html("");
    $("#std_name").html(stdname);
    var cls = $(this).data('cls');
    var sub = $(this).data('sub');
    
    var refscrf = $("input[name='_csrfToken']").val();
    $.ajax({ 
        url: baseurl +"/teacherSubject/getexamass", 
        data: {"id":id, "cls":cls, "sub":sub ,_csrfToken : refscrf}, 
        type: 'post',success: function (result) 
        {       
         if (result) {
            console.log(result);
            $("#stdinfodata_table" ).DataTable().destroy();
            $("#stdinfobody").html(result.html);   
            $("#stdinfodata_table" ).DataTable({
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
});


function getsub(val)
{
    var prdslot = '';
    $("#subj_s").html("");
    $("#scltymgs").html("");
    $("#starttime").html("");
    $("#sclst").css("display","none");
    var tut_filter = $("#tut_filter").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/Timetable/getsubjectsss',
        data:{'filter':tut_filter, 'classId':val, 'prdslot':prdslot},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            console.log(html.getslot);
            var sclinfo = html.sclinfo;
            $("#subj_s").html("");
            if (html.sep == "subjects")
            {
                $("#subj_s").html(html.abc); 
                $("#sclst").css("display","inline");
                $("#scltymgs").html(sclinfo['school_stym']+ "-"+sclinfo['school_etym']);
                $("#starttime").html(html.getslot);
            }
        }
    });
}

/*function addMinutes(time, minutes) {
  var date = new Date(new Date('01/01/2015 ' + time).getTime() + minutes * 60000);
  var tempTime = ((date.getHours().toString().length == 1) ? '0' + date.getHours() : date.getHours()) + ':' +
    ((date.getMinutes().toString().length == 1) ? '0' + date.getMinutes() : date.getMinutes()) + ':' +
    ((date.getSeconds().toString().length == 1) ? '0' + date.getSeconds() : date.getSeconds());
  return tempTime;
}

function slots(strt, end, dur, breakstrt, breakend)
{
    var starttime = strt;
    var interval = dur;
    var endtime = end;
    var timeslots = [];
    //timeslots.push("<option value='"+starttime+"'>"+starttime+"</option>");
    
    var strt_brk = breakstrt.split(",");
    var end_brk = breakend.split(",");
    console.log(strt_brk);
    
    while (starttime <= endtime) {
       
        if(jQuery.inArray(starttime, strt_brk) !== -1)
        {
            var bint = strt_brk.indexOf(starttime);
            var brek = end_brk[bint].split(' minutes');
            var brktym = 1;
        }
        else
        {
            timeslots.push("<option value='"+starttime+"'>"+starttime+"</option>");
            var brktym = 0;
        }
        
        if(brktym == 0)
        {
            starttime = addMinutes(starttime, interval);
        }
        else
        {
            starttime = addMinutes(starttime, brek[0]);
        }
    } 
    
    return timeslots;
}
*/

function egetsub(val, ids, prdslot)
{
    if(prdslot == "test")
    {
        prdslot = "";
    }
    else
    {
        prdslot = prdslot;
    }
    if(ids == "eclass")
    {
        $("#esubj").html("");
        $("#escltymgs").html("");
        //alert("Fdsg");
        $("#estarttime").html("");
        $("#esclst").css("display","none");
        var tut_filter = $("#tut_filter").val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var sids = "";
        $.ajax({
            type:'POST',
            url: baseurl + '/Timetable/getsubjectsss',
            data:{'filter':tut_filter, 'classId':val, 'subId':sids, 'prdslot':prdslot},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                $("#esubj").html("");
                var sclinfo = html.sclinfo;
                if (html.sep == "subjects")
                {
                    $("#esubj").html(html.abc); 
                    $("#esclst").css("display","inline");
                    $("#escltymgs").html(sclinfo['school_stym']+ "-"+sclinfo['school_etym']);
                    $("#etimepicker").html(html.getslot);
                }
            }
        });
    }
    else
    {
        $("#esubj").html("");
        $("#escltymgs").html("");
        $("#estarttime").html("");
        $("#esclst").css("display","none");
        //alert("rg");
        var tut_filter = $("#tut_filter").val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/Timetable/getsubjectsss',
            data:{'filter':tut_filter, 'classId':val, 'subId':ids, 'prdslot':prdslot},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                $("#esubj").html("");
                var sclinfo = html.sclinfo;
                if (html.sep == "subjects")
                {
                    $("#esubj").html(html.abc); 
                    $("#esclst").css("display","inline");
                    $("#escltymgs").html(sclinfo['school_stym']+ "-"+sclinfo['school_etym']);
                 
                    if(prdslot != "test")
                    {
                        $("#etimepicker").html(html.getslot);
                    }
                }
            }
        });
    }
    
}
/*
function eslots(strt, end, dur, breakstrt, breakend, prdslot)
{
    var starttime = strt;
    var interval = dur;
    var endtime = end;
    var timeslots = [];
    //timeslots.push("<option value='"+starttime+"'>"+starttime+"</option>");
    
    var strt_brk = breakstrt.split(",");
    var end_brk = breakend.split(",");
    console.log(strt_brk);
    
    while (starttime <= endtime) {
       
        if(jQuery.inArray(starttime, strt_brk) !== -1)
        {
            var bint = strt_brk.indexOf(starttime);
            var brek = end_brk[bint].split(' minutes');
            var brktym = 1;
        }
        else
        {
            if(prdslot == starttime)
            {
                timeslots.push("<option value='"+starttime+"' selected>"+starttime+"</option>");
            }
            else
            {
                timeslots.push("<option value='"+starttime+"'>"+starttime+"</option>");
            }
            
            var brktym = 0;
        }
        
        if(brktym == 0)
        {
            starttime = addMinutes(starttime, interval);
        }
        else
        {
            starttime = addMinutes(starttime, brek[0]);
        }
    } 
    
    return timeslots;
}*/
function gettchrinfo(val)
{
    var classid = $("#class").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/Timetable/gettchrinfo',
        data:{'subid':val, 'classId':classid},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            console.log(html);
            $("#tchr_name").val(html.tchrname); 
            if(html.tchrid == null)
            {
                $("#tchr_id").val("");
            }
            else
            {
                $("#tchr_id").val(html.tchrid);
            }
            
        }
    });
}

$('#edittable').on('hidden.bs.modal', function () {
 location.reload();
})

$('#viewnotify').on('hidden.bs.modal', function () {
 location.reload();
})

function egettchrinfo(val)
{
    //alert(val);
   // $(this).children('option:not(:selected)').prop('disabled', true);
    var classid = $("#eclass").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/Timetable/gettchrinfo',
        data:{'subid':val, 'classId':classid},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(html){
            console.log(html);
            $("#etchr_name").val(html.tchrname); 
            if(html.tchrid == null)
            {
                $("#etchr_id").val("");
            }
            else
            {
                $("#etchr_id").val(html.tchrid);
            }
            
        }
    });
}

function gettimings(val)
{
    //alert(val);
    var slot = val.split(' ');
    alert(slot[0]);
    var sclstrt = $("#sclstrt").val();
    var sclend = $("#sclend").val();
    var end = sclend-slot[0];
    alert(end);
    /*var i = sclstrt;
    for (i = sclstrt; i <= end; i+slot[0]) {
      console.log(i);
    }*/
    
}

function getsubcls(val, ids)
{
    if((ids == "eclass") || (ids == "class"))
    {
        $("#subjects").html("");
        $("#esubjects").html("");
        $('#request_for').val('').trigger('change') ;
        $('#erequest_for').val('').trigger('change') ;
        $("#examtypes").css("display","none");
        $("#examperiod").css("display","none");
        $("#eexamtypes").css("display","none");
        $("#eexamperiod").css("display","none");
        var subid = '';
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/teacherdashboard/getsubjecttchr',
            data:{'clsid':val, 'subid':subid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(data){
                console.log(data);
                $("#subjects").html(data.subjectname);
                $("#esubjects").html(data.subjectname);
            }
        });
    }
    else
    {
        $("#esubjects").html("");
        $("#esubjects").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/teacherdashboard/getsubjecttchr',
            data:{'clsid':val, 'subid':ids},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(data){
                console.log(data);
                $("#esubjects").html(data.subjectname);
            }
        });
    }
}

function getsubclsfilter(val, ids)
{
    if(ids == "classkinder")
    {
        var dashid = $("#dashid").val();
        var filter = $("#comm_filter").val();
        
        
        $("#subjectkinder").html("");
        $("#viewdiscovery").html("");
        var subid = '';
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/teacherkindergarten/getsubjecttchr',
            data:{'clsid':val, 'filter':filter, 'dashid':dashid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(data){
                console.log(data);
                $("#subjectkinder").html(data.subjectname);
                $("#viewdiscovery").html(data.viewdata);
            }
        });
    }
}

function getsclclsfilter(val, ids)
{
    if(ids == "classkinder")
    {
        var dashid = $("#dashid").val();
        var filter = $("#comm_filter").val();
        
        
        $("#subjectkinder").html("");
        $("#viewdiscovery").html("");
        var subid = '';
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/kindergarten/getsubjecttchr',
            data:{'clsid':val, 'filter':filter, 'dashid':dashid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(data){
                console.log(data);
                $("#subjectkinder").html(data.subjectname);
                $("#viewdiscovery").html(data.viewdata);
            }
        });
    }
}
/**************school filters for youme academy sections *******************/


function scl_stateexam_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewstateexam',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function scl_machinelearning_filter(val)
{
    $("#viewcommunity").html("");
    var added_for = $("#added_for").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsguide = $("#clsfilter").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewmachine',
        data:{'filter':val, 'added_for':added_for , 'clsguide':clsguide, 'title':title },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function scl_guidecls_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    //var filter = $("#comm_filter").val();
    var title = $("#title_filter").val();
    var filter = $("#comm_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewmachine',
        data:{'filter':filter, 'added_for':added_for, 'title':title, 'clsguide':val },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function scl_leadership_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewleader',
        data:{'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function scl_howitworks_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val(); 
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewhowworks',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function par_howitworks_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val(); 
    
    $.ajax({
        type:'POST',
        url: baseurl + '/Parentknowledge/viewhowworks',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function scl_intensive_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewintensive',
        data:{ 'filterfor':filterfor, 'filter':val, 'title': title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function scl_internship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewintern',
        data:{'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function scl_mentorship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewmentor',
        data:{'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function scl_scholarship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewscholar',
        data:{'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function scl_newtechnologies_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Schoolknowledge/viewtechnologies',
        data:{'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

/**************student filters for youme academy sections *******************/


function stud_stateexam_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewstateexam',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function parent_stateexam_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/parentknowledge/viewstateexam',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function kinder_stateexam_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewstateexam',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function stud_machinelearning_filter(val)
{
    $("#viewcommunity").html("");
    var added_for = $("#added_for").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsguide = $("#clsfilter").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewmachine',
        data:{'filter':val, 'added_for':added_for , 'clsguide':clsguide, 'title':title },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function parent_machinelearning_filter(val)
{
    $("#viewcommunity").html("");
    var added_for = $("#added_for").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsguide = $("#clsfilter").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/parentknowledge/viewmachine',
        data:{'filter':val, 'added_for':added_for , 'clsguide':clsguide, 'title':title },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console
            $("#viewcommunity").html(result);
        }

    });
}
function kinder_machinelearning_filter(val)
{
    $("#viewcommunity").html("");
    var added_for = $("#added_for").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var clsguide = $("#clsfilter").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewmachine',
        data:{'filter':val, 'added_for':added_for , 'clsguide':clsguide, 'title':title },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}


function stud_leadership_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewleader',
        data:{'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function parent_leadership_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/parentknowledge/viewleader',
       data:{'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function kinder_leadership_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewleader',
        data:{'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function stud_howitworks_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val(); 
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewhowworks',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function kinder_howitworks_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val(); 
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewhowworks',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function stud_intensive_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewintensive',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function parent_intensive_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Parentknowledge/viewintensive',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function kinder_intensive_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewintensive',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function stud_internship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewintern',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function parent_internship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/parentknowledge/viewintern',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function kinder_internship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewintern',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function kinder_mentorship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewmentor',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function kinder_scholarship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewscholar',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function kinder_newtechnologies_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Kinderknowledge/viewtechnologies',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function stud_mentorship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewmentor',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function parent_mentorship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/parentknowledge/viewmentor',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function stud_scholarship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewscholar',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function parent_scholarship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/parentknowledge/viewscholar',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function stud_newtechnologies_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Studentknowledge/viewtechnologies',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}
function parent_newtechnologies_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/parentknowledge/viewtechnologies',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

/************** teacher  filters for youme academy sections *******************/


function tchr_stateexam_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewstateexam',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title_filter},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function tchr_machinelearning_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var clsguide = $("#clsfilter").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewmachine',
        data:{'filter':val, 'added_for':added_for , 'clsguide':clsguide, 'title':title },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function sharedcontent(val)
{
    $("#add_shared_to").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/sharedcontent',
        data:{'guide':val },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#add_shared_to").html(result);
            $("#shared_content_modal").modal('show');
        }
    });
}

function tchr_guidecls_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var added_for = $("#added_for").val();
    var filter = $("#comm_filter").val();
     var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewmachine',
        data:{'filter':filter, 'added_for':added_for, 'clsguide':val, 'title':title  },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function tchr_leadership_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewleader',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function tchr_howitworks_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val(); 
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewhowworks',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function tchr_intensive_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var filterfor = $("#addedfor").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewintensive',
        data:{ 'filterfor':filterfor, 'filter':val, 'title':title},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }

    });
    
}

function tchr_internship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewintern',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function tchr_mentorship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewmentor',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function tchr_scholarship_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewscholar',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}

function tchr_newtechnologies_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title_filter = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/Teacherknowledge/viewtechnologies',
        data:{'filter':val, 'title':title_filter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
/*********** class filter ************/
function getscl_sctn(val)
{
    $("#sections").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/Classes/getsclsctns',
        data:'cname='+val,
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            $("#sections").html(result.html);
            
            $('#class_table').DataTable().destroy();
            $('#classbody').html(result.tabledata); 
            $( "#class_table" ).DataTable({
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
    });
}

function getclasses_grades(val)
{
    $("#aclass").html("");
    var cname = $("#grades").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/Classes/getclssctns',
        data:{'cname':cname, 'sclsectn':val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            $("#aclass").html(result.html);
            
            $('#class_table').DataTable().destroy();
            $('#classbody').html(result.tabledata); 
            $( "#class_table" ).DataTable({
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
    });
}
function getclassessections(val)
{
    
    var cname = $("#grades").val();
    var sclsectn = $("#sections").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/Classes/getsctns',
        data:{'cname':cname, 'sclsectn':sclsectn, 'classes':val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            
            $('#class_table').DataTable().destroy();
            $('#classbody').html(result.tabledata); 
            $( "#class_table" ).DataTable({
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
    });
}

/************ class subject fileter*************/

function getscl_sctn1(val)
{
    $("#sections").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/ClassSubjects/getsclsctns',
        data:'cname='+val,
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            $("#sections").html(result.html);
            
            $('#subjectsclass_table').DataTable().destroy();
            $('#subjectsclassbody').html(result.tabledata); 
            $( "#subjectsclass_table" ).DataTable({
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
    });
}

function getclasses_grades1(val)
{
    $("#aclass").html("");
    var cname = $("#grades").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/ClassSubjects/getclssctns',
        data:{'cname':cname, 'sclsectn':val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            $("#aclass").html(result.html);
            
            $('#subjectsclass_table').DataTable().destroy();
            $('#subjectsclassbody').html(result.tabledata); 
            $( "#subjectsclass_table" ).DataTable({
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
    });
}
function getclassessections1(val)
{
    
    var cname = $("#grades").val();
    var sclsectn = $("#sections").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/ClassSubjects/getsctns',
        data:{'cname':cname, 'sclsectn':sclsectn, 'classes':val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            
            $('#subjectsclass_table').DataTable().destroy();
            $('#subjectsclassbody').html(result.tabledata); 
            $( "#subjectsclass_table" ).DataTable({
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
    });
}

function getschoolstudents(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $('#class').html("");
    $.ajax({
        type:'POST',
        url: baseurl + '/LibraryAccessReport/getallstud',
        data:{'sclid':val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $('#libaccess_table').DataTable().destroy();
            $('#libaccess_body').html(result.tabledata); 
            $( "#libaccess_table" ).DataTable({
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
            
            
            $('#class').html(result.classe);
        }
    });
}

function getsessionstudent(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var sclid = $('#schoolid').val();
    if(sclid != "")
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/LibraryAccessReport/getsessstud',
            data:{'sclid':sclid, 'sessionid' : val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                $('#libaccess_table').DataTable().destroy();
                $('#libaccess_body').html(result.tabledata); 
                $( "#libaccess_table" ).DataTable({
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
        });
    }
    else
    {
        swal("Please Select school first");
    }
}

function getclassstudents(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var sclid = $('#schoolid').val();
    var session = $('#session').val();
    if(session != "")
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/LibraryAccessReport/getclsstud',
            data:{'sclid':sclid, 'sessionid' : session, 'classid' : val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                $('#libaccess_table').DataTable().destroy();
                $('#libaccess_body').html(result.tabledata); 
                $( "#libaccess_table" ).DataTable({
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
        });
    }
    else
    {
        swal("Please Select Session First");
    }
}
function getsclsessionstudent(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax({
        type:'POST',
        url: baseurl + '/SchoolLibraryAccessReport/getsessstud',
        data:{'sessionid' : val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $('#libaccess_table').DataTable().destroy();
            $('#libaccess_body').html(result.tabledata); 
            $( "#libaccess_table" ).DataTable({
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
    });
}

function getsclclassstudents(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    var session = $('#session').val();
    if(session != "")
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/SchoolLibraryAccessReport/getclsstud',
            data:{'sessionid' : session, 'classid' : val},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                $('#libaccess_table').DataTable().destroy();
                $('#libaccess_body').html(result.tabledata); 
                $( "#libaccess_table" ).DataTable({
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
        });
    }
    else
    {
        swal("Please Select Session First");
    }
}

$(document).on('click', '.viewpdffile', function(){
    $("#viewfile").html("");
    var pfile = $(this).data('file');
    var dirname = $(this).data('dirname');
    var nopge = $(this).data('nopge');
    $("#viewpdffile").modal("show");
    var x = '';
    //$("#viewfile").append('<div class="col-sm-12 text-center" style="margin: auto;height: 500px;overflow: scroll;">');
    for (x = 0; x < nopge; x++) {
    $("#viewfile").append('<img src="http://you-me-globaleducation.org/school/'+dirname+'/'+pfile+ x+'.jpg" style="width: 100%;">');
    } 
});

function getsessionstudentss(val)
{
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/Students/getstudsess',
        data:{'sessionid' : val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $('#student_table').DataTable().destroy();
            $('#studentbody').html(result); 
            $("#student_table" ).DataTable({
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
    });
}

function showstatusConfirmMessage1(id,url,str,sts) {
    var refscrf = $("input[name='_csrfToken']").val() ;
   
    url = baseurl+"/"+url;
    swal({
        title: areyou,
        text: chngsts,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yeschng,
        closeOnConfirm: false,
        cancelButtonText: cncl,  
        showLoaderOnConfirm: true
    }, function () {
        $.ajax({
            data : {
                val : id ,
               _csrfToken : refscrf,
               str : str,
               sts : sts
            },
            type : "post",
			url : url,
			
            success: function(response){
               //alert(response);
                if(response.result == "success"){
                    swal(statuschng, str+" "+ haschng, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
}

/***********  Attendance Report filter ************/
function ar_getscl_sctn(val)
{
    $("#sections").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/AttendanceReport/getsclsctns',
        data:'cname='+val,
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            $("#sections").html(result.html);
            
            $('#class_table').DataTable().destroy();
            $('#classbody').html(result.tabledata); 
            $( "#class_table" ).DataTable({
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
    });
}

function ar_getclasses_grades(val)
{
    $("#aclass").html("");
    var cname = $("#grades").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/AttendanceReport/getclssctns',
        data:{'cname':cname, 'sclsectn':val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            $("#aclass").html(result.html);
            
            $('#class_table').DataTable().destroy();
            $('#classbody').html(result.tabledata); 
            $( "#class_table" ).DataTable({
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
    });
}
function ar_getclassessections(val)
{
    
    var cname = $("#grades").val();
    var sclsectn = $("#sections").val();
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    
    $.ajax({
        type:'POST',
        url: baseurl + '/AttendanceReport/getsctns',
        data:{'cname':cname, 'sclsectn':sclsectn, 'classes':val},
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            console.log(result);
            
            $('#class_table').DataTable().destroy();
            $('#classbody').html(result.tabledata); 
            $( "#class_table" ).DataTable({
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
    });
}

function getperiod(val)
{
    $("#examperiod").css("display", "none");
    var cls = $("#s_listclass").val();
    var req = $("#request_for").val();
    if(cls != "")
    {
        $.ajax({
            type:'POST',
            url: baseurl + '/ExamAssessment/getperiodlst',
            data:{'cls':cls, 'exmtyp': val, 'req' : req},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){ 
                console.log(html);
                $("#examperiod").css("display", "none");
                $("#exam_period").html("");
                if(html.sections != "caternelle" || html.sections != "creche")
                {
                    $("#examperiod").css("display", "block");
                    $("#exam_period").html(html.result);
                }
            }
        });
    }
    else
    { 
        $("#submitreqerror").html("Please select class first.") ;
        $("#submitreqerror").fadeIn().delay('5000').fadeOut('slow');
        $("#examasserror").html("Please select class first.") ;
        $("#examasserror").fadeIn().delay('5000').fadeOut('slow');
    }
}

function egetperiod(val, id)
{
   
    if(id == "eexam_type")
    {
        var periodid = "";
    }
    else
    {
        var periodid = id;
    }
    $("#eexamperiod").css("display", "none");
    var cls = $("#s_listclass").val();
    var cls2 = $("#m_listclass").val();
    var req = $("#erequest_for").val();
    
    if(req != "Study Guide")
    {
        if(cls != "" && cls2 == undefined)
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/ExamAssessment/getperiodlst',
                data:{'req' : req, 'cls':cls, 'exmtyp': val, 'periodid':periodid},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    console.log(html);
                    //$("#eexamperiod").css("display", "none");
                    $("#eexam_period").html("");
                    if(html.sections != "maternelle" || html.sections != "creche")
                    {
                        $("#eexamperiod").css("display", "block");
                        $("#eexam_period").html(html.result);
                    }
                }
            });
        }
        else if(cls == undefined && cls2 != "")
        {
            $.ajax({
                type:'POST',
                url: baseurl + '/ExamAssessment/getperiodlst',
                data:{'req' : req, 'cls':cls2, 'exmtyp': val, 'periodid':periodid},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success:function(html){ 
                    console.log(html);
                    //$("#eexamperiod").css("display", "none");
                    $("#eexam_period").html("");
                    if(html.sections != "maternelle" || html.sections != "creche")
                    {
                        $("#eexamperiod").css("display", "block");
                        $("#eexam_period").html(html.result);
                    }
                }
            });
        }
        else
        {
            $("#editexamasserror").html("Please select class first.") ;
            $("#editexamasserror").fadeIn().delay('5000').fadeOut('slow');
        }
    }
}

function dropbox_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var subjfilter = $("#subjfilter").val();
    var title = $("#title_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/dropbox/viewmachine',
        data:{'filter':val, 'title':title,  'subjfilter':subjfilter },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function dropbox_sub_filter(val)
{
    $("#viewcommunity").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    var title = $("#title_filter").val();
    var filter = $("#comm_filter").val();
    $.ajax({
        type:'POST',
        url: baseurl + '/dropbox/viewmachine',
        data:{'filter':filter, 'title':title, 'subjfilter':val },
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success:function(result){
            $("#viewcommunity").html(result);
        }
    });
}
function getsesscoupndata(val)
{
    var sessionid = $("#session").val();
    
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax
    ({
        data : {'cls':val, 'sessionid':sessionid },
        type : "post",
		url: baseurl + '/Feediscount/getsessionchngedata',
		beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success: function(response){
            console.log(response);
            $("#feedis_table").DataTable().destroy();
            $('#feedisbody').html(response);
            $( "#feedis_table" ).DataTable({
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
    })  
}
function getcd(val)
{
    $("#instlmnt").select2().val("").trigger('change.select2');
}


function getstdnt(val, abc)
{
    if(abc == "add")
    {
        $("#eguid_class").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/Teacherdashboard/getstdnt',
            data:{'filter':val },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                $("#examselstdnt").html(result['student']);
                
            }
        });
    }
    else if(abc == "editd")
    {
        $("#examselclssctn").html("");
        $("#eexamselclssctn").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/Teacherdashboard/getsections',
            data:{'filter':val },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                $("#examselclssctn").html(result);
                $("#eexamselclssctn").html(result);
            }
        });
    }
    else
    {
        var cls = abc.split(",");
        console.log(cls);
        $("#examselclssctn").select2().val(cls).trigger('change.select2');
        $("#eexamselclssctn").select2().val(cls).trigger('change.select2');
        
        
    }
}
function getgradecls(val, abc)
{
    if(abc == "add")
    {
        $("#examselclssctn").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/Teacherdashboard/getsections',
            data:{'filter':val },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                $("#examselclssctn").html(result['section']);
                $("#examselstdnt").html(result['student']);
                
            }
        });
    }
    else if(abc == "editd")
    {
        $("#examselclssctn").html("");
        $("#eexamselclssctn").html("");
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax({
            type:'POST',
            url: baseurl + '/Teacherdashboard/getsections',
            data:{'filter':val },
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(result){
                console.log(result);
                $("#examselclssctn").html(result);
                $("#eexamselclssctn").html(result);
            }
        });
    }
    else
    {
        var cls = abc.split(",");
        console.log(cls);
        $("#examselclssctn").select2().val(cls).trigger('change.select2');
        $("#eexamselclssctn").select2().val(cls).trigger('change.select2');
        
        
    }
}

function getvendorsfoodsts(sclid)
{
    $("#vendors").html("");
    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/" + baseurl;
    $.ajax
    ({
        data : {'sclid':sclid },
        type : "post",
		url: baseurl + '/Canteenvendors/getvendors',
		beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success: function(response){
            console.log(response);
            $("#vendors").html(response);
        }
    })  
}
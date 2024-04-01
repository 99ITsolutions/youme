$(function () {

    var baseurl = window.location.pathname.split('/')[1];
    var baseurl = "/"+baseurl;

    //alert(baseurl);
//alert("ds");
    $('.js-sweetalert').on('click', function () {
		
        var type = $(this).data('type');		
        var id =  $(this).data("id") ;
        var url =  $(this).data("url") ;
        var str =  $(this).data("str") ;
        var geturl = $(this).data("get") ;
       
        if (type === 'confirm') {
            showConfirmMessage(id,url,str);
        }
        else if (type === 'approve_status') {
			var sts =  $(this).data("status") ;
			url = baseurl+"/"+url;
            approvestatusConfirmMessage(id,url,str,sts);
        }
        else if (type === 'status_change') {
			var sts =  $(this).data("status") ;
            showstatusConfirmMessage(id,url,str,sts);
        }
       
    });
});

function showstatusConfirmMessage(id,url,str,sts) {
    var refscrf = $("input[name='_csrfToken']").val() ;
    
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


function approvestatusConfirmMessage(id,url,str,sts) {
    var refscrf = $("input[name='_csrfToken']").val() ;
    
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


function showConfirmMessage(id,url,str) {
	
    var refscrf = $("input[name='_csrfToken']").val() ;
    
    swal({
        title: areyou,
        text: recover,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007bff",
        confirmButtonText: yesdelete,
        cancelButtonText: cncl,  
        closeOnConfirm: false,
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
                    swal(deleted, str+" "+ hasbeen, "success");
                    setTimeout(function(){ location.reload() ;  }, 1000);
                }
                else{
                    swal(errorpop, response.result, "error");
                }
            }
        })
    });
}


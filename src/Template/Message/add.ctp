<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
?><link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<style>
    .hide
    {
        display:none;
    }
    .input-group-text{
        font-size:0.8em;
    }
.js .inputfile {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}

.inputfile + label {
    max-width: 80%;
    font-size: 1.25rem;
    /* 20px */
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    display: inline-block;
    overflow: hidden;
    padding: 0.625rem 1.25rem;
    /* 10px 20px */
}

.no-js .inputfile + label {
    display: none;
}

.inputfile:focus + label,
.inputfile.has-focus + label {
    outline: 1px dotted #000;
    outline: -webkit-focus-ring-color auto 5px;
}

.inputfile + label * {
    /* pointer-events: none; */
    /* in case of FastClick lib use */
}

.inputfile + label svg {
    width: 1em;
    height: 1em;
    vertical-align: middle;
    fill: currentColor;
    margin-top: -0.25em;
    /* 4px */
    margin-right: 0.25em;
    /* 4px */
}


/* style 1 */

.inputfile-1 + label {
    color: #f1e5e6;
    background-color: #FBA80F;
        float: left;
}

.inputfile-1:focus + label,
.inputfile-1.has-focus + label,
.inputfile-1 + label:hover {
    background-color: #722040;
}
input[type=file] {
    display: none;
}
</style>
<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                            
            </div>
            <div class="body container">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'addmessage'] , 'id' => "send_message" , 'method' => "post"  ]); ?>
                    <div class="row clearfix">
                        <div class="row "><hr>
                            <div class="col-sm-3">
                                <label>Class</label>
                                <div class="form-group">
                                    <select class="form-control class" id="class" name="class" required onchange="getclass_data(this.value, this.id)">
                                        <option value="">Choose Class</option>
                                        <?php
                                        foreach($class_details as $key => $val){
                                        ?>
                                          <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>                                    
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group"> 
                                    <label>Choose Type</label>
                                    <select class="form-control class" id="role_type" name="role_type" onchange="getclass_data(this.value, this.id)" required>
                                        <option value="">Choose Type</option>
                                        <option value="student">Student</option>
                                        <option value="parent">Parent</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <label>Student</label>
                                <div class="form-group">
                                <select class="form-control class" name="student" id="student" placeholder="Choose Student" required>
                                    <option value="">Choose Student</option>
                                </select>  
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Subject</label>
                                <div class="form-group">
                                <input type="text" class="form-control" name="subject" required placeholder="Subject"> 
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <textarea name="message"class="form-control" rows="10" placeholder="Message here..."></textarea>
                                </div>
                            </div>
                             <div class="col-sm-12">
                                <div class="error" id="msgsenderror">
                                </div>
                                <div class="success" id="msgsendsuccess">
                                </div>
                            </div>
                            <div class="button_row" >
                                <hr>
                					<input type="file" name="upload_file" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected"  />
                					<label for="file-1"><span><i class="fa fa-plus"></i> Attachment</span></label>
                				
                                <button type="submit" class="btn btn-primary addstrucbtn" id="msgsendbtn" style="margin-right: 10px;">Send</button>
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
'use strict';

;( function ( document, window, index )
{
	var inputs = document.querySelectorAll( '.inputfile' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Firefox bug fix
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
	});
}( document, window, 0 ));


function getclass_data(get_val, id)
{
    
    $(".subject_field").css("display", "block");
    var baseurl = window.location.pathname.split('/')[1];
	var baseurl = "/" + baseurl;
	
	if(id == 'class'){
        var role_type = $("#role_type").val();
        var new_val = get_val;
    }else if(id == 'role_type'){
        var new_val = $("#class").val();
        var role_type = get_val;
        
    }
	
    $.ajax({
		type:'POST',
		url: baseurl + '/message/getstudent',
		data:'classId='+new_val+'&role_type='+role_type,
		beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		},
		success:function(html){
		    /*alert(html[0]);
		    var obj = jQuery.parseJSON(html);
		    alert(obj);*/
		    $( "#student" ).html('');
		    $( "#student" ).append( "<option value=''></option>" );
		    if(role_type == 'student'){
                $.each(html, function(key,value) {
                    $( "#student" ).append( "<option value='"+value.id+"'>"+value.f_name+" "+value.l_name+" ("+value.email+" )</option>" );
                     // alert(value.f_name);
                }); 
		    }else if(role_type == 'parent'){
		        $.each(html, function(key,value) {
                    $( "#student" ).append( "<option value='"+value.id+"'>"+value.s_f_name+" ("+value.email+" )</option>" );
                     // alert(value.f_name);
                });
		    }
		}

	});
}

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
                $("#msgsendsuccess").html("Teachers added succesfully.") ;
                $("#msgsendsuccess").fadeIn();
                setTimeout(function(){ location.href = baseurl +"/teachers/" ;  }, 1000);
                      
            }else if(response.result === "empty" ){
                $("#msgsenderror").html("Please fill in Details.") ;
                $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
                
            }else if(response.result === "activity" ){
                $("#msgsenderror").html("Activity not added.") ;
                $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
                
            }else{
                $("#msgsenderror").html("Some error occured. Error: "+response.result) ;
                $("#msgsenderror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});
  
</script>
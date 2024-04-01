<?php
    $tchr = array('No','Yes' );
    $emp = array('No','Yes' );
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <a href="<?=$baseurl?>TeacherMessages" title="Add" class="btn btn-sm btn-success" style="float:right"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1370') { echo $langlbl['title'] ; } } ?></a>            
            </div>
            <div class="body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'addmessage'] , 'id' => "tsend_message" , 'method' => "post"  ]); ?>
                    <div class="row ">
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1385') { echo $langlbl['title'] ; } } ?>*</label>
                            <div class="form-group">
                            <input type="text" class="form-control" name="subject" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1389') { echo $langlbl['title'] ; } } ?>"> 
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1386') { echo $langlbl['title'] ; } } ?></label>
                            <div class="form-group">
                                <input type="file" name="upload_file" id="upload_file" class="form-control"  />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1390') { echo $langlbl['title'] ; } } ?>*</label>
                                <textarea id="descmessage" name="descmessage" class="form-control" rows="10" required></textarea>
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
                            <button type="submit" class="btn btn-primary addstrucbtn" id="msgsendbtn" style="margin-right: 10px;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1391') { echo $langlbl['title'] ; } } ?></button>
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

  
</script>
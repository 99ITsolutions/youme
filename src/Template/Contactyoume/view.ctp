<style>
    .hide
    {
        display:none;
    }
    .input-group-text{
        font-size:0.8em;
    }
    .widget-area.blank {
background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
-webkit-box-shadow: none;
-moz-box-shadow: none;
-ms-box-shadow: none;
-o-box-shadow: none;
box-shadow: none;
}
body .no-padding {
padding: 0;
}
.widget-area {
background-color: #fff;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-ms-border-radius: 4px;
-o-border-radius: 4px;
border-radius: 4px;
-webkit-box-shadow: 0 0 16px rgba(0, 0, 0, 0.05);
-moz-box-shadow: 0 0 16px rgba(0, 0, 0, 0.05);
-ms-box-shadow: 0 0 16px rgba(0, 0, 0, 0.05);
-o-box-shadow: 0 0 16px rgba(0, 0, 0, 0.05);
box-shadow: 0 0 16px rgba(0, 0, 0, 0.05);
float: left;
margin-top: 30px;
padding: 25px 30px;
position: relative;
width: 100%;
}
.status-upload {
background: none repeat scroll 0 0 #f5f5f5;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-ms-border-radius: 4px;
-o-border-radius: 4px;
border-radius: 4px;
float: left;
width: 100%;
}
.status-upload form {
float: left;
width: 100%;
}
.status-upload form textarea {
background: none repeat scroll 0 0 #fff;
border: medium none;
-webkit-border-radius: 4px 4px 0 0;
-moz-border-radius: 4px 4px 0 0;
-ms-border-radius: 4px 4px 0 0;
-o-border-radius: 4px 4px 0 0;
border-radius: 4px 4px 0 0;
color: #777777;
float: left;
font-family: Lato;
font-size: 14px;
height: 142px;
letter-spacing: 0.3px;
padding: 20px;
width: 100%;
resize:vertical;
outline:none;
border: 1px solid #F2F2F2;
}

.status-upload ul {
float: right;
list-style: none outside none;
margin: 0;
padding: 0 0 0 15px;
width: auto;
}
.status-upload ul > li {
float: left;
}
.status-upload ul > li > a {
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-ms-border-radius: 4px;
-o-border-radius: 4px;
border-radius: 4px;
color: #777777;
float: left;
font-size: 14px;
height: 30px;
line-height: 30px;
margin: 10px 0 10px 10px;
text-align: center;
-webkit-transition: all 0.4s ease 0s;
-moz-transition: all 0.4s ease 0s;
-ms-transition: all 0.4s ease 0s;
-o-transition: all 0.4s ease 0s;
transition: all 0.4s ease 0s;
width: 30px;
cursor: pointer;
}
.status-upload ul > li > a:hover {
background: none repeat scroll 0 0 #606060;
color: #fff;
}
.status-upload form button {
border: medium none;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-ms-border-radius: 4px;
-o-border-radius: 4px;
border-radius: 4px;
color: #fff;
float: left;
font-family: Lato;
font-size: 14px;
letter-spacing: 0.3px;
margin-left: 9px;
margin-top: 9px;
padding: 6px 15px;
}
.dropdown > a > span.green:before {
border-left-color: #2dcb73;
}
.status-upload form button > i {
margin-right: 7px;
}

.from_student 
{
    background: #e0e6ef;
    padding: 10px 10px 0px 10px;
    margin-bottom: 10px !important;
    border-radius:7px;
}
.from_school
{
    background: #aed8ec;
    padding: 10px 10px 0px 10px;
    margin-bottom: 10px !important;
    border-radius:7px;
}
h3
{
    font-size:15px !important;
}

h3.student_name b
{
    color: #000 !important;
}

h3.school_name b
{
   color: #000 !important; 
}
</style>
<?php date_default_timezone_set('Africa/Kinshasa'); ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h1 style="font-size:24px"><a href="<?=$baseurl?>Contactyoume" title="Add" class="btn btn-sm btn-default" style="float:right"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1370') { echo $langlbl['title'] ; } } ?></a>  <?php if(count($all_messages) > 1){ ?><b>Re: </b><?php } ?><?= $get_messages->subject ?></h1>
            </div>
            <div class="body container">
                <?php
                foreach($all_messages as $value){ 
                    if($value['from_type'] == "superadmin")
                    {
                    ?>
                        <div class="row clearfix col-md-12">
                            <div class="col-md-6">
                                <div class="from_student">
                                    <div class="row mb-2">
                                        <div class="col-md-12 text-left">
                                            <!--<h3 class="school_name"><b><?=$value['sender']?></b></h3>-->
                                            <h3 class="school_name"><b>You-Me Executive Office</b></h3>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 text-left">
                                            <p><?=  $value->message ?></p>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 text-right">
                                            
                                            <?php if($value->read_msg == 1) { ?>
                                            <p><?= date('M d, Y G:i', $value->read_datetime) ?> <b>(Read)</b></p>
                                            <?php } else  { ?>
                                            <p><?php //echo date('M d, Y G:i', $value->created_date) ?> <b>(Delivered)</b></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row clearfix"><div class="col-md-12"><hr></div></div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    <?php
                    }
                    else
                    { ?>
                        <div class="row clearfix  col-md-12">
                            <div class="col-md-6"></div>
                            <div class="col-md-6 text-right">
                                <div class="from_school">
                                    <div class="row mb-2">
                                        <div class="col-md-12 text-left">
                                            <h3 class="student_name"><b><?=$value['sender']?></b></h3>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 text-left">
                                            <p><?=  $value->message ?></p>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 text-right ">
                                            
                                            <?php if($value->read_msg == 1) { ?>
                                            <p><?= date('M d, Y G:i', $value->read_datetime) ?> <b>(Read)</b></p>
                                            <?php } else { ?>
                                            <p><?php //echo date('M d, Y G:i', $value->created_date) ?> <b>(Delivered)</b></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row clearfix"><div class="col-md-12"><hr></div></div>
                                </div>
                            </div>
                        </div>
                 <?php   }
                 } ?>
                <div class="row ">
                    <div class="col-md-12">
                        <div class="widget-area no-padding blank" id="widget_box_sh" style="display:none">
							<div class="status-upload">
								<?php echo $this->Form->create(false , ['url' => ['action' => 'addreply'] , 'id' => "sendsreply" , 'method' => "post"  ]); ?> 
								    <input name="id" value="<?= $id ?>" type="hidden">
									<textarea name="message" required></textarea>
									<button type="submit" class="btn btn-success green" id="msgsendbtn"><i class="fa fa-share"></i> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1391') { echo $langlbl['title'] ; } } ?></button>
									<ul>
									    <li><a><b>:</b></a></li>
										<li><a id="show_reply" onclick="hide_show_reply(this.id)" ><i class="fa fa-trash"></i></a></li>
									</ul>
								<?php echo $this->Form->end(); ?>
							</div><!-- Status Upload  -->
						</div><!-- Widget Area -->
                    </div>
                    <div class="col-md-12">
                        <button  class="btn btn-default" id="hide_reply" onclick="hide_show_reply(this.id)"  style="margin-right: 10px;"><i class="fa fa-reply"></i> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1488') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                     <div class="col-sm-12">
                        <div class="error" id="msgreplyerror">
                        </div>
                        <div class="success" id="msgreplysuccess">
                        </div>
                    </div>
                </div>
                 
            </div>
        </div>
    </div>
</div>   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function hide_show_reply(get_id){
        if(get_id == 'hide_reply'){
            $('#widget_box_sh').show();
            $('#hide_reply').hide();
        }
        if(get_id == 'show_reply'){
            $('#widget_box_sh').hide();
            $('#hide_reply').show();
        }
    }
    
$("#sendsreply").submit(function(e){
    e.preventDefault();
    
    $("#msgsendbtn").prop("disabled", true);
    $("#msgsendbtn").text('Saving...');
    $(this).ajaxSubmit({
        error: function(){
            $("#msgreplyerror").html("Some error occured. Please try again.") ;
            $("#msgreplyerror").fadeIn().delay('5000').fadeOut('slow');
            $("#msgsendbtn").prop("disabled", false);
            $("#msgsendbtn").text('Save');
        },
        success: function(response){
            $("#msgsendbtn").prop("disabled", false);
            $("#msgsendbtn").text('Save');
            if(response.result === "success" ){ 
                setTimeout(function(){ location.href = baseurl +"/Contactyoume/view/<?= base64_encode($id) ?>" ;  }, 1000);
                      
            }else if(response.result === "empty" ){
                $("#msgreplyerror").html("Please fill in Details.") ;
                $("#msgreplyerror").fadeIn().delay('5000').fadeOut('slow');
                
            }else if(response.result === "activity" ){
                $("#msgreplyerror").html("Activity not added.") ;
                $("#msgreplyerror").fadeIn().delay('5000').fadeOut('slow');
                
            }else{
                $("#msgreplyerror").html("Some error occured. Error: "+response.result) ;
                $("#msgreplyerror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});
  
</script>

<!DOCTYPE html>
<html translate="no">
<head>
    <title>:: Admin :: Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="You-Me Global Education Login Page">
    <meta name="author" content="You-Me Global Education">
    <meta name="google" content="notranslate">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    

    <link rel='stylesheet' type='text/css' media='screen' href='<?=$baseurl?>login_css/style.css'>
    <link rel="stylesheet" href="<?=$baseurl?>login_css/responsive.css">
    <link rel="stylesheet" href="<?=$baseurl?>login_css/strap.css">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">-->
    
    <link rel="stylesheet" href="<?=$baseurl?>login_css/fonts.css">
    <!--<link rel="stylesheet" href="<?=$baseurl?>login_css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?=$baseurl?>login_css/owl.theme.default.min.css">-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;1,100&display=swap" rel="stylesheet">-->
    <style>
div.error {
    display: none;
    background: red;
    color: #fff;
    padding: 10px;
    margin-bottom: 10px;
    text-align:center;
}
div.success {
    display: none;
    background: green;
    color: #fff;
    padding: 10px;
    margin-bottom: 10px;
    text-align:center;
    width: 100%;
}
.fa-spinner{
    display: none;
}


.theme-orange #wrapper:before, .theme-orange #wrapper:after , .theme-orange:before, .theme-orange:after {
    background: #7199D6;
	border : #7199D6;
	color: #242E3B;
}
.theme-orange .auth-main .btn-primary , .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle
{
	background: #1B0951;
	border : #1B0951;
	color: #FFF;
	
}
a
{
	color: #242E3B;
    text-decoration: none;
    background-color: transparent;
}
a:hover, a:focus {
    color: #FFA812;
    text-decoration: none;
}
.auth-box .top img {
    width: 250px;
}

.helper-text
{
    display:inline !important;
}

.marginbottom {
    margin-bottom: 15px !important;
}
.eyepassword i
{
    margin-left: -30px;
    cursor: pointer;
}
.field-icon {
    float: right;
    margin-left: -25px;
    margin-top: -25px;
    position: relative;
    z-index: 2;
}
.fa-fw {
    width: 3.285714em !important;
}
	
.note {
    border: 2px solid #eb6363;
    border-radius: 5px;
    padding: 6px;
    margin: 0px 15px;
    width: 96.5%;
    background: #ffadad;
    color: #931212;
}

#meetingform {
    background: #EDF4FB;
    padding: 60px;
    text-align: center;
    border: 1px solid rgba(0,0,0,0.1);
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    margin-bottom:30px;
}

.form-container .form-horizontal .form-control, .form-container .form-horizontal .signin {
    border-radius: 0px; 
}
.form-container .form-horizontal .signin {
    color: #fff;
    background: #4daf7c;
}
div.error,div.success{
    font-size: 12 !important;
}
</style>
</head>
<body>
<div class="form-bg" >
    <div class="container">
        <div class="row">
            <!--<div class="col-sm-4">
                <div class="left-logo-overplay">
                    <img src="<?=$baseurl?>login_img/bg-logo.png">
                </div>
                <div class="left-logo">
                    <img src="<?=$baseurl?>login_img/you-me-live.png">
                </div>
            </div>-->
            <div class="col-sm-12">
                <div class="form-container" style="font-size:12px">
                    <<!--div class="left-content">
                        <h3 class="title">Nice To Meet You Again</h3>
                        <h4 class="sub-title">Welcome Back!</h4>
                    </div>-->
                    <div class="">
                        <div class="logo-form text-center" style="padding: 0% 0 0% 0;">
                        <img src="../ConferenceMeet/images/You-Me-live.png">
                        </div><hr>
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <div class="row clearfix container mt-4 text-left"><h3 class="heading" style="text-align: left;">Instructions* -</h3></div>
                                <div class="row clearfix container mt-1"><h4 class="heading" style="font-size:16px;text-align: left;">1. For iPhones, Please use safari browsers.</h4></div>
                                <div class="row clearfix container mt-1 mb-3"><h4 class="heading" style="font-size:16px;text-align: left;">2. Before start video, please make sure that pop up was not blocked of browser.</h4></div><br/><br/><br/>
                                <div class="row">
                                    <div class="offset-3 col-md-6">
                                        <?php   echo $this->Form->create(false , ['url' => ['action' => 'joinmeeting'] , 'id' => "meetingform" , 'class' =>"form-horizontal" ]); ?>
                                            <div class="form-group">
                                                <!--<label for="signin-email" class="control-label">Email/UserId</label>-->
                                                <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Your Name" required>
                                                <input type="hidden" class="form-control" id="mid" name="mid" value="<?= $_GET['mid']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <div class="error" id="error"></div>
                                                <div class="success" id="success"></div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-lg btn-block signin" style="margin-top:20px; width:100%!important"><i class="fa fa-spinner fa-spin"></i> Join</button>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=$baseurl?>js/libscripts.bundle.js"></script>
<!--<script src="<?=$baseurl?>js_3rdparty/jquery3_6.min.js"></script>-->
<script src="<?=$baseurl?>js_3rdparty/jquery.form.min.js"></script>

<script>
$("#meetingform").submit(function(e){
    $(".fa-spinner").css('display' , 'inline-block' );
    e.preventDefault();
    $(this).ajaxSubmit({
    success: function(response)
    {
        console.log(response);
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
            if(response.result === 0 ){ 
                $(".fa-spinner").css('display' , 'none' );
                $("#error").html("Error: Meeting Not started Yet!") ;
                $("#error").fadeIn().delay('5000').fadeOut('slow');
                
             //   location.href = "dashboard" ; 
               // location.href = baseurl +"/dashboard" ;
                
            }
            else if(response.result === 2 ){ 
                $(".fa-spinner").css('display' , 'none' );
                $("#error").html("Error: Meeting Ended!") ;
                $("#error").fadeIn().delay('5000').fadeOut('slow');
            }else if(response.result === 'failed' ){ 
                $(".fa-spinner").css('display' , 'none' );
                $("#error").html("Error: No Meeting Found!") ;
                $("#error").fadeIn().delay('5000').fadeOut('slow');
            }else if(response.result === 'invalid' ){ 
                $(".fa-spinner").css('display' , 'none' );
                $("#error").html("Error: Invalid Operation!") ;
                $("#error").fadeIn().delay('5000').fadeOut('slow');
            }
            else
            {
                location.href = response.result ;
                /*$(".fa-spinner").css('display' , 'none' );
                $("#error").html("Error: "+response.result) ;
                $("#error").fadeIn().delay('5000').fadeOut('slow');*/
            }
        }	
    });			
    return false;
});
  
  /* END */
</script>

</body>
</html>
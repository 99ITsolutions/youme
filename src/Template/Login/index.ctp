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
    <link rel="icon" href="<?=$baseurl."img/favicon.png" ?>" type="image/x-icon">
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
select.form-control.marginbottom+ i.fa {
  float: right;
  margin-top: -42px;
  margin-right: 15px;
  
  pointer-events: none;
  
  background-color: #FFF
    padding-right: 5px;
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
</style>
</head>
<body>
<div class="form-bg" >
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="left-logo-overplay">
                    <img src="<?=$baseurl?>login_img/bg-logo.png">
                </div>
                <div class="left-logo">
                    <img src="<?=$baseurl?>login_img/you-me-live.png">
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-container">
                    <div class="left-content">
                        <h3 class="title">Nice To Meet You Again</h3>
                        <h4 class="sub-title">Welcome Back!</h4>
                    </div>
                    <div class="right-content">
                        <div class="logo-form">
                        <img src="<?=$baseurl?>login_img/logo.png">
                        </div>
                        
                        <?php	echo $this->Form->create(false , ['url' => ['action' => 'logincheck'] , 'id' => "loginform", 'class' => "form-horizontal" ]); ?>
                        <!--<form class="form-horizontal">-->
                            <div class="form-group">

                            <span class="input-icon"><i class="fa fa-user"></i></span>
                                <!--<input type="email" class="form-control" placeholder="Username / Email">-->
                                <input type="text" class="form-control" id="email" name="email" value=""  placeholder="E-mail">
                            </div>
                            <div class="form-group">
                                <span class="input-icon"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" value="" placeholder="Password" />
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                
                            </div>

                            <div class="form-group">
                                <span class="input-icon"><i class="fa fa-language"></i></span>
                                <select class="form-control language_sel" id="language" name="language" >
                                    <option value="">Choose Language</option>
                                    <option value="1">English</option>
                                    <option value="2">French</option>
                                </select>
                            </div>
                            <div class="remember-me">
                                <input type="checkbox" name="remember" class="checkbox">
                                <span class="check-label">Remember Me</span>
                            </div>
                            
                            <div class="form-group">
                                <div class="error" id="error">
                                </div>
                                <div class="success" id="success">
                                </div>
                            </div>
                            <div class="form-group" style="text-align: end;">
                                <button type="submit" class="btn btn-primary btn-lg btn-block signin"><i class="fa fa-spinner fa-spin"></i> LOGIN</button>
                                <br>
                                <div class="form-group">
                                    <span class="helper-text m-b-10"><i class="fa fa-lock"></i> <a href="javascript:void(0);" title="Forgot Password" data-toggle="modal" data-target="#forgotpass">Forgot password?</a></span>
                                    | <span class="helper-text m-b-10"><i class="fa fa-question-circle"></i> <a href="javascript:void(0);" title="Need Help" data-toggle="modal" data-target="#needhlp">Need Help?</a></span>
                                </div>
                            </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>



<script src="<?=$baseurl?>js/libscripts.bundle.js"></script>
<!--<script src="<?=$baseurl?>js_3rdparty/jquery3_6.min.js"></script>-->
<script src="<?=$baseurl?>js_3rdparty/jquery.form.min.js"></script>
<script>


/* Login form submission */

$("#loginform").submit(function(e){
    $(".fa-spinner").css('display' , 'inline-block' );
    e.preventDefault();
    $(this).ajaxSubmit({
    success: function(response)
    {
        console.log(response);
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
            if(response.result === "success_superadmin" ){ 
                location.href = baseurl +"/dashboard" ;
            }
            else if(response.result === "success_cvendor" ){ 
                location.href = baseurl +"/cvendordashboard" ;
            }
            else if(response.result === "success_subadmin" ){ 
                location.href = baseurl +"/subadmindashboard" ;
            }
            else if(response.result === "success_parent" ){ 
                location.href = baseurl +"/parentdashboard" ;
            }
            else if(response.result === "success" ){ 
                location.href = baseurl +"/schooldashboard" ;
            }
            else if(response.result === "success_teacher" ){ 
                location.href = baseurl +"/teacherdashboard" ;
            }
            else if(response.result === "success_student" ){ 
                if(response.dash === "senior" ) {
                    location.href = baseurl +"/studentdashboard" ;
                }
                else
                {
                    location.href = baseurl +"/kinderdashboard" ;
                }
            }
            else if(response.result === "success_kindergarden" ){ 
                location.href = baseurl +"/kinderdashboard" ;
            }
            else if(response.result === "email" ){
                $(".error").css('display' , 'block' );
                $(".fa-spinner").css('display' , 'none' );
                $("#error").html("Invalid Login Details.") ;
                $("#error").fadeIn().delay('5000').fadeOut('slow');
            }
            else if(response.result === "captcha" )
            {
                $(".error").css('display' , 'block' );
                $(".fa-spinner").css('display' , 'none' );
                $("#error").html("Please check the reCaptcha.") ;
                $("#error").fadeIn().delay('5000').fadeOut('slow');
            }
            else
            {
                $(".error").css('display' , 'block' );
                $(".fa-spinner").css('display' , 'none' );
                $("#error").html(response.result) ;
                $("#error").fadeIn().delay('5000').fadeOut('slow');
            }
        }	
    });			
    return false;
});
  
  /* END */
</script>
 <!------------------ Forgot Password --------------------->

    
<div class="modal classmodal animated zoomIn" id="forgotpass" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel" style="color: #1B0951">Forgot Password</h6>
	            <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'fpass'] , 'id' => "fpassform" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <label>Registered Email*</label>
                        <div class="form-group">                                    
                            <input type="email" class="form-control" required name="email" placeholder="Email*">
                        </div>
                    </div>
                    <?php
                    if(!empty($company_detail[0]))
                    { ?>
                      <input type="hidden" name="type" value="1">
                      <input type="hidden" name="school_id" value="<?=$company_detail[0]['id']?>">
                    <?php 
                    }
                    else
                    { ?>
                        <input type="hidden" name="type" value="0">
                    <?php 
                    }
                    ?>
                    <div class="col-md-12">
                        <div class="error" id="fpasserror"></div>
                        <div class="success" id="fpasssuccess"></div>
                    </div>
                    <div class="col-md-12 button_row align-right" >
                        <hr>
                        <button type="submit" class="btn btn-primary forgotpassbtn" id="forgotpassbtn" style="background: #1B0951;border : #1B0951;	color: #FFF;">Send</button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>         

<!------------------Need help --------------------->

    
<div class="modal classmodal animated zoomIn" id="needhlp" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel" style="color: #1B0951">Contact You-Me (We want to hear from you)
                </h6>
	            <button type="button" class=" close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'nhelp'] , 'id' => "nhelpform" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-md-6">
                        <label>Prénom*</label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control marginbottom" required name="fname" placeholder="Prénom*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Nom de famille*</label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control marginbottom" required name="lname" placeholder="Nom de famille*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>You-Me E-mail (@ymge.org)*</label>
                        <div class="form-group">                                    
                            <input type="email" class="form-control marginbottom" required name="email" placeholder="E-mail*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Numéro de téléphone*</label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control marginbottom" required name="phone" placeholder="Numéro de téléphone*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Nom de l'école*</label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control marginbottom" required name="schoolname" placeholder="Nom de l'école*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Qui êtes-vous?*</label>
                        <div class="form-group">                                    
                            <select class="form-control marginbottom" required name="whocontact">
                                <option value="École & Admin">École & Admin</option>
                                <option value="Enseignant">Enseignant</option>
                                <option value="Élève">Élève</option>
                                <option value="Parent">Parent</option>
                            </select>
                            <i class="fa fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Raison de nous contacter*</label>
                        <div class="form-group">                                    
                            <select class="form-control marginbottom" required name="reason">
                                <option value="Identifiant oublié">Identifiant oublié</option>
                                <option value="Mot de passe oublié">Mot de passe oublié </option>
                                <option value="Assistance sur le profi">Assistance sur le profil </option>
                                <option value="Autre">Autre</option>
                                
                            </select>
                            <i class="fa fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Message*</label>
                        <div class="form-group">                                    
                            <textarea class="form-control marginbottom" required name="message" placeholder="Comment pouvons-nous vous aider?*"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 note" bis_skin_checked="1"><p><b>Note: </b>Nous tenons à vous préciser que les courriers électroniques (E-mail) n’ayant aucun lien direct avec You-Me Global Education (ymge@.org) resteront sans réponse de notre part. <br>
                        Bien à vous <br>
                        Équipe You-Me </p>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="nhlperror"></div>
                        <div class="success" id="nhlpsuccess"></div>
                    </div>
                    <div class="col-md-12 button_row align-right" >
                        <hr>
                        <button type="submit" class="btn btn-primary needhelpbtn" id="needhelpbtn" style="background: #1B0951;border : #1B0951;	color: #FFF;">Envoyer</button>
                    </div>
                   <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>         

<script>

$(".toggle-password").click(function() 
{
    //alert(this);
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $("#password").attr("type");
    //console.log(input);
    if (input == "password") {
        $("#password").attr("type", "text");
    } else {
        $("#password").attr("type", "password");
    }
}); 
/* forgot password form submission */

$("#fpassform").submit(function(e){
  e.preventDefault();
  
    $("#forgotpassbtn").prop("disabled", true);
    $("#forgotpassbtn").text("Sending...");

 $(this).ajaxSubmit({
    error: function(){
      $("#forgotpassbtn").text("Send");
      $("#fpasserror").html("Some error occured. Please try again.") ;
      $("#fpasserror").fadeIn().delay('5000').fadeOut('slow');
      $("#forgotpassbtn").prop("disabled", false);
    },
    success: function(response)
    {
        $("#forgotpassbtn").text("Send");
        $("#forgotpassbtn").prop("disabled", false);
        if(response.result === "success" )
        { 
            $("#fpasssuccess").html("Password has been sent to your registered email succesfully.") ;
            $("#fpasssuccess").fadeIn();
            setTimeout(function(){ location.reload();  }, 1000);
        }
        else if(response.result === "empty" ){
            $("#fpasserror").html("Please fill in Details.") ;
            $("#fpasserror").fadeIn().delay('5000').fadeOut('slow');
        }
        else
        {
            $("#fpasserror").html(response.result) ;
            $("#fpasserror").fadeIn().delay('5000').fadeOut('slow');
        }
  } 
});     
return false;

});

/* Need Help form submission */

$("#nhelpform").submit(function(e){
    e.preventDefault();
    $("#needhelpbtn").prop("disabled", true);
    $(this).ajaxSubmit({
        error: function(){
            $("#nhlperror").html("Une erreur s'est produite. Veuillez réessayer.") ;
            $("#nhlperror").fadeIn().delay('5000').fadeOut('slow');
            $("#needhelpbtn").prop("disabled", false);
        },
        success: function(response)
        {
            $("#needhelpbtn").prop("disabled", false);
            if(response.result === "success" )
            { 
                $("#nhlpsuccess").html("Merci de nous contacter. Notre équipe vous contactera dans les 24-48 heures ouvrables.") ;
                $("#nhlpsuccess").fadeIn();
                setTimeout(function(){ location.reload();  }, 1000);
            }
            else if(response.result === "empty" ){
                $("#nhlperror").html("Veuillez remplir les détails.") ;
                $("#nhlperror").fadeIn().delay('5000').fadeOut('slow');
            }
            else
            {
                $("#nhlperror").html(response.result) ;
                $("#nhlperror").fadeIn().delay('5000').fadeOut('slow');
            }
        } 
    });     
    return false;
});

</script>

</body>
</html>

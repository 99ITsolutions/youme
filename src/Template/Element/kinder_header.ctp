<?php 
$title = " Kinder Garten ::  Dashboard";
$color = "#e24571";
$btncolor = "#2680ce";
$loginas = "student";

$hostname = "localhost";
$username = "youmeglo_globaluser";
$password = "DFmp)9_p%Kql";
$database = "youmeglo_globalweb";

$con = mysqli_connect($hostname, $username, $password, $database); 
if(mysqli_connect_error($con)){ echo "Connection Error."; die();} 

/*print_r($_SESSION);

print_r($_COOKIE);

die;*/
?>
<!doctype html>
<html lang="en">

<head>
<title><?=$title?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Kinder Garten Dashboard">
<meta name="author" content="Kinder Garten Dashboard">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<?php header("Access-Control-Allow-Origin: *"); ?>
<link rel="icon" href="favicon.ico" type="image/x-icon">


<!-- VENDOR CSS -->
<link rel="stylesheet" href="<?=$baseurl?>css/bootstrap.min.css">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"  crossorigin="anonymous">
<link rel="stylesheet" href="<?=$baseurl?>css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?=$baseurl?>css/jquery-jvectormap-2.0.3.min.css"/>
<link rel="stylesheet" href="<?=$baseurl?>css/morris.min.css" />
<link rel="stylesheet" href="<?=$baseurl?>css/sweetalert.css"/>
<link rel="stylesheet" href="<?=$baseurl?>css/jquery-nestable.css"/>
<link rel="stylesheet" href="<?=$baseurl?>css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?=$baseurl?>css/bootstrap-multiselect.css">
<link rel="stylesheet" href="<?=$baseurl?>css/summernote.css"/>
<link rel="stylesheet" href="<?=$baseurl?>css/dropify.min.css">
<link rel="stylesheet" href="<?=$baseurl?>css_3rdparty/responsive.dataTables.min.css">
<!-- MAIN CSS -->
<link rel="stylesheet" href="<?=$baseurl?>css/main.css">
<link rel="stylesheet" href="<?=$baseurl?>css/color_skins.css">
<link rel="stylesheet" href="<?=$baseurl?>css_3rdparty/daterangepicker.css" />
<link href="<?=$baseurl?>css/mdtimepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=$baseurl?>css_3rdparty/jquery.timepicker.min.css">
<link href="<?=$baseurl?>css_3rdparty/select2.min.css" rel="stylesheet" />
<link href="<?=$baseurl?>css_3rdparty/jquery.fancybox.css" rel="stylesheet" />
<link rel="stylesheet" href="<?=$baseurl?>css/image-uploader.min.css">


<link rel="stylesheet" href="<?=$baseurl?>css/datepickk.min.css">
<link rel="stylesheet" href="<?=$baseurl?>css/fullcalendar.min.css" />
<link rel="stylesheet" href="<?=$baseurl?>css/custom.css">
<link rel="stylesheet" href="<?=$baseurl?>css_3rdparty/tempusdominus-bootstrap-4.min.css" />
<link rel="stylesheet" href="<?=$baseurl?>css/slim.min.css">
<link rel="stylesheet" href="<?=$baseurl?>css/lightbox.css" type="text/css" />
	

<style>
/* fallback */
@font-face {
  font-family: 'Material Icons';
  font-style: normal;
  font-weight: 400;
  src: url(<?=$baseurl?>css_3rdparty/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2) format('woff2');
}

.material-icons {
  font-family: 'Material Icons';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  -webkit-font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
} 
@media screen and (min-width: 991px) 
{
    #pdfiframe
    {
       width:750px; 
       height:500px;
    }
}
@media screen and (max-width: 990px) 
{
    #pdfiframe
    {
       width:450px; 
       height:500px;
    }
}

@media screen and (max-width: 767px) {   
    iframe {
        width: 100% !important;
    }
}

body{
    font-family: arial !important;
    font-size: 13px !important;
}
.sidebar-nav .metismenu a {
    font-size: 13px !important;
}
.h3, h3 {
    font-size: 13px !important;
}
.btn {
    font-size: 13px !important;
}
.block-header h2 {
    font-size: 15px !important;
	font-weight: bold;
}

.btn-link {
	color: <?= $color?> !important;
}
.theme-orange #wrapper:before, .theme-orange #wrapper:after, .theme-orange:before, .theme-orange:after{
	background: <?= $color?> !important;
}

h2.heading
{
	font-size: 15px !important;
	font-weight: bold;
	color: <?= $color?> !important;
}



.header 
{
	//border-top: #007bff 4px solid;
	border-top: <?= $color ?> 4px solid  !important ;
    border-radius: .55rem;
}
h5
{
	//color: #007bff !important;
	color: <?= $color?> !important;
	
}
h2.heading
{
	color: <?= $color?> !important;
}
.page-item.active .page-link
{
	background-color: <?= $color?> !important;
    border-color: <?= $color?> !important;
}
.iconsss, #defaultModalLabel, .subhead
{
	color: <?= $color?> !important;
}
h5
{
    color: <?= $color?> !important;
}


.form-control {
    font-size: 13px;
}
.page-loader-wrapper {
    background: <?= $color?> !important;
}
h6 {
    color: <?= $color?> !important;
}

.theme-orange .sidebar-nav .metismenu>li.active>a, .theme-orange .sidebar-nav .metismenu>li.active i 
{
	border-left-color: #242E3B !important;
	color:#242e3b !important;
}




.icon-title
{
    color:  <?= $color?> !important;
    font-weight:bold;
    font-size:22px;
}

.nav-tabs .nav-item a i {
    padding-left: 5px;
    color:  <?= $color?> !important;
}







#knowldge_layout
{
    border: 1px solid <?= $color?> !important;
    text-align: center;
    max-width: 31%;
    padding: 15px;
    margin:4px;
    height:130px;
    position: relative;
}


.icon-color
{
    color:  <?= $color?> !important;
}



.navbar-fixed-top .navbar-brand img{
    width: 86px;
    vertical-align: top;
    margin-top: 2px;
    max-height: 65px;
}

h2.heading a:hover, h2.heading a:focus
{
    color:<?= $color ?> !Important;
}

.switch {
  position: relative;
  display: inline-block;
  width: 55px;
  height: 25px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: <?= $color?> !important;
}

input:focus + .slider {
  box-shadow: 0 0 1px <?= $color?> !important;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
@media screen and (min-width: 769px)
{
    
    #left-sidebar 
    {
        padding-top:120px !important;
    }
    .navbar-brand
    {
        margin-right: 0px !important;
    }
    #main-content
    {
        margin-top: 95px;
    }
}
@media screen and (max-width: 768px) and (min-width: 601px)
{
    .container-fluid
    {
        height: 110px !important;
    }
    .navbar-center
    {
        width:45% !important;
    }
    .navbar-fixed-top .navbar-right {
        width:10% !important;
    }
    .navbar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand
    {
        width:0% !important;
    }
    .navbar-fixed-top .navbar-brand img
    {
        max-height:74px;
    }
    #left-sidebar 
    {
        padding-top:120px !important;
    }
     .navbar-brand
    {
        margin-right: 0px !important;
    }
    .navbar>.container-fluid>.col-sm-3
    {
        max-width:0% !important;
    }
    .navbar>.container-fluid>.col-sm-6
    {
        max-width:25% !important;
    }
    
    
}

@media screen and (max-width: 600px) and (min-width: 576px)
{
    .navbar>.container-fluid>.col-sm-3
    {
        max-width:0% !important;
    }
    .navbar>.container-fluid>.col-sm-6
    {
        max-width:25% !important;
    }
}
@media screen and (max-width: 575px) and (min-width: 425px)
{
    .container-fluid
    {
        height: 135px !important;
    }
    .navbar-center
    {
        width:45% !important;
    }
    .navbar-fixed-top .navbar-right {
        width:10% !important;
    }
    .navbar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand
    {
        width:22% !important;
    }
    .navbar-fixed-top .navbar-brand img
    {
        max-height:74px;
    }
    #left-sidebar 
    {
        padding-top:90px !important;
    }
    #main-content
    {
        margin-top: 95px;
    }
    
}
@media screen and (max-width: 425px) and (min-width: 200px) 
{
    .container-fluid
    {
        height: 230px !important;
    }
    .navbar-center
    {
        width:45% !important;
    }
    .navbar-fixed-top .navbar-right {
        width:10% !important;
    }
    .navbar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand
    {
        width:60% !important;
    }
    .navbar-fixed-top .navbar-brand img
    {
        max-height:74px;
    }
    #main-content
    {
        margin-top:200px;
    }
    #left-sidebar 
    {
        padding-top:160px !important;
    }
}

.goog-logo-link, .goog-te-gadget img{
    display:none !important;
}
.goog-te-gadget{
    font-size:0px !important;
}
.goog-te-gadget .goog-te-combo{
    margin-top:20px !important;
}
.goog-te-banner-frame{
    visibility: hidden !important;
}
#goog-gt-tt
{
     visibility: hidden !important;
}


.hoverbutton
{
 background: #f3e83c !important;
}

.hoverbutton i
{
 color: #333 !important;
}
.pulse-button {

  position: relative;
  border: none;
  box-shadow: 0 0 0 0 rgb(242 231 59 / 39%);
  -webkit-animation: pulse 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
  -moz-animation: pulse 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
  -ms-animation: pulse 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
  animation: pulse 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
}
.pulse-button:hover 
{
  -webkit-animation: none;-moz-animation: none;-ms-animation: none;animation: none;
}

@-webkit-keyframes pulse {to {box-shadow: 0 0 0 45px rgba(232, 76, 61, 0);}}
@-moz-keyframes pulse {to {box-shadow: 0 0 0 45px rgba(232, 76, 61, 0);}}
@-ms-keyframes pulse {to {box-shadow: 0 0 0 45px rgba(232, 76, 61, 0);}}
@keyframes pulse {to {box-shadow: 0 0 0 45px rgba(232, 76, 61, 0);}}
    
    
</style>

</head>
<body class="theme-orange">
    
    
<?php
if($btncolor == "#e24571" ) { 
?>
<style>
.btn-primary, .btn-secondary, .btn-success, .btn-info
{
	background-color: <?= $btncolor?> !important;
	border-color: <?= $btncolor?> !important;
	color: #ffffff !important;
} 
.bg-dash
{
    background-color: <?= $btncolor ?> !important;
}
.colorBtn
{
    color:#ffffff !important;
}
</style>
<?php 
} else { ?>
<style>
.btn-primary, .btn-secondary, .btn-success, .btn-info
{
	background-color: <?= $btncolor?> !important;
	border-color: <?= $btncolor?> !important;
	color: #242e3b !important;
}
.bg-dash
{
    background-color: <?= $btncolor ?> !important;
}
.colorBtn
{
    color:#242e3b !important;
}
</style>
<?php } ?>

<?php
if($color == "#e24571") {
?>
<style>

#left-sidebar {
    background: <?= $color?> !important;
    color:#fff;
}
.sidebar-nav .metismenu>li a {
    background: #ffffff1f;
    border-radius: 30px;
    margin: 0 12px 6px 12px;
}
ul.main-menu li a span , ul.metismenu li a span 
{
    /*color:#ffffff !important;*/
    font-weight:500;
	font-size:14px;
}
.sidebar-nav .metismenu .has-arrow::after {
    color: #ffffff !important;
}
.sidebar-nav .metismenu a:hover, .sidebar-nav .metismenu a:focus, .sidebar-nav .metismenu a:active {
    background: #fbc358 !important;
}

ul.main-menu li a span , ul.metismenu li a span 
{
    color:#ffffff !important;
}

ul.main-menu li.active a span , ul.metismenu li.active a span 
{
    color:#242e3b !important;
}
.sidebar-nav .metismenu ul a
{
    color:#fff;
}

.theme-orange .sidebar-nav .metismenu>li i 
{
	color: #ffffff !important;
}

.theme-orange #left-sidebar .nav-tabs .nav-link.active{
	color: #fff !important;
}

.sidebar-nav .metismenu>li a
{
    color:#ffffff !important;
}

</style>
<?php
} else { ?>
<style>
/*#left-sidebar
{
    background:  <?= $color?> !important;
    color: #242E3B;
}*/

#left-sidebar {
    background: <?= $color?> !important;
    color:#fff;
}
.sidebar-nav .metismenu>li a {
    background: #ffffff1f;
    border-radius: 30px;
    margin: 0 12px 6px 12px;
}
ul.main-menu li a span , ul.metismenu li a span 
{
    color:#ffffff !important;
    font-weight:500;
	font-size:14px;
}
.sidebar-nav .metismenu .has-arrow::after {
    color: #ffffff !important;
}
.sidebar-nav .metismenu a:hover, .sidebar-nav .metismenu a:focus, .sidebar-nav .metismenu a:active {
    background: #fbc358 !important;
}

ul.main-menu li a span , ul.metismenu li a span 
{
    color:#242E3B !important;
}
.sidebar-nav .metismenu ul a
{
    color:#242e3b;
}

.theme-orange .sidebar-nav .metismenu>li i 
{
	color: #242E3B !important;
}

.theme-orange #left-sidebar .nav-tabs .nav-link.active{
	color: #242E3B !important;
}
.sidebar-nav .metismenu>li
{
    color:#242e3b !important;
}

</style>

<?php } ?>
<style>
.sidebar-nav .metismenu a:hover, .sidebar-nav .metismenu a:focus, .sidebar-nav .metismenu a:active, .sidebar-nav .metismenu a:hover i, .sidebar-nav .metismenu a:focus i, .sidebar-nav .metismenu a:hover span, .sidebar-nav .metismenu a:focus span, .sidebar-nav .metismenu a:active span
{
    text-decoration:none;
   /* background-color:#f1f1f1; */
    border-left-color: #242E3B !important;
	color:#242e3b !important;
}
</style>
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="<?=$baseurl?>img/logo-icon.png" width="48" height="48" alt="CSS"></div>
        <p>Please wait...</p>        
    </div>
</div>
<!-- Overlay For Sidebars -->
<?php   echo $this->Form->create(false , [ 'method' => "post"  ]); echo $this->Form->end(); ?>
<div id="wrapper">
    <nav class="navbar navbar-fixed-top">
        <div class="container-fluid col-md-12 col-sm-12">
            <div class="navbar-btn col-md-1 col-sm-3">
                <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
            </div>

            <div class="navbar-brand col-md-3 col-sm-5">
                <a href="#"><img src="https://you-me-globaleducation.org/you-meheaderlogo.png" alt="You-Me Global Education" class="img-responsive logo"  style =" width:220px; max-height: 85px; " alt="School Logo"></a>
                <input type="hidden" id="loginas" value= "<?= $loginas ?>">
            </div>
            <div class="navbar-center col-md-4 col-sm-6" style=" text-align:center">
                <?php if(!empty($student_details[0])){ ?>
                    <a href="#"><img src='<?=$baseurl."img/".$student_details[0]['company']["comp_logo"]?>' style =" width:110px; max-height: 85px; " alt="School Logo" class="img-responsive logo"></a>
                    <p><?= $student_details[0]['company']["comp_name"]?></p>
                <?php } ?>
            </div>
            <div class="navbar-right col-md-4 col-sm-6">
              
                <div id="navbar-menu">
                    <ul class="nav navbar-nav">                        
                        <li>
                            <div class="user-account" style="display:block; text-align:center">
                                <?php if (!empty($student_details[0]['pic'])) { ?>
                                    <img src="<?=$baseurl."img/".$student_details[0]["pic"]?>" height="50" widht="50" class="rounded-circle user-photo" alt="Student pic"> 
                                <?php } else { ?>
                                    <img src="/school/img/female.jpg" height="50" widht="50" class="rounded-circle user-photo" alt="Student pic"> 
                                <?php } ?>
                                <div class="dropdown" style="display:block">
                                    <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>
                                        <?php if(!empty($student_details[0]))
                                        {
                                            echo $student_details[0]['f_name']." ". $student_details[0]['l_name'];
                                        } ?>
                                    </strong></a>                    
                                    <ul class="dropdown-menu dropdown-menu-right account animated flipInY">
                                        <li>
                                            <a href="#"><i class="icon-user"></i><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '601') { echo $langlbl['title'] ; } } ?></a>   
                                        </li>                       
                                        <li class="divider"></li>
                                        <li>
                                            <a href="/school/logouts"><i class="icon-power"></i><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '602') { echo $langlbl['title'] ; } } ?></a>   
                                        </li>
                                    </ul>
                                </div>
			                </div>
			            </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

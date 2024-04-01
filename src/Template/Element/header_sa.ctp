<?php
$hostname = "localhost";
$username = "youmeglo_globaluser";
$password = "DFmp)9_p%Kql";
$database = "youmeglo_globalweb";

$con = mysqli_connect($hostname, $username, $password, $database); 
if(mysqli_connect_error($con)){ echo "Connection Error."; die();} 


//print_r($cvndr_details); die;
if(!empty($user_details[0])){
    $title = ":: Super :: Admin Dashboard";
    $loginas = "superadmin";
   
}
else if(!empty($parent_details)){
    $title =  "Parent ::  Dashboard";
    $loginas = "parent";
   
}
else if(!empty($cvndr_details)){
    $title =  "Canteen Vendor ::  Dashboard";
    $loginas = "cvendor";
}
else{
    setcookie('id', '', time()-1000  , $baseurl );
    setcookie('tid', '', time()-1000  , $baseurl );
    setcookie('stid', '', time()-1000  , $baseurl );
    setcookie('subid', '', time()-1000  , $baseurl );
    setcookie('pid', '', time()-1000  , $baseurl );
    setcookie('sid', '', time()-1000  , $baseurl );
    setcookie('cid', '', time()-1000  , $baseurl );
    header("location: https://you-me-globaleducation.org/redirect");
    exit();
}

/*$sidebarcolor = "#01319d";
$buttoncolor = "#01319d";*/
if(!empty($cvndr_details)){
	$sidebarcolor = $cvndr_details[0]['nav_color'];
	$buttoncolor = $cvndr_details[0]['button_color'];
}
else
{
    $sidebarcolor = "#000036";
    $buttoncolor = "#000036";
}
$logouturl = base64_encode('school/login');

?>
<!doctype html>
<html lang="en" translate="no">

<head>
<title><?=$title?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="School Management Project Dashboard">
<meta name="author" content="School Management Project Dashboard">
<meta name="google" content="notranslate">
<link rel="icon" href="https://you-me-globaleducation.org/you-meheaderlogo.png" type="image/x-icon">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

<!--<meta http-equiv="refresh" content="3600;url=https://you-me-globaleducation.org/school/login" />-->
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
<link rel="stylesheet" href="<?=$baseurl?>css/image-uploader.min.css">
<link rel="stylesheet" href="<?=$baseurl?>css/custom.css">
<link rel="stylesheet" href="<?=$baseurl?>css_3rdparty/tempusdominus-bootstrap-4.min.css" />
<link rel="stylesheet" href="<?=$baseurl?>css/slim.min.css">
<link rel="stylesheet" href="<?=$baseurl?>css/lightbox.css">

<?php
if(!empty($parent_details)) { ?>
    <link rel="stylesheet" href="<?=$baseurl?>digitalsign/css/jquery.signature.css">
<?php }
?>

<style>

body{
    font-family: arial !important;
    font-size: 13px !important;
}
#left-sidebar {
    background: -webkit-linear-gradient(top, <?= $sidebarcolor ?>, <?= $sidebarcolor ?>);
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
	color: <?= $sidebarcolor?> !important;
}
.theme-orange #wrapper:before, .theme-orange #wrapper:after, .theme-orange:before, .theme-orange:after{
	background: <?= $sidebarcolor?> !important;
}

h2.heading
{
	font-size: 15px !important;
	font-weight: bold;
	color: <?= $sidebarcolor?> !important;
}

.header 
{
	border-top: <?= $sidebarcolor ?> 4px solid  !important ;
    border-radius: .55rem;
}
h5
{
	color: <?= $sidebarcolor?> !important;
	
}
h2.heading
{
	color: <?= $sidebarcolor?> !important;
}
.page-item.active .page-link
{
	background-color: <?= $buttoncolor?> !important;
    border-color: <?= $buttoncolor?> !important;
}
.iconsss, #defaultModalLabel, .subhead
{
	color: <?= $sidebarcolor?> !important;
}
h5
{
    color: <?= $sidebarcolor?> !important;
}
.bg-dash
{
	background-color: <?= $sidebarcolor?> !important;
	color: #ffffff !important;
}
.form-control {
    font-size: 13px;
}
.page-loader-wrapper {
    background: <?= $sidebarcolor?> !important;
}
h6 {
    color: <?= $sidebarcolor?> !important;
}
.btn-primary, .btn-secondary, .btn-success, .btn-info
{
	background-color: <?= $buttoncolor ?> !important;
	border-color: <?= $buttoncolor ?> !important;
	color: #ffffff;
}

.theme-orange #left-sidebar .nav-tabs .nav-link.active{
	color: #ffffff !important;
}
.theme-orange .sidebar-nav .metismenu>li i , .sidebar-nav .metismenu a
{
	color: #ffffff !important;
	font-size:14px;
}

.theme-orange .sidebar-nav .metismenu>li.active i ,  ul.metismenu li.active a span , .sidebar-nav .metismenu a:hover, .sidebar-nav .metismenu a:focus, .sidebar-nav .metismenu a:active, .sidebar-nav .metismenu a:hover i, .sidebar-nav .metismenu a:focus i, .sidebar-nav .metismenu a:hover span, .sidebar-nav .metismenu a:focus span, .sidebar-nav .metismenu a:active span
{
	color: #292929 !important;
	font-weight:bold;
	font-size:14px;
}


.theme-orange .sidebar-nav .metismenu>li.active>a
{
	border-left-color: #7b4fbd !important;
}
.sidebar-nav .metismenu ul a
{
    color:#ffffff;
}

<?php if(!empty($user_details[0]) || !empty($parent_details[0])) { ?>
.navbar-fixed-top .navbar-brand img{width:225px;vertical-align:top;margin-top:2px;max-height:85px }
#main-content { margin-top:90px;}
#left-sidebar { margin-top:15px; }
<?php } ?>
</style>


<style>


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
  background-color: <?= $sidebarcolor?> !important;
}

input:focus + .slider {
  box-shadow: 0 0 1px <?= $sidebarcolor?> !important;
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

.table .thead-dark th {
    color: #ffffff;
    background-color: #000036;
    border-color: #000036;
}
@media screen and  (max-width: 375px)  and (min-width: 200px) 
{
    .navbar-fixed-top .navbar-right
    {
       width: calc(100% - 350px) !important;
    }
}
@media screen and (min-width: 400px) 
{
    .navbar-brand
    {
       width: 0px !important;
    }
}
@media screen and  (max-width: 768px)  and (min-width: 400px) 
{
    .navbar-brand
    {
       width: 0px !important;
    }
}

<?php if(!empty($parent_details))
{ ?>
.user-account
{
    margin:20px 10px !important;
}
#left-sidebar {
    width:255px !important;
}

<?php } ?>


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

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="<?=$baseurl?>img/logo-icon.png" width="48" height="48" alt="CSS"></div>
        <p>Please wait...</p>        
    </div>
</div>
<!-- Overlay For Sidebars -->

<div id="wrapper">

    <nav class="navbar navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-btn">
                <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
            </div>

            <div class="navbar-brand">
                <img src="https://you-me-globaleducation.org/you-meheaderlogo.png" alt="You-Me Global Education" class="img-responsive logo" width="180px">
                 <input type="hidden" id="loginas" value= "<?= $loginas ?>"> 
                 
                 
            </div>
            <div class="navbar-right">
                <div id="navbar-menu">
                    <ul class="nav navbar-nav">
                        <li>
                            <?php if(!empty($user_details[0])) { ?>
                                <a href="<?=$baseurl?>logouta" class="icon-menu"><i class="icon-login"></i></a> 
                            <?php } elseif(!empty($parent_details[0])) { ?>
                                <a href="<?=$baseurl?>logoutp" class="icon-menu"><i class="icon-login"></i></a> 
                            <?php } elseif(!empty($cvndr_details[0])) { ?>
                                <a href="<?=$baseurl?>logoutc" class="icon-menu"><i class="icon-login"></i></a> 
                            <?php } ?> 
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>   


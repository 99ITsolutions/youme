<?php //print_r($emp_details); die;
if(!empty($company_details[0]['comp_name'])){
    $title = $company_details[0]['comp_name'];
    $color = $company_details[0]['primary_color'];
    $btncolor = $company_details[0]['button_color'];
    
}
else if(!empty($sclsub_details)){
    $title =  "Subadmin ::  Dashboard";
	$color = $sclsub_details[0]['company']['primary_color'];
	$btncolor = $sclsub_details[0]['company']['button_color'];

}
else if(!empty($student_details)){
    $title =  "Student ::  Dashboard";
	$color = $student_details[0]['company']['primary_color'];
	$btncolor = $student_details[0]['company']['button_color'];

}
else if(!empty($emp_details)){
    $title = " Teacher ::  Dashboard";
	$color = $emp_details[0]['company']['primary_color'];
	$btncolor = $emp_details[0]['company']['button_color'];
	
}
else{
    $title = ":: School ::  Dashboard";
}
?>
<?php $roomid= "118"; ?>
<!doctype html>
<html lang="en">

<head>
<title><?=$title?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="School Management Project Dashboard">
<meta name="author" content="School Management Project Dashboard">
<?php

if(!empty($company_details[0]['favicon']))
{ ?>
    <link rel="icon" href="<?=$baseurl."img/".$company_details[0]["favicon"]?>" type="image/x-icon">
<?php }
elseif(!empty($student_details[0]['company']['favicon']))
{ ?>
    <link rel="icon" href="<?=$baseurl."img/".$student_details[0]['company']["favicon"]?>" type="image/x-icon">
<?php }
elseif(!empty($emp_details[0]['company']['favicon']))
{ ?>
    <link rel="icon" href="<?=$baseurl."img/".$emp_details[0]['company']["favicon"]?>" type="image/x-icon">
<?php }
elseif(!empty($sclsub_details[0]['company']['favicon']))
{ ?>
    <link rel="icon" href="<?=$baseurl."img/".$sclsub_details[0]['company']["favicon"]?>" type="image/x-icon">
<?php }
/*elseif(!empty($user_details[0])){
?>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
<?php
}*/
?>

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
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
<!-- MAIN CSS -->
<link rel="stylesheet" href="<?=$baseurl?>css/main.css">
<link rel="stylesheet" href="<?=$baseurl?>css/color_skins.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<link href="<?=$baseurl?>css/mdtimepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" rel="stylesheet" />


<link rel="stylesheet" href="<?=$baseurl?>css/image-uploader.min.css">
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="<?=$baseurl?>css/datepickk.min.css">

<link rel="stylesheet" href="<?=$baseurl?>css/fullcalendar.min.css" />

<link rel="stylesheet" href="<?=$baseurl?>css/custom.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />

<!--<link rel="stylesheet" href="<?=$baseurl?>css/croppie.css">-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="<?=$baseurl?>js/croppie.js"></script>
<link rel="stylesheet" href="<?=$baseurl?>css/croppie.css">-->

<link rel="stylesheet" href="https://www.beautybossnetwork.com/membership/crop/css/slim.min.css">
<style>

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
.bg-dash
{
	background-color: #440884 !important;
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

@media screen and (max-width: 768px)
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
}
    
</style>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://conference.you-me-globaleducation.org/external_api.js"></script>
        
        <script>
        
		var apiObj = null;
        var joinids = [];
        var leftids = [];
window.onload = function StartMeeting(){
    const domain = 'conference.you-me-globaleducation.org';
    
    const options = {
        roomName: '<?php echo $roomid;?>',
        width: '100%',
        height: 500,
        parentNode: document.querySelector('#jitsi-meet-conf-container'),
        userInfo: {
            displayName: '<?php echo $conferenceuser;?>',
            email: '<?php echo $conferenceuseremail;?>'
        },
        configOverwrite:{
            doNotStoreRoom: true,
        //    startVideoMuted: 0,
            startWithVideoMuted: false,
            startWithAudioMuted: false,
            enableWelcomePage: false,
            prejoinPageEnabled: false,
            disableRemoteMute: false,
            remoteVideoMenu: {
                disableKick: false
            },
        },
        interfaceConfigOverwrite: {
        HIDE_INVITE_MORE_HEADER: true,
        DEFAULT_LOGO_URL: 'https://www.you-me-globaleducation.org/youme-logo.png',
        BRAND_WATERMARK_LINK: 'https://www.you-me-globaleducation.org/youme-logo.png',
        SHOW_BRAND_WATERMARK: true,
        <?php if($conferenceuseremail == 'nanhu.nancy@gmail.com'){?>
        TOOLBAR_BUTTONS: 
        ['microphone', 'camera', 'closedcaptions', 'desktop','fullscreen',
        'fodeviceselection', 'hangup', 'chat','etherpad','settings', 'raisehand',
        'videoquality', 'filmstrip', 'feedback','mute-everyone'
        ] <?php }else{?>
        TOOLBAR_BUTTONS: 
        ['microphone', 'camera', 'closedcaptions', 'desktop','fullscreen',
        'fodeviceselection', 'hangup', 'chat','etherpad','settings', 'raisehand',
        'videoquality', 'filmstrip', 'feedback'
        ]        
        <?php } ?>
        }
    };
    apiObj = new JitsiMeetExternalAPI(domain, options);

    apiObj.addEventListeners({
        
        participantJoined: function(data){
            console.log('participantJoined', data);
            
            joinids.push(data['id']);
            console.log('participantids', joinids);
            
        },
        participantLeft: function(data){
            leftids.push(data['id']);
            console.log('participantLeft', leftids);
        },        
        
        readyToClose: function () {
 
            $('#jitsi-meet-conf-container').empty();
            
           <?php if($conferenceuseremail == 'nanhu.nancy@gmail.com'){?>
           
            var activeids = joinids.filter(x => leftids.indexOf(x) === -1);
            var i;
            for (i = 0; i < activeids.length; i++) {
                
        /*    apiObj.executeCommand('kickParticipant',
                participantID: 'hfc'
            ); */
            
            }
            
            $('#jitsi-meet-conf-container').empty();
            
            //window.location.replace("https://you-me-globaleducation.org/school/teacherNotifications");
            <?php }else{?>
            
            window.location.replace("https://you-me-globaleducation.org/school/studentdashboard");
            <?php }?>
            
        }
    });
}

	</script>

</head>
<body class="theme-orange">
<?php 
/*if($btncolor == "#6A287E" || $btncolor == "#F52887"  || $btncolor == "#4863A0"  || $btncolor == "#736F6E"  || $btncolor == "#7D0552" || $btncolor == "#1B0951"  || $btncolor == "#2a2a2a" || $btncolor == "#000080") {*/
if($btncolor == "#2a2a2a" || $btncolor == "#1B0951" || $btncolor == "#5D7E98"  || $btncolor == "#7199D6") { 
?>
<style>
.btn-primary, .btn-secondary, .btn-success, .btn-info
{
	background-color: <?= $btncolor?> !important;
	border-color: <?= $btncolor?> !important;
	color: #ffffff !important;
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
</style>
<?php } ?>

<?php
/*if( $color == "#6A287E" || $color == "#F52887" || $color == "#4863A0" || $color == "#736F6E" || $color == "7D0552" || $color == "000080" || $color == "#2a2a2a" || $color == "#1B0951") { */
if($color == "#2a2a2a" || $color == "#1B0951" || $color == "#5D7E98"  || $color == "#7199D6") { 
?>
<style>
#left-sidebar
{
    background:  <?= $color?> !important;
    color: #fff;
}

ul.main-menu li a span , ul.metismenu li a span 
{
    color:#fff;
}

ul.main-menu li.active a span , ul.metismenu li.active a span 
{
    color:#242e3b;
}
.sidebar-nav .metismenu ul a
{
    color:#fff;
}

.theme-orange .sidebar-nav .metismenu>li i 
{
	color: #fff !important;
}

.theme-orange #left-sidebar .nav-tabs .nav-link.active{
	color: #fff !important;
}

.sidebar-nav .metismenu>li a
{
    color:#fff !important;
}

</style>
<?php
} else { ?>
<style>
#left-sidebar
{
    background:  <?= $color?> !important;
    color: #242E3B;
}

ul.main-menu li a span , ul.metismenu li a span 
{
    color:#242E3B;
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
    background-color:#f1f1f1;
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
        <div class="container-fluid">
            <div class="navbar-btn">
                <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
            </div>

            <div class="navbar-brand" style="width:33%">
                <a href="#"><img src="http://www.you-me-globaleducation.org/youme-logo.png" alt="You-Me Technology Logo" class="img-responsive logo"  style =" width:220px; max-height: 85px; " alt="School Logo"></a>
            <?php 
                /*if(!empty($company_details[0])){ ?>
                        <a href="<?=$baseurl?>"><img src='<?=$baseurl."img/".$company_details[0]["comp_logo"]?>' style =" width:110px; max-height: 90px; "  alt="School Logo" class="img-responsive logo"></a>
           <?php }
                elseif(!empty($student_details[0])){ ?>
                        <a href="<?=$baseurl?>"><img src='<?=$baseurl."img/".$student_details[0]['company']["comp_logo"]?>' style =" width:110px; max-height: 85px; " alt="School Logo" class="img-responsive logo"></a>
           <?php }
               elseif(!empty($emp_details[0])){ ?>
                            <a href="<?=$baseurl?>"><img src='<?=$baseurl."img/".$emp_details[0]['company']["comp_logo"]?>'  alt="School Logo" class="img-responsive logo"></a>
               <?php }*/
               /* elseif(!empty($user_details[0])) { ?>
                        <a href="<?=$baseurl?>"><img src="http://www.you-metechnologies.com/3D-white-background.png" alt="You-Me Technology Logo" class="img-responsive logo" width=150px></a>  
             <?php }*/ ?>
                              
            </div>
            <div class="navbar-center" style="width:33%; text-align:center">
                <!--<img src="http://www.you-metechnologies.com/youme-logo.png" alt="You-Me Technology Logo" class="img-responsive logo" width=225px>-->
                <?php 
                if(!empty($company_details[0])){ ?>
                        <a href="<?=$baseurl?>"><img src='<?=$baseurl."img/".$company_details[0]["comp_logo"]?>' style =" width:110px; max-height: 90px; "  alt="School Logo" class="img-responsive logo"></a>
           <?php }
                elseif(!empty($student_details[0])){ ?>
                        <a href="<?=$baseurl?>"><img src='<?=$baseurl."img/".$student_details[0]['company']["comp_logo"]?>' style =" width:110px; max-height: 85px; " alt="School Logo" class="img-responsive logo"></a>
           <?php }
               elseif(!empty($emp_details[0])){ ?>
                            <a href="<?=$baseurl?>"><img src='<?=$baseurl."img/".$emp_details[0]['company']["comp_logo"]?>'  style =" width:110px; max-height: 85px; " alt="School Logo" class="img-responsive logo"></a>
               <?php }
               elseif(!empty($sclsub_details[0])){ ?>
                            <a href="<?=$baseurl?>"><img src='<?=$baseurl."img/".$sclsub_details[0]['company']["comp_logo"]?>'  style =" width:110px; max-height: 85px; " alt="School Logo" class="img-responsive logo"></a>
               <?php }
               /* elseif(!empty($user_details[0])) { ?>
                        <a href="<?=$baseurl?>"><img src="http://www.you-metechnologies.com/3D-white-background.png" alt="You-Me Technology Logo" class="img-responsive logo" width=150px></a>  
             <?php } */?>
            </div>
            <div class="navbar-right" style="width:32%">
                <!-- <form id="navbar-search" class="navbar-form search-form">
                    <input value="" class="form-control" placeholder="Search here..." type="text">
                    <button type="button" class="btn btn-default"><i class="icon-magnifier"></i></button>
                </form>                -->
                
                <div id="navbar-menu">
                    <ul class="nav navbar-nav">                        
                        <li>
                            <div class="user-account" style="display:block; text-align:center">
			                    <?php 
                                    if (!empty($company_details[0]['comp_logo'])) 
                                    {
                                        ?>
                                            <img src="<?=$baseurl."img/".$company_details[0]["comp_logo"]?>" height="50" widht="50" class="rounded-circle user-photo" alt="Company logo"> 
                                        <?php
                                    }
                                    elseif (!empty($emp_details[0]['pict'])) 
                                    {
                                        ?>
                                            <img src="<?=$baseurl."img/".$emp_details[0]["pict"]?>" height="50" widht="50" class="rounded-circle user-photo" alt="Employee Pic"> 
                                        <?php
                                    }
                                    elseif (!empty($sclsub_details[0]['picture'])) 
                                    {
                                        ?>
                                            <img src="<?=$baseurl."img/".$sclsub_details[0]["picture"]?>" height="50" widht="50" class="rounded-circle user-photo" alt="Sub Admin Pic"> 
                                        <?php
                                    }
                                    elseif (!empty($student_details[0]['pic'])) 
                                    {
                                        ?>
                                            <img src="<?=$baseurl."img/".$student_details[0]["pic"]?>" height="50" widht="50" class="rounded-circle user-photo" alt="Student pic"> 
                                        <?php
                                    }
                                    else
                                    {  ?>
                                            <img src="<?=$baseurl?>img/user.png" class="rounded-circle user-photo" alt="User Profile Picture">
                                    <?php
                                        }
                                    ?>
                                    <div class="dropdown" style="display:block">
                                       
                                        <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>
                                            <?php 
                                                if(!empty($company_details[0]))
                                                {
                                                    echo $company_details[0]['comp_name'];
                                                }
                                                elseif(!empty($emp_details[0]))
                                                {
                                                    echo $emp_details[0]['f_name']." ". $emp_details[0]['l_name'];
                                                }
                                                elseif(!empty($student_details[0]))
                                                {
                                                    echo $student_details[0]['f_name']." ". $student_details[0]['l_name'];
                                                } 
                                                elseif(!empty($sclsub_details[0]))
                                                {
                                                    echo $sclsub_details[0]['fname']." ". $sclsub_details[0]['lname'];
                                                }
                                            ?>
                                        </strong></a>                    
                                        <ul class="dropdown-menu dropdown-menu-right account animated flipInY">
                                            <li>
                                                <?php 
                                                if(!empty($company_details[0]))
                                                { ?>
                                                   <a href="<?=$baseurl?>schools/profile/<?=md5($company_details[0]['id'])?>"><i class="icon-user"></i>My Profile</a>
                                                <?php }
                                                elseif(!empty($emp_details[0]))
                                                { ?>
                                                   <a href="<?=$baseurl?>employee/profile/<?=md5($emp_details[0]['id'])?>"><i class="icon-user"></i>My Profile</a>
                                                <?php }
                                                elseif(!empty($student_details[0]))
                                                { ?>
                                                   <a href="<?=$baseurl?>students/profile/<?=md5($student_details[0]['id'])?>"><i class="icon-user"></i>My Profile</a>   
                                                <?php 
                                                } 
                                                elseif(!empty($sclsub_details[0]))
                                                { ?>
                                                   <a href="<?=$baseurl?>schoolSubadmin/profile/<?=md5($sclsub_details[0]['id'])?>"><i class="icon-user"></i>My Profile</a>   
                                                <?php 
                                                } 
                                                ?>
                                                
                                            </li>                       
                                            <li class="divider"></li> 
                                            <li>
                                                <?php 
                                                if(!empty($company_details[0]))
                                                { ?>
                                                   <a href="<?=$baseurl?>logout"><i class="icon-power"></i>Logout</a>
                                                <?php }
                                                elseif(!empty($emp_details[0]))
                                                { ?>
                                                   <a href="<?=$baseurl?>logout"><i class="icon-power"></i>Logout</a>
                                                <?php }
                                                elseif(!empty($student_details[0]))
                                                { ?>
                                                   <a href="<?=$baseurl?>logout"><i class="icon-power"></i>Logout</a>   
                                                <?php } 
                                                elseif(!empty($sclsub_details[0]))
                                                    { ?>
                                                       <a href="<?=$baseurl?>logout"><i class="icon-power"></i>Logout</a>   
                                                <?php } 
                                            ?>
                                                
                                            </li>
                                        </ul>
                                    </div>
			                </div>
			            </li>
                        <li>
                            <?php 
                            if(!empty($company_details[0])){ ?>
                                   <!-- <a href="<?=$baseurl?>logout" class="icon-menu"><i class="icon-login"></i></a>-->
                           <?php }
                           elseif(!empty($student_details[0])){ ?>
                                    <!--<a href="<?=$baseurl?>logout" class="icon-menu"><i class="icon-login"></i></a>-->
                           <?php }
                           elseif(!empty($emp_details[0])){ ?>
                                    <!--<a href="<?=$baseurl?>logout" class="icon-menu"><i class="icon-login"></i></a>-->
                           <?php }
                           /*elseif(!empty($user_details[0])) { ?>
                                    <a href="<?=$baseurl?>logouta" class="icon-menu"><i class="icon-login"></i></a> 
                             <?php } */?>
                            
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

<?php
    if(!empty($company_details[0]))
    {
        $spec_cond = md5($company_details[0]['id']) ;
        $left_sidebar = '';
    }
    elseif(!empty($emp_details[0])){
        $spec_cond = md5($emp_details[0]['id']) ;
        $left_sidebar = '';
    }
    elseif(!empty($sclsub_details[0])){
        $spec_cond = md5($sclsub_details[0]['id']) ;
        $left_sidebar = '';
    }
    elseif(!empty($student_details[0])){
        $spec_cond = md5($student_details[0]['id']) ;
    }
    $left_sidebar = "style = 'padding-top:115px !important '";
?>

  <div id="left-sidebar" class="sidebar" <?php echo $left_sidebar ?>>
        <div class="sidebar-scroll">
            <!--<div class="user-account">
                <?php 
                if (!empty($company_details[0]['comp_logo'])) 
                {
                    ?>
                        <img src="<?=$baseurl."img/".$company_details[0]["comp_logo"]?>" height="50" widht="50" class="rounded-circle user-photo" alt="Company logo"> 
                    <?php
                }
                elseif (!empty($emp_details[0]['pict'])) 
                {
                    ?>
                        <img src="<?=$baseurl."img/".$emp_details[0]["pict"]?>" height="50" widht="50" class="rounded-circle user-photo" alt="Employee Pic"> 
                    <?php
                }
                elseif (!empty($student_details[0]['pic'])) 
                {
                    ?>
                        <img src="<?=$baseurl."img/".$student_details[0]["pic"]?>" height="50" widht="50" class="rounded-circle user-photo" alt="Student pic"> 
                    <?php
                }
                else
                {  ?>
                        <img src="<?=$baseurl?>img/user.png" class="rounded-circle user-photo" alt="User Profile Picture">
                <?php
                    }
                ?>
                <div class="dropdown">
                    <span>Welcome,</span>
                    <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>
                        <?php 
                            if(!empty($company_details[0]))
                            {
                                echo $company_details[0]['comp_name'];
                            }
                            elseif(!empty($emp_details[0]))
                            {
                                echo $emp_details[0]['e_name'];
                            }
                            elseif(!empty($student_details[0]))
                            {
                                echo $student_details[0]['f_name']." ". $student_details[0]['l_name'];
                            } 
                        ?>
                    </strong></a>                    
                    <ul class="dropdown-menu dropdown-menu-right account animated flipInY">
                        <li>
                            <?php 
                            if(!empty($company_details[0]))
                            { ?>
                               <a href="<?=$baseurl?>users/profile/<?=md5($company_details[0]['id'])?>"><i class="icon-user"></i>My Profile</a>
                            <?php }
                            elseif(!empty($emp_details[0]))
                            { ?>
                               <a href="<?=$baseurl?>employee/profile/<?=md5($emp_details[0]['id'])?>"><i class="icon-user"></i>My Profile</a>
                            <?php }
                            elseif(!empty($student_details[0]))
                            { ?>
                               <a href="<?=$baseurl?>students/profile/<?=md5($student_details[0]['id'])?>"><i class="icon-user"></i>My Profile</a>   
                        <?php } 
                        ?>
                            
                        </li>                       
                        <li class="divider"></li> 
                        <li>
                            <?php 
                            if(!empty($company_details[0]))
                            { ?>
                               <a href="<?=$baseurl?>logout"><i class="icon-power"></i>Logout</a>
                            <?php }
                            elseif(!empty($emp_details[0]))
                            { ?>
                               <a href="<?=$baseurl?>logout"><i class="icon-power"></i>Logout</a>
                            <?php }
                            elseif(!empty($student_details[0]))
                            { ?>
                               <a href="<?=$baseurl?>logout"><i class="icon-power"></i>Logout</a>   
                        <?php } 
                        ?>
                            
                        </li>
                    </ul>
                </div>
                <hr>
               
            </div>-->
            <!-- Nav tabs -->
            
            <ul class="nav nav-tabs" style="display:none">
            <?php
            if(!empty($company_details[0]))
            {?>
                <li class="nav-item"><a class="nav-link <?=(in_array($controllerName , ['Schooldashboard', 'Schoolownrole' ,'Students' , 'Employee' , 'Classes' , 'Fees', 'Feehead','Feesetup','Feedetail' , 'Routes' , 'Vehicles' , 'Head' , 'Subhead' , 'Balance' , 'Transfer' , 'Discount' , 'Holiday' , 'Income', 'IncomeHead' , 'Stopage', 'Department' , 'Defaulter', 'Promotion', 'Enquiry', 'Attendence', 'Setting', 'Template', 'Sms', 'Registeration', 'Records' , 'Expensesvoucher' , 'Identity', 'Feecollection', 'FeeGeneration']) ?  "active" : ""  )?>" data-toggle="tab" href="#project_menu">Menu</a></li>                
             <?php    
            }
			elseif(!empty($emp_details[0]))
            {?>
                <li class="nav-item"><a class="nav-link <?=(in_array($controllerName , ['Schooldashboard','Payment' ,'Schoolownrole' ,'Students' , 'Employee' , 'Classes' , 'Fees', 'Feehead','Feesetup','Feedetail' , 'Routes' , 'Vehicles' , 'Head' , 'Subhead' , 'Balance' , 'Transfer' , 'Discount' , 'Holiday' , 'Income', 'IncomeHead' , 'Stopage', 'Department' , 'Defaulter', 'Promotion', 'Enquiry', 'Attendence', 'Setting', 'Template', 'Sms', 'Registeration', 'Records' , 'Expensesvoucher', 'Feecollection', 'FeeGeneration']) ?  "active" : ""  )?>" data-toggle="tab" href="#project_menu">Menu</a></li>                
             <?php    
            }
            elseif(!empty($student_details[0]))
            {?>
                <li class="nav-item"><a class="nav-link <?=(in_array($controllerName , ['Schooldashboard','Payment']) ?  "active" : ""  )?>" data-toggle="tab" href="#project_menu">Menu</a></li>                
             <?php    
            }	
            ?>
            </ul>
                
            <!-- Tab panes -->
            <div class="tab-content p-l-0 p-r-0">
                <?php 
            if(!empty($company_details[0]))
            { ?> 
                
            <!-- Tab panes -->
           
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['Schooldashboard', 'Schoolknowledge', 'Admissions', 'Readmissions', 'Schoolmarketplace', 'IdentityCard', 'SchoolSubadmin', 'SchoolLibrary', 'Schoolattendance', 'StudentSummary', 'AttendanceReport', 'SchoolTutorialfee','SchoolCalendar', 'SchoolNotification', 'Message', 'Schools', 'SchoolApproval', 'ExamAssessment', 'Gallery', 'Calendar', 'ClassSubjects', 'Students' ,  'Teachers' ,'Fees' , 'Knowledge', 'Subjects' , 'Classes' , 'Fees', 'Feehead','Feesetup','Feedetail' , 'Routes' , 'Vehicles' , 'Head' , 'Subhead' , 'Balance' , 'Transfer' , 'Discount' , 'Holiday' , 'Income' , 'Stopage', 'Department' , 'Defaulter', 'Promotion', 'Enquiry', 'Attendence', 'Setting', 'FeeGeneration','Template','Sms', 'Registeration', 'IncomeHead', 'Records' , 'Expensesvoucher' , 'Identity', 'Feecollection', 'FeeGeneration'] )  ? "active" : ""  )?>" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">                            
	                        <li class="<?=$controllerName == "Schooldashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>schooldashboard"><i class="icon-speedometer"></i><span>Dashboard</span></a></li>
	                        <li class="<?=$controllerName == "Schoolknowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolknowledge"><i class="fa fa-info"></i>You-Me Knowledge Center</a></li>
	                        <li class="<?=$controllerName == "Schoolmarketplace" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolmarketplace"><i class="fa fa-shopping-bag"></i>You-Me Market Place</a></li>
		                    <li class="<?=$controllerName == "SchoolSubadmin" ? "active" : "" ?>"><a href="<?=$baseurl?>SchoolSubadmin"><i class="fa fa-users"></i>Subadmin</a></li>
		                    <li class="<?=$controllerName == 'Classes' ? "active" : ""  ?>"><a   href="<?=$baseurl?>classes"><i class="fa fa-graduation-cap"></i>Classes</a></li>
	                        <li class="<?=$controllerName == 'Subjects' ? "active" : ""  ?>"><a   href="<?=$baseurl?>subjects"><i class="fa fa-book"></i>Subjects</a></li>
	                        <li class="<?=$controllerName == 'ClassSubjects' ? "active" : ""  ?>"><a   href="<?=$baseurl?>classSubjects"><i class="fa fa-book"></i>Class Subjects</a></li>
	                        <li class="<?=$controllerName == "Students" ? "active" : "" ?>"><a href="<?=$baseurl?>students"><i class="fa fa-graduation-cap"></i>Students </a></li>
	                        
	                        <li class="<?=(in_array($controllerName , ['Admissions', 'Readmissions']) ? "active" : ""  )?>" >
                                <a href="#Admissions" class="has-arrow"><i class="fa fa-university"></i><span>Admissions</span></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['Admissions']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Admissions">New Admissions</a></li>
                                    <li class="<?=(in_array($controllerName , ['Readmissions']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Readmissions">ReApplying Students</a></li>
                                </ul>
                            </li> 
                            
		                    <li class="<?=$controllerName == "Schoolattendance" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolattendance"><i class="fa fa-calendar"></i>Attendance</a></li>
		                    <li class="<?=$controllerName == "Teachers" ? "active" : "" ?>"><a href="<?=$baseurl?>teachers"><i class="icon-users"></i>Teachers</a></li>
		                    <li class="<?=$controllerName == "Fees" ? "active" : "" ?>"><a href="<?=$baseurl?>fees"><i class="fa fa-money "></i>Fees</a></li>
		                    <li class="<?=$controllerName == "ExamAssessment" ? "active" : "" ?>"><a href="<?=$baseurl?>examAssessment"><i class="fa fa-book"></i>Exams/Assignments List</a></li>
		                    <li class="<?=$controllerName == "Knowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>knowledge"><i class="fa fa-info"></i>Knowledge Base</a></li>
		                    <li class="<?=$controllerName == "" ? "active" : "" ?>"><a href="<?=$baseurl?>gallery"><i class="fa fa-image"></i>Gallery</a></li>
		                    <li class="<?=$controllerName == "SchoolCalendar" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolCalendar"><i class="fa fa-calendar"></i>Calendar</a></li>
		                    <li class="<?=$controllerName == "SchoolNotification" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolNotification"><i class="fa fa-bell"></i><span id="sclannouncemnts">Notification (<?= $schoolnotfycount ?>)</span></a></li>
		                    <li class="<?=$controllerName == "Message" ? "active" : "" ?>"><a href="<?=$baseurl?>message"><i class="fa fa-phone"></i>Cases (<?= $countmsg ?>)</a></li>
		                    <li class="<?=$controllerName == "SchoolLibrary" ? "active" : "" ?>"><a href="<?=$baseurl?>SchoolLibrary"><i class="fa fa-book"></i>Library</a></li>
		                    <li class="<?=$controllerName == "IdentityCard" ? "active" : "" ?>"><a href="<?=$baseurl?>IdentityCard"><i class="fa fa-id-card-o"></i>ID Card</a></li>
		                    <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) ? "active" : ""  )?>" >
                                <a href="#TutorialFee" class="has-arrow"><i class="fa fa-file"></i><span>Tutoring Center</span></a>
                                <ul>
                                     <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee">Tutoring Fee</a></li>
                                     <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "add" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/add">Student Tutoring Registration</a></li>
                                     <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "students" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/students">Student List</a></li>
                                </ul>
                            </li>
		                    <li class="<?=(in_array($controllerName , ['AttendanceReport' , 'StudentSummary' , 'Modules']) ? "active" : ""  )?>" >
                                <a href="#AttendanceReport" class="has-arrow"><i class="fa fa-file"></i><span>Reports</span></a>
                                <ul><li class="<?=(in_array($controllerName , ['AttendanceReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>AttendanceReport">Attendance Report</a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['StudentSummary']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>StudentSummary">Student Summary</a></li></ul>
                            </li>
                        </ul>
                    </nav>
                </div>
                   
            <?php }
            //elseif(!empty($sclsub_detailsss[0]))
            elseif(!empty($sclsub_details[0]))
            { 
                //print_r($sclsub_details);
                $privilages = explode(",", $sclsub_details[0]['privilages']); 
                 ?> 
                
            <!-- Tab panes -->
           
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['Schooldashboard', 'IdentityCard', 'Schoolknowledge', 'Schoolmarketplace', 'SchoolSubadmin', 'SchoolLibrary', 'Schoolattendance', 'StudentSummary', 'AttendanceReport', 'SchoolTutorialfee','SchoolCalendar', 'SchoolNotification', 'Message', 'Schools', 'SchoolApproval', 'ExamAssessment', 'Gallery', 'Calendar', 'ClassSubjects', 'Students' ,  'Teachers' ,'Fees' , 'Knowledge', 'Subjects' , 'Classes' , 'Fees', 'Feehead','Feesetup','Feedetail' , 'Routes' , 'Vehicles' , 'Head' , 'Subhead' , 'Balance' , 'Transfer' , 'Discount' , 'Holiday' , 'Income' , 'Stopage', 'Department' , 'Defaulter', 'Promotion', 'Enquiry', 'Attendence', 'Setting', 'FeeGeneration','Template','Sms', 'Registeration', 'IncomeHead', 'Records' , 'Expensesvoucher' , 'Identity', 'Feecollection', 'FeeGeneration'] )  ? "active" : ""  )?>" id="project_menu" style="display:block !important">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">                            
	                        <li class="<?=$controllerName == "Schooldashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>schooldashboard"><i class="icon-speedometer"></i><span>Dashboard</span></a></li>
	                        <li class="<?=$controllerName == "Schoolknowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolknowledge"><i class="fa fa-info"></i>You-Me Knowledge Center</a></li>
		                    <li class="<?=$controllerName == "Schoolmarketplace" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolmarketplace"><i class="fa fa-shopping-bag"></i>You-Me Market Place</a></li>
		                    
		                    <?php if(in_array("1", $privilages)) { ?>
		                        <li class="<?=$controllerName == 'Classes' ? "active" : ""  ?>"><a   href="<?=$baseurl?>classes"><i class="fa fa-graduation-cap"></i>Classes</a></li>
	                        <?php } if(in_array("2", $privilages)) {?>
	                            <li class="<?=$controllerName == 'Subjects' ? "active" : ""  ?>"><a   href="<?=$baseurl?>subjects"><i class="fa fa-book"></i>Subjects</a></li>
	                        <?php } if(in_array("3", $privilages)) {?>
	                            <li class="<?=$controllerName == 'ClassSubjects' ? "active" : ""  ?>"><a   href="<?=$baseurl?>classSubjects"><i class="fa fa-book"></i>Class Subjects</a></li>
	                        <?php } if(in_array("4", $privilages)) {?>
	                            <li class="<?=$controllerName == "Students" ? "active" : "" ?>"><a href="<?=$baseurl?>students"><i class="fa fa-graduation-cap"></i>Students </a></li>
		                    <?php } if(in_array("6", $privilages)) {?>
		                        <li class="<?=$controllerName == "Schoolattendance" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolattendance"><i class="fa fa-calendar"></i>Attendance</a></li>
		                    <?php } if(in_array("5", $privilages)) {?>
		                        <li class="<?=$controllerName == "Teachers" ? "active" : "" ?>"><a href="<?=$baseurl?>teachers"><i class="icon-users"></i>Teachers</a></li>
		                    <?php } if(in_array("7", $privilages)) {?>
		                        <li class="<?=$controllerName == "Fees" ? "active" : "" ?>"><a href="<?=$baseurl?>fees"><i class="fa fa-money "></i>Fees</a></li>
		                    <?php } if(in_array("8", $privilages)) {?>
		                        <li class="<?=$controllerName == "ExamAssessment" ? "active" : "" ?>"><a href="<?=$baseurl?>examAssessment"><i class="fa fa-book"></i>Exams/Assignments List</a></li>
		                    <?php } if(in_array("9", $privilages)) {?>
		                        <li class="<?=$controllerName == "Knowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>knowledge"><i class="fa fa-info"></i>Knowledge Base</a></li>
		                    <?php } if(in_array("10", $privilages)) {?>
		                        <li class="<?=$controllerName == "Gallery" ? "active" : "" ?>"><a href="<?=$baseurl?>gallery"><i class="fa fa-image"></i>Gallery</a></li>
		                    <?php } if(in_array("11", $privilages)) {?>
		                        <li class="<?=$controllerName == "SchoolCalendar" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolCalendar"><i class="fa fa-calendar"></i>Calendar</a></li>
		                    <?php } if(in_array("12", $privilages)) {?>
		                        <li class="<?=$controllerName == "SchoolNotification" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolNotification"><i class="fa fa-bell"></i><span id="sclannouncemnts">Notification (<?= $schoolnotfycount ?>)</span></a></li>
		                    <?php } if(in_array("13", $privilages)) {?>
		                        <li class="<?=$controllerName == "Message" ? "active" : "" ?>"><a href="<?=$baseurl?>message"><i class="fa fa-phone"></i>Cases (<?= $countmsg ?>)</a></li>
		                    <?php } if(in_array("15", $privilages)) {?>
		                        <li class="<?=$controllerName == "SchoolLibrary" ? "active" : "" ?>"><a href="<?=$baseurl?>SchoolLibrary"><i class="fa fa-book"></i>Library</a></li>
		                    <?php }  if(in_array("17", $privilages)) {?>
		                         <li class="<?=$controllerName == "IdentityCard" ? "active" : "" ?>"><a href="<?=$baseurl?>IdentityCard"><i class="fa fa-id-card-o"></i>ID Card</a></li>
		                    
		                    <?php }
		                    if(in_array("14", $privilages)) {?>
    		                    <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) ? "active" : ""  )?>" >
                                    <a href="#TutorialFee" class="has-arrow"><i class="fa fa-file"></i><span>Tutoring Center</span></a>
                                    <ul>
                                         <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee">Tutoring Fee</a></li>
                                         <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "add" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/add">Student Tutoring Registration</a></li>
                                         <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "students" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/students">Student List</a></li>
                                    </ul>
                                </li>
                            <?php } if(in_array("16", $privilages)) {?>
    		                    <li class="<?=(in_array($controllerName , ['AttendanceReport' , 'StudentSummary' , 'Modules']) ? "active" : ""  )?>" >
                                    <a href="#AttendanceReport" class="has-arrow"><i class="fa fa-file"></i><span>Reports</span></a>
                                    <ul><li class="<?=(in_array($controllerName , ['AttendanceReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>AttendanceReport">Attendance Report</a></li></ul>
                                    <ul><li class="<?=(in_array($controllerName , ['StudentSummary']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>StudentSummary">Student Summary</a></li></ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
                   
            <?php }
            elseif(!empty($student_details[0]))
            { 
            ?> 
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['Studentdashboard', 'Subjectattendance', 'Studentmarketplace', 'ExamListing', 'StudentDiscussion', 'Meetings', 'Studentknowledge', 'StudentAttendance', 'StudentMessages', 'StudentFee', 'AllGrades',  'SubjectGrade', 'TutoringCenter', 'Assessments', 'Announcement', 'Subjectdetails', 'Calendar', 'ClassLibrary' , 'SubjectLibrary' ,   'ViewGallery', 'Studentsubjects', 'ViewKnowledge', 'Students', 'Payment', 'Holiday', 'ParentsHoliday', 'StudentAttendance'] )  ? "active" : ""  )?>" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li class="<?=$controllerName == "Studentdashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>studentdashboard"><i class="icon-speedometer"></i><span>Dashboard</span></a></li>
                            <!--<li class="<?=$controllerName == "Students" ? "active" : "" ?>"><a href="<?=$baseurl?>studentdashboard/studentprofile"><i class="fa fa-user"></i><span>Student Profile</span></a></li>-->
                            <li class="<?=$controllerName == "Studentknowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>studentknowledge"><i class="fa fa-info" aria-hidden="true"></i><span>You-Me Knowledge Center</span></a></li>
                            <li class="<?=$controllerName == "Studentmarketplace" ? "active" : "" ?>"><a href="<?=$baseurl?>Studentmarketplace"><i class="fa fa-shopping-bag"></i>You-Me Market Place</a></li>
		                    
                            <li class="<?=$controllerName == "ViewKnowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>viewKnowledge"><i class="fa fa-book" aria-hidden="true"></i><span>Knowledge Base</span></a></li>
							
								<li  <?= $showsidebar ?> class="<?=$controllerName == "Calendar" ? "active" : "" ?>" ><a href="<?=$baseurl?>Calendar"><i class="fa fa-calendar"></i><span>Calendar</span></a></li> 
    							<li  <?= $showsidebar ?> class="<?=$controllerName == "StudentAttendance" ? "active" : "" ?>" ><a href="<?=$baseurl?>studentAttendance"><i class="fa fa-address-card-o"></i><span>Attendance</span></a></li> 
    							<li  <?= $showsidebar ?> class="<?=$controllerName == "StudentFee" ? "active" : "" ?>" ><a href="<?=$baseurl?>studentFee"><i class="fa fa-usd"></i><span>My Fees</span></a></li> 
                                <li  <?= $showsidebar ?> class="<?=$controllerName == "ViewGallery" ? "active" : "" ?>"><a href="<?=$baseurl?>viewGallery"><i class="fa fa-image"></i>Events</a></li>
                                <li  <?= $showsidebar ?> class="<?=$controllerName == "Announcement" ? "active" : "" ?>" ><a href="<?=$baseurl?>announcement" id="studentannouncements"><i class="fa fa-bullhorn" aria-hidden="true"></i><span id="studannouncemnts">Announcement (<?= $studntnoti_count ?>)</span></a></li>
                                <li  <?= $showsidebar ?> class="<?=$controllerName == "ClassLibrary" ? "active" : "" ?>"><a href="<?=$baseurl?>ClassLibrary"><i class="fa fa-book" aria-hidden="true"></i><span>Library</span></a></li>
    						    <li  <?= $showsidebar ?> class="<?=$controllerName == "TutoringCenter" ? "active" : "" ?>" ><a href="<?=$baseurl?>TutoringCenter"><i class="fa fa-file-text" aria-hidden="true"></i><span>Tutoring Center</span></a></li>
    							<!--<li  <?= $showsidebar ?> class="<?=$controllerName == "TutoringCenter" ? "active" : "" ?>" ><a href="javascript:void(0)" data-toggle="modal" data-target="#tutoringlogin"><i class="fa fa-file-text" aria-hidden="true"></i><span>Tutorial Center</span></a></li>-->
                                <li  <?= $showsidebar ?> class="<?=$controllerName == "StudentMessages" ? "active" : "" ?>" ><a  href="<?=$baseurl?>StudentMessages"><i class="fa fa-phone" aria-hidden="true"></i><span>Contact School Office (<?= $countunreadmsg ?>)</span></a></li>
                                <li  <?= $showsidebar ?> class="<?=$controllerName == "Meetings" ? "active" : "" ?>" ><a  href="<?=$baseurl?>Meetings"><i class="fa  fa-link" aria-hidden="true"></i><span>Online Class</span></a></li>
                          
                            	
                        </ul>
                    </nav>
                </div>
                   
            <?php } 
		    elseif(!empty($emp_details[0]))
			{ ?> 
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['Teacherdashboard', 'Teacherattendance', 'Teachermarketplace', 'MeetingLink', 'Conference', 'Teacherknowledge', 'ClassAttendance', 'ViewSchoolGallery',  'TeacherexamAssessment','Tutorialfee', 'TeacherCalendar', 'Employee', 'TeacherPost', 'DetailAssessment', 'TeacherSubject', 'TeacherNotifications',  'TeacherLibrary', 'ClassGrade', 'ClassAssessment', 'ClassExams'] )  ? "active" : ""  )?>" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li class="<?=$controllerName == "Teacherdashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherdashboard"><i class="icon-speedometer"></i><span>Dashboard</span></a></li>
                            <li class="<?=$controllerName == "Teacherknowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherknowledge"><i class="fa fa-info" aria-hidden="true"></i><span>You-Me Knowledge Center</span></a></li>
                            <li class="<?=$controllerName == "Teachermarketplace" ? "active" : "" ?>"><a href="<?=$baseurl?>Teachermarketplace"><i class="fa fa-shopping-bag"></i>You-Me Market Place</a></li>
		                    
                            <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) ? "active" : ""  )?>" >
                                <a href="#TutorialFee" class="has-arrow"><i class="fa fa-file"></i><span>Tutoring Center</span></a>
                                <ul>
                                     <li class="<?=(in_array($controllerName , ['Tutorialfee']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>tutorialfee">Tutoring Fee</a></li>
                                     <li class="<?=(in_array($controllerName , ['Tutorialfee']) && $actionName == "subjects" ? "active" : ""  )?>"><a   href="<?=$baseurl?>tutorialfee/subjects">Add Tutoring Content</a></li>
                                     <li class="<?=(in_array($controllerName , ['Tutorialfee']) && $actionName == "students" ? "active" : ""  )?>"><a   href="<?=$baseurl?>tutorialfee/students">Student List</a></li>
                                </ul>
                            </li>
                            <li class="<?=$controllerName == "Conference" ? "active" : "" ?>"><a href="<?=$baseurl?>Conference"><i class="fa fa-phone"></i>Conference </a></li>
                            
                            <li class="<?=$controllerName == "TeacherexamAssessment" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherexamAssessment"><i class="fa fa-book"></i>Exams/Assignments </a></li>
                            <li class="<?=$controllerName == "TeacherNotifications" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherNotifications"  id="teacherannouncements"><i class="fa fa-bell"></i><span id="tchrannouncemnts">Notification (<?= $tchrntfctn_count ?>)</span></a></li>
							<li class="<?=$controllerName == "Teacherattendance" ? "active" : "" ?>" ><a href="<?=$baseurl?>teacherattendance"><i class="fa fa-address-card-o"></i><span>Attendance</span></a></li> 
                            <li class="<?=$controllerName == "TeacherCalendar" ? "active" : "" ?>" ><a href="<?=$baseurl?>teacherCalendar"><i class="fa fa-calendar"></i><span>Calendar</span></a></li> 
							<li class="<?=$controllerName == "ViewSchoolGallery" ? "active" : "" ?>"><a href="<?=$baseurl?>viewSchoolGallery"><i class="fa fa-image"></i>Events</a></li>
                            <li class="<?=$controllerName == "MeetingLink" ? "active" : "" ?>"><a href="<?=$baseurl?>MeetingLink"><i class="fa fa-link" aria-hidden="true"></i><span>Meeting Link</span></a></li>
                            <li class="<?=$controllerName == "TeacherLibrary" ? "active" : "" ?>"><a href="<?=$baseurl?>TeacherLibrary"><i class="fa fa-book" aria-hidden="true"></i><span>Library</span></a></li>

                        </ul>
                    </nav>
                </div>
            <?php 
			}
            ?>  
            </div>    

        </div>
    </div>
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header" style="margin-top:50px">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
			<?php 
			    if($mainpage=="Studentdashboard") { 
			        if($subpage == "Studentdashboard / Studentprofile Studentdashboard") { 
			        ?>  
    			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Student Dashboard</h2>
                	<?php
			        }
			        else { ?>
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Student Dashboard</h2>
			        <?php
			        }
			    }
			    else if($mainpage=="ViewKnowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> View Knowledge</h2>
			<?php }
			    else if($mainpage=="Studentsubjects") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Student Subjects</h2>
			<?php }
			    else if($mainpage=="ViewGallery") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Events</h2>
			<?php }
			    else if($mainpage=="ClassLibrary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Class Library</h2>
			<?php }
			    else if($mainpage=="SubjectLibrary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Library</h2>
			    <?php } 
			    else if($mainpage=="IdentityCard") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Identity Card</h2>
			    <?php }
			    else if($mainpage=="allGrades") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> All Grades</h2>
			    <?php }
			    else if($mainpage=="ExamListing") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Exams Listing</h2>
			    <?php }
			    else if($mainpage=="Subjectattendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Subject Attendance</h2>
			    <?php }
			    else if($mainpage=="Teachermarketplace") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> You-Me Market Place</h2>
			    <?php }
			    else if($mainpage=="Studentmarketplace") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> You-Me Market Place</h2>
			    <?php }
			    else if($mainpage=="StudentAttendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Attendance</h2>
			    <?php }
			    else if($mainpage=="TutoringCenter") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Tutoring Center</h2>
			    <?php }
			    else if($mainpage=="SubjectGrade") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Subject Grade</h2>
			    <?php }
			    else if($mainpage=="StudentDiscussion") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Student Discussion</h2>
			    <?php }
			    else if($mainpage=="TeacherSubject") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Class Subject Details</h2>
			    <?php }
			    else if($mainpage=="ClassGrade") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Class Grade</h2>
			    <?php }
			    else if($mainpage=="ClassAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Class Assignments</h2>
			    <?php }
			    else if($mainpage=="ClassAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Class Assignments</h2>
			    <?php }
			    else if($mainpage=="ClassExams") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Class Exams</h2>
			    <?php }
			    else if($mainpage=="TeacherPost") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Teacher Post</h2>
			    <?php }
			    else if($mainpage=="SchoolSubadmin") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Subadmin </h2>
			    <?php }
			    else if($mainpage=="ClassAttendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Attendance </h2>
			    <?php }
			     else if($mainpage=="TeacherLibrary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Library </h2>
			    <?php }
			    else if($mainpage=="TeacherNotifications") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Notifications </h2>
			    <?php }
			    else if($mainpage=="DetailAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Assignment Detail </h2>
			    <?php }
			    else if($mainpage=="ExamAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Exam/Assignment List </h2>
			    <?php }
			    else if($mainpage=="TeacherexamAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Exam/Assignment List </h2>
			    <?php }
			    else if($mainpage=="Teacherattendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Attendance </h2>
			    <?php }
			    else if($mainpage=="SchoolApproval") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> School Approval </h2>
			    <?php }
			    else if($mainpage=="SchoolCalendar") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Calendar </h2>
			    <?php }
			    else if($mainpage=="Schoolmarketplace") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> You-Me Market Place </h2>
			    <?php }
			    else if($mainpage=="TeacherCalendar") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Calendar </h2>
			    <?php }
			     else if($mainpage=="ViewSchoolGallery") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Gallery </h2>
			    <?php }
			    else if($mainpage=="SchoolNotification") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Notification </h2>
			    <?php }
			    else if($mainpage=="Feehead") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Fee Description </h2>
			    <?php }
			    else if($mainpage=="Assessments") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Assignments </h2>
			    <?php }
			    else if($mainpage=="StudentMessages") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Contact School </h2>
			    <?php }
			    else if($mainpage=="Schoolattendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Attendance </h2>
			    <?php }
			    else if($mainpage=="StudentSummary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Student Summary</h2>
			    <?php }
			    else if($mainpage=="AttendanceReport") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Attendance Report </h2>
			    <?php }
			    else if($mainpage=="SchoolTutorialfee") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> School Tutoring Fee</h2>
			    <?php }
			    else if($mainpage=="Schoolknowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Knowledge Center</h2>
			    <?php }
			     else if($mainpage=="Studentknowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Knowledge Center</h2>
			    <?php }
			     else if($mainpage=="Teacherknowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Knowledge Center</h2>
			    <?php }
			    else if($mainpage=="MeetingLink") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Meeting Link</h2>
			    <?php }
			    else if($mainpage=="SchoolLibrary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Library</h2>
			    <?php }
			    else if($mainpage=="Tutorialfee") { ?> 
			       <?php if($subpage == "Tutorialfee / Subjects Tutorialfee") { 
			        ?>  
    			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Tutoring Subjects</h2>
                	<?php
			        }
			        else { ?>
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Tutoring Fee</h2>
			        <?php
			        } ?>
			    <?php }
    			else { ?>
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?=$mainpage?></h2>
                    	<!--<ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html" class="iconsss"><i class="icon-home"></i></a></li>                            
                    	    <li class="breadcrumb-item active"><?=$sidebar_prefix.$subpage?></li>
                    	</ul>-->
    			<?php }
    			 ?>
			</div>     
                         
                </div>
            </div>
			

    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	           <div class="body">
	                <div class="row clearfix">
	                    
        <div class="container">
        <!--    <button onclick="myFunction()">Hangup</button> -->
          <!--  <div id='jitsi-meet-conf-container'></div>-->
         <?php  $meetingID = "meet123";
$name = "Aman";
$password = "mp";
$createTime = 1614335771742;

$isHTML5Client ="true";
$join_parameters = "meetingID=" .($meetingID)."&fullName=" .urlencode($name)."&joinViaHtml5=" .$isHTML5Client."&password=".$password."&createTime=".$createTime."&redirect=true";
//$join_url = "https://meeting.you-me-globaleducation.org/bigbluebutton/api?".$join_parameters."&checksum=".sha1("join".$join_parameters.env("aTGBy6CgNh5xqxvUOMDIsPNh671fkcLGnkq8qrfYrA"));
        
$secret = "aTGBy6CgNh5xqxvUOMDIsPNh671fkcLGnkq8qrfYrA";
$string = "joinmeetingID=123&password=mp&fullName=Chris".$secret;

$abc = "meetingID=meet123&password=mp&fullName=Chris";

$sh = sha1($string);

$join_url = "https://meeting.you-me-globaleducation.org/bigbluebutton/api/join?".$abc."&checksum=".$sh;
  ?>
        
        <a href="<?= $join_url ?> ">JOin</a>
        </div>
                        
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	 

 
</div>


 <!------------------ Add YouMe marketplace contact us --------------------->

    
<div class="modal classmodal animated zoomIn" id="marketplacecontact" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel" style="margin:12px 0px">Contact Us - </h6>
                <div class="col-md-1"><span id="logoproduct" style="margin:0px"></span></div>
                <div class="col-md-6"><h6 id="proddname" style="margin:12px 0px"></h6></div>
    		    <button type="button" class=" close" data-dismiss="modal">
      		        <span aria-hidden="true">&times;</span>
    		    </button>
    		    
    	    </div>
            <div class="modal-body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'marketprocontact'] , 'id' => "marketprocontactform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    
                </div>
                <div class="row clearfix">
                    <input type="hidden" name="prodcid" id="prodcid" >
                    
                    <div class="col-sm-6">
                        <label>Name *</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" required placeholder="Enter Name *">
                        </div>
                    </div>
                           
                    <div class="col-sm-6">
                        <label>Email*</label>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email"  required placeholder="Enter Email *">
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Contact*</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="number" required  placeholder="Enter Contact Number " >
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Quantity*</label>
                        <div class="form-group">
                            <input type="number" name="prodquantity" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <label>Message*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="Enter Description *" ></textarea>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="error" id="marketplcecontcterror">
                        </div>
                        <div class="success" id="marketplcecontctsuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="marketplcecontctbtn" class="btn btn-primary marketplcecontctbtn">Send</button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>  
 <!------------------ Add YouMe contact us --------------------->

    
<div class="modal classmodal animated zoomIn" id="youmecontactus" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel" style="margin:12px 0px">Contact Us - </h6>
                <div class="col-md-1"><span id="logouni" style="margin:0px"></span></div>
                <div class="col-md-6"><h6 id="univname" style="margin:12px 0px"></h6></div>
    		    <button type="button" class=" close" data-dismiss="modal">
      		        <span aria-hidden="true">&times;</span>
    		    </button>
    		    
    	    </div>
            <div class="modal-body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'youmeconatct'] , 'id' => "youmecontactusfom" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    
                </div>
                <div class="row clearfix">
                    <input type="hidden" name="univid" id="univid" >
                    
                    <div class="col-sm-6">
                        <label>Name *</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" required placeholder="Enter Name *">
                        </div>
                    </div>
                           
                    <div class="col-sm-6">
                        <label>Email*</label>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email"  required placeholder="Enter Email *">
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Contact*</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="number" required  placeholder="Enter Contact Number " >
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Budget*</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="budget" required  placeholder="Enter Budget " >
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Academic Year*</label>
                        <div class="form-group">
                            <select name="academic_year" class="form-control attstudent" required>
                                <option value="">Select Year</option>
                                <option value="2021">2021</option>
                                <option value="2021">2022</option>
                                <option value="2021">2023</option>
                                <option value="2021">2024</option>
                                <option value="2021">2025</option>
                                <option value="2021">2026</option>
                                <option value="2021">2027</option>
                                <option value="2021">2028</option>
                                <option value="2021">2029</option>
                                <option value="2021">2030</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <label>Message*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="Enter Description *" ></textarea>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="error" id="youmecontcterror">
                        </div>
                        <div class="success" id="youmecontctsuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="youmecontactbtn" class="btn btn-primary youmecontactbtn">Send</button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>  


<!------------------ Add YouMe contact us --------------------->

    
<div class="modal classmodal animated zoomIn" id="localyoumecontactus" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel" style="margin:12px 0px">Contact Us - </h6>
                <div class="col-md-1"><span id="llogouni" style="margin:0px"></span></div>
                <div class="col-md-6"><h6 id="lunivname" style="margin:12px 0px"></h6></div>
    		    <button type="button" class=" close" data-dismiss="modal">
      		        <span aria-hidden="true">&times;</span>
    		    </button>
    		    
    	    </div>
            <div class="modal-body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'youmeconatctlocal'] , 'id' => "localyoumecontactusfom" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    
                </div>
                <div class="row clearfix">
                    <input type="hidden" name="univid" id="univid" >
                    
                    <div class="col-sm-6">
                        <label>Name *</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" required placeholder="Enter Name *">
                        </div>
                    </div>
                           
                    <div class="col-sm-6">
                        <label>Email*</label>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email"  required placeholder="Enter Email *">
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Contact*</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="number" required  placeholder="Enter Contact Number " >
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Budget*</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="budget" required  placeholder="Enter Budget " >
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Academic Year*</label>
                        <div class="form-group">
                            <select name="academic_year" class="form-control attstudent" required>
                                <option value="">Select Year</option>
                                <option value="2021">2021</option>
                                <option value="2021">2022</option>
                                <option value="2021">2023</option>
                                <option value="2021">2024</option>
                                <option value="2021">2025</option>
                                <option value="2021">2026</option>
                                <option value="2021">2027</option>
                                <option value="2021">2028</option>
                                <option value="2021">2029</option>
                                <option value="2021">2030</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <label>Message*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="Enter Description *" ></textarea>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="error" id="youmecontcterror">
                        </div>
                        <div class="success" id="youmecontctsuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="youmecontactbtn" class="btn btn-primary youmecontactbtn">Send</button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>  


 <!------------------ Tutoring Login --------------------->

    
<div class="modal classmodal animated zoomIn" id="tutoringlogin" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Tutoring Center Login</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'tutoringCenter'] , 'id' => "tutoringloginform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <div class="form-group">                                    
                            <input type="text" class="form-control" required name="tutoremail" id="tutoremail" placeholder="Username*">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">                                    
                            <input type="password" class="form-control" required name="tutorpassword" id="tutorpassword" placeholder="Password*">
                        </div>
                    </div>
				    <input type="hidden" class="form-control"  name="tutorid" id="tutorid">
                    <div class="col-md-12">
                        <div class="error" id="tutoringloginerror"></div>
                        <div class="success" id="tutoringloginsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary tutoringloginbtn" id="tutoringloginbtn">Login</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              


<!-- Default Size -->
<div class="modal fade" id="view_info" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="title" id="defaultModalLabel">User Details</h6>
            </div>
            <div class="modal-body">
                <div class="body top_counter">
                    <div class="icon">
                        <img src="<?=$baseurl?>img/avatar2.jpg" class="rounded-circle" alt="">
                    </div>
                    <div class="content m-t-5">
                        <div>Team Leader</div>
                        <h6>Aiden Chavez</h6>
                    </div>
                </div>
                <hr>                
                <small class="text-muted">Address: </small>
                <p>795 Folsom Ave, Suite 600 San Francisco, 94107</p>
                <small class="text-muted">Email address: </small>
                <p>michael@gmail.com</p>
                <small class="text-muted">Mobile: </small>
                <p>+ 202-555-2828</p>
                <small class="text-muted">Birth Date: </small>
                <p class="m-b-0">October 22th, 1990</p>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="<?=$baseurl?>js/libscripts.bundle.js"></script>
<script src="<?=$baseurl?>js/index.js"></script>
<script src="<?=$baseurl?>js/vendorscripts.bundle.js"></script>
<script src="<?=$baseurl?>js/mainscripts.bundle.js"></script>
<script src="<?=$baseurl?>js/jvectormap.bundle.js"></script>  
<script src="<?=$baseurl?>js/morrisscripts.bundle.js"></script> 
<script src="<?=$baseurl?>js/knob.bundle.js"></script>  
<script src="<?=$baseurl?>js/bootstrap-datepicker.min.js"></script> 
<script src="<?=$baseurl?>js/bootstrap-multiselect.js"></script>
<script src="<?=$baseurl?>js/dropify.min.js"></script>
<script src="<?=$baseurl?>js/dropify.js"></script>
<script src="<?=$baseurl?>js/sweetalert.min.js"></script>  
<script src="<?=$baseurl?>js/dialogs.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>
<script src="<?=$baseurl?>js/datatablescripts.bundle.js"></script>
<script src="<?=$baseurl?>js/jquery-datatable.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="<?=$baseurl?>js/mdtimepicker.js" type="text/javascript"></script>
<link href="<?=$baseurl?>css/mdtimepicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?=$baseurl?>js/image-uploader.min.js"></script>
<script src="<?=$baseurl?>js/datepickk.min.js"></script>
<script src="<?=$baseurl?>js/fullcalendar.min.js"></script>
<script src="https://www.beautybossnetwork.com/membership/crop/js/slim.kickstart.min.js"></script>
<script src="<?=$baseurl?>js/custom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script src="<?=$baseurl?>js/jquery.easyPaginate.js"></script>
</body>
</html>
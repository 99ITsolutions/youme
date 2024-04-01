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
    elseif(!empty($parent_details[0])){
        $spec_cond = md5($parent_details[0]['id']) ;
    }
    $left_sidebar = "style = 'padding-top:115px !important '";
    
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '33') { $labl33 = $langlbl['title'] ; }
        if($langlbl['id'] == '2070') { $labl2070 = $langlbl['title'] ; }
        if($langlbl['id'] == '2076') { $labl2076 = $langlbl['title'] ; }
        if($langlbl['id'] == '2084') { $labl2084 = $langlbl['title'] ; }
        if($langlbl['id'] == '2085') { $labl2085 = $langlbl['title'] ; }
        if($langlbl['id'] == '2129') { $labl2129 = $langlbl['title'] ; }
        if($langlbl['id'] == '499') { $labl499 = $langlbl['title'] ; }
        if($langlbl['id'] == '529') { $labl529 = $langlbl['title'] ; } 
        if($langlbl['id'] == '588') { $labl588 = $langlbl['title'] ; } 
        if($langlbl['id'] == '593') { $labl593 = $langlbl['title'] ; } 
        if($langlbl['id'] == '98') { $labl98 = $langlbl['title'] ; }
        if($langlbl['id'] == '1824') { $labl1824 = $langlbl['title'] ; }
        if($langlbl['id'] == '188') { $labl188 = $langlbl['title'] ; } 
        if($langlbl['id'] == '234') { $labl234 = $langlbl['title'] ; } 
        if($langlbl['id'] == '14') { $labl14 = $langlbl['title'] ; }
        if($langlbl['id'] == '22') { $labl22 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1216') { $labl1216 = $langlbl['title'] ; }
        if($langlbl['id'] == '20') { $labl20 = $langlbl['title'] ; }
        if($langlbl['id'] == '11') { $labl11 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1518') { $labl1518 = $langlbl['title'] ; } 
        if($langlbl['id'] == '32') { $labl32 = $langlbl['title'] ; }
        if($langlbl['id'] == '35') { $labl35 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1519') { $labl1519 = $langlbl['title'] ; } 
        if($langlbl['id'] == '16') { $labl16 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1184') { $labl1184 = $langlbl['title'] ; } 
        if($langlbl['id'] == '997') { $labl997 = $langlbl['title'] ; }
        if($langlbl['id'] == '1026') { $labl1026 = $langlbl['title'] ; }
        if($langlbl['id'] == '2044') { $labl2044 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1210') { $labl1210 = $langlbl['title'] ; } 
        if($langlbl['id'] == '563') { $labl563 = $langlbl['title'] ; } 
        if($langlbl['id'] == '523') { $labl523 = $langlbl['title'] ; }
        if($langlbl['id'] == '578') { $labl578 = $langlbl['title'] ; }
        if($langlbl['id'] == '595') { $lbl595 = $langlbl['title'] ; }
        if($langlbl['id'] == '1353') { $labl1353 = $langlbl['title'] ; }
        if($langlbl['id'] == '1223') { $labl1223 = $langlbl['title'] ; }
        if($langlbl['id'] == '934') { $labl934 = $langlbl['title'] ; }
	    if($langlbl['id'] == '19') { $labl19 = $langlbl['title'] ; }
		if($langlbl['id'] == '2064') { $labl2064 = $langlbl['title'] ; } 
		if($langlbl['id'] == '2036') { $labl2036 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1920') { $labl1920 = $langlbl['title'] ; }
		if($langlbl['id'] == '235') { $labl235 = $langlbl['title'] ; }
		if($langlbl['id'] == '21') { $labl21 = $langlbl['title'] ; }
		if($langlbl['id'] == '1581') { $labl1581 = $langlbl['title'] ; } 
		if($langlbl['id'] == '15') { $labl15 = $langlbl['title'] ; }
		if($langlbl['id'] == '12') { $labl12 = $langlbl['title'] ; }
		if($langlbl['id'] == '13') { $labl13 = $langlbl['title'] ; }
		if($langlbl['id'] == '8') { $labl8 = $langlbl['title'] ; }
		if($langlbl['id'] == '1495') { $labl1495 = $langlbl['title'] ; }
		if($langlbl['id'] == '1417') { $labl1417 = $langlbl['title'] ; }
		if($langlbl['id'] == '1213') { $labl1213 = $langlbl['title'] ; }
		if($langlbl['id'] == '1758') { $labl1758 = $langlbl['title'] ; }
		if($langlbl['id'] == '1858') { $labl1858 = $langlbl['title'] ; }
		if($langlbl['id'] == '1728') { $labl1728 = $langlbl['title'] ; }
		if($langlbl['id'] == '1070') { $labl1070 = $langlbl['title'] ; } 
		if($langlbl['id'] == '317') { $labl317 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1723') { $labl1723 = $langlbl['title'] ; }
		if($langlbl['id'] == '1379') { $labl1379 = $langlbl['title'] ; }
		if($langlbl['id'] == '1861') { $labl1861 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1862') { $labl1862 = $langlbl['title'] ; }
		if($langlbl['id'] == '1516') { $labl1516 = $langlbl['title'] ; }
		if($langlbl['id'] == '34') { $labl34 = $langlbl['title'] ; }
		if($langlbl['id'] == '1793') { $labl1793 = $langlbl['title'] ; }
		if($langlbl['id'] == '1727') { $labl1727 = $langlbl['title'] ; }
		if($langlbl['id'] == '1854') { $labl1854 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1855') { $labl1855 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1856') { $labl1856 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1859') { $labl1859 = $langlbl['title'] ; }
		if($langlbl['id'] == '1860') { $labl1860 = $langlbl['title'] ; }
		if($langlbl['id'] == '1925') { $labl1925 = $langlbl['title'] ; }
		if($langlbl['id'] == '1221') { $labl1221 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1771') { $labl1771 = $langlbl['title'] ; } 
        if($langlbl['id'] == '1732') { $labl1732 = $langlbl['title'] ; }
        if($langlbl['id'] == '1953') { $labl1953 = $langlbl['title'] ; }
        if($langlbl['id'] == '2014') { $labl2014 = $langlbl['title'] ; }
        if($langlbl['id'] == '507') { $labl507 = $langlbl['title'] ; }
        if($langlbl['id'] == '508') { $labl508 = $langlbl['title'] ; }
        if($langlbl['id'] == '509') { $labl509 = $langlbl['title'] ; }
        if($langlbl['id'] == '24') { $labl24 = $langlbl['title'] ; }
        if($langlbl['id'] == '1212') { $labl1212 = $langlbl['title'] ; }
        if($langlbl['id'] == '1215') { $labl1215 = $langlbl['title'] ; }
        if($langlbl['id'] == '1217') { $labl1217 = $langlbl['title'] ; }
        if($langlbl['id'] == '1218') { $labl1218 = $langlbl['title'] ; }
        if($langlbl['id'] == '1219') { $labl1219 = $langlbl['title'] ; }
        if($langlbl['id'] == '1220') { $labl1220 = $langlbl['title'] ; }
        if($langlbl['id'] == '1222') { $labl1222 = $langlbl['title'] ; }
        if($langlbl['id'] == '1815') { $labl1815 = $langlbl['title'] ; }
        if($langlbl['id'] == '1224') { $labl1224 = $langlbl['title'] ; }
        if($langlbl['id'] == '942') { $labl942 = $langlbl['title'] ; }
        if($langlbl['id'] == '1725') { $labl1725 = $langlbl['title'] ; }
        if($langlbl['id'] == '1311') { $labl1311 = $langlbl['title'] ; }
		if($langlbl['id'] == '1231') { $labl1231 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1877') { $labl1877 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1857') { $labl1857 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1377') { $labl1377 = $langlbl['title'] ; } 
		if($langlbl['id'] == '1541') { $labl1541 = $langlbl['title'] ; } 
		if($langlbl['id'] == '927') { $labl927 = $langlbl['title'] ; }
		if($langlbl['id'] == '546') { $labl546 = $langlbl['title'] ; }
		if($langlbl['id'] == '930') { $labl930 = $langlbl['title'] ; }
		if($langlbl['id'] == '931') { $labl931 = $langlbl['title'] ; }
		if($langlbl['id'] == '936') { $labl936 = $langlbl['title'] ; }
		if($langlbl['id'] == '933') { $labl933 = $langlbl['title'] ; }
		if($langlbl['id'] == '2121') { $labl2121 = $langlbl['title'] ; }
		if($langlbl['id'] == '937') { $labl937 = $langlbl['title'] ; }
		
		if($langlbl['id'] == '2134') { $labl2134 = $langlbl['title'] ; }
		if($langlbl['id'] == '2135') { $labl2135 = $langlbl['title'] ; }
		if($langlbl['id'] == '2136') { $labl2136 = $langlbl['title'] ; }
		if($langlbl['id'] == '2137') { $labl2137 = $langlbl['title'] ; }
		if($langlbl['id'] == '2138') { $labl2138 = $langlbl['title'] ; }
		
		if($langlbl['id'] == '2140') { $labl2140 = $langlbl['title'] ; }
		if($langlbl['id'] == '2143') { $labl2143 = $langlbl['title'] ; }
		if($langlbl['id'] == '2144') { $labl2144 = $langlbl['title'] ; }
        if($langlbl['id'] == '2147') { $labl2147 = $langlbl['title'] ; }
        if($langlbl['id'] == '2148') { $labl2148 = $langlbl['title'] ; }
        if($langlbl['id'] == '2153') { $labl2153 = $langlbl['title'] ; }
        if($langlbl['id'] == '2158') { $labl2158 = $langlbl['title'] ; }
        if($langlbl['id'] == '2251') { $labl2251 = $langlbl['title'] ; }
        if($langlbl['id'] == '2351') { $labl2351 = $langlbl['title'] ; }
        if($langlbl['id'] == '2252') { $labl2252 = $langlbl['title'] ; }
    } 
?>

  <div id="left-sidebar" class="sidebar">
        <div class="sidebar-scroll">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" style="display:none">
            <?php
            
            if(!empty($company_details[0]))
            {?>
                <li class="nav-item"><a class="nav-link <?=(in_array($controllerName , ['SchoolReportcard','Schooldashboard', 'Schoolownrole' ,'Students' , 'Employee' , 'Classes' , 'Fees', 'Feehead','Feesetup','Feedetail' , 'Routes' , 'Vehicles' , 'Head' , 'Subhead' , 'Balance' , 'Transfer' , 'Discount' , 'Holiday' , 'Income', 'IncomeHead' , 'Stopage', 'Department' , 'Defaulter', 'Promotion', 'Enquiry', 'Attendence', 'Setting', 'Template', 'Sms', 'Registeration', 'Records' , 'Expensesvoucher' , 'Identity', 'Feecollection', 'FeeGeneration']) ?  "active" : ""  )?>" data-toggle="tab" href="#project_menu">Menu</a></li>                
             <?php    
            }
			elseif(!empty($emp_details[0]))
            {?>
                <li class="nav-item"><a class="nav-link <?=(in_array($controllerName , ['SchoolReportcard','Schooldashboard', 'Employee' , 'Classes' , 'Fees', 'Feehead','Feesetup','Feedetail' , 'Routes' , 'Vehicles' , 'Head' , 'Subhead' , 'Balance' , 'Transfer' , 'Discount' , 'Holiday' , 'Income', 'IncomeHead' , 'Stopage', 'Department' , 'Defaulter', 'Promotion', 'Enquiry', 'Attendence', 'Setting', 'Template', 'Sms', 'Registeration', 'Records' , 'Expensesvoucher', 'Feecollection', 'FeeGeneration']) ?  "active" : ""  )?>" data-toggle="tab" href="#project_menu">Menu</a></li>                
             <?php    
            }
            elseif(!empty($student_details[0]))
            {?>
                <li class="nav-item"><a class="nav-link <?=(in_array($controllerName , ['SchoolReportcard','Schooldashboard','Payment']) ?  "active" : ""  )?>" data-toggle="tab" href="#project_menu">Menu</a></li>                
             <?php    
            }	
            ?>
            </ul>
                
            <!-- Tab panes -->
            <div class="tab-content p-l-0 p-r-0">
            <?php 
            if(!empty($company_details[0]))
            { 
                $scl_pri = explode(",", $company_details[0]['scl_privilages']);
            ?> 
            <!-- Tab panes -->
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['SchoolReportcard','SchoolMeet','Schooldashboard', 'Reporteditrequest', 'Qrcodepasscode', 'Datarequest', 'Canteenreport', 'Schoolteacherdairy', 'Printable', 'ReportcardReport', 'Studentdairyreport', 'Feediscount', 'Codeconduct', 'TeacherCases', 'Contactyoume', 'ParentCases', 'SchoolLibraryAccessReport', 'Loginhistoryreport', 'Reportcards', 'SchoolkinderApplication', 'ClassesSubjects', 'Kindergarten', 'MeetingReport', 'Timetable', 'Schoolknowledge', 'Admissions', 'Readmissions', 'Schoolmarketplace', 'IdentityCard', 'SchoolSubadmin', 'SchoolLibrary', 'Schoolattendance', 'StudentSummary', 'AttendanceReport', 'SchoolTutorialfee','SchoolCalendar', 'SchoolNotification', 'Message', 'Schools', 'SchoolApproval', 'ExamAssessment', 'Gallery', 'Calendar', 'ClassSubjects', 'Students' ,  'Teachers' ,'Fees' , 'Knowledge', 'Subjects' , 'Classes' , 'Fees', 'Feehead','Feesetup','Feedetail' , 'Routes' , 'Vehicles' , 'Head' , 'Subhead' , 'Balance' , 'Transfer' , 'Discount' , 'Holiday' , 'Income' , 'Stopage', 'Department' , 'Defaulter', 'Promotion', 'Enquiry', 'Attendence', 'Setting', 'FeeGeneration','Template','Sms', 'Registeration', 'IncomeHead', 'Records' , 'Expensesvoucher' , 'Identity', 'Feecollection', 'FeeGeneration'] )  ? "active" : ""  )?>" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu"  style="padding-bottom:45px !important;">  
                        
	                        <li class="<?=$controllerName == "Schooldashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>schooldashboard"><i class="icon-speedometer"></i><?= $labl588 ?></a></li>
	                        <li class="<?=$controllerName == "Schoolknowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolknowledge"><i class="fa fa-info"></i><?= $labl593 ?></a></li>
	                        <li class="<?=$controllerName == "SchoolMeet" ? "active" : "" ?>"><a href="<?= $baseurl ?>SchoolMeet"><i class="fa fa-video-camera"></i><span><?= $lbl595 ?></span></a></li>
	                        
	                        <li><a href="http://learn.eltngl.com/" target="_blank"><i class="fa fa-globe"></i>National Geographic</a></li>
	                        
	                        <li class="<?=(in_array($controllerName , ['Admissions', 'Readmissions']) ? "active" : ""  )?>" >
                                <a href="#Admissions" class="has-arrow"><i class="fa fa-university"></i><span><?= $labl98 ?></span></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['Admissions']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Admissions"><?= $labl188 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Readmissions']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>readmissions"><?= $labl234 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Printable']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Printable"><?= $labl2153 ?></a></li>
                                </ul>
                            </li> 
                            
		                    <li class="<?=$controllerName == "Schoolattendance" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolattendance"><i class="fa fa-calendar"></i><?= $labl1216 ?></a></li>
		                    <li class="<?=$controllerName == "SchoolCalendar" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolCalendar"><i class="fa fa-calendar"></i><?= $labl20 ?></a></li>
		                    <li class="<?=(in_array($controllerName , ['Canteenreport']) && $actionName == "schoolvendorreport" ? "active" : ""  )?>"><a href="<?=$baseurl?>Canteenreport/schoolvendorreport"><i class="fa fa-file"></i><?= $labl2251 ?></a></li>
		                    
		                    <li class="<?=$controllerName == "Codeconduct" ? "active" : "" ?>"><a href="<?=$baseurl?>Codeconduct"><i class="fa fa-file"></i><?= $labl2143 ?></a></li>
		                    <!--<li class="<?=$controllerName == "Datarequest" ? "active" : "" ?>"><a href="<?=$baseurl?>Datarequest"><i class="fa fa-database"></i>Archive Data Request</a></li>-->
		                    <?php if($countmsg != 0)
		                    { ?>
		                    <li class="<?=$controllerName == "Message" ? "active" : "" ?>"><a href="<?=$baseurl?>message"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope"></i><?= $labl2084 ?> (<?= $countmsg ?>)</a></li>
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "Message" ? "active" : "" ?>"><a href="<?=$baseurl?>message"><i class="fa fa-envelope"></i><?= $labl2084 ?> (<?= $countmsg ?>)</a></li>
		                    
		                    <?php } ?>
		                    
		                    <?php if($counttmsg != 0)
		                    { ?>
		                    <li class="<?=$controllerName == "TeacherCases" ? "active" : "" ?>"><a href="<?=$baseurl?>TeacherCases"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope"></i><?= $labl2070 ?> (<?= $counttmsg ?>)</a></li>
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "TeacherCases" ? "active" : "" ?>"><a href="<?=$baseurl?>TeacherCases"><i class="fa fa-envelope"></i><?= $labl2070 ?> (<?= $counttmsg ?>)</a></li>
		                    
		                    <?php } ?>
		                    
		                    <?php if($countpmsg != 0)
		                    { ?>
		                    <li class="<?=$controllerName == "ParentCases" ? "active" : "" ?>"><a href="<?=$baseurl?>ParentCases"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope"></i><?= $labl2085 ?> (<?= $countpmsg ?>)</a></li>
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "ParentCases" ? "active" : "" ?>"><a href="<?=$baseurl?>ParentCases"><i class="fa fa-envelope"></i><?= $labl2085 ?> (<?= $countpmsg ?>)</a></li>
		                    
		                    <?php } ?>
		                    
		                    <?php if($countmsgyoume != 0)
		                    { ?>
		                    <li class="<?=$controllerName == "Contactyoume" ? "active" : "" ?>"><a href="<?=$baseurl?>Contactyoume"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope"></i>Contact You-Me (<?= $countmsgyoume ?>)</a></li>
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "Contactyoume" ? "active" : "" ?>"><a href="<?=$baseurl?>Contactyoume"><i class="fa fa-envelope"></i>Contact You-Me (<?= $countmsgyoume ?>)</a></li>
		                    
		                    <?php } ?>
		                    
		                    <li class="<?=$controllerName == "ClassesSubjects" ? "active" : "" ?>"><a href="<?=$baseurl?>ClassesSubjects"><i class="fa fa-graduation-cap"></i><?= $labl11 ?></a></li>
		                    <?php if(in_array("Senior", $scl_pri)) { ?>
		                    <li class="<?=$controllerName == "ExamAssessment" ? "active" : "" ?>"><a href="<?=$baseurl?>examAssessment"><i class="fa fa-book"></i><?= $labl2076 ?></a></li>
		                    <?php } ?>
		                    <li class="<?=$controllerName == "Fees" ? "active" : "" ?>"><a href="<?=$baseurl?>fees"><i class="fa fa-money "></i><?= $labl16 ?></a></li>
		                    <li class="<?=$controllerName == "Gallery" ? "active" : "" ?>"><a href="<?=$baseurl?>gallery"><i class="fa fa-image"></i><?= $labl1184 ?></a></li>
		                    <li class="<?=$controllerName == "IdentityCard" ? "active" : "" ?>"><a href="<?=$baseurl?>IdentityCard"><i class="fa fa-id-card-o"></i><?= $labl32 ?></a></li>
		                    <?php if(in_array("KinderGarten", $scl_pri)) { ?>
		                    <li class="<?=$controllerName == "Kindergarten" ? "active" : "" ?>"><a href="<?=$baseurl?>Kindergarten"><i class="icon-speedometer"></i><?= $labl1519  ?></a></li>
		                    <?php } ?>
		                    <li class="<?=$controllerName == "Knowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>knowledge"><i class="fa fa-info"></i><span class="notranslate"><?= $labl35 ?></span></a></li>
		                    
		                    <li class="<?=$controllerName == "SchoolLibrary" ? "active" : "" ?>"><a href="<?=$baseurl?>SchoolLibrary"><i class="fa fa-book"></i><?= $labl1210 ?></a></li>
		                    
		                    <li class="<?=(in_array($controllerName , ['loginhistoryreport']) ? "active" : ""  )?>" >
                                <a href="#Loginhistoryreport" class="has-arrow"><i class="fa fa-history"></i><span><?= $labl2135 ?></span></a>
                                <ul><li class="<?=(in_array($controllerName , ['Loginhistoryreport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Loginhistoryreport/teacherreport"><?= $labl2136 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['Loginhistoryreport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Loginhistoryreport/studentreport"><?= $labl2137 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['Loginhistoryreport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Loginhistoryreport/subadminreport"><?= $labl2138 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['Loginhistoryreport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Loginhistoryreport/parentreport"><?= $labl2140 ?></a></li></ul>
                            </li> 
		                    <?php if($schoolnotfycount != 0) { ?>
		                    <li class="<?=$controllerName == "SchoolNotification" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolNotification"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-bell"></i><span id="sclannouncemnts"><?= $labl21 ?> (<?= $schoolnotfycount ?>)</span></a></li>
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "SchoolNotification" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolNotification"><i class="fa fa-bell"></i><span id="sclannouncemnts"><?= $labl21 ?> (<?= $schoolnotfycount ?>)</span></a></li>
		                    <?php } ?>
		                    <li class="<?=$controllerName == "Reporteditrequest" ? "active" : "" ?>"><a href="<?=$baseurl?>Reporteditrequest"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Recorder Edit Request </a></li>
		                    
		                    <li class="<?=$controllerName == "Qrcodepasscode" ? "active" : "" ?>"><a href="<?=$baseurl?>Qrcodepasscode"><i class="fa fa-key"></i>Qr Passcode</a></li>
		                    
		                    <?php if(in_array("Senior", $scl_pri)) { ?> <li class="<?=(in_array($controllerName , ['AttendanceReport' , 'StudentSummary' , 'MeetingReport', 'ReportcardReport', 'SchoolLibraryAccessReport']) ? "active" : ""  )?>" >
                                <a href="#AttendanceReport" class="has-arrow"><i class="fa fa-file"></i><span><?= $labl34 ?></span></a>
                                <ul><li class="<?=(in_array($controllerName , ['AttendanceReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>AttendanceReport"><?= $labl563 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['StudentSummary']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>StudentSummary"><?= $labl578 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['SchoolLibraryAccessReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolLibraryAccessReport"><?= $labl1581 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['MeetingReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>MeetingReport"><?= $labl1417 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['ReportcardReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>ReportcardReport"><?= $labl2158 ?></a></li></ul>
                            </li> <?php } ?>
                            <li class="<?=$controllerName == "Reportcards" ? "active" : "" ?>"><a href="<?=$baseurl?>Reportcards"><i class="fa fa-file"></i><?= $labl1793 ?></a></li>
		                   
                            
	                        <li class="<?=$controllerName == "Timetable" ? "active" : "" ?>"><a href="<?=$baseurl?>Timetable"><i class="fa fa-calendar"></i><?= $labl1495 ?></a></li>
		                    
		                    <?php if(in_array("KinderGarten", $scl_pri)) { ?>
		                    <li class="<?=$controllerName == "SchoolkinderApplication" ? "active" : "" ?>"><a href="<?=$baseurl?>SchoolkinderApplication"><i class="fa fa-tasks"></i><?= $labl1824 ?></a></li>
		                    <?php } ?>
		                    
		                    
		                    <li class="<?=$controllerName == "Students" ? "active" : "" ?>"><a href="<?=$baseurl?>students"><i class="fa fa-graduation-cap"></i><?= $labl12 ?> </a></li>
	                        <li class="<?=$controllerName == "Studentdairyreport" ? "active" : "" ?>"><a href="<?=$baseurl?>Studentdairyreport"><i class="fa fa-file"></i><?= $labl2147 ?> </a></li>
	                        
	                        <li class="<?=$controllerName == "SchoolSubadmin" ? "active" : "" ?>"><a href="<?=$baseurl?>SchoolSubadmin"><i class="fa fa-users"></i><?= $labl8 ?></a></li>
		                    
	                        
	                        <!--<li class="<?=$controllerName == 'Classes' ? "active" : ""  ?>"><a   href="<?=$baseurl?>classes"><i class="fa fa-graduation-cap"></i>Classes</a></li>
	                        <li class="<?=$controllerName == 'Subjects' ? "active" : ""  ?>"><a   href="<?=$baseurl?>subjects"><i class="fa fa-book"></i>Subjects</a></li>
	                        <li class="<?=$controllerName == 'ClassSubjects' ? "active" : ""  ?>"><a   href="<?=$baseurl?>classSubjects"><i class="fa fa-book"></i>Class Subjects</a></li>-->
	                        
	                        <li class="<?=$controllerName == "Teachers" ? "active" : "" ?>"><a href="<?=$baseurl?>teachers"><i class="icon-users"></i><?= $labl15 ?></a></li>
		                    <li class="<?=$controllerName == "Schoolteacherdairy" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolteacherdairy"><i class="icon-users"></i><?= $labl2148 ?></a></li>
		                    
		                    <?php if(in_array("Senior", $scl_pri)) { ?><li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) ? "active" : ""  )?>" >
                                <a href="#TutorialFee" class="has-arrow"><i class="fa fa-file"></i><span><?= $labl1221 ?></span></a>
                                <ul>
                                     <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee"><?= $labl997 ?></a></li>
                                     <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "add" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/add"><?= $labl507 ?></a></li>
                                     <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "students" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/students"><?= $labl508 ?></a></li>
                                     <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "students" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/content"><?= $labl509 ?></a></li>
                                </ul>
                            </li> <?php } ?>
                            
                            <li class="<?=$controllerName == "Schoolmarketplace" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolmarketplace"><i class="fa fa-shopping-bag"></i><span class="notranslate"><?= $labl1213 ?></span></a></li>
		                   
		               
                        </ul>
                    </nav>
                </div>
            <?php }
            elseif(!empty($sclsub_details[0]))
            { 
                $privilages = explode(",", $sclsub_details[0]['privilages']); 
                $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                 ?> 
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['SchoolMeet','Subadmindashboard', 'ReportcardReport', 'Codeconduct', 'Printable', 'Schoolteacherdairy', 'Studentdairyreport', 'Feediscount', 'ParentCases', 'TeacherCases', 'Contactyoume', 'SchoolkinderApplication', 'SchoolLibraryAccessReport', 'ClassesSubjects', 'MeetingReport', 'Admissions', 'Readmissions', 'IdentityCard', 'Schoolknowledge', 'Schoolmarketplace', 'SchoolSubadmin', 'SchoolLibrary', 'Schoolattendance', 'StudentSummary', 'AttendanceReport', 'SchoolTutorialfee','SchoolCalendar', 'SchoolNotification', 'Message', 'Schools', 'SchoolApproval', 'ExamAssessment', 'Gallery', 'Calendar', 'ClassSubjects', 'Students' ,  'Teachers' ,'Fees' , 'Knowledge', 'Subjects' , 'Classes' , 'Fees', 'Feehead','Feesetup','Feedetail' , 'Routes' , 'Vehicles' , 'Head' , 'Subhead' , 'Balance' , 'Transfer' , 'Discount' , 'Holiday' , 'Income' , 'Stopage', 'Department' , 'Defaulter', 'Promotion', 'Enquiry', 'Attendence', 'Setting', 'FeeGeneration','Template','Sms', 'Registeration', 'IncomeHead', 'Records' , 'Expensesvoucher' , 'Identity', 'Feecollection', 'FeeGeneration'] )  ? "active" : ""  )?>" id="project_menu" style="display:block !important">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu"  style="padding-bottom:45px !important;">                            
	                        <li class="<?=$controllerName == "Subadmindashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>Subadmindashboard"><i class="icon-speedometer"></i><span class="notranslate"><?= $labl588 ?></span></a></li>
	                        <li class="<?=$controllerName == "Schoolknowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolknowledge"><i class="fa fa-info"></i><span class="notranslate"><?= $labl593 ?></span></a></li>
	                        <li class="<?=$controllerName == "SchoolMeet" ? "active" : "" ?>"><a href="<?= $baseurl ?>SchoolMeet"><i class="fa fa-video-camera"></i><span><?= $lbl595 ?></span></a></li>
		                    <li><a href="http://learn.eltngl.com/" target="_blank"><i class="fa fa-globe"></i>National Geographic</a></li>
		                    <?php  if(in_array("18", $privilages)) {?>
		                    <li class="<?=(in_array($controllerName , ['Admissions', 'Readmissions']) ? "active" : ""  )?>" >
                                <a href="#Admissions" class="has-arrow"><i class="fa fa-university"></i><span><?= $labl13 ?></span></a>
                                <ul>
                                    <?php if(in_array("84", $roles)) {?>
                                        <li class="<?=(in_array($controllerName , ['Admissions']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Admissions"><?= $labl188 ?></a></li>
                                    <?php } if(in_array("85", $roles)) {?>
                                        <li class="<?=(in_array($controllerName , ['Readmissions']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>readmissions"><?= $labl234 ?></a></li>
                                    <?php } if(in_array("84", $roles) || in_array("85", $roles)) {?>
                                    <li class="<?=(in_array($controllerName , ['Printable']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Printable"><?= $labl2153 ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li> 
		                    <?php   } if(in_array("6", $privilages)) {?>
		                        <li class="<?=$controllerName == "Schoolattendance" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolattendance"><i class="fa fa-calendar"></i><?= $labl14 ?></a></li>
		                    <?php } 
		                    if(in_array("11", $privilages)) {?>
		                        <li class="<?=$controllerName == "SchoolCalendar" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolCalendar"><i class="fa fa-calendar"></i><?= $labl20 ?></a></li>
		                    <?php } if(in_array("33", $privilages)) { ?>
		                        <li class="<?=(in_array($controllerName , ['Canteenreport']) && $actionName == "schoolvendorreport" ? "active" : ""  )?>"><a href="<?=$baseurl?>Canteenreport/schoolvendorreport"><i class="fa fa-file"></i><?= $labl2251 ?></a></li>
		                    <?php }
		                    if(in_array("31", $privilages)) { ?>
		                    <li class="<?=$controllerName == "Codeconduct" ? "active" : "" ?>"><a href="<?=$baseurl?>Codeconduct"><i class="fa fa-file"></i><?= $labl2143 ?></a></li>
		                   
		                    <?php } if(in_array("13", $privilages)) { ?>
		                        
		                        <?php if($countmsg != 0)
		                    { ?>
		                    <li class="<?=$controllerName == "Message" ? "active" : "" ?>"><a href="<?=$baseurl?>message"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope"></i><?= $labl2084 ?> (<?= $countmsg ?>)</a></li>
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "Message" ? "active" : "" ?>"><a href="<?=$baseurl?>message"><i class="fa fa-envelope"></i><?= $labl2084 ?> (<?= $countmsg ?>)</a></li>
		                    
		                    <?php }
		                    }
		                    if(in_array("27", $privilages)) { 
		                    if($counttmsg != 0)
		                    { ?>
		                    <li class="<?=$controllerName == "TeacherCases" ? "active" : "" ?>"><a href="<?=$baseurl?>TeacherCases"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope"></i><?= $labl2070 ?> (<?= $counttmsg ?>)</a></li>
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "TeacherCases" ? "active" : "" ?>"><a href="<?=$baseurl?>TeacherCases"><i class="fa fa-envelope"></i><?= $labl2070 ?> (<?= $counttmsg ?>)</a></li>
		                    
		                    <?php }  }
		                    
		                    if(in_array("25", $privilages)) { 
		                     if($countpmsg != 0)
		                    { ?>
		                    <li class="<?=$controllerName == "ParentCases" ? "active" : "" ?>"><a href="<?=$baseurl?>ParentCases"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope"></i><?= $labl22 ?> Parent (<?= $countpmsg ?>)</a></li>
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "ParentCases" ? "active" : "" ?>"><a href="<?=$baseurl?>ParentCases"><i class="fa fa-envelope"></i><?= $labl22 ?> Parent (<?= $countpmsg ?>)</a></li>
		                    
		                    <?php } }
		                    
		                     if(in_array("19", $privilages)) {
		                    ?>
		                    
		                     <?php if($countmsgyoume != 0)
		                    { ?>
		                    <li class="<?=$controllerName == "Contactyoume" ? "active" : "" ?>"><a href="<?=$baseurl?>Contactyoume"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope"></i>Contact You-Me (<?= $countmsgyoume ?>)</a></li>
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "Contactyoume" ? "active" : "" ?>"><a href="<?=$baseurl?>Contactyoume"><i class="fa fa-envelope"></i>Contact You-Me (<?= $countmsgyoume ?>)</a></li>
		                    
		                    <?php } ?>
		                    
		                    <?php } 
		                    if((in_array("1", $privilages)) || (in_array("2", $privilages)) || (in_array("3", $privilages)) ) { ?>
		                    <li class="<?=$controllerName == "ClassesSubjects" ? "active" : "" ?>"><a href="<?=$baseurl?>ClassesSubjects"><i class="fa fa-graduation-cap"></i><?= $labl11 ?></a></li>
		                    

                            <?php }
		                    if(in_array("8", $privilages)) {?>
		                        <li class="<?=$controllerName == "ExamAssessment" ? "active" : "" ?>"><a href="<?=$baseurl?>examAssessment"><i class="fa fa-book"></i><?= $labl1518 ?> </a></li>
		                    <?php } 
		                    if(in_array("7", $privilages)) {?>
		                        <li class="<?=$controllerName == "Fees" ? "active" : "" ?>"><a href="<?=$baseurl?>fees"><i class="fa fa-money "></i><?= $labl24 ?></a></li>
		                    <?php } 
		                    if(in_array("20", $privilages)) { ?>
		                    <li class="<?=$controllerName == "Kindergarten" ? "active" : "" ?>"><a href="<?=$baseurl?>Kindergarten"><i class="icon-speedometer"></i><?= $labl1519 ?></a></li>
		                    <?php } 
		                    
		                    if(in_array("10", $privilages)) {?>
		                        <li class="<?=$controllerName == "Gallery" ? "active" : "" ?>"><a href="<?=$baseurl?>gallery"><i class="fa fa-image"></i><?= $labl1184 ?></a></li>
		                    <?php } 
		                    if(in_array("17", $privilages)) {?>
		                         <li class="<?=$controllerName == "IdentityCard" ? "active" : "" ?>"><a href="<?=$baseurl?>IdentityCard"><i class="fa fa-id-card-o"></i><?= $labl32 ?></a></li>
		                    <?php }
		                     
		                    if(in_array("15", $privilages)) {?>
		                        <li class="<?=$controllerName == "SchoolLibrary" ? "active" : "" ?>"><a href="<?=$baseurl?>SchoolLibrary"><i class="fa fa-book"></i><?= $labl1210 ?></a></li>
		                    <?php }  
		                    if(in_array("28", $privilages)) { ?>
		                    <li class="<?=(in_array($controllerName , ['loginhistoryreport']) ? "active" : ""  )?>" >
                                <a href="#Loginhistoryreport" class="has-arrow"><i class="fa fa-history"></i><span><?= $labl2135 ?></span></a>
                                <?php if(in_array("111", $roles)) {?>
                                <ul><li class="<?=(in_array($controllerName , ['Loginhistoryreport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Loginhistoryreport/teacherreport"><?= $labl2136 ?></a></li></ul>
                                <?php } if(in_array("110", $roles)) {?>
                                <ul><li class="<?=(in_array($controllerName , ['Loginhistoryreport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Loginhistoryreport/studentreport"><?= $labl2137 ?></a></li></ul>
                                <?php } if(in_array("109", $roles)) {?>
                                <ul><li class="<?=(in_array($controllerName , ['Loginhistoryreport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Loginhistoryreport/subadminreport"><?= $labl2138 ?></a></li></ul>
                                <?php } if(in_array("112", $roles)) { ?>
                                <ul><li class="<?=(in_array($controllerName , ['Loginhistoryreport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Loginhistoryreport/parentreport"><?= $labl2140 ?></a></li></ul>
                                <?php } ?>
                            </li>
                            <?php }
		                    if(in_array("12", $privilages)) {?>
		                        <?php if($schoolnotfycount != 0) { ?>
    		                    <li class="<?=$controllerName == "SchoolNotification" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolNotification"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-bell"></i><span id="sclannouncemnts"><?= $labl21 ?> (<?= $schoolnotfycount ?>)</span></a></li>
    		                    <?php } else { ?>
    		                    <li class="<?=$controllerName == "SchoolNotification" ? "active" : "" ?>"><a href="<?=$baseurl?>schoolNotification"><i class="fa fa-bell"></i><span id="sclannouncemnts"><?= $labl21 ?> (<?= $schoolnotfycount ?>)</span></a></li>
    		                    <?php } ?>
		                    <?php } 
		                    if(in_array("34", $privilages)) {?>
		                        <li class="<?=$controllerName == "Qrcodepasscode" ? "active" : "" ?>"><a href="<?=$baseurl?>Qrcodepasscode"><i class="fa fa-key"></i>Qr Passcode</a></li>
		                    <?php } 
	                        if(in_array("16", $privilages)) {?>
    		                    <li class="<?=(in_array($controllerName , ['AttendanceReport' , 'StudentSummary', 'SchoolLibraryAccessReport' , 'ReportcardReport', 'MeetingReport']) ? "active" : ""  )?>" >
                                    <a href="#AttendanceReport" class="has-arrow"><i class="fa fa-file"></i><span><?= $labl34 ?></span></a>
                                    <?php if(in_array("78", $roles)) {?>
                                    <ul><li class="<?=(in_array($controllerName , ['AttendanceReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>AttendanceReport"><?= $labl563 ?></a></li></ul>
                                    <?php } if(in_array("79", $roles)) {?>
                                    <ul><li class="<?=(in_array($controllerName , ['StudentSummary']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>StudentSummary"><?= $labl578 ?></a></li></ul>
                                    <?php } if(in_array("80", $roles)) {?>
                                    <ul><li class="<?=(in_array($controllerName , ['SchoolLibraryAccessReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolLibraryAccessReport"><?= $labl1581 ?></a></li></ul>
                                    <?php } if(in_array("81", $roles)) {?>
                                    <ul><li class="<?=(in_array($controllerName , ['MeetingReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>MeetingReport"><?= $labl1417 ?></a></li></ul>
                                    <?php } if(in_array("116", $roles)) {?>
                                    <ul><li class="<?=(in_array($controllerName , ['ReportcardReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>ReportcardReport"><?= $labl2158 ?></a></li></ul>
                                    <?php } ?>
                                </li>
                            <?php }
                             if(in_array("21", $privilages)) {
                            ?>
                            <li class="<?=$controllerName == "Reportcards" ? "active" : "" ?>"><a href="<?=$baseurl?>Reportcards"><i class="fa fa-file"></i><?= $labl1793 ?></a></li>
		                   
                            <?php }
                            if(in_array("22", $privilages)) { ?>
                            <li class="<?=$controllerName == "Timetable" ? "active" : "" ?>"><a href="<?=$baseurl?>Timetable"><i class="fa fa-calendar"></i><?= $labl1495 ?></a></li>
                            <?php }
                             if(in_array("23", $privilages)) { ?>
		                    <li class="<?=$controllerName == "SchoolkinderApplication" ? "active" : "" ?>"><a href="<?=$baseurl?>SchoolkinderApplication"><i class="fa fa-tasks"></i><?= $labl1824 ?></a></li>
		                    <?php } 
		                    
                            if(in_array("4", $privilages)) {?>
	                            <li class="<?=$controllerName == "Students" ? "active" : "" ?>"><a href="<?=$baseurl?>students"><i class="fa fa-graduation-cap"></i><?= $labl12 ?> </a></li>
		                    <?php } 
		                    if(in_array("30", $privilages)) { ?>
		                    
		                    <li class="<?=$controllerName == "Studentdairyreport" ? "active" : "" ?>"><a href="<?=$baseurl?>Studentdairyreport"><i class="fa fa-file"></i><?= $labl2147 ?> </a></li>
		                    <?php } if(in_array("29", $privilages)) { ?>
		                    <li class="<?=$controllerName == "Schoolteacherdairy" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolteacherdairy"><i class="fa fa-file"></i><?= $labl2148 ?> </a></li>
	                        <?php }
                            if(in_array("5", $privilages)) {?>
		                        <li class="<?=$controllerName == "Teachers" ? "active" : "" ?>"><a href="<?=$baseurl?>teachers"><i class="icon-users"></i><?= $labl15 ?></a></li>
		                    <?php } 
                            if(in_array("14", $privilages)) {?>
    		                    <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) ? "active" : ""  )?>" >
                                    <a href="#TutorialFee" class="has-arrow"><i class="fa fa-file"></i><span><?= $labl1221 ?></span></a>
                                    <ul>
                                        <?php if(in_array("66", $roles)) {?>
                                         <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "add" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/add"><?= $labl507 ?></a></li>
                                        <?php } if(in_array("68", $roles)) {?>
                                         <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "students" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/students"><?= $labl529 ?></a></li>
                                        <?php } if(in_array("61", $roles)) {?>
                                         <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee"><?= $labl997 ?></a></li>
                                        <?php } if(in_array("69", $roles)) {?>
                                         <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) && $actionName == "students" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolTutorialfee/content"><?= $labl509 ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php }
                            if(in_array("9", $privilages)) {  ?>
		                        <li class="<?=$controllerName == "Knowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>knowledge"><i class="fa fa-info"></i><span class="notranslate"><?= $labl35 ?></span></a></li>
		                    <?php }
                            ?>
                            
                             <li class="<?=$controllerName == "Schoolmarketplace" ? "active" : "" ?>"><a href="<?=$baseurl?>Schoolmarketplace"><i class="fa fa-shopping-bag"></i><span class="notranslate"><?= $labl1213 ?></span></a></li>
		                   
                        </ul>
                    </nav>
                </div>
                   
            <?php }
            elseif(!empty($student_details[0]))
            {  
            ?> 
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['Studentdashboard', 'Canteen', 'Studentdairy', 'Codeconduct', 'Dropbox', 'Studyguide', 'Quiz', 'Students', 'Subjectattendance', 'Studentmarketplace', 'StudentTimetable', 'ExamListing', 'StudentDiscussion', 'Meetings', 'Studentknowledge', 'StudentAttendance', 'StudentMessages', 'StudentFee', 'AllGrades',  'SubjectGrade', 'TutoringCenter', 'Assessments', 'Announcement', 'Subjectdetails', 'Calendar', 'ClassLibrary' , 'SubjectLibrary' ,   'ViewGallery', 'Studentsubjects', 'ViewKnowledge', 'Holiday', 'ParentsHoliday', 'StudentAttendance'] )  ? "active" : ""  )?>" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu"  style="padding-bottom:45px !important;">
                            <li class="<?=$controllerName == "Studentdashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>studentdashboard"><i class="icon-speedometer"></i><span><?= $labl588 ?></span></a></li>
                            <li class="<?=$controllerName == "Studentknowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>studentknowledge"><i class="fa fa-info" aria-hidden="true"></i><?= $labl593 ?></a></li>
                            
                            <li><a href="http://learn.eltngl.com/" target="_blank"><i class="fa fa-globe"></i>National Geographic</a></li>
                            <?php if($studntnoti_count != 0) { ?>
                            <li  class="<?=$controllerName == "Announcement" ? "active" : "" ?>" ><a href="<?=$baseurl?>announcement" id="studentannouncements"   class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-bullhorn" aria-hidden="true"></i><span id="studannouncemnts"><?= $labl1219 ?> (<?= $studntnoti_count ?>)</span></a></li>
                           	<?php  } else { ?>
                           	<li  class="<?=$controllerName == "Announcement" ? "active" : "" ?>" ><a href="<?=$baseurl?>announcement" id="studentannouncements"><i class="fa fa-bullhorn" aria-hidden="true"></i><span id="studannouncemnts"><?= $labl1219 ?> (<?= $studntnoti_count ?>)</span></a></li>
                           	
                           	<?php } ?> 
                           	<li  class="<?=$controllerName == "StudentAttendance" ? "active" : "" ?>" ><a href="<?=$baseurl?>studentAttendance"><i class="fa fa-address-card-o"></i><span><?= $labl1216 ?></span></a></li> 
                            <li  class="<?=$controllerName == "Calendar" ? "active" : "" ?>" ><a href="<?=$baseurl?>Calendar"><i class="fa fa-calendar"></i><span><?= $labl1215 ?></span></a></li> 
							<li  class="<?=$controllerName == "Canteen" ? "active" : "" ?>" ><a href="<?=$baseurl?>Canteen"><i class="fa fa-cutlery"></i><span><?= $labl2252 ?></span></a></li> 
                            
							<?php if($countunreadmsg != 0) { ?>
							<li  class="<?=$controllerName == "StudentMessages" ? "active" : "" ?>" ><a  href="<?=$baseurl?>StudentMessages"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope" aria-hidden="true"></i><span><?= $labl1222 ?> (<?= $countunreadmsg ?>)</span></a></li>
                            <?php } else { ?>
                            <li  class="<?=$controllerName == "StudentMessages" ? "active" : "" ?>" ><a  href="<?=$baseurl?>StudentMessages"><i class="fa fa-envelope" aria-hidden="true"></i><span><?= $labl1222 ?> (<?= $countunreadmsg ?>)</span></a></li>
                            
                            <?php   } ?>
                            <!--<li  class="<?=$controllerName == "Dropbox" ? "active" : "" ?>"><a href="<?=$baseurl?>Dropbox"><i class="fa fa-dropbox"></i>Drop Box</a></li>    -->
                            <li  class="<?=$controllerName == "ViewGallery" ? "active" : "" ?>"><a href="<?=$baseurl?>viewGallery"><i class="fa fa-image"></i><?= $labl1218 ?></a></li>
                            <?php if($libaccess == 1)
                            { ?>
                            <li  class="<?=$controllerName == "ClassLibrary" ? "active" : "" ?>"><a href="<?=$baseurl?>ClassLibrary"><i class="fa fa-book" aria-hidden="true"></i><span><?= $labl1220 ?></span></a></li>
							<?php } ?>	
							<li  class="<?=$controllerName == "StudentFee" ? "active" : "" ?>" ><a href="<?=$baseurl?>studentFee"><i class="fa fa-usd"></i><span><?= $labl1217 ?></span></a></li>
							<li  class="<?=$controllerName == "Studentdairy" ? "active" : "" ?>" ><a href="<?=$baseurl?>Studentdairy"><i class="fa fa-file"></i><span><?= $labl2144 ?></span></a></li> 
    						<li  class="<?=$controllerName == "Meetings" ? "active" : "" ?>" ><a  href="<?=$baseurl?>Meetings"><i class="fa  fa-link" aria-hidden="true"></i><span>You-Me Live</span></a></li>
    						<li class="<?=$controllerName == "StudentTimetable" ? "active" : "" ?>" ><a href="<?=$baseurl?>StudentTimetable"><i class="fa fa-calendar" aria-hidden="true"></i><span><?= $labl1495 ?></span></a></li>
    						<li class="<?=$controllerName == "TutoringCenter" ? "active" : "" ?>" ><a href="<?=$baseurl?>TutoringCenter"><i class="fa fa-file-text" aria-hidden="true"></i><span><?= $labl1221 ?></span></a></li>
    						<li class="<?=$controllerName == "ViewKnowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>studentdashboard/myschool"><i class="fa fa-book" aria-hidden="true"></i><span><?= $labl35 ?></span></a></li>
                            
    						<li class="<?=$controllerName == "Studentmarketplace" ? "active" : "" ?>"><a href="<?=$baseurl?>Studentmarketplace"><i class="fa fa-shopping-bag"></i><span class="notranslate"><?= $labl1213 ?></span></a></li>
		                </ul>
                    </nav>
                </div>
                   
            <?php } 
		    elseif(!empty($emp_details[0]))
			{
			    $class_sectns = [];
    			foreach($employeeclasses as $clsemploye) {
    			    $class_sectns[] = $clsemploye['class']['school_sections'];
    			}
    			$string = 'Maternelle';
                $string1 = 'Creche';
                $str = 'Primaire';
                $str1 = 'Humanités Scientifiques';
                $str2 = 'Humanité Pedagogie générale';
                $str3 = 'Humanité Math - Physique';
                $str4 = 'Humanité Littéraire';
                $str5 = 'Humanité Electricité Générale';
                $str6 = 'Humanité Commerciale & Gestion';
                $str7 = 'Humanité Chimie - Biologie';
                $str8 = 'Cycle Terminal de l\'Education de Base (CTEB)';
    			?> 
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['Teacherdashboard', 'Teacherdairy', 'Tstudentdairy', 'Teachertracking', 'TeacherMessages', 'ClassQuiz', 'TeacherTimetable', 'TeacherkinderApplication', 'Teacherkindergarten', 'Teacherattendance', 'Teachermarketplace', 'MeetingLink', 'Teacherclass', 'Teacherknowledge', 'ClassAttendance', 'ViewSchoolGallery',  'TeacherexamAssessment','Tutorialfee', 'TeacherCalendar', 'Employee', 'TeacherPost', 'DetailAssessment', 'TeacherSubject', 'TeacherNotifications',  'TeacherLibrary', 'ClassGrade', 'ClassAssessment', 'ClassExams'] )  ? "active" : ""  )?>" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu"  style="padding-bottom:45px !important;">
                            <li class="<?=$controllerName == "Teacherdashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherdashboard"><i class="icon-speedometer"></i><?= $labl927 ?></a></li>
                            <li class="<?=$controllerName == "Teacherknowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherknowledge"><i class="fa fa-info" aria-hidden="true"></i><span class="notranslate"><?= $labl593 ?></span></a></li>
                            <li><a href="http://learn.eltngl.com/" target="_blank"><i class="fa fa-globe"></i>National Geographic</a></li>
                            <li class="<?=$controllerName == "Teacherattendance" ? "active" : "" ?>" ><a href="<?=$baseurl?>teacherattendance"><i class="fa fa-address-card-o"></i><span><?= $labl930 ?></span></a></li> 
                            <li class="<?=$controllerName == "TeacherCalendar" ? "active" : "" ?>" ><a href="<?=$baseurl?>teacherCalendar"><i class="fa fa-calendar"></i><span><?= $labl931 ?></span></a></li> 
                            
                            
                            <?php if($counttmmsg != 0) { ?>
		                    <li  class="<?=$controllerName == "TeacherMessages" ? "active" : "" ?>" ><a  href="<?=$baseurl?>TeacherMessages"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-envelope" aria-hidden="true"></i><span><?= $labl1222 ?> (<?= $counttmmsg ?>)</span></a></li>
                            
		                    <?php } else { ?>
		                    <li  class="<?=$controllerName == "TeacherMessages" ? "active" : "" ?>" ><a  href="<?=$baseurl?>TeacherMessages"><i class="fa fa-envelope" aria-hidden="true"></i><span><?= $labl1222 ?> (<?= $counttmmsg ?>)</span></a></li>
                            
		                    <?php } ?>
                            
                            <?php if($_GET['subid'] != ''){?>
                             <li><a href="<?=$baseurl?>teacherclass"><i class="fa fa-envelope"></i><?= $labl1925 ?> </a></li>   

                            <?php }else{?>
                              <li class="<?=$controllerName == "Teacherclass" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherclass"><i class="fa fa-envelope"></i><?= $labl1925 ?> </a></li>
                            <?php } ?>
                            



                            <li class="<?=$controllerName == "TeacherexamAssessment" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherexamAssessment"><i class="fa fa-book"></i><?= $labl936 ?>/Guides </a></li>
                            <li class="<?=$controllerName == "ViewSchoolGallery" ? "active" : "" ?>"><a href="<?=$baseurl?>viewSchoolGallery"><i class="fa fa-image"></i><?= $labl1218 ?></a></li>
                            <?php if(in_array($string, $class_sectns) || in_array($string1, $class_sectns) ) {?>
                            <li class="<?=$controllerName == "Teacherkindergarten" ? "active" : "" ?>"><a href="<?=$baseurl?>Teacherkindergarten"><i class="icon-speedometer" aria-hidden="true"></i><?= $labl1815 ?></a></li>
                            <?php } ?>
                            
                            <li class="<?=$controllerName == "TeacherLibrary" ? "active" : "" ?>"><a href="<?=$baseurl?>TeacherLibrary"><i class="fa fa-book" aria-hidden="true"></i><span><?= $labl933 ?></span></a></li>
                            
                            <li class="<?=(in_array($controllerName , ['Teachertracking']) ? "active" : ""  )?>" >
                                <a href="#Teachertracking" class="has-arrow"><i class="fa fa-history"></i><span><?= $labl2135 ?></span></a>
                                <ul><li class="<?=(in_array($controllerName , ['Teachertracking']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Teachertracking/studentreport"><?= $labl2137 ?></a></li></ul>
                            </li> 
                            
                            <?php// if(in_array($str, $class_sectns) || in_array($str1, $class_sectns) || in_array($str2, $class_sectns) || in_array($str3, $class_sectns)  || in_array($str4, $class_sectns)  || in_array($str5, $class_sectns)  || in_array($str6, $class_sectns)  || in_array($str7, $class_sectns)  || in_array($str8, $class_sectns)   ) {?>
                            <li class="<?=$controllerName == "MeetingLink" ? "active" : "" ?>"><a href="<?=$baseurl?>MeetingLink"><i class="fa fa-link" aria-hidden="true"></i><span><?= $labl934 ?></span></a></li>
                            <?php
                            //}
                            if($tchrntfctn_count != 0) { ?>
		                    <li class="<?=$controllerName == "TeacherNotifications" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherNotifications"  id="teacherannouncements"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-bell"></i><span id="tchrannouncemnts"><?= $labl937 ?> (<?= $tchrntfctn_count ?>)</span></a></li>
                            
		                    <?php } else { ?>
		                    <li class="<?=$controllerName == "TeacherNotifications" ? "active" : "" ?>"><a href="<?=$baseurl?>teacherNotifications"  id="teacherannouncements"><i class="fa fa-bell"></i><span id="tchrannouncemnts"><?= $labl937 ?> (<?= $tchrntfctn_count ?>)</span></a></li>
                            
		                    <?php } ?>
							<li class="<?=$controllerName == "TeacherTimetable" ? "active" : "" ?>"><a href="<?=$baseurl?>TeacherTimetable"><i class="fa fa-calendar" aria-hidden="true"></i><span><?= $labl1495 ?></span></a></li>
                            <li  class="<?=$controllerName == "Tstudentdairy" ? "active" : "" ?>" ><a href="<?=$baseurl?>Tstudentdairy"><i class="fa fa-file"></i><span><?= $labl2144 ?></span></a></li> 
                            <li  class="<?=$controllerName == "Teacherdairy" ? "active" : "" ?>" ><a href="<?=$baseurl?>Teacherdairy"><i class="fa fa-file"></i><span><?= $labl2148 ?></span></a></li> 
                            <li class="<?=$controllerName == "TeacherkinderApplication" ? "active" : "" ?>"><a href="<?=$baseurl?>TeacherkinderApplication"><i class="fa fa-tasks"></i><?= $labl1824 ?></a></li>
		                    <?php if(in_array($str, $class_sectns) || in_array($str1, $class_sectns) || in_array($str2, $class_sectns) || in_array($str3, $class_sectns)  || in_array($str4, $class_sectns)  || in_array($str5, $class_sectns)  || in_array($str6, $class_sectns)  || in_array($str7, $class_sectns)  || in_array($str8, $class_sectns)   ) {?>
                            
                            <li class="<?=(in_array($controllerName , ['SchoolTutorialfee']) ? "active" : ""  )?>" >
                                <a href="#TutorialFee" class="has-arrow"><i class="fa fa-file"></i><span><?= $labl1221 ?></span></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['Tutorialfee']) && $actionName == "subjects" ? "active" : ""  )?>"><a   href="<?=$baseurl?>tutorialfee/subjects"><?= $labl546 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Tutorialfee']) && $actionName == "students" ? "active" : ""  )?>"><a   href="<?=$baseurl?>tutorialfee/students"><?= $labl508 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Tutorialfee']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>tutorialfee"><?= $labl997 ?></a></li>
                                     
                                </ul>
                            </li>
                            <?php } ?>
                            <li class="<?=$controllerName == "Teachermarketplace" ? "active" : "" ?>"><a href="<?=$baseurl?>Teachermarketplace"><i class="fa fa-shopping-bag"></i><span class="notranslate"><?= $labl1213 ?></span></a></li>
		                    
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
                    <div class="col-lg-6 col-md-6 col-sm-12">
			<?php 
			    if($mainpage=="Studentdashboard") { 
			        if($subpage == "Studentdashboard / Studentprofile Studentdashboard") { 
			        ?>  
    			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1224 ?></h2>
                	<?php
			        }
			        else { ?>
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Student Dashboard</h2>
			        <?php
			        }
			    } 
			    else if($mainpage=="Parentdashboard") {  ?>
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Parent Dashboard</span></h2>
			 <?php } 
			 else if($mainpage=="Canteenreport") {  ?>
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2351 ?></h2>
			 <?php } 
			 else if($mainpage=="Qrcodepasscode") {  ?>
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Qr Code Passcode</h2>
			 <?php }
			 else if($mainpage=="Teacherdashboard") {  ?>
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl942 ?></h2>
			 <?php } 
			 else if($mainpage=="Printable") {  ?>
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2153 ?></h2>
			 <?php } 
			 else if($mainpage=="Feediscount") {  ?>
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2121 ?></h2>
			 <?php } 
			 else if($mainpage=="Canteen") {  ?>
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2252 ?></h2>
			 <?php }
			    else if($mainpage=="Studyguide") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1725 ?></h2>
			<?php }
			else if($mainpage=="ViewKnowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl35 ?></h2>
			<?php }  
			else if($mainpage=="ParentPayFee") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1217 ?></h2>
			<?php } 
			else if($mainpage=="Teacherdairy") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl2148 ?></h2>
			<?php }
			else if($mainpage=="ReportcardReport") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl2158 ?></h2>
			<?php }
			else if($mainpage=="Schoolteacherdairy") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl2148 ?></h2>
			<?php }
			else if($mainpage=="Kindergarten") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1815 ?></h2>
			<?php }
			else if($mainpage=="Teacherkindergarten") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1815 ?></h2>
			<?php }
			else if($mainpage=="Calendar") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1311 ?></h2>
			<?php } 
				else if($mainpage=="Announcement") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1231 ?></h2>
			<?php }
				else if($mainpage=="ClassQuiz") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1877 ?></h2>
			<?php }
			else if($mainpage=="Quiz") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1727 ?></h2>
			<?php }
			 else if($mainpage=="Studentdairy") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl2144 ?></h2>
			<?php }
			 else if($mainpage=="Studentdairyreport") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl2147 ?></h2>
			<?php }
			else if($mainpage=="Tstudentdairy") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl2144 ?></h2>
			<?php }
			    else if($mainpage=="Studentsubjects") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1857 ?></h2>
			<?php }
			    else if($mainpage=="ViewGallery") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1218 ?></h2>
			<?php }
			 else if($mainpage=="Contactyoume") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Contact You-Me</h2>
			<?php }
			    else if($mainpage=="ClassLibrary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1377 ?></h2>
			<?php } 
			    else if($mainpage=="Feedetail") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1541 ?></h2>
			<?php }
			else if($mainpage=="Reportcards") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1793 ?></h2>
			<?php }
			     else if($mainpage=="Students") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl12 ?></h2>
			<?php }
			    else if($mainpage=="Teachers") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl15 ?></h2>
			<?php }
			    else if($mainpage=="Readmissions") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl235 ?></h2>
			<?php }
			else if($mainpage=="Admissions") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl98 ?></h2>
			<?php }
		
		    else if($mainpage=="SchoolkinderApplication") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1824 ?></h2>
			<?php }
			 else if($mainpage=="TeacherkinderApplication") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1824 ?></h2>
			<?php }
			 else if($mainpage=="TeacherCases") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2070 ?></h2>
			<?php }
			else if($mainpage=="AllGrades") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1858 ?></h2>
			<?php }
			    else if($mainpage=="SubjectLibrary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1210 ?></h2>
			    <?php } 
			    else if($mainpage=="MeetingReport") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1417 ?></h2>
			    <?php }
			    else if($mainpage=="IdentityCard") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl499 ?></h2>
			    <?php }
			    else if($mainpage=="allGrades") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1858 ?></h2>
			    <?php }
			    else if($mainpage=="ExamListing") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1758 ?></h2>
			    <?php }
			    else if($mainpage=="Subjectattendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1728 ?></h2>
			    <?php }
			    else if($mainpage=="Teachermarketplace") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1213 ?></h2>
			    <?php }
			    else if($mainpage=="Message") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl2014 ?></h2>
			    <?php }
			    else if($mainpage=="Studentmarketplace") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1213 ?></h2>
			    <?php }
			    else if($mainpage=="StudentAttendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1216 ?></h2>
			    <?php }
			    elseif($mainpage=="SchoolLibraryAccessReport") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1581 ?></h2>
            			<?php }
			    else if($mainpage=="TutoringCenter") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1221 ?></h2>
			    <?php }
			    else if($mainpage=="SubjectGrade") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1771 ?></h2>
			    <?php }
			    else if($mainpage=="StudentDiscussion") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1732 ?></h2>
			    <?php }
			    else if($mainpage=="Holiday") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1953 ?></h2>
			    <?php }
			    else if($mainpage=="TeacherSubject") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1854 ?></h2>
			    <?php }
			   
			    else if($mainpage=="ClassGrade") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1855 ?></h2>
			    <?php }
			    else if($mainpage=="ClassAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1856 ?></h2>
			    <?php }
			    else if($mainpage=="ClassAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1856 ?></h2>
			    <?php }
			    else if($mainpage=="ClassExams") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1859 ?></h2>
			    <?php }
			    else if($mainpage=="TeacherPost") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1860 ?></h2>
			    <?php }
			    else if($mainpage=="SchoolSubadmin") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl8 ?> </h2>
			    <?php }
			    else if($mainpage=="ClassAttendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1216 ?> </h2>
			    <?php }
			     else if($mainpage=="TeacherLibrary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1210 ?> </h2>
			    <?php }
			    else if($mainpage=="TeacherNotifications") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1070 ?> </h2>
			    <?php }
			    else if($mainpage=="DetailAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1861 ?> </h2>
			    <?php }
			    else if($mainpage=="ExamAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>
			        <?php if(empty($sclsub_details[0])) {echo $labl2076; } else { echo $labl1516; } ?></h2>
			    <?php }
			    else if($mainpage=="TeacherexamAssessment") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1516 ?> </h2>
			    <?php }
			    else if($mainpage=="Teacherattendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1216 ?> </h2>
			    <?php }
			     else if($mainpage=="StudentTimetable") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1495 ?></h2>
			    <?php } 
			    else if($mainpage=="TeacherMessages") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1222 ?></h2>
			    <?php }
			    else if($mainpage=="TeacherTimetable") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1495 ?></h2>
			    <?php }
			    else if($mainpage=="SchoolApproval") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1862 ?></h2>
			    <?php }
			    else if($mainpage=="SchoolCalendar") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl20 ?> </h2>
			    <?php }
			    else if($mainpage=="Schoolmarketplace") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1213 ?></h2>
			    <?php }
			    else if($mainpage=="Knowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl35 ?></h2>
			    <?php }
			    else if($mainpage=="Schooldashboard") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> School Dashboard </h2>
			    <?php }
			    
			    else if($mainpage=="Timetable") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1495 ?> </h2>
			    <?php }
			    else if($mainpage=="TeacherCalendar") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl20 ?> </h2>
			    <?php }
			     else if($mainpage=="ViewSchoolGallery") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1184 ?> </h2>
			    <?php }
			    else if($mainpage=="SchoolNotification") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1070 ?> </h2>
			    <?php }
			    else if($mainpage=="Feehead") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl317 ?>  </h2>
			    <?php }
			    else if($mainpage=="Assessments") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1723 ?>  </h2>
			    <?php }
			    else if($mainpage=="StudentMessages") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1379 ?> </h2>
			    <?php }
			    else if($mainpage=="Schoolattendance") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1216 ?> </h2>
			    <?php }
			    else if($mainpage=="StudentSummary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl578 ?></h2>
			    <?php } 
			    else if($mainpage=="StudentFee") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>  <?= $labl1353 ?> </h2>
			    <?php }
			    else if($mainpage=="Meetings") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>  You-Me Live</h2>
			    <?php }
			    else if($mainpage=="AttendanceReport") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl563 ?> </h2>
			    <?php } 
			    else if($mainpage=="SchoolTutorialfee") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl523 ?></h2>
			    <?php }
			    else if($mainpage=="Loginhistoryreport") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2134 ?></h2>
			    <?php }
			    else if($mainpage=="Schoolknowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl593 ?></h2>
			    <?php }
			    else if($mainpage=="ParentCases") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl22 ?></h2>
			    <?php }
			     else if($mainpage=="Studentknowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl593 ?></h2>
			    <?php }
			    else if($mainpage=="Parentknowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl593 ?></h2>
			    <?php }
			     else if($mainpage=="Teacherknowledge") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl593 ?></h2>
			    <?php }
			    else if($mainpage=="MeetingLink") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl934 ?></h2>
			    <?php } 
			    else if($mainpage=="ClassesSubjects") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl11 ?></h2>
			    <?php }
			    else if($mainpage=="Gallery") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl19 ?></h2>
			    <?php }
			    else if($mainpage=="Schools") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2064 ?></h2>
			    <?php }
			    else if($mainpage=="ClassSubjects") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl11 ?></h2>
			    <?php }
			    else if($mainpage=="Subjects") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2036 ?></h2>
			    <?php }
			    else if($mainpage=="Codeconduct") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2143 ?></h2>
			    <?php }
			     else if($mainpage=="Fees") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl16 ?></h2>
			    <?php }
			    else if($mainpage=="Employee") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl1920 ?></h2>
			    <?php }
			    else if($mainpage=="SchoolLibrary") { ?>  
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl2044 ?></h2>
			    <?php }
			    else if($mainpage=="Tutorialfee") { ?> 
			       <?php if($subpage == "Tutorialfee / Subjects Tutorialfee") { 
			        ?>  
    			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1026 ?></h2>
                	<?php
			        }
			        else { ?>
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl33 ?></h2>
			        <?php
			        } ?>
			    <?php }
			    else if($mainpage=="Teacherclass") { ?> 
			       <?php if($subpage == "Teacherclass / ClassallSubjects Teacherclass") { 
			        ?>  
    			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl2129 ?></h2>
                	<?php
			        } else { ?>
			        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $labl1925 ?></h2>
			    <?php } }
    			else { ?>
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?=$mainpage?></h2>
    			<?php }
    			 ?>
			</div>     
                         
                </div>
            </div>


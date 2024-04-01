<?php
if(!empty($user_details[0])){
    $spec_cond = md5($user_details[0]['id']) ;
}
elseif(!empty($parent_details[0])){
    $spec_cond = md5($parent_details[0]['id']) ;
}

foreach($lang_label as $langlbl) {
    if($langlbl['id'] == '634') { $lbl634 = $langlbl['title'] ; }
    if($langlbl['id'] == '578') { $lbl578 = $langlbl['title'] ; }
    if($langlbl['id'] == '586') { $lbl586 = $langlbl['title'] ; }
    if($langlbl['id'] == '588') { $lbl588 = $langlbl['title'] ; }
    if($langlbl['id'] == '589') { $lbl589 = $langlbl['title'] ; }
    if($langlbl['id'] == '590') { $lbl590 = $langlbl['title'] ; }
    if($langlbl['id'] == '591') { $lbl591 = $langlbl['title'] ; }
    if($langlbl['id'] == '592') { $lbl592 = $langlbl['title'] ; }
    if($langlbl['id'] == '593') { $lbl593 = $langlbl['title'] ; }
    if($langlbl['id'] == '594') { $lbl594 = $langlbl['title'] ; }
    if($langlbl['id'] == '595') { $lbl595 = $langlbl['title'] ; }
    if($langlbl['id'] == '596') { $lbl596 = $langlbl['title'] ; }
    if($langlbl['id'] == '601') { $lbl601 = $langlbl['title'] ; }
    if($langlbl['id'] == '602') { $lbl602 = $langlbl['title'] ; }
    if($langlbl['id'] == '604') { $lbl604 = $langlbl['title'] ; }
    if($langlbl['id'] == '775') { $lbl775 = $langlbl['title'] ; }
    if($langlbl['id'] == '21') { $lbl21 = $langlbl['title'] ; }
    if($langlbl['id'] == '1417') { $lbl1417 = $langlbl['title'] ; }
    if($langlbl['id'] == '1217') { $lbl1217 = $langlbl['title'] ; }
    if($langlbl['id'] == '840') { $lbl840 = $langlbl['title'] ; }
    if($langlbl['id'] == '804') { $lbl804 = $langlbl['title'] ; }
    if($langlbl['id'] == '1222') { $lbl1222 = $langlbl['title'] ; }
    if($langlbl['id'] == '721') { $lbl721 = $langlbl['title'] ; }
    if($langlbl['id'] == '635') { $lbl635 = $langlbl['title'] ; }
    if($langlbl['id'] == '2233') { $lbl2233 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '603') { $lbl603 = $langlbl['title'] ; } 
    if($langlbl['id'] == '886') { $lbl886 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1213') { $lbl1213 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1581') { $lbl1581 = $langlbl['title'] ; }
    if($langlbl['id'] == '873') { $lbl873 = $langlbl['title'] ; }
    if($langlbl['id'] == '862') { $lbl862 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '864') { $lbl864 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2134') { $labl2134 = $langlbl['title'] ; }
	if($langlbl['id'] == '2135') { $labl2135 = $langlbl['title'] ; }
	if($langlbl['id'] == '2141') { $labl2141 = $langlbl['title'] ; }
	
	if($langlbl['id'] == '2142') { $labl2142 = $langlbl['title'] ; }
	if($langlbl['id'] == '2144') { $labl2144 = $langlbl['title'] ; }
	if($langlbl['id'] == '2198') { $labl2198 = $langlbl['title'] ; }
	
	if($langlbl['id'] == '2239') { $lbl2239 = $langlbl['title'] ; }
	if($langlbl['id'] == '2240') { $lbl2240 = $langlbl['title'] ; }
	if($langlbl['id'] == '2241') { $lbl2241 = $langlbl['title'] ; }
	if($langlbl['id'] == '2242') { $lbl2242 = $langlbl['title'] ; }
	if($langlbl['id'] == '2243') { $lbl2243 = $langlbl['title'] ; }
	if($langlbl['id'] == '2244') { $lbl2244 = $langlbl['title'] ; }
	if($langlbl['id'] == '2245') { $lbl2245 = $langlbl['title'] ; }
	if($langlbl['id'] == '2246') { $lbl2246 = $langlbl['title'] ; }
	
	if($langlbl['id'] == '2329') { $lbl2329 = $langlbl['title'] ; }
	if($langlbl['id'] == '2330') { $lbl2330 = $langlbl['title'] ; }
	if($langlbl['id'] == '2331') { $lbl2331 = $langlbl['title'] ; }
	
	if($langlbl['id'] == '2371') { $lbl2371 = $langlbl['title'] ; }
	if($langlbl['id'] == '2372') { $lbl2372 = $langlbl['title'] ; }
	if($langlbl['id'] == '2373') { $lbl2373 = $langlbl['title'] ; }
	if($langlbl['id'] == '2390') { $lbl2390 = $langlbl['title'] ; }
	
	if($langlbl['id'] == '2401') { $lbl2401 = $langlbl['title'] ; }
}
?>
  
  
  <div id="left-sidebar" class="sidebar">
        <div class="sidebar-scroll">
            <div class="user-account">
                <?php 
                if(!empty($user_details[0]['picture']))
                {   ?>
                    <img src="<?=$baseurl."img/".$user_details[0]["picture"]?>" height="50" widht="50" class="rounded-circle user-photo" alt="User Profile Picture"> 
                    <?php
                }
                elseif(!empty($parent_details[0]['image']))
                { ?>
                    <img src="<?=$baseurl?>img/<?= $parent_details[0]['image'] ?>"  height="50" widht="50" class="rounded-circle user-photo" alt="User Profile Picture">
                <?php }
                elseif(!empty($cvndr_details[0]['logo']))
                { ?>
                    <img src="<?=$baseurl?>canteen/<?= $cvndr_details[0]['logo'] ?>"  height="50" widht="50" class="rounded-circle user-photo" alt="User Profile Picture">
                <?php }
                else
                {  ?>
                    <img src="<?=$baseurl?>img/user.png" class="rounded-circle user-photo" alt="User Profile Picture">
                <?php
                }
                ?>
                <div class="dropdown">
                    <span><?= $lbl586 ?>,</span>
                    <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>
                        <?php 
                            if(!empty($user_details[0]))
                            {  
                              echo $user_details[0]['fname'];
                            }
                            elseif(!empty($parent_details[0]))
                            {  
                              echo $parent_details[0]['parent_email'];
                            }
                            elseif(!empty($cvndr_details[0]))
                            {  
                              echo $cvndr_details[0]['l_name']." ".$cvndr_details[0]['f_name'];
                            }
                            
                        ?>
                    </strong></a>                    
                    <ul class="dropdown-menu dropdown-menu-right account animated flipInY">
                        <li>
                            <?php 
                            if(!empty($user_details[0]))
                            {  ?>
                              <a href="<?=$baseurl?>subadmin/profile/<?=md5($user_details[0]['id'])?>"><i class="icon-user"></i><?= $lbl601 ?></a>
                            <?php }
                            elseif(!empty($parent_details[0]))
                            {  ?>
                              <a href="<?=$baseurl?>parentdashboard/profile/<?=md5($parent_details[0]['id'])?>"><i class="icon-user"></i><?= $lbl601 ?></a>
                            <?php }
                            elseif(!empty($cvndr_details[0]))
                            {  ?>
                              <a href="<?=$baseurl?>canteenvendors/profile/<?=md5($cvndr_details[0]['id'])?>"><i class="icon-user"></i><?= $lbl601 ?></a>
                            <?php }
                            ?>
                        </li>                       
                        <li class="divider"></li> 
                        <li>
                            <?php 
                            if(!empty($user_details[0]))
                            {  ?>
                              <a href="<?=$baseurl?>logouta"><i class="icon-power"></i><?= $lbl602 ?></a>
                            <?php }
                            elseif(!empty($parent_details[0]))
                            {  ?>
                              <a href="<?=$baseurl?>logoutp"><i class="icon-power"></i><?= $lbl602 ?></a>
                            <?php } 
                            elseif(!empty($cvndr_details[0])) { ?>
                                <a href="<?=$baseurl?>logoutc"><i class="icon-power"></i><?= $lbl602 ?></a> 
                            <?php } ?> 
                        </li>
                    </ul>
                </div>
                <hr>
               
            </div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
            <?php
            if(!empty($user_details[0]))
            {
             if(count ( array_intersect($user_privilage, $school_cat_priv) ) > 0 ){
                ?>
                <li class="nav-item"><a class="nav-link <?=(in_array($controllerName , ['Dashboard' , 'Logintracking', 'MarketQueries', 'Schools', 'Users' , 'Roles' , 'Schoolroles' ,'Modules' , 'Session' ]) ?  "active" : ""  )?>" data-toggle="tab" href="#project_menu">Menu</a></li>                
             <?php } 
            }
            ?>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content p-l-0 p-r-0">
            <?php 
            if(!empty($user_details[0]))
            {  ?>
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['Dashboard', 'Cvendordashboard', 'Canteenreport', 'Vendorfoodrpt', 'Foodstatus', 'Canteenimage', 'Meet' , 'Logintracking', 'Canteenvendors', 'LibraryAccessReport', 'SchoolMeetingReport', 'Categories', 'MarketQueries',  'Dealers', 'Products',  'Schools', 'MarketQueries' , 'Queries', 'Subadmin', 'Session', 'Notification' ,'Schoolroles' , 'Summary' , 'Session', 'KnowledgeCenter' ] )  ? "active" : ""  )?>" id="hr_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu" style="padding-bottom:35px !important;">
                            
                            <li class="<?=$controllerName == "Dashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>dashboard"><i class="icon-speedometer"></i><span class="notranslate"><?= $lbl588 ?></span></a>
                            </li>
                            <?php if($user_details[0]['role'] == '2') 
                            { ?>
                            <li class="<?=(in_array($controllerName , ['Subadmin']) ? "active" : ""  )?>" >
                                <a href="#Subadmin" class="has-arrow"><i class="fa fa-users"></i><span><?= $lbl589 ?></span></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['Subadmin']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>subadmin"><?= $lbl604 ?></a></li>
                                </ul>
                            </li>
                            
                            <li class="<?=(in_array($controllerName , ['Schools' , 'Schoolroles' , 'Modules']) ? "active" : ""  )?>" >
                                <a href="#Schools" class="has-arrow"><i class="fa fa-university"></i><span><?= $lbl590 ?></span></a>
                                <ul>
                                     <li class="<?=(in_array($controllerName , ['Schools']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>schools"><?= $lbl634 ?></a></li>
                                </ul>
                            </li>

                            <!--<li class="<?=$controllerName == "Canteenvendors" ? "active" : "" ?>"><a   href="<?=$baseurl?>Canteenvendors"><i class="fa fa-cutlery"></i><span><?= $labl2198 ?></span></a></li>
                            -->
                            
                            <li class="<?=(in_array($controllerName , ['Canteenvendors', 'Foodstatus', 'Canteenimage', 'Canteenreport' , 'Vendorfoodrpt']) ? "active" : ""  )?>" >
                                <a href="#Schools" class="has-arrow"><i class="fa fa-cutlery"></i><span><?= $lbl2246 ?></span></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['Canteenvendors']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenvendors"><?= $labl2198 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Canteenvendors']) && $actionName == "fooditems" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenvendors/fooditems"><?= $lbl2240 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Canteenvendors']) && $actionName == "assignfoodscl" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenvendors/assignfoodscl"><?= $lbl2241 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Foodstatus']) ? "active" : ""  )?>"><a   href="<?=$baseurl?>Foodstatus"><?= $lbl2239 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Canteenimage']) ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenimage"><?= $lbl2233 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['vendorfoodrpt']) ? "active" : ""  )?>"><a   href="<?=$baseurl?>vendorfoodrpt"><?= $lbl2242 ?></a></li>
                                    
                                    <li class="<?=(in_array($controllerName , ['Canteenreport']) ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenreport"><?= $lbl2243 ?></a></li>
                                </ul>
                            </li>
                            
                            <li class="<?=$controllerName == "Notification" ? "active" : "" ?>"><a   href="<?=$baseurl?>notification"><i class="fa fa-bell"></i><span><?= $lbl591 ?></span></a></li>
                            <li class="<?=(in_array($controllerName , ['Summary', 'SchoolMeetingReport', 'LibraryAccessReport']) ? "active" : ""  )?>" >
                                <a href="#AttendanceReport" class="has-arrow"><i class="fa fa-file"></i><span><?= $lbl592 ?></span></a>
                                <ul><li class="<?=(in_array($controllerName , ['LibraryAccessReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>LibraryAccessReport"><?= $lbl1581 ?></a></li></ul>
                                
                                <ul><li class="<?=(in_array($controllerName , ['Summary']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Summary"><?= $lbl578 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['SchoolMeetingReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolMeetingReport"><?= $lbl1417 ?></a></li></ul>
                            </li>
                            <li class="<?=(in_array($controllerName , ['Logintracking']) ? "active" : ""  )?>" >
                                <a href="#Logintracking" class="has-arrow"><i class="fa fa-history"></i><span><?= $labl2135 ?></span></a>
                                <ul><li class="<?=(in_array($controllerName , ['Logintracking']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Logintracking/schoolreport"><?= $labl2141 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['Logintracking']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Logintracking/subadminreport"><?= $labl2142 ?></a></li></ul>
                            </li> 
                            
                            <li class="<?=(in_array($controllerName , ['KnowledgeCenter', 'Queries']) ? "active" : ""  )?>" >
                                <a href="#KnowledgeCenter" class="has-arrow"><i class="fa fa-info"></i><?= $lbl593 ?></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['KnowledgeCenter']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>KnowledgeCenter"><?= $lbl593 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Queries']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Queries"><?= $lbl775 ?></a></li>
                                </ul>
                            </li>
                            <li class="<?=(in_array($controllerName , ['Categories', 'Dealers', 'Products', 'MarketQueries']) ? "active" : ""  )?>" >
                                <a href="#MarketPlace" class="has-arrow"><i class="fa fa-shopping-bag"></i><span class="notranslate"><?= $lbl594 ?></span></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['Categories']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Categories"><?= $lbl864 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Dealers']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Dealers"><?= $lbl862 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Products']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Products"><?= $lbl840 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['MarketQueries']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>MarketQueries"><?= $lbl775 ?></a></li>
                                </ul>
                            </li> 
                            
                            <li class="<?=$controllerName == "Meet" ? "active" : "" ?>"><a href="<?= $baseurl ?>meet"><i class="fa fa-video-camera"></i><span><?= $lbl595 ?></span></a></li>
                           
                            <li class="<?=$controllerName == "Session" ? "active" : "" ?>"><a href="<?= $baseurl ?>session"><i class="fa fa-calendar"></i><span><?= $lbl596 ?></span></a></li>
                            <?php } 
                            else if($user_details[0]['role'] == '3')
                            { 
                                
                                $mprivilages = explode(",", $user_details[0]['menus_privilages']);
                                
                                if(in_array("2", $mprivilages)) { ?>
                                <li class="<?=(in_array($controllerName , ['Subadmin']) ? "active" : ""  )?>" >
                                    <a href="#Subadmin" class="has-arrow"><i class="fa fa-users"></i><span><?= $lbl589 ?></span></a>
                                    <ul>
                                        <li class="<?=(in_array($controllerName , ['Subadmin']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>subadmin"><?= $lbl604 ?></a></li>
                                    </ul>
                                </li>
                                <?php } if(in_array("1", $mprivilages)) { ?>
                                    <li class="<?=(in_array($controllerName , ['Schools' , 'Schoolroles' , 'Modules']) ? "active" : ""  )?>" >
                                        <a href="#Schools" class="has-arrow"><i class="fa fa-university"></i><span><?= $lbl590 ?></span></a>
                                        <ul>
                                             <li class="<?=(in_array($controllerName , ['Schools']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>schools"><?= $lbl634 ?></a></li>
                                        </ul>
                                    </li>
                            <?php } if(in_array("14", $mprivilages)) { ?>        
                            <li class="<?=(in_array($controllerName , ['Canteenvendors', 'Foodstatus', 'Canteenimage', 'Canteenreport' , 'Vendorfoodrpt']) ? "active" : ""  )?>" >
                                <a href="#Schools" class="has-arrow"><i class="fa fa-cutlery"></i><span><?= $lbl2246 ?></span></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['Canteenvendors']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenvendors"><?= $labl2198 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Canteenvendors']) && $actionName == "fooditems" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenvendors/fooditems"><?= $lbl2240 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Canteenvendors']) && $actionName == "assignfoodscl" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenvendors/assignfoodscl"><?= $lbl2241 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Foodstatus']) ? "active" : ""  )?>"><a   href="<?=$baseurl?>Foodstatus"><?= $lbl2239 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Canteenimage']) ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenimage"><?= $lbl2233 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['vendorfoodrpt']) ? "active" : ""  )?>"><a   href="<?=$baseurl?>vendorfoodrpt"><?= $lbl2242 ?></a></li>
                                    
                                    <li class="<?=(in_array($controllerName , ['Canteenreport']) ? "active" : ""  )?>"><a   href="<?=$baseurl?>Canteenreport"><?= $lbl2243 ?></a></li>
                                </ul>
                            </li>
                            <?php  } if(in_array("3", $mprivilages)) { ?>
                            <li class="<?=$controllerName == "Notification" ? "active" : "" ?>"><a   href="<?=$baseurl?>notification"><i class="fa fa-bell"></i><span><?= $lbl591 ?></span></a></li>
                            
                            <?php } if(in_array("4", $mprivilages)) { ?>
                            <li class="<?=(in_array($controllerName , ['Summary', 'SchoolMeetingReport', 'LibraryAccessReport']) ? "active" : ""  )?>" >
                                <a href="#AttendanceReport" class="has-arrow"><i class="fa fa-file"></i><span><?= $lbl592 ?></span></a>
                                <ul><li class="<?=(in_array($controllerName , ['LibraryAccessReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>LibraryAccessReport"><?= $lbl1581 ?></a></li></ul>
                                
                                <ul><li class="<?=(in_array($controllerName , ['Summary']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Summary"><?= $lbl578 ?></a></li></ul>
                                <ul><li class="<?=(in_array($controllerName , ['SchoolMeetingReport']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>SchoolMeetingReport"><?= $lbl1417 ?></a></li></ul>
                            </li>    
                            <?php } ?>
                            <li class="<?=(in_array($controllerName , ['Logintracking']) ? "active" : ""  )?>" >
                                <a href="#Logintracking" class="has-arrow"><i class="fa fa-history"></i><span><?= $labl2135 ?></span></a>
                                <ul><li class="<?=(in_array($controllerName , ['Logintracking']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Logintracking/schoolreport"><?= $labl2141 ?></a></li></ul>
                                
                            </li> 
                            
                            <?php if(in_array("6", $mprivilages)) { ?>
                            <li class="<?=(in_array($controllerName , ['KnowledgeCenter', 'Queries']) ? "active" : ""  )?>" >
                                <a href="#KnowledgeCenter" class="has-arrow"><i class="fa fa-info"></i><?= $lbl593 ?></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['KnowledgeCenter']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>KnowledgeCenter"><?= $lbl593 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Queries']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Queries"><?= $lbl775 ?></a></li>
                                </ul>
                            </li>
                            <?php } if(in_array("11", $mprivilages)) { ?>
                            <li class="<?=(in_array($controllerName , ['Categories', 'Dealers', 'Products', 'MarketQueries']) ? "active" : ""  )?>" >
                                <a href="#MarketPlace" class="has-arrow"><i class="fa fa-shopping-bag"></i><span class="notranslate"><?= $lbl594 ?></span></a>
                                <ul>
                                    <li class="<?=(in_array($controllerName , ['Categories']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Categories"><?= $lbl864 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Dealers']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Dealers"><?= $lbl862 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['Products']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>Products"><?= $lbl840 ?></a></li>
                                    <li class="<?=(in_array($controllerName , ['MarketQueries']) && $actionName == "index" ? "active" : ""  )?>"><a   href="<?=$baseurl?>MarketQueries"><?= $lbl775 ?></a></li>
                                </ul>
                            </li> 
                            <?php } if(in_array("12", $mprivilages)) { ?>
                             <li class="<?=$controllerName == "Meet" ? "active" : "" ?>"><a href="<?= $baseurl ?>meet"><i class="fa fa-video-camera"></i><span><?= $lbl595 ?></span></a></li>
                            <?php } if(in_array("13", $mprivilages)) { ?>
                             <li class="<?=$controllerName == "Session" ? "active" : "" ?>"><a href="<?= $baseurl ?>session"><i class="fa fa-calendar"></i><span><?= $lbl596 ?></span></a></li>
                            <?php } 
                           } ?>
                        </ul>
                    </nav>
                </div>
            <?php 
            } 
            
            elseif(!empty($parent_details[0]))
            { 
            ?> 
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , [ 'Studyguide', 'ParentQrIdcard', 'Parentreport', 'Canteenreport', 'Canteenfund', 'Parentdairy', 'ParentMessages', 'ParentNotification', 'Quiz', 'StudentTimetable', 'Students', 'Parentdashboard', 'Parents', 'Parentmarketplace',  'Parentknowledge', 'ParentSummary', 'ParentPayFee'] )  ? "active" : ""  )?>" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu"  style="padding-bottom:45px !important;">
                            <li class="<?=$controllerName == "parentdashboard" ? "active" : "" ?>"><a href="<?=$baseurl?>parentdashboard"><i class="icon-speedometer"></i><span><?= $lbl588 ?></span></a></li>
                            
                            
                            <li class="<?=$controllerName == "Parentknowledge" ? "active" : "" ?>"><a href="<?=$baseurl?>Parentknowledge"><i class="fa fa-info" aria-hidden="true"></i><?= $lbl593 ?></a></li>
                            <li><a href="http://learn.eltngl.com/" target="_blank"><i class="fa fa-globe"></i>National Geographic</a></li>
                            
                            
                            <?php if($countprmsg != 0) { ?>
		                    <li  class="<?=$controllerName == "ParentMessages" ? "active" : "" ?>" ><a  href="<?=$baseurl?>ParentMessages"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-phone" aria-hidden="true"></i><span><?= $lbl1222 ?> (<?= $countprmsg ?>)</span></a></li>
		                    <?php } else { ?>
		                    <li  class="<?=$controllerName == "ParentMessages" ? "active" : "" ?>" ><a  href="<?=$baseurl?>ParentMessages"><i class="fa fa-phone" aria-hidden="true"></i><span><?= $lbl1222 ?> (<?= $countprmsg ?>)</span></a></li>
		                    <?php } ?>
		                    <li class="<?=$controllerName == "parentdashboard" ? "active" : "canteenpin" ?>"><a href="<?=$baseurl?>parentdashboard/canteenpin"><i class="fa fa-key"></i><span><?= $lbl2329 ?></span></a></li>
                            
		                    <li  class="<?=$controllerName == "Canteenfund" ? "active" : "" ?>" ><a  href="<?=$baseurl?>canteenfund"><i class="fa fa-money" aria-hidden="true"></i><span><?= $lbl2330 ?></span></a></li>
                            <li  class="<?=$controllerName == "Canteenreport" ? "active" : "parentreport" ?>" ><a  href="<?=$baseurl?>Canteenreport/parentreport"><i class="fa fa-file" aria-hidden="true"></i><span><?= $lbl2331 ?></span></a></li>
                            <li  class="<?=$controllerName == "ParentQrIdcard" ? "active" : "ParentQrIdcard" ?>" ><a  href="<?=$baseurl?>ParentQrIdcard"><i class="fa fa-id-card-o" aria-hidden="true"></i><span><?= $lbl2401 ?></span></a></li>
                            
                            <li  class="<?=$controllerName == "ParentPayFee" ? "active" : "" ?>" ><a href="#"><i class="fa fa-usd"></i><span><?= $lbl1217 ?></span></a></li> 
                            
                            <?php if($parntnoti_count != 0) { ?>
		                    <li  class="<?=$controllerName == "ParentNotification" ? "active" : "" ?>" ><a href="<?=$baseurl?>ParentNotification" class="hoverbutton pulse-button"  style="color: #333 !important;" id="parentannouncements" id="parentannouncements"><i class="fa fa-bullhorn" aria-hidden="true"></i><span id="parannouncemnts"><?= $lbl21 ?> (<?= $parntnoti_count ?>)</span></a></li>
		                    <?php } else { ?>
		                    <li  class="<?=$controllerName == "ParentNotification" ? "active" : "" ?>" ><a href="<?=$baseurl?>ParentNotification" id="parentannouncements"><i class="fa fa-bullhorn" aria-hidden="true"></i><span id="parannouncemnts"><?= $lbl21 ?> (<?= $parntnoti_count ?>)</span></a></li>
		                    <?php } ?>
		                    <li class="<?=$controllerName == "Parentdairy" ? "active" : "" ?>"><a href="<?=$baseurl?>Parentdairy"><i class="fa fa-file"></i><?= $labl2144 ?></a></li>
		                    <li class="<?=$controllerName == "Parentreport" ? "active" : "" ?>"><a href="<?=$baseurl?>Parentreport"><i class="fa fa-file"></i><span>Report Card</span></a></li>
                            <li class="<?=$controllerName == "Parentmarketplace" ? "active" : "" ?>"><a href="<?=$baseurl?>Parentmarketplace"><i class="fa fa-shopping-bag"></i><span><?= $lbl1213 ?></span></a></li>
		                </ul>
                    </nav>
                </div>
                   
            <?php }
            
            elseif(!empty($cvndr_details[0]))
            { 
            ?> 
                <div class="tab-pane animated fadeIn <?=(in_array($controllerName , [ 'Cvendordashboard', 'Orderfood' ] )  ? "active" : ""  )?>" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu"  style="padding-bottom:45px !important;">
                            <li class="<?= ($controllerName == "Cvendordashboard" && $actionName == "index") ? "active" : "" ?>"><a href="<?=$baseurl?>Cvendordashboard"><i class="icon-speedometer"></i><span><?= $lbl588 ?></span></a></li>
                            <li class="<?=$actionName == "foodlist" ? "active" : "" ?>"><a href="<?=$baseurl?>Cvendordashboard/foodlist"><i class="fa fa-cutlery"></i><?= $lbl2371 ?></a></li>
                            <li class="<?=$actionName == "foodquantity" ? "active" : "" ?>"><a href="<?=$baseurl?>Cvendordashboard/foodquantity"><i class="fa fa-cutlery"></i><?= $lbl2372 ?></a></li>
                            <li class="<?=$controllerName == "Orderfood" ? "active" : "" ?>"><a href="<?=$baseurl?>Orderfood"><i class="fa fa-cutlery"></i><?= $lbl2373 ?></a></li>
                            <!--<li class="<?=$actionName == "schoollist" ? "active" : "" ?>"><a href="<?=$baseurl?>Cvendordashboard/schoollist"><i class="fa fa-university"></i>School Info</a></li>-->
                        </ul>
                    </nav>
                </div>
                   
            <?php }
            ?>  
            </div>    

        </div>
    </div>
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
			            <?php 
			             if($mainpage=="KnowledgeCenter") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl593 ?></h2>
            			<?php }
            			elseif($mainpage=="Subadmin") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl603 ?></h2>
            			<?php }
            			elseif($mainpage=="Meet") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl886 ?></h2>
            			<?php }
            			elseif($mainpage=="Canteenvendors") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2198 ?></h2>
            			<?php }
            			elseif($mainpage=="Parentdairy") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $labl2144 ?></h2>
            			<?php }  
            			elseif($mainpage=="Orderfood") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl2390 ?></h2>
            			<?php }
            			elseif($mainpage=="Parentmarketplace") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl1213 ?></h2>
            			<?php }
            			elseif($mainpage=="Canteenimage") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl2233 ?></h2>
            			<?php } 
            			elseif($mainpage=="Canteenreport") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl2243 ?></h2>
            			<?php }
            			elseif($mainpage=="Vendorfoodrpt") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl2242 ?></h2>
            			<?php }
            			elseif($mainpage=="Canteenfund") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl2330 ?></h2>
            			<?php }
            			elseif($mainpage=="Parentreport") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Report Card</h2>
			                 <!--bjbj <input type="submit" value="Submit" id="submit" name="submit">-->
            			<?php }
            			elseif($mainpage=="Parentknowledge") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl593 ?></h2>
            			<?php }
            			elseif($mainpage=="LibraryAccessReport") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl1581 ?></h2>
            			<?php }
            			elseif($mainpage=="Dashboard") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Dashboard</h2>
            			<?php }
            			elseif($mainpage=="MarketQueries") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl873 ?></h2>
            			<?php } 
            			elseif($mainpage=="Dealers") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl804 ?></h2>
            			<?php }
            			elseif($mainpage=="Products") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl840 ?></h2>
            			<?php }
            			elseif($mainpage=="Queries") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl775 ?></h2>
            			<?php }
            			elseif($mainpage=="Summary") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl721 ?></h2>
            			<?php }
            			elseif($mainpage=="ParentMessages") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl1222 ?></h2>
            			<?php }
            			elseif($mainpage=="Schools") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl635 ?></h2>
            			<?php }
            			elseif($mainpage=="SchoolMeetingReport") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a><?= $lbl1417 ?></h2>
            			<?php }
            			else { ?>
                            <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?=$mainpage?></h2>
			            <?php }?>
			        </div>   
			        <?php if(!empty($parent_details[0])) {  //print_r($parent_details[0]);
			                if($subpage == "Parentreport / Editreport Parentreport") {
			                    $studid = $_GET['studentid'];
			                    $sclid = $parent_details[0]['student']['school_id'];
			                    $sessionid = $parent_details[0]['student']['session_id'];
    			                $clsid = $_GET['classid']; 
    			                
    			                $hostname = "localhost";
                                $username = "youmeglo_globaluser";
                                $password = "DFmp)9_p%Kql";
                                $database = "youmeglo_globalweb";
                                $con = mysqli_connect($hostname, $username, $password, $database); 
                                if(mysqli_connect_error($con)){ echo "Connection Error."; die(); }
                                $getdata = mysqli_query($con, "SELECT * FROM `parentsignature_report` WHERE session_id = '".$sessionid."' AND student_id = '".$studid."' AND class_id = '".$clsid."'");
                    					
    			                ?>
<style>
    .kbw-signature { width: 300px; height: 100px;}
    #defaultSignature canvas{
        width: 100% !important;
        height: auto;
    }
</style>
                            <?php while($data = mysqli_fetch_assoc($getdata)) { 
                                $now = time();
                                $publish_date =date('Y-m-d', $data['school_publish_date']);
                                $NewDate = strtotime($publish_date . " +15 days");
                                $diff = $NewDate-$now;
                                if($data['signature'] == "" && $diff > 0) { ?>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                    <?php echo $this->Form->create(false , ['url' => ['action' => 'submitsign'] , 'id' => "submitsignreport" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                			        
                			            <div class="mt-2 col-md-12 row clearfix">
                    	                    <label class="pr-2"><?= "E-Signature" ?>*
                    	                    <br/>
                    	                    Report Card Publish date => <?= date("d-m-Y", $data['school_publish_date']);?>
                    	                    <br/>
                    	                    Expire date of report card => <?= date("d-m-Y", $NewDate);?>
                    	                    </label>
                    	                    <div id="defaultSignature" class="kbw-signature m-2"></div>
                    	                    <textarea id="sigpad" name="signature_image" style="display: none"></textarea>
                                            <p style="clear: both;">
                                            	<button class="btn btn-primary" id="removeSignature">Clear Signature</button> 
                                            </p>
                                            <input type="hidden" name="signid" value="<?= $data['id'] ?>" />
                                            <div class="col-sm-1" style="flex: 0 0 19%; max-width: 19%;">
                    	                        <button type="submit" class="btn btn-primary submtsignbtn" id="submtsignbtn">Upload Signature</button>
                    	                    </div>
                	                    </div>
                	                    
                	                <?php echo $this->Form->end(); ?>
                			        </div>
        			            <?php } } ?>
        			            <div class="col-sm-12 clearfix">
            			            <div id="signerror" class="error"></div>
            			            <div id="signsuccess" class="success"></div>
        			            </div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$baseurl?>digitalsign/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?=$baseurl?>digitalsign/js/jquery.signature.min.js"></script>
<script>
    
var signature = $('#defaultSignature').signature({syncField: '#sigpad', syncFormat: 'PNG'}); 
 
$('#removeSignature').click(function(e) {
    e.preventDefault();
    signature.signature('clear');
    $("#sigpad").val('');
});
</script>  
			        <?php } } ?>
                </div>
            </div>


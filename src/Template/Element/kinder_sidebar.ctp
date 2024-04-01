<?php foreach($lang_label as $langlbl) { 
if($langlbl['id'] == '1219') { $lbl1219 = $langlbl['title'] ; } 
if($langlbl['id'] == '23') { $lbl23 = $langlbl['title'] ; } 
if($langlbl['id'] == '2133') { $lbl2133 = $langlbl['title'] ; } 
if($langlbl['id'] == '1495') { $labl1495 = $langlbl['title'] ; }
if($langlbl['id'] == '1824') { $labl1824 = $langlbl['title'] ; }

}

?>
<div id="left-sidebar" class="sidebar">
    <div class="sidebar-scroll">
        <!-- Nav tabs -->
        <!-- Tab panes -->
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane animated fadeIn <?=(in_array($controllerName , ['Kinderdashboard', 'Canteen', 'KinderApplications', 'Meetings', 'KinderTimetable', 'KinderAnnouncement', 'KinderLibrary', 'KinderMessages', 'Meetingskinder' , 'Alphabets' , 'Kinderknowledge' ,'KinderGallery', 'Allactivities'] )  ? "active" : ""  )?>" id="hr_menu">
                <nav class="sidebar-nav">
                    <ul class="main-menu metismenu" style="padding-bottom:35px !important;">
                        <li class="<?=$controllerName == "Kinderdashboard" ? "active" : "" ?>"><a href="<?= $baseurl ?>Kinderdashboard"><i class="icon-speedometer"></i><span class="notranslate">Dashboard</span></a>
                        <?php if($_SESSION['parent_id'] != '') { ?>
                        <li class="<?=$controllerName == "Kinderknowledge" ? "active" : "" ?>"><a href="<?= $baseurl ?>Kinderknowledge"><i class="fa fa-info"></i><span>You-Me Academy</span></a></li>
                        <?php } ?>
                        <li><a href="http://learn.eltngl.com/" target="_blank"><i class="fa fa-globe"></i>National Geographic</a></li>
                        <?php if($_SESSION['parent_id'] != '')
                        {
                            if($studntnoti_count != 0) { ?>
                                <li  class="<?=$controllerName == "KinderAnnouncement" ? "active" : "" ?>" ><a href="<?=$baseurl?>KinderAnnouncement" id="studentannouncements"  class="hoverbutton pulse-button"  style="color: #333 !important;"><i class="fa fa-bullhorn" aria-hidden="true"></i><span id="studannouncemnts"><?= $lbl1219 ?> (<?= $studntnoti_count ?>)</span></a></li>
                            <?php  } else { ?>
                               	<li  class="<?=$controllerName == "KinderAnnouncement" ? "active" : "" ?>" ><a href="<?=$baseurl?>KinderAnnouncement" id="studentannouncements"><i class="fa fa-bullhorn" aria-hidden="true"></i><span id="studannouncemnts"><?= $lbl1219 ?> (<?= $studntnoti_count ?>)</span></a></li>
                        	<?php } ?>
                    	 
                        <li class="<?=$controllerName == "KinderMessages" ? "active" : "" ?>"><a href="<?= $baseurl ?>KinderMessages"><i class="fa fa-envelope"></i><span>Contact School Office</span></a></li>
                        <?php } ?>
                        <li class="<?=$controllerName == "KinderGallery" ? "active" : "" ?>"><a href="<?= $baseurl ?>KinderGallery"><i class="fa fa-image"></i><span><?= $lbl2133 ?></span></a></li>
                        <?php if($libaccess == 1) { ?>
                        <li class="<?=$controllerName == "KinderLibrary" ? "active" : "" ?>"><a href="<?= $baseurl ?>KinderLibrary"><i class="fa fa-book"></i><span><?= $lbl23 ?></span></a></li>
                        <?php } 
                        if($_SESSION['parent_id'] != '')
                        {
                        ?>
                        <li class="<?=$controllerName == "kinderTimetable" ? "active" : "" ?>"><a href="<?=$baseurl?>kinderTimetable"><i class="fa fa-calendar"></i><?= $labl1495 ?></a></li>
                        <?php } ?>
                        <li  class="<?=$controllerName == "Meetings" ? "active" : "" ?>" ><a  href="<?=$baseurl?>Meetings"><i class="fa  fa-link" aria-hidden="true"></i><span>You-Me Live</span></a></li>
    						
                        <li class="<?=$controllerName == "KinderApplications" ? "active" : "" ?>"><a href="<?= $baseurl ?>KinderApplications"><i class="fa fa-tasks"></i><span><?= $labl1824 ?></span></a></li>
                        
                    </ul>
                </nav>
            </div>
        </div> 
        <div>
            <img src="<?= $baseurl ?>img/sidebar_gif.gif" width="220px"/>
        </div>
    </div>
</div>
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
			            <?php 
			             if($mainpage=="Kinderknowledge") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>You-Me Academy</h2>
            			<?php }
            			
            			elseif($mainpage=="kinderdashboard") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Kinder Garten dashboard</h2>
            			<?php }
            			elseif($mainpage=="Subadmin") { ?>  
			                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Admin</h2>
            			<?php }
            			elseif($mainpage=="KinderLibrary") { ?>
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?= $lbl23 ?></h2>
			        <?php
			        } ?>
			        </div>   
                </div>
            </div>


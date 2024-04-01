<style>
.nav-tabs .nav-item {
    margin-bottom: -1px;
    text-align: center;
    width: 125px;
    color: #242e3b;
    text-transform: capitalize;
}
.nav-tabs .nav-item a i {
    padding-left: 5px;
    color: #ffa812;
}

.nav-tabs .nav-item a , .nav-tabs .nav-item a:hover{
    font-size: 14px;
}

.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    border-color: #242e3b #242e3b #fff;
}

.nav-tabs
{
    border-bottom: 1px solid #242e3b;
}



.icon-description
{
    color:#242e3b;
    font-size:14px;
    font-family: unset;
    text-align:justify;
}}

.tab-content>.active {
    display: -webkit-inline-box;
} 
div#knowldge_layout
{
    margin-left: 0px;
    display: inline-block !important;
    opacity: 1;
    min-width:150px !important;
    min-height:80px !important;
    max-width: 175px !important;
}
#tab1 {max-width:945px !important;}
.tab-pane .fade .active .show .easyPaginateList
{
    display: inline-block !important;
}
.easyPaginateNav
{
    width: 945px;
    text-align: right !important;
    margin-top: 20px;
}
.easyPaginateNav a {padding:5px;}
.easyPaginateNav a.current {font-weight:bold;text-decoration:underline;}


#knowldge_layout:hover .edit, #knowldge_layout:hover .delete, #knowldge_layout:hover .view {
	display: inline-block;
}

#knowldge_layout:hover
{
    border: solid 1px #242e3b;
    -moz-box-shadow: 7px 7px 7px #242e3b;
    -webkit-box-shadow: 7px 7px 7px #242e3b;
        box-shadow: 7px 7px 7px #242e3b;
}

.edit {
	margin-top: 7px;	
	padding-right: 7px;
	position: absolute;
	right: 0;
	top: 0;
	display: none;
}

.delete {
	margin-top: 32px;	
	padding-right: 7px;
	position: absolute;
	right: 0;
	top: 5px;
	display: none;
}

.view {
	margin-top: 60px;	
	padding-right: 7px;
	position: absolute;
	right: 0;
	top: 7px;
	display: none;
}

a.edit {
	color: #6c757d;
}

a.delete {
	color: #ff0000;
}
a.view {
	color: #17a2b8;
}

fa-lg {
    font-size: 1.2em;
}


.col-container {
  display: table;
  width: 100%;
}
.col {
  display: table-cell;
  padding: 16px;
}
.col_img img{
    height:100%;
    width:100%;
    max-height:230px;
    min-height:230px;
    margin-bottom:20px;
}

@media screen and (min-width: 1080px) {
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    top: 5px;
    left: 15px;
    padding: 5px 16px;
}
}
@media screen and (max-width: 1079px) {
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    top: 5px;
    left: 15px;
    padding: 5px 16px;
}
}
#right_icon {
    list-style:none;
    position: absolute;
    right: 15px;
}
#right_icon li{
    background: #444;
    color: #fff;
    padding: 5px;
    border-top: 1px solid #fff;
}
.col_img button, .col_img a{
    background:none;
    color:#fff;
    box-shadow:none;
    border:none;
    text-align:center;
}
.col_img a{
    margin-left:5px;
}
.crop_preview {
	background:#e1e1e1;
	width:300px;
	padding:30px;
	height:300px;
	margin-top:30px
}
</style>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <h2 class="col-md-6  heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1730') { echo $langlbl['title'] ; } } ?> <?= $subject_details['subject_name'] ?>  > <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1725') { echo $langlbl['title'] ; } } ?></h2> 
				    <ul class="header-dropdown">
                        <input type="hidden" name="subid" id="subid" value="<?= $subjectid ?>">
                        <input type="hidden" name="clsid" id="clsid" value="<?= $classid ?>">
                        <!--<div class="col-md-6  align-right"><a href="javascript:void(0)" title="Back"  id="assbackbutton" class=" btn btn-primary" ><span class="notranslate">Back</span> </a></div>  -->
                        <li>
                            <a href="javascript:void(0)" title="Back"  id="assbackbutton" class=" btn btn-primary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?> </a>
                        </li>
                    </ul>
                </div>
            </div>
            <input type="hidden" name="stdid" id="stdid" value="<?= $studentid ?> "> 
            <div class="body">
                <div class="row viewstudyguidecontent" id="viewstudyguidecontent">
                    <?php foreach($content_details as $content){ 
                        $todaydt = time();
                        $startdate = strtotime($content['start_date']);
                        $enddate = strtotime($content['end_date']);
                        if($content->from_tab == 'exams'){
                            if(($todaydt >= $startdate) && ($todaydt <= $enddate)) { ?>
                                <div class="col-sm-2 col_img" style="height:285px !important">
                                    <!--<a href="<?=$baseurl?>studyguide/view/<?= $content['id'] ?>"  data-nopge="<?= $content['numpages'] ?>" data-dirname="<?= $content['dirname'] ?>" data-file="<?= $content['file_name'] ?>" title="View PDF" class="viewpdffile " >-->
                                    <a href="<?=$baseurl?>studyguide/view/<?= $content['id'] ?>" title="View PDF">
                                    <!--<a href="javascript:void(0)" data-file ="<?= ($content['file_name']) ?>" title="view" class="studyguide">-->
                                        <?php if($content->image != '' || $content->image != null) { ?>
                                            <img src ="<?= $baseurl ?>img/<?= $content->image ?>" ?>
                                        <?php } else { ?>
                                            <img src ="http://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                        <?php } ?>
                                        <div class="set_icon"><i class="fa fa-file-pdf-o"></i></div>
                                        
                                    </a>
                                    <p class="title" style="color:#000"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?>: <?= $content->title ?></p>
                                </div>
                            <?php 
                            }
                        }else{  if(($todaydt >= $startdate) && ($todaydt <= $enddate)) { ?>
                        <div class="col-sm-2 col_img">
                                <a href="<?=$baseurl?>studyguide/view/<?php echo $content['id'] ?>" class="viewknow"  style="color:#000">
                                <?php if(!empty($content->image )) { ?>
                                <img src ="<?= $baseurl ?>img/<?= $content->image ?>" >
                                <?php } else { ?>
                                <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                <?php } ?>
                                <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'word'){ ?><i class="fa fa-file-word-o"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                                </a><p class="title"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->title) ?></br>
                                <!--<b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->classname) ?></p>-->
                                </a>
                            </div>
                    <?php }} }?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div class="row clearfix">
    <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
</div>
    </div>
</div>
   
<div class="modal fade bd-example-modal-lg" id="viewpdffile" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">   
        <div class="modal-header header">
                <!--<h6 class="title" id="defaultModalLabel">Passcode</h6>-->
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-sm-12 text-center" style="margin: auto;height: 500px;overflow: scroll;" id="viewfile"></div>
	        </div>
        </div>
    </div>
</div>   
<!------------------ Pop up for status approval --------------------->

<div class="modal fade " id="studyguidepdf" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1725') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <div id="viewstudypdf"></div>
                        </div>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>    

<?php
foreach($lang_label as $langlbl) 
{ 
    if($langlbl['id'] == '1996')  {  $dwldfileacc = $langlbl['title'] ;  } 
     
}
?>
         
<script>

function popup()
{
    setTimeout(function(){ swal("<?php echo $dwldfileacc ?>"); }, 4000);
    //alert(".");
}
    function image_pdf(get_val){
        if(get_val == 'pdf'){
            $(".image_up").hide();
            $('.pdf_up').show();
            $("#pdf_up").attr('required', ''); 
            $("#image_up").removeAttr('required');
            $('#image_up').val('');
            
        }else if(get_val == 'image'){
            $(".pdf_up").hide();
            $('.image_up').show();
            $("#image_up").attr('required', ''); 
            $("#pdf_up").removeAttr('required');
            $('#pdf_up').val('');
            
        }
        
    }
</script>

            
    
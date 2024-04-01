<!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>-->
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
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    bottom:60px;
    left: 10px;
    padding: 5px 16px;
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
				    <h2 class="col-lg-6 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1497') { echo $langlbl['title'] ; } } ?> (<?= $sub_name ?> (<?= $cls_name ?>))</h2>
				    <ul class="header-dropdown">
                       
                        <input type="hidden" value="<?= $classid ?>" id="class" name="class" >
                        <input type="hidden" value="<?= $subjectid ?>" id="subject" name="subject" >
                        <li style="width:160px; padding:0px 10px">
                            <select class=" form-control community_filter" id="tut_filter" onchange="viewlibrary_filter(this.value)">
					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '754') { echo $langlbl['title'] ; } } ?></option>
					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '755') { echo $langlbl['title'] ; } } ?></option>
					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '756') { echo $langlbl['title'] ; } } ?></option>
					            <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '757') { echo $langlbl['title'] ; } } ?></option>
					        </select>
                        </li>
                        <li>
                            <a href="<?= $baseurl ?>ClassLibrary" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '752') { echo $langlbl['title'] ; } } ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="body">
                <div class="row viewtutcontent" id="viewtutcontent">
                    <?php foreach($content_details as $content){ ?>
                    
                    <div class="col-sm-2 col_img">
                    <a href="<?=$baseurl?>ClassLibrary/viewcontent/<?php echo md5($content['id']) ?>" title="view" >
                        <!--<ul id="right_icon">
                           <li><i class="fa fa-eye"></i></li>
                        </ul>-->
                        <?php if($content->image != '' || $content->image != null) { ?>
                            <img src ="<?= $baseurl ?>img/<?= $content->image ?>" ?>
                        <?php } else { ?>
                            <img src ="http://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                        <?php } ?>
                        <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'audio'){ ?><i class="fa fa-headphones"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                    </a>
                     <p class="title" style="color:#000"><b>Titre</b>: <?= ucfirst($content->title) ?></p>
                    </div>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


<!------------------ End --------------------->


<script>
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    


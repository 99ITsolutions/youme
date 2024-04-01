<?php
    $statusarray = array('Inactive','Active' );
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '2088') { $lbl2088 = $langlbl['title'] ; } 
    }
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="col-md-6 text-left heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '942') { echo $langlbl['title'] ; } } ?> >  <?= $class_details[0]['c_name'] ?> - <?= $class_details[0]['c_section'] ?> (<?= $class_details[0]['school_sections'] ?>) (<?= $subject_details[0]['subject_name'] ?>)   </h2>
                    <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>teacherdashboard" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
            </div>
            <input type="hidden" val="<?= $gid ?>" id="classid">
            <input type="hidden" val="<?= $sid ?>" id="subjectid">
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-3" id ="teacherclsstd">
                        <h5><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '508') { echo $langlbl['title'] ; } } ?></h5>   
                        <ul>
                            <?php
                            foreach($studentdetails as $stdntdtl):
                            ?>
                            <li><a href="javascript:void(0)" data-toggle="modal" class="studentdtldata" data-sub="<?= $sid ?>" data-cls="<?= $gid ?>" data-stdname="<?= $stdntdtl['l_name'].' '.$stdntdtl['f_name'] ?>" data-id="<?= $stdntdtl['id'] ?>"><?= $stdntdtl['l_name']. " " .$stdntdtl['f_name'] ?></a></li>
                            <?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash ">
                                    <div class="body" style="padding: 15px; !important;">
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF !important" href="<?= $baseurl ?>teacherPost?subid=<?= $sid ?>&gradeid=<?= $gid ?>"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1924') { echo $langlbl['title'] ; } } ?> </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body" style="padding: 15px; !important;">
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF" href="<?= $baseurl ?>classGrade?subid=<?= $sid ?>&gradeid=<?= $gid ?>">Grades</a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            <div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body" style="padding: 15px; !important;">
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF" href="<?= $baseurl ?>classAssessment?studentdetails=0&subid=<?= $sid ?>&gradeid=<?= $gid ?>"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1723') { echo $langlbl['title'] ; } } ?> </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body" style="padding: 15px; !important;">
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF" href="<?= $baseurl ?>classExams?studentdetails=0&subid=<?= $sid ?>&gradeid=<?= $gid ?>"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1244') { echo $langlbl['title'] ; } } ?> </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body" style="padding: 15px; !important;">
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF" href="<?= $baseurl ?>classQuiz?studentdetails=0&subid=<?= $sid ?>&gradeid=<?= $gid ?>"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1727') { echo $langlbl['title'] ; } } ?> </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body" style="padding: 15px; !important;">  
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF" href="<?=$baseurl?>teacherclass/classallSubjects?classid=<?=$gid?>&subid=<?=$sid?>"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1863') { echo $langlbl['title'] ; } } ?> </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            
                            <div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body" style="padding: 15px; !important;">  
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF" href="<?=$baseurl?>teacherclass/classallSubjects?classid=<?=$gid?>&subid=<?=$sid?>"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2129') { echo $langlbl['title'] ; } } ?> </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

 <!------------------ Student Data --------------------->

    
<div class="modal classmodal animated zoomIn bd-example-modal-lg" id="studentdata" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
		        <h6 class="title" id="defaultModalLabel"><span id="std_name"></span> (<?= $class_details[0]['c_name'] ?> - <?= $class_details[0]['c_section'] ?> (<?= $class_details[0]['school_sections'] ?>) (<?= $subject_details[0]['subject_name'] ?>)) </h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
               <div class="row clearfix container">
                    <div class="table-responsive">
                        <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="stdinfodata_table" data-page-length='50'>
                            <thead class="thead-dark">
                                <tr>
                                    <th><?= $lbl2088 ?></th>
                                </tr>
                            </thead>
                            <tbody id="stdinfobody" class="modalrecdel">
				            </tbody>
                        </table>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>              
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php
if(!empty($emp_details)){
    if(!empty($_GET))
    {
        if($_GET['studentdetails'] == 1)
        { 
            ?>
            <script>
                $("#studentdata").modal("show");
            </script>
            <?php
        }
        elseif($_GET['studentdetails'] == 0)
        {
            ?>
            <script>
                $("#studentdata").modal("hide");
            </script>
            <?php
        }
        
    }
} 
?>
<style>
    .badge { 
        position: relative;
        top: -12px !important;
        left: -3px !important;
        border: 1px solid;
        border-radius: 50%;
        background: #6c757d;
        color: #fff;
    }
    .bg-dash
    {
        max-height:65px !important;
    }
</style>`
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1796') { $lbl1796 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1797') { $lbl1797 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1799') { $lbl1799 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1046') { $lbl1046 = $langlbl['title'] ; }
    if($langlbl['id'] == '1018') { $yerlylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1019') { $hlfyrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1020') { $quatrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1021') { $mnthlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1800') { $weklylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2076') { $lbl2076 = $langlbl['title'] ; }
     
    if($langlbl['id'] == '1964') { $lbl1964 = $langlbl['title'] ; }
    if($langlbl['id'] == '76') { $lbl76 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '361') { $lbl361 = $langlbl['title'] ; }
    if($langlbl['id'] == '362') { $lbl362 = $langlbl['title'] ; }
    if($langlbl['id'] == '363') { $lbl363 = $langlbl['title'] ; }
    if($langlbl['id'] == '364') { $lbl364 = $langlbl['title'] ; }
    if($langlbl['id'] == '355') { $lbl355 = $langlbl['title'] ; }
    if($langlbl['id'] == '365') { $lbl365 = $langlbl['title'] ; }
    if($langlbl['id'] == '2082') { $lbl2082 = $langlbl['title'] ; }
    if($langlbl['id'] == '388') { $lbl388 = $langlbl['title'] ; }
    if($langlbl['id'] == '366') { $lbl366 = $langlbl['title'] ; }
    if($langlbl['id'] == '85') { $lbl85 = $langlbl['title'] ; }
    if($langlbl['id'] == '367') { $lbl367 = $langlbl['title'] ; }
    if($langlbl['id'] == '368') { $lbl368 = $langlbl['title'] ; }
    if($langlbl['id'] == '369') { $lbl369 = $langlbl['title'] ; }
    if($langlbl['id'] == '333') { $lbl333 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2086') { $lbl2086 = $langlbl['title'] ; }
    if($langlbl['id'] == '2087') { $lbl2087 = $langlbl['title'] ; }
    
    
} ?>

   
           <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1516') { echo $langlbl['title'] ; } } ?></h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem texamasstable" id="texamasstable" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <!--<th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>-->
                                            <th style="display:none">ID</th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '363') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?= $lbl2086 ?></th>
                                            <th><?= $lbl2087 ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '355') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '365') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?= $lbl1964  ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '366') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '85') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '367') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '333') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n=1;
                                    foreach($approvedetails as $value)
                                    {
                                      // echo $value['type'];
                                        if( $value['type'] == "Assessment" ) 
                                        { $etype = $asslbl; } 
                                        elseif( $value['type'] == "Quiz" ) 
                                        { $etype = $quizlbl; } 
                                        elseif( $value['type'] == "Exams" ) 
                                        { $etype =  $exmlbl; } 
                                        else
                                        {
                                            $etype = $studgulbl;
                                        }
                                       // echo $value['addquestion'];
                                        if($value['addquestion'] > 0 )
                                        {
                                        /*if( $value['status'] == 0)
                                        {
                                            $sts = '<a href="javascript:void()" data-url="examAssessment/approvestatus/'.$value['id'].'" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Exam/Assignment Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                                        }
                                        else 
                                        { 
                                            $sts = '<a href="javascript:void()" data-url="examAssessment/approvestatus/'.$value['id'].'" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Exam/Assignment Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                                        }*/
                                        
                                        if( $value['status'] == 0)
                                        {
                                            $sts = '<a href="javascript:void()"  class="btn btn-sm" title="Status" ><label class="switch"><input type="checkbox" disabled><span class="slider round"></span></label></a>';
                                        }
                                        else 
                                        { 
                                            $sts = '<a href="javascript:void()"  class="btn btn-sm" title="Status" ><label class="switch"><input type="checkbox" checked disabled><span class="slider round"></span></label></a>';
                                        }
                                                    //echo $etype;
                                        ?>
                                        <tr> 
                                            <!--<td class="width45">
                                            <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </td>-->
                                            <!--<td>
                                                <span ><?= $value['teacher_name'] ?></span>
                                            </td>-->
                                            <td style="display:none"><?= $value['id'] ?></td>
                                            <td>
                                                <span ><?= $etype ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['exam_type'] ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['exam_period'] ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['class_name'] ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['subject_name'] ?></span>
                                            </td>
                                            <td>
                                                <span><?= ucfirst($value['title']) ?> </span>
                                            </td>
                                            <td>
                                                <span><?= $value['max_marks'] ?></span>
                                            </td>
                                            <td>
                                                <?php if(!empty($value['file_name']))
                                                { ?>
                                                    <a href="webroot/img/<?= $value['file_name'] ?>" target="_blank"><span><?= ucfirst($value['file_name']) ?></span></a>
                                                <?php 
                                                } 
                                                else 
                                                { ?>
                                                    <a href="<?=$baseurl?>teacherexamAssessment/pdf/<?= $value['id'] ?>" target="_blank"><span><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1434') { echo $langlbl['title'] ; } } ?></span></a>
                                                <?php 
                                                } ?>
                                            </td>
                                            <td>
                                                <span><?= $sts ?></span>
                                            </td>
                                            <td>
                                                <span><?=date('d-m-Y', $value['created_date'])?></span>
                                            </td>
                                            <td>
                                                <span><?=date('d-m-Y H:i', strtotime($value['start_date']))?></span>
                                            </td>
                                            <td>
                                                <span><?=date('d-m-Y H:i', strtotime($value['end_date']))?></span>
                                            </td>
                                            
                                            <td>
                                                <?php
                                                $sd = strtotime($value['start_date']);
                                                $now = time();
                                                if($now <= $sd) {
                                                ?>
                                                <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary teditsubmitreq" data-toggle="modal"  data-target="#editsubmitreq" title="Edit"><i class="fa fa-edit"></i></button>
                                                <?php } ?>
                                                <button type="button" data-id="<?=$value['id']?>" data-url="teacherexamAssessment/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Exam/Assignment" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                        $n++;
                                        }
                                    }
                                    ?>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
                
            </div>

        
    <div>
            <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
    </div>               

   

<script>
    function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    

<div class="modal classmodal animated zoomIn" id="editsubmitreq" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1517') { echo $langlbl['title'] ; } } ?></h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'exmassedit'] , 'id' => "teditexamassform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                            <div class="error" id="editexamasserror"></div>
                            <div class="success" id="editexamasssuccess"></div>
                        </div>
                    <!--<div class="col-md-4">
                        <div class="form-group">     
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control class_s" id="eclass" name="eclass" placeholder="Choose Class" required onchange="getsubcls(this.value, this.id)">
                                <option value="">Choose Class</option>
                                <?php foreach($empcls_details as $empdtl) { ?>
        	                        <option  value="<?= $empdtl['class']['id'] ?>" ><?= $empdtl['class']['c_name']."-".$empdtl['class']['c_section'] ." (". $empdtl['class']['school_sections'].")" ?> </option>
                                <?php } ?>
                            </select> 
                        </div>
                    </div>-->
                    <div class="col-md-4">
                        <div class="form-group">     
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></label>
                            <!--<select class="form-control class_s" id="class" name="class" placeholder="Choose Class" required  onchange="getsubcls(this.value, this.id)">-->
                            <select class="form-control" id="s_listclass" name="grade" placeholder="Choose Class" disabled required onchange="getgradecls(this.value, 'edit')">
                                <option value="">Choose Class</option>
                                <?php foreach($empcls_details as $empdtl) 
                                {
        	                        $classkey = $empdtl['class']['c_name']."_". $empdtl['class']['school_sections']; ?>
        	                        <option  value="<?= $classkey ?>" ><?= $empdtl['class']['c_name']." (". $empdtl['class']['school_sections'].")" ?> </option>
                                <?php } ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">     
                            <label>Section(s)</label>
                            <!--<select class="form-control class_s" id="class" name="class[]" multiple placeholder="Choose Class" required  onchange="getsubcls(this.value, this.id)">-->
                            <select class="form-control class_s" id="examselclssctn" name="eclass[]" multiple placeholder="Choose Class" required >
                                <option value="">Choose sections</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control" name="esubjects" id="esubjects" placeholder="Choose Subjects" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '363') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control request_opt" name="erequest_for" id="erequest_for" placeholder="Choose Options" onchange="egetexamtype(this.value, 'direct')" required>
                                <option value="">Choose Option</option>
                                <option value="Assessment">Assignment</option>
                                <option value="Exams">Exams</option>
                                <option value="Quiz">Quiz</option>
                                <option value="Study Guide">Study Guide</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="eexamtypes" style="display:none;">
                        <div class="form-group">  
                            <label>Semestre/Trimestre</label>
                            <select class="form-control request_opt" name="eexam_type" id="eexam_type" placeholder="Choose Options" onchange="egetperiod(this.value, this.id)">
                                <option value="">Choose Option</option>
                                <!--<option value="Premier Trimestre">Premier Trimestre</option>
                                <option value="Deuxieme Trimestre">Deuxieme Trimestre</option>
                                <option value="Troisieme Trimestre">Troisieme Trimestre</option>-->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="eexamperiod" style="display:none;">
                        <div class="form-group">  
                            <label><?= $lbl2082 ?></label>
                            <select class="form-control request_opt" name="eexam_period" id="eexam_period" placeholder="Choose Options">
                                <option value="">Choose Option</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?></label>
                            <!--<input type="date" class="form-control" name="start_date" id="start_date" required>-->
                            <!--<input type="text" class="form-control datetimepicker" id="datetimepicker" data-date-format="dd-mm-yyyy" name="doj"  required placeholder="Start Date & Time *">-->
                            <div class="input-group date" id="edatetimepicker1" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#edatetimepicker1"  name="estart_date" id="estart_date" required/>
                              <div class="input-group-append" data-target="#edatetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?></label>
                           <!-- <input type="date" class="form-control" name="end_date" id="end_date" required>-->
                            <div class="input-group date" id="edatetimepicker2" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#edatetimepicker2" name="eend_date" id="eend_date" required>
                              <div class="input-group-append" data-target="#edatetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="maxmarks">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '371') { echo $langlbl['title'] ; } } ?></label>
                            <input type="number" name="emax_marks" id="max_marks" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '372') { echo $langlbl['title'] ; } } ?>"  class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-12" style="display:block;">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></label>
                            <input type="text" name="etitle" id="etitle" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?>"  class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12" id="guideinstr">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '373') { echo $langlbl['title'] ; } } ?></label>
                            <textarea name="einstruction" id="einstruction" placeholder="Enter Instruction"   class="form-control"  rows="3" required> </textarea>
                        </div>
                    </div>
                    <!--<div class="col-md-12">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></label>
                            <input type="hidden" name="etitle" id="etitle" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?>"  class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '373') { echo $langlbl['title'] ; } } ?></label>
                            <textarea name="einstruction" id="einstruction" placeholder="Enter Instruction"   class="form-control"  rows="3" required> </textarea>
                        </div>
                    </div>-->
                    
                        <input type="hidden" name="exam_assid" id="exam_assid" >
                    <div class="col-md-12"  id="econtentuplod" >
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '374') { echo $langlbl['title'] ; } } ?> *</label>
                                <select class="form-control" required  name="econtentupload" id="econtentupload" onchange="econtntupd(this.value)">
                                    <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '375') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '376') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="custom"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '377') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="epdfupload" style="display:none">
                        <div class="col-md-12">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?></label> <p id="fileName"></p>
                                <input type="file" name="efileupload" id="efileupload" placeholder="File Upload"  class="form-control" >
                                <input type="hidden" name="pre_file" id="pre_file" >
                            </div>
                        </div>
                        
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary editexamassbtn" id="editexamassbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                        </div>
                    </div>
                    <div class="row clearfix" id="ecustomize" style="display:none">
                        <div class="col-md-12" id="eexamformat" style="display:none">
                            <div class="form-group">   
                                <label>View Exam Format</label>
                                <div class="form-group">      
                                    <div class="row container">
                                        <span class="mr-2"><input type="radio" id="editable" name="exam_format" checked value="custom" class="mr-1"> Editable Exam</span>
                                        <span class="mr-2"><input type="radio" id="pdfex" name="exam_format" value="pdf" class="mr-1"> Download in Pdf</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary editexamassbtn" id="editexamassbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '354') { echo $langlbl['title'] ; } } ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                        </div>
                    </div>
                   <?php echo $this->Form->end(); ?>
                </div>
                    
                   
            </div>
             
        </div>
    </div>
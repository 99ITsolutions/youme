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
</style>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '1018') { $yerlylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1019') { $hlfyrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1020') { $quatrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1021') { $mnthlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1800') { $weklylbl = $langlbl['title'] ; } 
    
    
} 
$allocated_marks =[];
foreach($questiondetails as $value)
{
    $allocated_marks[] = $value['marks'];
}
$marks_allocated = array_sum($allocated_marks);
$maximum_marks = $datadetails[0]['max_marks'];

$marks_left = $maximum_marks - $marks_allocated;


if( $datadetails[0]['exam_type'] == "Quarterly" ) { 
    $typexm = $quatrlbl; 
}
elseif( $datadetails[0]['exam_type'] == "Monthly" ) { 
    $typexm = $mnthlbl; 
}
elseif( $datadetails[0]['exam_type'] == "Weekly" ) { 
    $typexm = $weklylbl; 
} 
elseif( $datadetails[0]['exam_type'] == "Yearly" ) { 
    $typexm = $yerlylbl; 
}
else { 
    $typexm =  $hlfyrlbl ;
} 

if( $datadetails[0]['type'] == "Exams" ) { 
    $exmtyp = $exmlbl; 
}
elseif( $datadetails[0]['type'] == "Quiz" ) { 
    $exmtyp = $quizlbl; 
}
elseif( $datadetails[0]['type'] == "Assessment" ) { 
    $exmtyp = $asslbl; 
}
else { 
    $exmtyp =  $studgulbl ;
} 
?>
   
           <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <h3 class="col-md-9  align-left ">
                                    <div class="col-sm-4" style="float:left">
                                        <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?>: </b><?= $classdtl[0]['c_name']." - ".$classdtl[0]['c_section'] ?> </p>
                                        <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '371') { echo $langlbl['title'] ; } } ?>: </b><?= $datadetails[0]['max_marks'] ?></p>
                                        <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1512') { echo $langlbl['title'] ; } } ?>: </b><span id="allo"><?= $marks_allocated ?> </span></p>
                                        <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1513') { echo $langlbl['title'] ; } } ?>: </b><?= $exmtyp ?> </p>
                                    </div>
                                    <div class="col-md-1"style="float:left"> </div>
                                    
                                    <div class="col-sm-7" style="float:left">
                                        <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?>: </b><?= ucwords($datadetails[0]['title'])  ?> </p>
                                        <p><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?>: </b><?= $subjectdtl[0]['subject_name']?> ( <?= $typexm ?> ) </p>
                                        <p style="text-align:justify;"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '373') { echo $langlbl['title'] ; } } ?>: </b><?= $datadetails[0]['special_instruction']?> </p> 
                                    </div>
                                </h3>
                                <div class="col-md-3 align-right">
                                    <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#cutsomize_exam"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1511') { echo $langlbl['title'] ; } } ?></a>
                                    <a href="<?= $baseurl ?>examAssessment?examid=<?= $examid ?>&examAssmodal=1" title="Back" class="btn btn-info" id="examAss_modal" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="exmId" id="exmId" value="<?= $examid ?>">
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem quest_table" id="quest_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1509') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1510') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1246') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1200') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '72') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="questnbody" class="modalrecdel">
                                    <?php
                                    $n=1;
                                    foreach($questiondetails as $value)
                                    {
                                        ?>
                                        <tr> 
                                            
                                            <td>
                                                <span ><?= substr($value['question'], 0, 40); ?>...</span>
                                            </td>
                                            <td>
                                                <span ><?= $value['ques_type'] ?></span>
                                            </td>
                                            <td>
                                                <span ><?= $value['marks'] ?></span>
                                            </td>
                                            <td>
                                                <span><?=date('d-m-Y', $value['created_date'])?></span>
                                            </td>
                                            <td>
                                                <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary ecutsomize_exam" data-toggle="modal"  data-target="#ecutsomize_exam" title="Edit"><i class="fa fa-edit"></i></button>
                                                
                                                <button type="button" data-id="<?=$value['id']?>" data-url="../../examAssessment/delete_question" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Question" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                        $n++;
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




 <!------------------Add Question --------------------->

    
<div class="modal classmodal animated zoomIn" id="cutsomize_exam" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1511') { echo $langlbl['title'] ; } } ?>  (<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1512') { echo $langlbl['title'] ; } } ?> : <span id="marksAllocated"><?= $marks_allocated ?>/<?= $maximum_marks ?></span>)</h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'addcutsomizeexm'] , 'id' => "addcutsomizeexmform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row clearfix">
                    <!--<div class="wrapper" style="width:100% !important">
                        <div class="input-box row container">-->
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1509') { echo $langlbl['title'] ; } } ?> </label>
                                    <input type="text" name="question" id="question" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1808') { echo $langlbl['title'] ; } } ?>"  class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1246') { echo $langlbl['title'] ; } } ?> </label>
                                    <input type="number" name="marks" id="marks" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1809') { echo $langlbl['title'] ; } } ?>"  class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1510') { echo $langlbl['title'] ; } } ?> </label>
                                    <select name="optionques" id="optionques" class="form-control" required  onchange="optionobjective(this.value)">
                                        <option value="subjective">Subjective</option>
                                        <option value="objective">Objective</option>
                                    </select>
                                </div>
                            </div>
                           <!-- <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>-->
                            
                            <div class="col-md-12" id="quesVal" style="display:none;">
                                <div class="form-group">  
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2184') { echo $langlbl['title'] ; } } ?></label>
                                    <select name="valueques[]" id="valueques" class="form-control js-example-tokenizer" multiple="multple">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" id="maxwords">
                                <div class="form-group">  
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1810') { echo $langlbl['title'] ; } } ?></label>
                                    <input type="number" name="maxwords" id="maxwords" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1811') { echo $langlbl['title'] ; } } ?>"  class="form-control">
                                </div>
                            </div>
                            
                        <!--</div>
                    </div>-->
                
                    <input type="hidden" name="submitId" id="submitId" value="<?= $examid?>">
                    <div class="col-md-12">
                        <div class="error" id="examcustmerror"></div>
                        <div class="success" id="examcustmsuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary exmcustmbtn" id="exmcustmbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1515') { echo $langlbl['title'] ; } } ?> </button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1209') { echo $langlbl['title'] ; } } ?> </button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>


 <!------------------Edit Question --------------------->

    
<div class="modal classmodal animated zoomIn" id="ecutsomize_exam" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1514') { echo $langlbl['title'] ; } } ?>  (<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1512') { echo $langlbl['title'] ; } } ?> : <span id="emarksAllocated"><?= $marks_allocated ?></span>/<?= $maximum_marks ?>)</h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'edcustmque'] , 'id' => "edcustmqueform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row clearfix">
                    <!--<div class="wrapper" style="width:100% !important">
                        <div class="input-box row container">-->
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1509') { echo $langlbl['title'] ; } } ?> </label>
                                    <input type="text" name="equestion" id="equestion" placeholder="Enter Question"  class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1246') { echo $langlbl['title'] ; } } ?> </label>
                                    <input type="number" name="emarks" id="emarks" placeholder="Enter Marks"  class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1510') { echo $langlbl['title'] ; } } ?> </label>
                                    <select name="eoptionques" id="eoptionques" class="form-control" required  onchange="eoptionobjective(this.value)">
                                        <option value="subjective">Subjective</option>
                                        <option value="objective">Objective</option>
                                    </select>
                                </div>
                            </div>
                           <!-- <button class="col-sm-1 btn add-btn"><i class="fa fa-plus"></i></button>-->
                            
                            <div class="col-md-12" id="equesVal" style="display:none;">
                                <div class="form-group">  
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2184') { echo $langlbl['title'] ; } } ?></label>
                                    <select name="evalueques[]" id="evalueques" class="form-control js-example-tokenizer" multiple="multple">
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-12" id="emaxwords">
                                <div class="form-group">  
                                    <label>Max Words</label>
                                    <input type="number" name="emaxwords" id="emax_words" placeholder="Enter max words"  class="form-control">
                                </div>
                            </div>
                            
                        <!--</div>
                    </div>-->
                
                    <input type="hidden" name="examid" id="examid">
                    <input type="hidden" name="queid" id="queid">
                    <div class="col-md-12">
                        <div class="error" id="edcustmqueerror"></div>
                        <div class="success" id="edcustmquesuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary edcustmquebtn" id="edcustmquebtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?> </button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1209') { echo $langlbl['title'] ; } } ?> </button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>



</div>        



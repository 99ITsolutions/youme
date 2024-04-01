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
<?php
$allocated_marks =[];
foreach($questiondetails as $value)
{
    $allocated_marks[] = $value['marks'];
}
$marks_allocated = array_sum($allocated_marks);
$maximum_marks = $datadetails[0]['max_marks'];

$marks_left = $maximum_marks - $marks_allocated;

foreach($lang_label as $langlbl) {
    if($langlbl['id'] == '1029') { $lbl1029 = $langlbl['title'] ; }
    if($langlbl['id'] == '371') { $lbl371 =  $langlbl['title'] ; }
    if($langlbl['id'] == '1512') { $lbl1512 = $langlbl['title'] ; }
    if($langlbl['id'] == '1513') { $lbl1513 = $langlbl['title'] ; }
    if($langlbl['id'] == '1514') { $lbl1514 = $langlbl['title'] ; }
    if($langlbl['id'] == '388') { $lbl388 = $langlbl['title'] ; }
    if($langlbl['id'] == '1030') { $lbl1030 = $langlbl['title'] ; }
    if($langlbl['id'] == '373') { $lbl373 = $langlbl['title'] ; }
    if($langlbl['id'] == '1511') { $lbl1511 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '1509') { $lbl1509 = $langlbl['title'] ; }
    if($langlbl['id'] == '1510') { $lbl1510 =  $langlbl['title'] ; }
    if($langlbl['id'] == '1246') { $lbl1246 = $langlbl['title'] ; }
    if($langlbl['id'] == '1200') { $lbl1200 = $langlbl['title'] ; }
    if($langlbl['id'] == '1209') { $lbl1209 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '1412') { $lbl1412 = $langlbl['title'] ; }
    if($langlbl['id'] == '72') { $lbl72 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '1808') { $lbl1808 = $langlbl['title'] ; }
    if($langlbl['id'] == '1809') { $lbl1809 = $langlbl['title'] ; }
    if($langlbl['id'] == '1810') { $lbl1810 = $langlbl['title'] ; }
    if($langlbl['id'] == '1811') { $lbl1811 = $langlbl['title'] ; }
    if($langlbl['id'] == '1515') { $lbl1515 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '1798') { $lbl1798 = $langlbl['title'] ; }
    if($langlbl['id'] == '1742') { $lbl1742 = $langlbl['title'] ; }
    if($langlbl['id'] == '1724') { $lbl1724 = $langlbl['title'] ; }
}

if($datadetails[0]['type'] == "Assessment")
{
    $datatype = $lbl1742;
}
elseif($datadetails[0]['type'] == "Quiz")
{
    $datatype = $lbl1798;
}
elseif($datadetails[0]['type'] == "Exams")
{
    $datatype = $lbl1724;
}
?>
   
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h3 class="col-md-9  align-left ">
                        <div class="col-sm-4" style="float:left">
                            <p><b><?= $lbl1029 ?>: </b><?= $classdtl[0]['c_name']." - ".$classdtl[0]['c_section'] ?> </p>
                            <p><b><?= $lbl371 ?>: </b><?= $datadetails[0]['max_marks'] ?></p>
                            <p><b><?= $lbl1512 ?>: </b><span id="allo"><?= $marks_allocated ?> </span></p>
                            <p><b><?= $lbl1513 ?>: </b><?= $datatype ?> </p>
                        </div>
                        <div class="col-md-1"style="float:left"> </div>
                        <div class="col-sm-7" style="float:left">
                            <p><b><?= $lbl388 ?>: </b><?= ucwords($datadetails[0]['title'])  ?> </p>
                            <p><b><?= $lbl1030 ?>: </b><?= $subjectdtl[0]['subject_name']?> ( <?= $datadetails[0]['exam_type'] ?> ) </p>
                            <p style="text-align:justify;"><b><?= $lbl373 ?>: </b><?= $datadetails[0]['special_instruction']?> </p> 
                        </div>
                    </h3>
                    <div class="col-md-3 align-right">
                        <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#cutsomize_exam"><?= $lbl1511 ?></a>
                        <a href="<?= $baseurl ?>teacherexamAssessment" title="Back" class="btn btn-info" id="examAss_modal" ><?= $lbl41 ?></a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="exmId" id="exmId" value="<?= $ids ?>">
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem quest_table" id="quest_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th><?= $lbl1509 ?></th>
                                <th><?= $lbl1510 ?></th>
                                <th><?= $lbl1246 ?></th>
                                <th><?= $lbl1200 ?></th>
                                <th><?= $lbl72 ?></th>
                            </tr>
                        </thead>
                        <tbody id="questnbody" class="modalrecdel">
                            <?php
                            $n=1;
                            foreach($questiondetails as $value) {  ?>
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
        
<div><?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?></div>               
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
                <h6 class="title" id="defaultModalLabel"><?= $lbl1511 ?> (<?= $lbl1512 ?>: <span id="marksAllocated"><?= $marks_allocated ?>/<?= $maximum_marks ?></span>)</h6>
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
                                    <label><?= $lbl1509 ?></label>
                                    <input type="text" name="question" id="question" placeholder="<?= $lbl1808 ?>"  class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?= $lbl1246 ?></label>
                                    <input type="number" name="marks" id="marks" placeholder="<?= $lbl1809 ?>"  class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?= $lbl1510 ?></label>
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
                                    <label><?= $lbl1811 ?></label>
                                    <input type="number" name="maxwords" id="maxwords" placeholder="<?= $lbl1810 ?>"  class="form-control">
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
                        <button type="submit" class="btn btn-primary exmcustmbtn" id="exmcustmbtn"><?= $lbl1515 ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $lbl1209 ?></button>
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
                <h6 class="title" id="defaultModalLabel"><?= $lbl1514 ?>  (<?= $lbl1512 ?>: <span id="emarksAllocated"><?= $marks_allocated ?></span>/<?= $maximum_marks ?>)</h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'edcustmque'] , 'id' => "editquesform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row clearfix">
                    <!--<div class="wrapper" style="width:100% !important">
                        <div class="input-box row container">-->
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?= $lbl1509 ?></label>
                                    <input type="text" name="equestion" id="equestion" placeholder="Enter Question"  class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?= $lbl1246 ?></label>
                                    <input type="number" name="emarks" id="emarks" placeholder="Enter Marks"  class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label><?= $lbl1510 ?></label>
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
                
                    <input type="hidden" name="examid" id="examid" value="<?= $examid ?>">
                    <input type="hidden" name="queid" id="queid">
                    <div class="col-md-12">
                        <div class="error" id="edcustmqueerror"></div>
                        <div class="success" id="edcustmquesuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary edcustmquebtn" id="edcustmquebtn"><?= $lbl1412 ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $lbl1209 ?></button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

</div>        



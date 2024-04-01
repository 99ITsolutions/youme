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
<?php // print_r($school_details); ?>

   
           <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                            <h2 class="col-md-6 heading">Exams/Assessment Approval Request</h2>
                            <h2 class="col-md-6 align-right"><a href="javascript:void(0)"  title="Approve All" data-str= "All Status" data-url = "schoolApproval/approveall"  id="approveexamsass" class="btn btn-sm btn-success">Approve All </a> </h2>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="examsass_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th>Request For</th>
                                            <th>Exams Type</th>
                                            <th>Class</th>
                                            <th>Subject</th>
                                            <th>Title</th>
                                            
                                            <th>Created Date</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                            <th>View File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n=1;
                                    foreach($approvedetails as $value)
                                    {
                                        if( $value['status'] == 0)
                                        {
                                            $sts = '<a href="javascript:void()" data-url="schoolApproval/approvestatus/'.$value['id'].'" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Exam/Assessment Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                                        }
                                        else 
                                        { 
                                            $sts = '<a href="javascript:void()" data-url="schoolApproval/approvestatus/'.$value['id'].'" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Exam/Assessment Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                                        }
                                                    
                                        ?>
                                        <tr> 
                                            <td class="width45">
                                            <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value['id'] ?>">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span ><?= $value['type'] ?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['exam_type'] ?></span>
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
                                                <span><?=date('d-m-Y', $value['created_date'])?></span>
                                            </td>
                                            <td>
                                                <span><?=date('d-m-Y h:i A', strtotime($value['start_date']))?></span>
                                            </td>
                                            <td>
                                                <span><?=date('d-m-Y h:i A', strtotime($value['end_date']))?></span>
                                            </td>
                                            <td>
                                                <span><?= $sts ?></span>
                                            </td>
                                            <td>
                                                <a href="webroot/img/<?= $value['file_name'] ?>" target="_blank"><span><?= ucfirst($value['file_name']) ?></span></a>
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


<!------------------ Pop up for status approval --------------------->

<div class="modal fade bd-example-modal-lg" id="approval_status" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">School Approval Status Notifications</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                
            </div>
             
        </div>
    </div>
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

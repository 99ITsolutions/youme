<?php
    $statusarray = array('Inactive','Active' );
   
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="heading col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1440') { echo $langlbl['title'] ; } } ?></h2>
                    <h2 class="text-right col-md-6">
                        <a href="javascript:void(0)"  title="Approve All" data-str= "All Status" data-url = "schools/approveallclasssub"  id="approveclasssub" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1437') { echo $langlbl['title'] ; } } ?></a> 
                        <a href="<?=$baseurl?>schools/approveStatus/<?= $sclid ?>" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '881') { echo $langlbl['title'] ; } } ?></a>
                    </h2>
                </div>
                
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="approveTable" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '9') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '10') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '101') { echo $langlbl['title'] ; } } ?></th>
                                <!--<th>Action</th>-->
                            </tr>
                        </thead>
                        <tbody > 
                            <?php //print_r($classsub_details);
                            $n=1;
                            foreach($classsub_details as $value){
                                if(!empty($user_details))
                                {
                                    if( $value['status'] == 0)
                                    {
                                        $sts = '<a href="javascript:void()" data-url="schools/classsubapprovestatus" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Class Subject Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                                    }
                                    else 
                                    { 
                                        $sts = '<a href="javascript:void()" data-url="schools/classsubapprovestatus" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Class Subject Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                                    }
                                }
                                else
                                {
                                    if( $value['status'] == 0)
                                    {
                                        $sts = '<label class="switch"><input type="checkbox" disabled ><span class="slider round"></span></label>';
                                    
                                    }
                                    else 
                                    { 
                                        $sts = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round"></span></label>';
                                    }
                                }
                                
                                $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
					            
                                ?>
                                <tr>
                                    <td class="width45">
                                    <label class="fancy-checkbox">
                                            <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value['id'] ?>">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold"> <?= $value['class']['c_name']. "-". $value['class']['c_section'] ?></span>
                                    </td>
                                    <td>
                                        <span> <?= $value['subject_name'] ?></span>
                                    </td>
                                    <td><?= $sts ?></td>
                                    <!--<td><?= $edit ?></td>-->
                                 
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

              



    <!------------------ Edit Class --------------------->

    
<div class="modal classmodal  animated zoomIn" id="editsub"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Edit Subject</h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editsub'] , 'id' => "editsubform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <div class="form-group">                                    
                            <input type="text" class="form-control" id="esubject_name" required name="esubject_name" placeholder="Subject Name*">
                            <input type="hidden" id="eid"  name="eid" >
                        </div>
                    </div>
		            
                    <div class="col-md-12">
                        <div class="error" id="editsuberror"></div>
                        <div class="success" id="editsubsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editsubbtn" id="editsubbtn">Update</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

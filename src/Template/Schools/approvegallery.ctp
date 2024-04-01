<?php
    $statusarray = array('Inactive','Active' );
   
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <h2 class="heading  col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1445') { echo $langlbl['title'] ; } } ?></h2>
                <h2 class="text-right col-md-6"><a href="javascript:void(0)"  title="Approve All" data-str= "All Status" data-url = "schools/approveallgallery"  id="approvegallery" class="btn btn-sm btn-success approve"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1437') { echo $langlbl['title'] ; } } ?></a> 
                <a href="<?=$baseurl?>schools/approveStatus/<?= $sclid ?>" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '881') { echo $langlbl['title'] ; } } ?></a>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem approvegallery" id="approveTable approvegallery" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '412') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '414') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '413') { echo $langlbl['title'] ; } } ?> </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '101') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1447') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody > 
                            <?php
                            $n=1;
                            foreach($gallery_details as $value){
                                if(!empty($user_details))
                                {
                                    if( $value['status'] == 0)
                                    {
                                        $sts = '<a href="javascript:void()" data-url="schools/galleryapprovestatus" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Gallery Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                                    }
                                    else 
                                    { 
                                        $sts = '<a href="javascript:void()" data-url="schools/galleryapprovestatus" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Gallery Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
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
                                        <span class="mb-0 font-weight-bold"><?= $value['title'] ?></span>
                                    </td>
                                    <td>
                                        <button type="button" data-id="20" class="btn btn-sm btn-outline-secondary viewdescription" data-desc="<?= $value['description'] ?>" data-toggle="modal" data-target="#viewdescription" title="View Description"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '52') { echo $langlbl['title'] ; } } ?></button>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold"><?= date("m-d-Y", strtotime($value['event_date'])) ?></span>
                                    </td>
                                    <td><?= $sts ?></td>
                                    <td>
                                        <button type="button" data-id="20" class="btn btn-sm btn-outline-secondary viewimages" data-image="<?= $value['images'] ?>" data-toggle="modal" data-target="#viewimages" title="View Images"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1447') { echo $langlbl['title'] ; } } ?></button>
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
        <div>
    <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
</div>  
    </div>
</div>

             



<!------------------ Description --------------------->

<!------------------ Pop up for status approval --------------------->

<div class="modal fade " id="viewdescription" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Description</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                <div id="description"></div>
            </div>
             
        </div>
    </div>
</div>         

<div class="modal fade " id="viewimages" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Event Images</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                <div id="gallery"></div>
            </div>
             
        </div>
    </div>
</div>         

   
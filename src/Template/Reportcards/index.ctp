<?php
    $statusarray = array('Inactive','Active' );
?>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <h2 class="heading col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1793') { echo $langlbl['title'] ; } } ?></h2>
                                <h2 class="align-right col-md-6">
                                    
                                    <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                                </h2>
                            </div>
                            <div class="col-md-12">
                                <div class="error" id="geterror"><?= $error ?></div>
                            </div>
                            <?php //	echo $this->Form->create(false , ['url' => ['action' => 'getdata'] , 'id' => "getdata" , 'method' => "post"  ]); ?>
                            <div class="row col-md-12">
                                <div class="col-sm-3">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1247') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">                                    
                                        <select class="form-control grades" id="grades" name="grades" onchange="getscl_sctn(this.value)">
                                            <option value="">Choose Grades</option>
                                            <?php
                                            foreach($grade_details as $key => $val){
                                                $tchrid = '';
                                                if($tchrid != '' && ($val['id'] == $tchrid))
                                                {
                                                    $sel = "selected";
                                                }
                                                else
                                                {
                                                    $sel = "";
                                                }
                                                if(!empty($sclsub_details[0]))
                                                { 
                                                    
                                                    if(strtolower($val['school_sections']) == "creche" || strtolower($val['school_sections']) == "maternelle") {
                                                        $clsmsg = "kindergarten";
                                                    }
                                                    elseif(strtolower($val['school_sections']) == "primaire") {
                                                        $clsmsg = "primaire";
                                                    }
                                                    else
                                                    {
                                                        $clsmsg = "secondaire";
                                                    }
                                                    $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                                    
                                                    $clsmsg = trim($clsmsg);
                                                    if(in_array($clsmsg, $subpriv)) { 
                                                        $show = 1;
                                                    }
                                                    else
                                                    {
                                                        $show = 0;
                                                    }
                                                } else { 
                                                    $show = 1;
                                                }
                                                if($show == 1) {
                                                ?>
                                                  <option  value="<?=$val['c_name']?>" <?= $sel ?> ><?php echo $val['c_name'] ;?> </option>
                                                <?php
                                                }
                                           }
                                            ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '100') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">                                    
                                         <select class="form-control sections" id="sections" name="sections" onchange="getclasses_grades(this.value)">
                                             <option value="">Choose Section</option>
                                         </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">  
                                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '9') { echo $langlbl['title'] ; } } ?></label>
                                        <select class="form-control classes" id="aclass" name="classes" onchange="getclassessections(this.value)">
                                             <option value="">Choose Classes</option>
                                         </select>
                                    </div>
                                </div>
                                <!--<div class="col-sm-1">
                                    <button type="submit" class="btn btn-primary addclsbtn mt-4" id="addclsbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '243') { echo $langlbl['title'] ; } } ?></button>
                                </div>-->
                            </div>
                            <?php // echo $this->Form->end(); ?>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem class_table" id="class_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1247') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '9') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '100') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '101') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '102') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="classbody" class="modalrecdel"> 
                                        <?php if($filters == "filters") { 
                                            foreach($class_details as $value) 
                                            {   
                                                $report = '<button type="button" class="btn btn-sm btn-outline-secondary book" data-toggle="modal" title="Report"><i class="fa fa-report"></i></button>';

                                                $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editclass" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></button>';
                            					$delete = '<button type="button" data-url="classes/delete" data-id='.$value['id'].' data-str="Class" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
                            		
                                                if( $value['active'] == 0)
                                                {
                                                    $status = '<label class="switch"><input type="checkbox" disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                                                
                                                }
                                                else 
                                                { 
                                                    $status = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                                                }
                                                
                                                
                            					echo  '<tr>
                                                        <td class="width45">
                                                        <label class="fancy-checkbox">
                                                                <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <span class="mb-0 font-weight-bold">'.$value['c_name'].'</span>
                                                        </td>
                                                        <td>
                                                            <span>'.$value['c_section'].'</span>
                                                        </td>
                                                        <td>
                                                            <span>'.$value['school_sections'].'</span>
                                                        </td>
                                                        
                                                        <td>
                                                            '.$status.'
                                                        </td>
                                                        <td>
                            							'.$report.$edit.$delete.'
                                                        </td>
                                                    </tr>';             
                                            }
                                        }?>
				                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
            </div>


        </div>
    </div>

<style type="text/css">
    .editclass, .btn-outline-danger{
        display: none;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?php
if(!empty($error))
{
    ?>
    <script>
        $("#geterror").fadeIn().delay('5000').fadeOut('slow');
    </script>
    <?php
}
?>


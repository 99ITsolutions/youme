<?php
    $statusarray = array('Inactive','Active' );
    foreach($lang_label as $langlbl) { 
        if($langlbl['id'] == '104') { $lbl104 = $langlbl['title'] ; } 
        if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
        if($langlbl['id'] == '100') { $lbl100 = $langlbl['title'] ; }
        if($langlbl['id'] == '9') { $lbl9 = $langlbl['title'] ; }
        if($langlbl['id'] == '243') { $lbl243 = $langlbl['title'] ; }
        if($langlbl['id'] == '1247') { $lbl1247 = $langlbl['title'] ; }
        if($langlbl['id'] == '102') { $lbl102 = $langlbl['title'] ; }
    }
?>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <h2 class="align-right col-md-12">
                                    <!--<a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addclass"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '104') { echo $langlbl['title'] ; } } ?> Grade</a>-->
                                    <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                                </h2>
                            </div>
                            <div class="col-md-12">
                                <div class="error" id="geterror"><?= $error ?></div>
                            </div>
                            <?php //	echo $this->Form->create(false , ['url' => ['action' => 'getdata'] , 'id' => "getdata" , 'method' => "post"  ]); ?>
                            <div class="row col-md-12">
                                <div class="col-sm-3">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2167') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">                
                                        <select class="form-control chgrades" id="grades" name="grades" onchange="ar_getscl_sctn(this.value)">
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
                                                    //echo "subadmin";
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
                                                    //print_r($subpriv);
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
                                            } ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '100') { echo $langlbl['title'] ; } } ?></label>
                                    <div class="form-group">                                    
                                         <select class="form-control chsections" id="sections" name="sections" onchange="ar_getclasses_grades(this.value)">
                                             <option value="">Choose Section</option>
                                         </select>
                                    </div>
                                </div>
                                <!--<div class="col-sm-3">
                                    <div class="form-group">  
                                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '9') { echo $langlbl['title'] ; } } ?></label>
                                        <select class="form-control classes" id="aclass" name="classes" onchange="ar_getclassessections(this.value)">
                                             <option value="">Choose Classes</option>
                                         </select>
                                    </div>
                                </div>-->
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
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '102') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="classbody" class="modalrecdel"> 
                                        <?php if($filters == "filters") { 
                                            foreach($class_details as $value) 
                                            {   
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
                                                    <td><a href="'.$baseurl.'view/'.$value['id'].'" class="btn btn-sm btn-outline-secondary" title="Report"><i class="fa fa-user"></i></a></td>
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php
if(!empty($error))
{?>
    <script>$("#geterror").fadeIn().delay('5000').fadeOut('slow');</script>
<?php } ?>


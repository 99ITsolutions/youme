<?php
    $statusarray = array('Inactive','Active' );
    foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2027') { $yerlylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1019') { $hlfyrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1020') { $quatrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1021') { $mnthlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2028') { $chosesesslbl = $langlbl['title'] ; }
    if($langlbl['id'] == '2121') { $lbl2121 = $langlbl['title'] ; }
    if($langlbl['id'] == '2175') { $lbl2175 = $langlbl['title'] ; }
    if($langlbl['id'] == '2219') { $lbl2219 = $langlbl['title'] ; }
    if($langlbl['id'] == '2220') { $lbl2220 = $langlbl['title'] ; }
    if($langlbl['id'] == '2237') { $lbl2237 = $langlbl['title'] ; }
    if($langlbl['id'] == '2238') { $lbl2238 = $langlbl['title'] ; }
    if($langlbl['id'] == '130') { $lbl130 = $langlbl['title'] ; } 
    if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; } 
    if($langlbl['id'] == '337') { $lbl337 = $langlbl['title'] ; }
    if($langlbl['id'] == '321') { $lbl321 = $langlbl['title'] ; }
    if($langlbl['id'] == '322') { $lbl322 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '2250') { $lbl2250 = $langlbl['title'] ; }  
    if($langlbl['id'] == '2249') { $lbl2249 = $langlbl['title'] ; }
} 
?>
<style>
@media screen and (max-width: 440px) and (min-width: 200px)
{
    .sclstufeemodule>.col-md-8
    {
       text-align:left !important;
    }
    .sclstufeemodule>.col-md-8>.btn
    {
       display:block !important;
       margin-top:5px !important;
       width:60% !important;
    }
}
</style>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row sclstufeemodule">
                    <h2 class="col-md-3 heading"><?= $lbl2219 ?></h2>
                    <div class="col-md-9 text-right">
                        
                        <?php if(!empty($sclsub_details[0])) { 
                            $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                            if(in_array("126", $roles)) { ?>
                                <a href="canteenfundreport" title="<?= $lbl2250 ?>" class="btn btn-sm btn-success" ><?= $lbl2250 ?></a>
                            <?php }
                            if(in_array("123", $roles)) { ?>
                                <a href="javascript:void(0);" title="Add" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addfeestruc"><?= $lbl2220 ?></a>
    	                <?php } } else { ?>
                            <a href="javascript:void(0);" title="Add" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addfeestruc"><?= $lbl2220 ?></a>
                        <?php } ?>
    	                <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a>
                    </div>
                </div>           
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="canteenfee_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th><?= $lbl130 ?></th>
                                <th><?= $lbl147 ?></th>
                                <th><?= $lbl337 ?></th>
                                <th><?= $lbl321 ?></th>
                                <th><?= $lbl322?></th>
                                <th><?= $lbl2237?></th>
                                <th><?= $lbl2238 ?></th>
                                <th><?= $lbl2249 ?></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="cnteenfeebody" class="modalrecdel"> 
                            <?php
                            $n=1;
                            foreach($feecanteen_details as $value){
                                if(!empty($sclsub_details[0]))
                                { 
                                    if(strtolower($value['sclsection']) == "creche" || strtolower($value['sclsection']) == "maternelle") {
                                        $clsmsg = "kindergarten";
                                    }
                                    elseif(strtolower($value['sclsection']) == "primaire") {
                                        $clsmsg = "primaire";
                                    }
                                    else
                                    {
                                        $clsmsg = "secondaire";
                                    }
                                    $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
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
                                <tr>
                                    <td class="width45">
                                        <span><?=$value['adm']?></span>
                                    </td>
                                    <td class="width45">
                                        <span><?=$value['student']?></span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold"><?=$value['c_name']."-".$value['c_section']." (".$value['school_section'].")" ?></span>
                                    </td>
                                    <td>
                                        <span><?=$value['start_year']?></span>
                                    </td>
                                    <td>
                                        <span><?= "$".$value['amount']?></span>
                                    </td>
                                    <td>
                                        <span><?= "$".$value['daily_limit']?></span>
                                    </td>
                                    
                                    <td>
                                        <span><?= date("d-m-Y h:i A", $value['created_date']) ?></span>
                                    </td>
                                    <td>
                                        <span><?= ucfirst($value['deposit_by']) ?></span>
                                    </td>
                                    <td>
                                        <?php if(!empty($sclsub_details[0])) { 
                                                $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                                if(in_array("124", $roles)) { ?>
                                                    <button type="button" data-id='<?= $value['id'] ?>' class="btn btn-sm btn-outline-secondary editstruc" data-toggle="modal"  data-target="#editstruc" title="Edit"><i class="fa fa-edit"></i></button>
                                                <?php } if(in_array("125", $roles)) { ?>
                                                    <button type="button" data-id="<?=$value['id']?>" data-url="fees/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Fees structure" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                            <?php }
                                        } else { ?>
                                            <button type="button" data-id='<?= $value['id'] ?>' class="btn btn-sm btn-outline-secondary editstruc" data-toggle="modal"  data-target="#editstruc" title="Edit"><i class="fa fa-edit"></i></button>
                                            <button type="button" data-id="<?=$value['id']?>" data-url="cfdelete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Student Canteen Fee" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                        <?php } ?>
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

<!------------------ Add Class --------------------->

<div class="modal classmodal animated zoomIn" id="addfeestruc" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl2220 ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addcanteen'] , 'id' => "addcanteenform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?= $chosesesslbl ?></label>
                            <select class="form-control session" id="select_year" name="start_year" required>
                                <option value=""><?= $chosesesslbl ?></option>
                                <?php
                                foreach($session_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '337') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control js-states" id="aclass" required name="class" onchange="getstud(this.value)">
                                <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '338') { echo $langlbl['title'] ; } } ?></option>
                                <?php
                                foreach($class_details as $key => $val){
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
                                      <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section']." (". $val['school_sections'] . ")";?> </option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>  
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '12') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control" required name="student" id="liststudent">
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '343') { echo $langlbl['title'] ; } } ?>*</label>
                            <input type="number" class="form-control" name="amount"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '527') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?= $lbl2237?>*</label>
                            <input type="number" class="form-control" name="daily_limit"  required placeholder="<?= $lbl2237?>*">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?= $lbl2249 ?>*</label>
                            <input type="text" class="form-control" name="deposit_by"  required placeholder="<?= $lbl2249 ?> *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="cfunderror"></div>
                        <div class="success" id="cfundsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addcfundbtn" id="addcfundbtn" style="margin-right: 10px;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '95') { echo $langlbl['title'] ; } } ?></button>
                    <!--<button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>-->
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>          


<!------------------ Edit Class --------------------->

    
<div class="modal classmodal  animated zoomIn" id="editstruc"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2221') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editcanteen'] , 'id' => "editcanteenform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="eid"  name="eid" >
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?= $chosesesslbl ?></label>
                            <select class="form-control sess_name" id="select_year2" name="start_year" required>
                                <option value=""><?= $chosesesslbl  ?></option>
                                <?php
                                foreach($session_details as $key => $val){
                                ?>
                                  <option  value="<?=$val['id']?>" id="sy_<?=$val['id']?>" ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
		            <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '337') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control class_s" id="class" required name="class" onchange="egetstud(this.value, this.id)">
                                <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '338') { echo $langlbl['title'] ; } } ?></option>
                                <?php
                                foreach($class_details as $key => $val){
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
                                      <option  value="<?=$val['id']?>" id="class_<?=$val['id']?>"><?php echo $val['c_name'] ."-" . $val['c_section']." (". $val['school_sections'] . ")";?> </option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>  
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '12') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control studentchose" required name="estudent" id="estudent">
                             
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '343') { echo $langlbl['title'] ; } } ?></label>
                            <input type="number" class="form-control" id="amount" name="amount"  required placeholder="Amount *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?= $lbl2237?>*</label>
                            <input type="number" class="form-control" name="daily_limit" id="daily_limit" required placeholder="<?= $lbl2237?> *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?= $lbl2249 ?>*</label>
                            <input type="text" class="form-control" name="deposit_by" id="deposit_by" required placeholder="<?= $lbl2249 ?> *">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="ecfunderror"></div>
                        <div class="success" id="ecfundsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editcfundbtn" id="editcfundbtn" style="margin-right:15px"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?> </button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function getstud(val)
    {
        $("#liststudent").html("");
        var sessid = $("#select_year").val();
        var refscrf = $("input[name='_csrfToken']").val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        $.ajax
        ({
            data : {_csrfToken:refscrf, 'clsid':val, 'start_year': sessid },
            type : "post",
    		url: baseurl + '/fees/getstud',
            success: function(response){
                console.log(response);
                $("#liststudent").html(response);
            }
        })  
    }
    
    function egetstud(val, id)
    {
        if(id == "class")
        {
            $("#estudent").html("");
            var sessid = $("#select_year2").val();
            var refscrf = $("input[name='_csrfToken']").val();
            var baseurl = window.location.pathname.split('/')[1];
            var baseurl = "/" + baseurl;
            $.ajax
            ({
                data : {_csrfToken:refscrf, 'clsid':val,  'stuid':id, 'start_year': sessid },
                type : "post",
        		url: baseurl + '/fees/egetstud',
                success: function(response){
                    console.log(response);
                    $("#estudent").html(response);
                }
            })  
        }
        else
        {
            $("#estudent").html("");
            var sessid = $("#select_year2").val();
            var refscrf = $("input[name='_csrfToken']").val();
            var baseurl = window.location.pathname.split('/')[1];
            var baseurl = "/" + baseurl;
            $.ajax
            ({
                data : {_csrfToken:refscrf, 'clsid':val, 'stuid':id, 'start_year': sessid },
                type : "post",
        		url: baseurl + '/fees/egetstud',
                success: function(response){
                    console.log(response);
                    $("#estudent").html(response);
                }
            })  
        }
        
    }
</script>
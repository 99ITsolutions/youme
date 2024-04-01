<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
    .col-sm-2 {
        -ms-flex: 0 0 18%;
        flex: 0 0 18.2%;
        max-width: 18.2%;
        padding-right:10px !important;
        padding-left:10px !important;
    }
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1579') { echo $langlbl['title'] ; } } ?></h2>
                        </div>
                    </div>
                    <div class="body" id="gen_pdf">
                        <div class="row clearfix col-md-12 ">
                            <div class="error" id="summryerror"><?= $error ?></div>
                        </div>
                        <div class="row clearfix">
	                        <div class="col-md-3">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '635') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <select class="form-control class" id="schoolid" name="schoolid" required onchange="getschoolstudents(this.value)">
                                        <option value="">Choose School</option>
                                        <?php
                                        foreach($school_details as $key => $val){
                                            if($sclid != '' && ($val['id'] == $sclid))
                                            {
                                                $sel = "selected";
                                            }
                                            else
                                            {
                                                $sel = "";
                                            }
                                        ?>
                                          <option  value="<?=$val['id']?>" <?= $sel ?>><?php echo $val['comp_name'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select> 
                                </div>
    	                    </div>
    	                    <div class="col-md-3">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1003') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <select class="form-control session" id="session" name="session" required onchange="getsessionstudent(this.value)">
                                        <option value="">Choose Session</option>
                                        <?php 
                                        foreach($sessiondetails as $key => $val){
                                            if($sessionid != '' && ($val['id'] == $sessionid))
                                            {
                                                $sel = "selected";
                                            }
                                            else
                                            {
                                                $sel = "";
                                            }
                                        ?>
                                          <option  value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['startyear'] ."-" . $val['endyear'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select> 
                                </div>
    	                    </div>
    	                    <div class="col-md-3">
    	                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?> *</label>
                                <div class="form-group">
                                    <select class="form-control class" id="class" name="class" required onchange="getclassstudents(this.value)">
                                        <option value="">Choose Class</option>
                                        <?php if(!empty($clsid))
                                        {
                                        foreach($classdetails as $key => $val){
                                            if($clsid != '' && ($val['id'] == $clsid))
                                            {
                                                $sel = "selected";
                                            }
                                            else
                                            {
                                                $sel = "";
                                            }
                                        ?>
                                          <option  value="<?=$val['id']?>" <?= $sel ?> ><?php echo $val['c_name'] ."-" . $val['c_section'];?> </option>
                                        <?php
                                        }
                                       }
                                        ?>
                                    </select> 
                                </div>
    	                    </div>
    	                    
    	                </div>
    	                
    	                <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem libaccess_table" id="libaccess_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '131') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '132') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '133') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '134') { echo $langlbl['title'] ; } } ?></th>
                                        
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '135') { echo $langlbl['title'] ; } } ?></th>
                                        <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?></th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="libaccess_body" class="modalrecdel"> 
                                   
        	                    </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>

<!------------------ End --------------------->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php
if(!empty($error))
{
    ?>
    <script>
        $("#summryerror").fadeIn().delay('5000').fadeOut('slow');
    </script>
    <?php
}
?>
<!------------------ End --------------------->

<script>


function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    



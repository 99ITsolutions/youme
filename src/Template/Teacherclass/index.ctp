<style>
/*.bg-dash
{
    background-color:#242E3B !important;
}*/

@media screen and (max-width: 444px) and (min-width: 200px) 
{
    #tchrattnmod>.buttons
    {
        display:block !important;
        text-align:left !important;
    
    }
}
</style>
<?php //print_r($emp_details);
if(!empty($emp_details))
{ ?>
    <div class="row clearfix ">
        <div class="card ">
           <div class="header">
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <?php foreach($classes_details as $val)
                    { 
	                    ?>
	                    <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card text-center bg-dash ">
                                <div class="body" style="height:90px !important;">
                                    <div class="p-15 text-light teachersubdtlss">
                                        <span><b><a  class="colorBtn" href="<?=$baseurl?>teacherclass/classall?classid=<?=$val['class']['id'];?>" class="teachersubdtldata" ><?php echo $val['class']['c_name'] ."-" . $val['class']['c_section']." (" . $val['class']['school_sections'].")";?>
</a></b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    } ?>
                    
                </div>
            </div>
	    </div>
	</div>
	<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>
 <?php
}
?>

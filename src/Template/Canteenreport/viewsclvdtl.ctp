<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
   
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-11 heading">View detailing report
                            
                            </h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0)"  onclick="goBack()" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
                        </div>
                        <div class=" row col-md-12 mt-4">
                            School: <?= $school_details['comp_name']; ?> <br>
                            Vendor Company: <?= $vendor_details['vendor_company']; ?> <br>
                            Date: <?= $sdate; ?> <br>
                        </div>
                    </div>
                    <div class="body" id="gen_pdf">
                    <div class="row  clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive"><br><br>
                            <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="meetinglink_table" data-page-length='50'>
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="display:none"></th>
                                        <th>Food Image</th>   
                                        <th>Food Name</th>
                                        <th>Food Quantity </th>
                                        <th>Food Price </th>
                                        <th>Total amount</th>  
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="meetinglink_body" class="modalrecdel"> 
                                    <?php if(!empty($cso_details)) {
                                        
                                    foreach($cso_details as $value)
                                    {
                                        if($value['order_status'] == 0)
                                        {
                                            $sts = "Pending";
                                        }
                                        elseif($value['order_status'] == 1)
                                        {
                                            $sts = "Delivered";
                                        }
                                        elseif($value['order_status'] == 2)
                                        {
                                            $sts = "Cancelled";
                                        }
                                        else
                                        {
                                            $sts = "Undelivered";
                                        }
            ?>
                                        <tr>
                                            <td style="display:none"></td>
                                            <td> <img src="<?= $baseurl ?>/c_food/<?= $value['food_item']['food_img'] ?>" width="50px" /></td>
                                            <td><?= $value['food_item']['food_name'] ?></td>
                                            
                                            <td class="text-center"><?= $value->quantity ?></td>
                                            <td class="text-center"><?= $value->food_amount ?></td>
                                            <td class="text-center"><?= "$".$value->tammt ?></td>
                                            <td class="text-center"><?= $sts ?></td>
                                        </tr>
                                        <?php
                                    } }
                                    ?>
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
<script>
    //$('.wrapper').on("change", ".scllist", function (e) {
    function getvendors(val) {
        //var sclid = $(this).val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        
        $("#vendor").html("");
        $.ajax({
            type:'POST',
            url: baseurl + '/Canteenvendors/getvendors',
            data:{'sclid':val, 'multi': 'multi'},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                console.log(html);
                if(html)
                {    
                    $("#vendor").html(html);
                }
            }
        });
    } //) 
</script>

 



            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading">Approve Teachers</h2>
                            <h2 class="text-right"><a href="javascript:void(0)"  title="Approve All" data-str= "All Status" data-url = "schools/approveallteachers"  id="approveteacher" class="btn btn-sm btn-success mt-4">Approve All </a> </h2>
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
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile No</th>
                                            <th>Qualifications</th>
                                            <th>Date Of Joining</th>
                                            <th>Subjects</th>
                                            <th>Status</th>
                                            <!--<th>Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n=1;
                                    foreach($teacher_details as $value){
                                        ?>
                                        <tr>
                                            <td class="width45">
                                            <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="checkbox" id="<?= $value['id'] ?>">
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value['f_name']?></span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?=$value['l_name']?></span>
                                            </td>
                                            
                                            <td>
                                                <span><?=$value['mobile_no']?></span>
                                            </td>
                                            <td>
                                                <span><?=$value['quali']?></span>
                                            </td>
                                            <td>
                                                <span><?= date('d-m-Y', strtotime( $value['doj']))?></span>
                                            </td>
                                            <td>
                                                <span><?= $value['subjects_name'] ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                if(!empty($user_details[0]['id']))
                                                {
                                                    if( $value['status'] == 0)
                                                    {
                                                    ?>
                                                    <a href="javascript:void()" data-url="schools/teacherapprovestatus" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Teacher Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>
                                                    <?php 
                                                    }
                                                    else 
                                                    { ?>
                                                        <a href="javascript:void()" data-url="schools/teacherapprovestatus" data-id="<?=$value['id']?>" data-status="<?=$value['status']?>" data-str="Teacher Status" class="btn btn-sm js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>
                                                    <?php 
                                                    }
                                                }
                                                else
                                                {
                                                    if( $value['status'] == 0)
                                                    {
                                                    ?>
                                                    <label class="switch">
                                                      <input type="checkbox" disabled >
                                                      <span class="slider round"></span>
                                                    </label>
                                                    <?php 
                                                    }
                                                    else 
                                                    { ?>
                                                        <label class="switch">
                                                          <input type="checkbox" checked disabled >
                                                          <span class="slider round"></span>
                                                        </label>
                                                    <?php 
                                                    }
                                                }
                                                ?>
                                                 
                                               
                                            </td>
                                            <!--<td>
                                                <a href="<?=$baseurl?>teachers/edit/<?= md5($value['id'])?>" title="Edit" id="<?= $value['id']; ?>" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                            </td>-->
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
             <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
 
            </div>
        </div>
    </div>




 

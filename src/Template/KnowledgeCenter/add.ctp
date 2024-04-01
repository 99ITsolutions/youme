<?php
    $school_id =$this->request->session()->read('company_id');
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Add University</h2>
                <ul class="header-dropdown">
                    <li><a href="<?= $baseurl ?>knowledgeCenter/studyabroad" class="btn btn-info" >Back</a></li>
                </ul>
            </div>
            <div class="body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'adduniversity'] , 'id' => "adduniversityform", 'enctype' => "multipart/form-data"  , 'method' => "post"  ]); ?>
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label>University Logo*</label>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="logo"  required placeholder="Upload File *">
                                </div>
                            </div>
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4">
                                <label>University Name*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="uni_name"  required placeholder="Enter Name *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Email*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email"  required placeholder="Enter Email *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Website Link*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="website"  required placeholder="Enter Website Link *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Contact Number*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="contact"  required placeholder="Enter Contact Number *">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Country*</label>
                                <div class="form-group">
                                    <select class="form-control country" name="country" id="country">
                                        <option value=""></option>
                                        <?php foreach($countries_details as $country) { ?>
                                            <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Courses Offered*</label>
                                <div class="form-group">
                                    <textarea class="form-control" id="courses" name="courses" required placeholder="Enter Courses *" ></textarea>
                                </div>
                            </div>
                           
                            
                            
                            
                            <div class="col-sm-12">
                                <label>Description*</label>
                                <div class="form-group">
                                    <textarea class="form-control" id="desc" name="desc" rows="5" required placeholder="Enter Description *" ></textarea>
                                </div>
                            </div>
					
                           
                            <div class="col-sm-12">
                                <div class="error" id="univerror">
                                </div>
                                <div class="success" id="univsuccess">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    
                                    <div class="mt-4 ml-4">
                                        <button type="submit" id="addunivbtn" class="btn btn-primary addunivbtn">Save</button>
                   
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    




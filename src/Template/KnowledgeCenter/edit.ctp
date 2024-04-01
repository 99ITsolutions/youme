<style>
    .comments_ht
    {
        max-height:315px;
        overflow-y:auto;
    }
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Edit Knowledge Base</h2>
            </div>
            <div class="body">
                
                <div class="row clearfix mb-4">
                    <div class="col-sm-8">
                        <iframe width="660" height="315" src="https://www.youtube.com/embed/FwwIYdB_wic" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="col-sm-4 comments_ht">
                        <h2 class="heading">Comments & Reviews</h2>

<p>Name: good</p>
                    </div>
                </div>
                
                <?php   
                echo $this->Form->create(false , ['url' => ['action' => 'editknowledge'] , 'id' => "editknowledgeform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <!--<input type="hidden" name="school_id" value="<?=$school_id?>">-->
                    <div class="col-sm-3">
                        <label>File Type *</label>
                        <div class="form-group">
                            <select class="form-control file_type" id="file_type" name="file_type" required onchange="file_typess(this.value)">
                                <option value="video" >Video</option>
                                <option value="audio">Audio</option>
                                <option value="pdf">PDF</option>
                            </select> 
                        </div>
                    </div>
                           
                    <div class="col-sm-3">
                        <label>Title*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="s_name"  required placeholder="Enter Title *" value="Video Title 1">
                        </div>
                    </div>
                    
                    <div class="col-sm-3" id="link_file">
                        <label>File Link*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="file_link"  required placeholder="Enter Link *" value="https://www.youtube.com/embed/FwwIYdB_wic">
                        </div>
                    </div>
                    
                    <div class="col-sm-3" id="upload_file" style="display:none">
                        <label>File Upload*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="file_link"  required placeholder="Upload File *">
                        </div>
                    </div>
                            
                    <div class="col-sm-12">
                        <label>Description*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="Enter Description *" >Video Description</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="error" id="knowldgeerror">
                        </div>
                        <div class="success" id="knowldgesuccess">
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="mt-4 ml-4">
                                <button type="submit" id="editknowledgebtn" class="btn btn-primary editknowledgebtn">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>    



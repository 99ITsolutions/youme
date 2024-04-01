
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Add Library Content</h2>
            </div>
            <div class="body"> 
                <?php echo $this->Form->create(false , ['url' => ['action' => 'addlibrarycontent'] , 'id' => "addlibcontentform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <label>Class *</label>
                            <div class="form-group">
                                <select class="form-control class_s" id="classID" name="classID" required onchange="subjctcls(this.value)">
                                     <option value="" >Choose Class</option>
                                    <?php foreach($class_details as $cls) {?>
                                    <option value="<?= $cls['id'] ?>"><?= $cls['c_name']."-".$cls['c_section'] ?></option>
                                    <?php } ?>
                                </select> 
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <label>Subject *</label>
                            <div class="form-group">
                                <select class="form-control subj_s" id="cls_sub" name="cls_sub" required >
                                    <option value="" >Choose Subject</option>
                                </select> 
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <label>File Type *</label>
                            <div class="form-group">
                                <select class="form-control file_type" id="file_type" name="file_type" required onchange="file_typess(this.value)">
                                    <option value="video" >Video</option>
                                    <option value="audio">Audio</option>
                                    <option value="pdf">PDF</option>
                                </select> 
                            </div>
                        </div>
                               
                        <div class="col-sm-6">
                            <label>Title*</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="title" id="title"  required placeholder="Enter Title *">
                            </div>
                        </div>
                        
                        <div class="col-sm-12" id="link_file">
                            <label>File Link</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="file_link" id="file_link"  placeholder="Enter Link " >
                            </div>
                        </div>
                        
                        <div class="col-sm-12" id="upload_file" style="display:none">
                            <label>File Upload*</label>
                            <div class="form-group">
                                <input type="file" class="form-control" name="file_upload" id="file_upload">
                            </div>
                        </div>
                                
                        <div class="col-sm-12">
                            <label>Description*</label>
                            <div class="form-group">
                                <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="Enter Description *" ></textarea>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <label>Cover Image*</label>
                            <div class="form-group">
                                <input type="file" class="form-control" name="cover_image" id="cover_image" accept=".jpg,.png,.jpeg">
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
            				<div id="upload_image"></div>
            	  		</div>
                        <div class="col-sm-12">
                            <div class="error" id="addtutconterror">
                            </div>
                            <div class="success" id="addtutcontsuccess">
                            </div>
                        </div>
                        
                        <div class="button_row" >
                            <hr>
                            <button type="submit" id="addtutcontbtn" class="btn btn-primary addtutcontbtn">Save</button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>    
<script>
function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}
</script>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?=$baseurl?>webroot/js/croppie.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?=$baseurl?>css/croppie.css">
<script>
$(document).ready(function(){
    var $image_crop;
	$image_crop = jQuery('#upload_image').croppie({
        enableExif: true,
        viewport: {
          width:200,
          height:200,
          type:'square' //circle
        },
        boundary:{
          width:300,
          height:300
        }
    });
    
	$('#cover_image').on('change', function () { 
		var reader = new FileReader();
		reader.onload = function (e) {
			$image_crop.croppie('bind', {
				url: e.target.result
			}).then(function(){
				console.log('jQuery bind complete');
			});			
		}
		reader.readAsDataURL(this.files[0]);
	});
	/*$('.cropped_image').on('click', function (ev) {
		$image_crop.croppie('result', {
			type: 'canvas',
			size: 'viewport'
		}).then(function (response) {
			$.ajax({
				url: "http://coderszine.com/demo/crop-image-and-upload-using-jquery-and-php/upload.php",
				type: "POST",
				data: {"image":response},
				success: function (data) {
					html = '<img src="' + response + '" />';
					$("#upload-image-i").html(html);
				}
			});
		});
	});	*/
});
</script>



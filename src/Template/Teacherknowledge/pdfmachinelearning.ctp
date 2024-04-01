<style>
    .comments_ht
    {
        max-height:315px;
        overflow-y:auto;
    }
    .reply { margin-left: 30px; }
    .reply_form {
    	margin-left: 40px;
    	display: none;
    }
    #comment_form { margin-top: 10px; }
    #left-sidebar { left: -250px; }
    #main-content { width:100%; height: 100vh;
        background: #000;
    z-index: 99;
    opacity: 0.8;
    margin-top:0px;
    padding-top:120px;
    }
    .block-header{
        display:none;
    }
    .card{
        background:transparent;
        box-shadow: none;
        
    }
</style>
<link href="https://cdn.rawgit.com/nanostudio-org/nanogallery2/dev/dist/css/nanogallery2.min.css" rel="stylesheet">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <div class="col-lg-6 col-md-6 col-sm-6  offset-3">
                <!--<div class="modal classmodal animated zoomIn" id="shareguides" role="dialog" style="display:block">
                    <div class="modal-dialog  modal-lg" role="document">-->
                        <div class="modal-content modal"  id="shareguides">
                            <div class="modal-header header">
                                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2132') { echo $langlbl['title'] ; } } ?></h6>
                                <a href="<?= $baseurl ?>Teacherknowledge/viewmachinelearning/<?= $guid_id ?>" class="close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                	        </div>
                            <div class="modal-body">
                                <?php	echo $this->Form->create(false , ['url' => ['action' => 'sharerequest'] , 'id' => "sharerequestform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                                
                                
                                <div class="wrapper">
                                    <div class="row clearfix" style-"margin-top:10px; margin-bottom:10px;">
                                        <div class="col-md-6">
                                            <select class="form-control clsgrade class_s" id="clsgrade1" name="grades[]" placeholder="Choose Class" required  style="margin-right:15px !important;">
                                                <?php foreach($empclses_details as $empdtl) { ?>
                                	               <option  value="<?= $empdtl['class']['id'] ?>" ><?= $empdtl['class']['c_name']."-".$empdtl['class']['c_section']." (". $empdtl['class']['school_sections'].")" ?> </option>
                                                <?php } ?>
                                            </select>
                                            
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control subgrade" id="subgrade1" name="subjects[]" placeholder="Choose Subjects" required>
                                                <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '300') { echo $langlbl['title'] ; } } ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn add-btn"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row clearfix">
                                    <div class="col-md-6">
                                        <div class="form-group">  
                                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?></label>
                                            <div class="input-group date" id="sfdatetimepicker1" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#sfdatetimepicker1"  name="start_date" id="start_date" required/>
                                                <div class="input-group-append" data-target="#sfdatetimepicker1" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">  
                                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?></label>
                                            <div class="input-group date" id="stdatetimepicker2" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#stdatetimepicker2" name="end_date" id="end_date" required>
                                                <div class="input-group-append" data-target="#stdatetimepicker2" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="display:block;">
                                        <div class="form-group">  
                                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></label>
                                            <input type="text" name="title" id="title" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?>"  class="form-control" value="<?= ucwords($knowledge_details[0]['title']) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="guideinstr">
                                        <div class="form-group">  
                                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '373') { echo $langlbl['title'] ; } } ?></label>
                                            <textarea name="instruction" id="instruction" placeholder="Enter Instruction"   class="form-control"  rows="3" required><?= ucwords($knowledge_details[0]['description']) ?> </textarea>
                                        </div>
                                    </div>
                                     <input type="hidden" name="gid" id="gid" value="<?= $knowledge_details[0]['id'] ?>">
                                    
                                </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="error" id="sharereqerror"></div>
                                            <div class="success" id="sharereqsuccess"></div>
                                        </div>
                                    </div>
                                    <div class="button_row" >
                                        <hr>
                                        <button type="submit" class="btn btn-primary sharereqbtn" id="sharereqbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2130') { echo $langlbl['title'] ; } } ?></button>
                                        <a href="<?= $baseurl ?>Teacherknowledge/viewmachinelearning/<?= $guid_id ?>" class="btn btn-secondary"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></a>
                                    </div>
                                    
                                   <?php echo $this->Form->end(); ?>
                                </div>
                           <!-- </div>-->
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
 
      // allowed maximum input fields
      var max_input = 5;
 
      // initialize the counter for textbox
      var x = 1;
 
      // handle click event on Add More button
      $('.add-btn').click(function (e) {
        e.preventDefault();
        if (x < max_input) { // validate the condition
          x++; // increment the counter
          
          $('.wrapper').append(`
                <div class="row clearfix" style="margin-top:10px; margin-bottom:10px;">
                    <div class="col-md-6">
                        <select class="form-control clsgrade class_s" id="clsgrade`+x+`" name="grades[]" placeholder="Choose Class" style="margin-right:15px !important;" required>
                            <?php foreach($empclses_details as $empdtl) { ?>
                	               <option  value="<?= $empdtl['class']['id'] ?>" ><?= $empdtl['class']['c_name']."-".$empdtl['class']['c_section']." (". $empdtl['class']['school_sections'].")" ?> </option>
                                <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control subgrade" id="subgrade`+x+`"  name="subjects[]" required>
                            <option value=""><?php echo $subjectssch ?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                       <a href="#" class="col-sm-2 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      })
      
      $('.wrapper').on("change", ".clsgrade", function (e) {
        
        var gradeid = $(this).val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var id = this.id;
        var splitid = id.split("clsgrade");
        //alert(splitid[1]);
        $("#subgrade"+splitid[1]).html("");
        
        var subid = '';
        $.ajax({
            type:'POST',
            url: baseurl + '/teacherdashboard/getsubjecttchr',
            data:{'clsid':gradeid, 'subid':subid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html)
                {    
                    $("#subgrade"+splitid[1]).html(html.subjectname);
                }
          
            }

        });
  
      })
 
    });
</script>
<?php //print_r($form_details);?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <!-- If a user adds a bookmark of v4 to his/her homescreen, app will be fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex, nofollow">
    
    <link rel="shortcut icon" href="/favicon.ico">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>ONline Form</title>
    <style>
      body { 
		margin: 0; 
		font-family: Inter,sans-serif;
		font-size: 16px;
		background-color: #ecedf3;
		color: #2c3345;
	}
      html, body, #root { height: 100%; }
	  .container
	  {
		  background-color:#fff;
		  height : auto;
	  }
	  .img{
		    width: 150px;
			margin: 20px;
	  }
	  .margin{
		  margin: 75px 0 0 0;
	  }
	  .title{
		  font-weight:bold;
		  font-size:20px;
	  }
	  .form-margin{
		  margin: 25px;
	  }
	  input[type=checkbox], input[type=radio] {
			margin: 4px 5px 0 20px;
			margin: 4px 5px 0 20px;
	  }
    </style>
    <!--  React portal for card styles, don't remove it -->
    <style class="cardStylesReactPortal">
      
    </style>
  </head>
  <body>
         <?php foreach($form_details as $form):
			$background = "../img/".$form->background;
		 ?>     
		 
    <div id="root">
		<div class="container " style="background-image:url('<?= $background?>')">
			<div class="row">
				<div class="pull-left col-md-4"><img src="../img/<?=$form->logo?>"  class="img-responsive img "></div>
				<div class="col-md-4 text-center title margin">
					<span ><?=ucfirst($form->title)?> Form</span>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12 title"> Instructions: </div>
				
				<div class="col-md-12">
					<span ><?=ucfirst($form->instruction)?> </span>
				</div>
			</div>
			<div class="row form-margin">
			<?php   echo $this->Form->create(false , ['url' => ['action' => 'submitform'] , 'id' => "submitform" , 'enctype' => 'multipart/form-data', 'method' => "post"  ]);?>
			<?php foreach($form_fields as $fields): 
				//print_r($fields);
				if($fields->type == "text")
				{
					?>
					<div class="col-sm-12">
                        <label><?= ucfirst($fields->fields)?>*</label>
						<div class="form-group">
						<input class="form-control" type="text" name="<?= $fields->id?>" placeholder="Enter <?= $fields->fields?>" />
						</div>
					</div>
					<?php
				}
				elseif($fields->type == "textarea")
				{
					?>
					<div class="col-sm-12">
                        <label><?= ucfirst($fields->fields)?>*</label>
						<div class="form-group">
						<textarea  class="form-control" name="<?= $fields->id?>" rows="5" placeholder="Enter <?= $fields->fields?>" ></textarea>
						</div>
					</div>
					<?php
				}
				elseif($fields->type == "file")
				{
					?>
					<div class="col-sm-12">
                        <label><?= ucfirst($fields->fields)?>*</label>
						<div class="form-group">
						<input class="form-control" type="file" name="<?= $fields->id?>" placeholder="Enter <?= $fields->fields?>" />
						</div>
					</div>
					<?php
				}
				elseif($fields->type == "radio")
				{
					$options = explode(",", $fields->options);
					
					?>
					<div class="col-sm-12">
                        <label><?= ucfirst($fields->fields)?>*</label>
						<div class="form-group">
						<?php foreach($options as $i =>$key) {

							//echo $i.' '.$key .'</br>';
							echo "<input type= 'radio' id = ".$i." value = ".$key." name=".$fields->id.">". $key ;
						} ?>
						</div>
					</div>
					<?php
				}
				elseif($fields->type == "checkbox")
				{
					$options = explode(",", $fields->options);
					
					?>
					<div class="col-sm-12">
                        <label><?= ucfirst($fields->fields)?>*</label>
						<div class="form-group">
						<?php foreach($options as $i =>$key) {

							//echo $i.' '.$key .'</br>';
							echo "<input type= 'checkbox' id = '".$i."' value = '".$key."' name='".$fields->id."[]'>". $key ;
						} ?>
						</div>
					</div>
					<?php
				}
				elseif($fields->type == "select")
				{
					$options = explode(",", $fields->options);
					
					?>
					<div class="col-sm-12">
                        <label><?= ucfirst($fields->fields)?>*</label>
						<div class="form-group">
						<select name="<?= $fields->id?>" class= 'form-control'>
						<?php foreach($options as $i =>$key) {							
							echo "<option class= 'form-control' value = ".$key.">".$key ."</option>";
						} ?>
						</select>
						</div>
					</div>
					<?php
				}
				
				?>
				
				
			<?php endforeach; ?>
				<input type="hidden" name="form_id" value="<?= $fields->form_id?>">
				<?php $rand = rand(0000,9999);?>
				<input type="hidden" name="unique_id" value="<?= md5($rand)?>">
			<div class="col-sm-12">
				<div class="form-group">
					<button type="submit" id="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
			
			<?php echo $this->Form->end(); ?>
			</div>
			<div class="row">
				<div class="col-md-12 title"> Notes: </div>
				
				<div class="col-md-12">
					<span ><?=ucfirst($form->notes)?> </span>
				</div>
			</div>
		</div>
	</div>
    <div id="portal-root"></div>


		<?php endforeach; ?>
  </body>
</html>

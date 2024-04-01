
<!doctype html>
<html lang="en">

<head>
<title>:: BPMT :: Accept Terms</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="BPMT Login Page">
<meta name="author" content="Bizhawkz">

<link rel="icon" href="favicon.ico" type="image/x-icon">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="/bpmt/css/bootstrap.min.css">

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<!-- MAIN CSS -->
<link rel="stylesheet" href="/bpmt/css/main.css">
<link rel="stylesheet" href="/bpmt/css/color_skins.css">
<style>
div#error {
    display: none;
    background: red;
    color: #fff;
    padding: 10px;
    margin: 0 auto 10px;
    text-align:center;
    width :25%;
}
.terms {
    padding: 2%;
    height: calc(100vh - 200px);
    overflow-y: scroll;
    background: #fff;
    margin: 5%;
    margin-top: 0;
    margin-bottom: 5px;
}
#acceptform{
    text-align:center;
    margin-top: 15px;
}
.termhead {
    text-align: center;
    padding: 2% 5%;
    font-size: 24px;
    font-weight: 700;
}
.continuebtn {
    width: 100px;
    margin: 0 auto;
}
#continuebox{
    display: none;
}
</style>
</head>

<body class="theme-orange">
	<!-- WRAPPER -->
	<div id="wrapper">
            <div class="termhead" >
                Notice
            </div>
		<div class='terms' >
            <?php 

            if(!empty($retrieve_termuser_details))
            {?>
                <center> <h4><?= $retrieve_termuser_details['t']['name'];?></h4></center>
            <?php
            }
            ?> 
            <div><?= $retrieve_termuser_details['t']['content'];?>
 
         </div>
        </div>

            <?php	echo $this->Form->create(false , ['url' => ['action' => 'acceptform'] , 'id' => "acceptform"  ]); ?>
        <div id="scrollbox">
            <p class="text-center"><i class="fa fa-arrow-down"></i> Scroll down to accept and continue</p>
        </div>
        <div id="continuebox">
            <div class="form-group clearfix">
                <label class="fancy-checkbox element-left">
                    <input type="hidden" name="id" value="<?= $retrieve_termuser_details['id'];?>">
                    <input type="checkbox" name="accept" >
                    <span>I accept</span>
                </label>								
            </div>
            <div id="error" > </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block continuebtn">Continue</button>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

	<!-- END WRAPPER -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>
<script>

$(document).ready(function () {
    $('.terms').bind('scroll', chk_scroll);
});

function chk_scroll(e) {
    var elem = $(e.currentTarget);

    //alert(elem.scrollTop());
    if (Math.round(elem[0].scrollHeight - elem.scrollTop()) == Math.round(elem.innerHeight())) {
        $("#continuebox").show();
        $("#scrollbox").hide();
    }
}

/* Login form submission */

$("#acceptform").submit(function(e){
    e.preventDefault();
   $(this).ajaxSubmit({
    success: function(response){
      if(response.result === "success" ){ 
                location.href = "dashboard" ; 
            }
    else if(response.result === "empty" ){ 
        $("#error").html("Please accept the terms & conditions") ;
    $("#error").fadeIn().delay('5000').fadeOut('slow');
              
                
            }
  else{
    $("#error").html("Some error occured. Error: "+response.result) ;
    $("#error").fadeIn().delay('5000').fadeOut('slow');
  }
    }	
  });			
  return false;
  
  });
  
  /* END */
</script>
</body>
</html>

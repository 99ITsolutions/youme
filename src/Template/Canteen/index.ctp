<style>
.meal-plan {
    padding-top: 20px !important;
}
.canteen-bg{
    position: relative;
    padding: 0;
    background-size: cover;
}
.canteen-bg input{
    width: 100%;
    padding: 18px 18px 11.5px 50px;
    border: 1px solid #eae1e1;
    font-size: 15px;
}
.canteen-bg select{
    width: 100%;
    padding: 18px 18px 18px 50px;
    border: 1px solid #eae1e1;
    font-size: 15px;
}
.canteen-bg .col-md-4{
     padding:0;
     position:relative;
}
.canteen-bg .col-md-2{
     padding:0;
}
.canteen-bg button{
    width: 100%;
    border: none;
    background: -webkit-linear-gradient(left, #ffc33c, #ffa812);
    padding: 14.3px 10px;
    color: #fff;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    font-size: 18px;
}
.canteen-bg .text h2{
color: #fff;
    font-size: 36px;
    text-align: center;
    padding-bottom: 15px;
        text-shadow: #00000026 2px 2px 2px;
}
:focus-visible {
    outline: -webkit-focus-ring-color auto 0px;
}
button:focus {
    outline: 0px dotted;
    outline: 0px auto -webkit-focus-ring-color;
}
.border-radius1{
  border-top-left-radius: 10px;  
  border-bottom-left-radius: 10px;    border-right: 1px solid #d4d4d4 !important;
}
.canteen-bg i {
    color: #a047a0;
    font-size: 20px;
    position: absolute;
    left: 14px;
    top: 19px;
    z-index: 9;
}

.mealbg1{   
    background:url(https://you-me-globaleducation.org/h2_banner-1.png);
}
.box{
    text-align: left;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease-in-out;   
    border-radius: 10px;
    border: 1px solid #ddd;
}
.box:hover{ box-shadow: 0 0 15px rgba(0, 0, 0, 0.3); }
.box img{
    width: 500px;
    height: 270px;
    transition: all 0.3s ease-in-out;
}
.box:hover img{
    transform: scale(1.3); 
    
}
.box .box-content{
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    width: 100%;
    padding: 15px 0 20px;
    transform: translateX(-50%);
    position: absolute;
    top: 46%;
    left: 60%;
    z-index: 2;
    transition: all 0.3s ease-in-out;
}
.box:hover .box-content{  }
.box .title{
     font-size: 26px !important;
    font-weight: 600;
    text-transform: uppercase;
    margin: 0 0 15px 0;
}
.box .post{
    font-size: 14px;
    font-weight: 400;
    letter-spacing: 1px;
    text-transform: capitalize;
    margin: 0 0 10px;
    display: block;
}
.box .icon{
    padding: 0;
    margin: 0;
    list-style: none;
    transition: all 0.4s ease-out;
}
.box .icon li{
    margin: 0 3px;
    display: inline-block;
}
.box .icon li a{
    color: #fff;
    font-size: 16px;
    line-height: 32px;
    height: 35px;
    width: 35px;
    border: 2px solid #fff;
    display: block;
    transition: all 0.3s;
}
.box .icon li a:hover{
    border-radius: 0 10px 0 10px;
    box-shadow: 0 0 5px #fff;
}
.box a{
border: none;
    background: -webkit-linear-gradient(left, #ffc33c, #ffa812);
    padding: 10px 25px;
    color: #fff;
    border-radius: 5px;
    font-size: 15px;   
}
.box button{
    border: none;
    background: -webkit-linear-gradient(left, #ffc33c, #ffa812);
    padding: 10px 25px;
    color: #fff;
    border-radius: 5px;
    font-size: 15px;   
}
/* ................. */
.w-1001 {
    width: 100%;
    height: 600px;
    object-fit: cover;
}
@media only screen and (max-width: 992px){
    .date-time{
        margin:0 30px;
    }
    .carousel-control-prev {
        left: -16px;
        width: 100px;
        top: -30px;
    }
    .carousel-control-next {
        right: -28px;
        width: 100px;
        top: -30px;
    }
    .meal-plan {
    padding: 0 14px;
}
}
@media only screen and (max-width: 767px)
{
    .meal-plan {
    padding: 0px;
}
    .date-time{
        margin:0px;
    }
    .w-1001 {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    .canteen-bg .col-md-4 {
        margin: 10px 30px;
    }
     .canteen-bg .col-md-2 {
        margin: 10px 30px;
    }
    .canteen-bg button{
        border-radius:0;
    }
    .canteen-bg input {
        border-radius: 0;
    }
    .box {
        text-align: left;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease-in-out;
        border-radius: 10px;
        margin:15px 5px;
    }
}
#left-sidebar { left: -250px; }
#main-content { width:100%;}
.fixed-bottom
{
  background: #27a80a;
  padding: 10px;
  color: #fff;
  font-weight:bold;
  font-size:14px;
  margin-top:20px;
  text-align:center;
}
.fourform {
    position: absolute;
    width: 100%;
    top: 32%;    left: 0;
}
.fiveform {
    width: 100%;
    top: 32% !important;    
    left: 0 !important;
}
.w-1001{
    width:100%;
}
.carousel-control-next {
    right: -15px;
    width: 77px;
}
.carousel-control-prev {
    left: -15px;
    width: 77px;
}
@media only screen and (max-width: 767px)
{
    .btn {
    font-size: 12px !important;
}
.card .header {
    padding: 5px;
}
.card .body {
    padding: 5px;
}
.fourform {
    position: absolute;
    width: 100%;
    top: 6%;
    left: 0;
    padding: 0px 10px;
    margin:0px;
}
.fiveform {
    /*position: absolute;*/
    width: 100%;
    top: 6%;
    left: 0;
    padding: 50px 0;
}
.w-1001 {
    width: 100%;
    /* height: 600px; */
}
/* ............... */
.canteen-bg .text h2 {
    font-size: 22px;
    padding: 0 30px;
}
.carousel-control-next {
    display: none;
}
.carousel-control-prev {
    display: none;
}
.box img {
    width: 100%;
}
.box .box-content {
    top: 10%;
}
.block-header h2 {
    font-size: 20px !important;
}
input[type='date'], input[type='time'] {
    -webkit-appearance: none;content: attr(placeholder);
    
     display:block;
}
input[type="date"]:not(.has-value):before{
  color: #333;
  content: attr(placeholder);
}
select {
-webkit-appearance: none;content: attr(placeholder);
    
     display:block;
  color: #333;
  content: attr(placeholder);
}
}
.carousel-item
{
    transition: transform .3s ease-in-out,-webkit-transform .3s ease-in-out !important;
}

</style>
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2253') { $lbl2253 = $langlbl['title'] ; }
    if($langlbl['id'] == '2254') { $lbl2254 = $langlbl['title'] ; }
    if($langlbl['id'] == '2255') { $lbl2255 = $langlbl['title'] ; }
    if($langlbl['id'] == '2256') { $lbl2256 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '2257') { $lbl2257 = $langlbl['title'] ; }
    if($langlbl['id'] == '2258') { $lbl2258 = $langlbl['title'] ; }
    if($langlbl['id'] == '2259') { $lbl2259 = $langlbl['title'] ; }
    if($langlbl['id'] == '2260') { $lbl2260 = $langlbl['title'] ; }
    if($langlbl['id'] == '2261') { $lbl2261 = $langlbl['title'] ; }
}
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="col-lg-6 col-5 align-left">
                        <a href="<?= $baseurl ?>Canteen/foodhistory" title="Food History"  class="btn btn-sm btn-success"><?= $lbl2254 ?></a>
                    </h2>
                    <h2 class="col-lg-6 col-7 align-right">
                        <?php if($_SESSION['dashb'] == "kinder")
                        { ?>
                            <a href="<?= $baseurl ?>kinderdashboard" title="Dashboard"  class="btn btn-sm btn-success"><?= $lbl2255 ?></a>
                        <?php }
                        else
                        { ?>
                            <a href="<?= $baseurl ?>studentdashboard" title="Dashboard"  class="btn btn-sm btn-success"><?= $lbl2255 ?></a>
                        <?php } ?>
                        
                        <a href="javascript:void(0)"  onclick="goBack()" title="Back"  class="btn btn-sm btn-success"><?= $lbl41 ?></a>
                    </h2>
                </div>
            </div>
          <?php //print_r($ci_details); ?>
            <div class="body">
                <section class="canteen-bg">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php $slider_images = explode("," , $ci_details['slider_banner_image']);
                            foreach($slider_images as $key => $si) { 
                            if($key == 0) { $active = "active"; } else { $active= ""; } ?>
                                <li data-target="#carouselExampleIndicators" data-slide-to="<?= $key ?>" class="<?= $active ?>"></li>
                            <?php } ?>
                        </ol>
                        <div class="carousel-inner">
                            <?php $slider_images = explode("," , $ci_details['slider_banner_image']);
                            foreach($slider_images as $key => $si) { 
                            if($key == 0) { $active = "active"; } else { $active= ""; } ?>
                            <div class="carousel-item <?= $active ?>">
                                <img class="d-block w-1001" src="<?= $baseurl ?>canteen_banners/<?= $si ?>" alt="First slide">
                            </div>
                            <?php } ?>
                            <!--<div class="carousel-item">
                                <img class="d-block w-1001" src="<?= $baseurl ?>canteen_banners/canteen-2.jpg" alt="Second slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-1001" src="<?= $baseurl ?>canteen_banners/canteen-3.jpg" alt="Third slide">
                            </div>-->
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>    
                    <div class="fourform">
                        <div class="text">
                            <h2><?= $lbl2253 ?></h2>
                        </div>
                        <?php echo $this->Form->create(false , ['url' => ['action' => 'getfeaturevendors'] , 'id' => "getfeaturevendors" , 'method' => "post"  ]); ?>
                        <div class="row justify-content-center date-time">
                            
                            <div class="col-md-4">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <input type="date" placeholder="Select The Date" data-date-format="dd-mm-yyyy" class="border-radius1" id="selectdate" name="seldate" onchange="this.className=(this.value!=''?'has-value':'')">    
                            </div>
                            <input type="hidden" name="selectdate" id="seldate">
                            <div class="col-md-4">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <select name="gettime" id="gettime">
                                    <option value="sel" selected="selected"><?= $lbl2256 ?></option>
                                </select>
                            </div> 
                            <div class="col-md-2">
                                <button type="submit" name="submit"><?= $lbl2257 ?></button>    
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </section>
                <section class="meal-plan">
                    <div class="row justify-content-center">
                        <div class="col-md-4">  
                            <div class="box">
                                <img src="<?= $baseurl ?>canteen_banners/<?= $ci_details['meal_image1'] ?>">
                                <div class="box-content">
                                    <!--<h3 class="title"><?= $lbl2258 ?></h3>
                                    <button><?= $lbl2261 ?></button>-->
                                    
                                    <button><?= $lbl2258 ?></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">  
                            <div class="box">
                                <img src="<?= $baseurl ?>canteen_banners/<?= $ci_details['meal_image2'] ?>">
                                <div class="box-content">
                                    <!--<h3 class="title"><?= $lbl2259 ?></h3>
                                    <button><?= $lbl2261 ?></button>-->
                                    <button><?= $lbl2259 ?></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">  
                            <div class="box">
                                <img src="<?= $baseurl ?>canteen_banners/<?= $ci_details['meal_image3'] ?>">
                                <div class="box-content">
                                    <!--<h3 class="title"><?= $lbl2260 ?></h3>
                                    <button><?= $lbl2261 ?></button>-->
                                    <button><?= $lbl2260 ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>   
<!--<footer class="fixed-bottom justify-content-center text-center">
    <div class="row container">
        <div class="col-md-12 text-center">
            <a href="<?= $baseurl ?>canteen/viewcart" style="color:#fff;">Click here for View Cart</a>
        </div>
    </div> 
</footer>-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    //$(document).on('change', '#selectdate', function() {
    $('input[type="date"]').change(function()
    {
        var seldate = this.value;
        $("#seldate").val(seldate);
        $("#gettime").html("")
        var refscrf = $("input[name='_csrfToken']").val();
        $.ajax({
            url: baseurl +"/canteen/gettime", 
            data: {"seldate":seldate, _csrfToken:refscrf}, 
			type: 'post',
            success: function(result) {
                if (result) 
                {
                    $("#gettime").html(result)
                }
                else
                {
                    swal("Something went wrong. Please try again",  "error");
                }
            }
        })
    });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
     $("#carouselExampleIndicators").carousel({
         interval : 3000,
         pause: false
     });
});
</script>
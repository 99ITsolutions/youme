<style>
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

.box img {
    width: 100% !important;
}
.box-content {
    background: #fff;
    color: #000 !important;
    left: 35% !important;
    width: 60% !important;
    text-align: center;
    border-radius: 15px;
    opacity: 0.95;
}
@media only screen and (max-width: 1024px){
    .feature-btn{
    text-align:center;
}
.feature-vendor{
    text-align: center;
}
}
@media only screen and (max-width: 767px)
{
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
        margin-bottom: 30px;
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
.fourform {
    position: absolute;
    width: 100%;
    top: 6%;
    left: 0;
    padding: 50px 0;
}
.w-1001 {
    width: auto;
    height: 600px;
}
.feature-btn{
    text-align:center;
}
.feature-vendor{
    font-size:14px;
    text-align: center;
}
.box-content {
    left: 50% !important;
    width: 90% !important;
}
}
</style>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '2288') { $lbl2288 = $langlbl['title'] ; }
    if($langlbl['id'] == '2289') { $lbl2289 = $langlbl['title'] ; }
    if($langlbl['id'] == '2255') { $lbl2255 = $langlbl['title'] ; }
} ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h4 class="col-lg-8 feature-vendor"><?= $lbl2288 ?>: <?= date("d-m-Y", strtotime($seldate)) ?> & Timings: <?= $seltime ?> </h4>
                    <h2 class="col-lg-4 col-12 align-right feature-btn">
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
          
            <div class="body">
                <section class="meal-plan">
                    <div class="row justify-content-center">
                        <?php foreach($vendor_details as $value) { ?>
                        <div class="col-md-6 col-lg-4">  
                            <div class="box">
                                <!--<img src="https://you-me-globaleducation.org/h2_banner-1.png">-->
                                <img src="<?= $baseurl ?>canteen/<?= $value['canteen_vendor']['logo'] ?>" >
                                <div class="box-content">
                                    <h3 class="title"><?php echo $value['canteen_vendor']['vendor_company'] ?></h3>
                                    <a href="<?= $baseurl ?>canteen/vendorfood/<?= strtotime($seldate) ?>/<?= $value['id'] ?>"><?= $lbl2289 ?></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
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


<style>
    /*.canteen-bg{
        background:url(<?= $baseurl ?>webroot/canteen/<?= $vendor_details['logo'] ?>);      
        padding: 10% 3% 8% 3%;
        background-size: cover;
        margin:10px 0px;
    }*/
    #left-sidebar { left: -250px; }
    #main-content { width:100%;}
    h1 {
        font-size: 1.8rem !important;
    }
    .foodimg img {
        width: 180px;
        height: 150px;
        border: 1px solid #ddd;
        border-radius: 9px;
    }
    .foodcontent
    {
        top: 20px;
        font-size: 14px;
        font-weight: bold;
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
    width: 85% !important;
    text-align: center;
    left: 50% !important;
    border-radius: 15px;
    opacity: 0.95;
}
/* ...................ipad view................ */
@media only screen and (max-width: 1024px){
    h2.col-lg-4.align-center.dtime {
    text-align: left;
}
h2.col-lg-4.align-right.vendor-btn {
    top: -22px;
}
.foodimg img {
    width: 100%;
}
.crtdiv {
    width: 140px !important;
}
.box .box-content {
    left: 50%;
    background: #fff;
    color: #000;
    padding: 25px 5px;
    border-radius: 10px;
    text-align: center;
    opacity: 0.95;
    width:90%;
    }
    .cont {

    left: 44% !important;
}
.fodcontnt {
    width: 62% !important;
}
.fixed-bottom {
    padding: 25px 50px !important;
    font-size: 16px !important;
}
span#quantity {

    padding: 10px 50px !important;

}
}
    @media only screen and (max-width: 767px)
    { 
    .card .body {
    overflow-x: hidden;
    }

    .box a {
    font-size: 12px;
    padding: 10px;
    }
    .box .box-content {
    left: 50%;
    background: #fff;
    color: #000;
    padding: 25px 5px;
    border-radius: 10px;
    text-align: center;
    opacity: 0.95;
    width: 96% !important;
    }
    .qty {
    right: 10px;
    /* top: -60% !important; */
    }
    .cont {
        left: 67px !important;
        margin-bottom:2.5rem;
    }
    .fodcontnt {
    width: 69% !important;
    float: left;
}
    .box {
    margin-top: 25px;
    }
    h1#vendorname {
    text-align: center;
    }
    .vendor-btn{
    text-align: center;
    padding:10px 0;
    }
    .foodimg img {
    width: 100%;
    height: 120px;
    }
    .foodcontent {
    top: 14px ;

    }
    .crtdiv {
    width: 140px !important;
}
div#foodlistvndr {
    padding-bottom: 35px;
}
.footer-cont{
    margin:0;
}
span#quantity {

    padding: 10px 12px !important;
    font-size:13px !important;
}
.quantity-box{
    padding:0;
}
.card-box{
    padding:0;
}
.fixed-bottom {

    padding: 20px 0 !important;
}
h2.col-lg-4.align-center.dtime {
    text-align: center !important;
}
h2.col-lg-4.align-right.vendor-btn {
    top: 0px !important;
}
 }
 .box a {
    font-size: 13px !important;
}
   
    .cont {
	      position: relative;
	      top: 15%;
	      left: 25%;
	      transform: translate(-50%, -50%);
	      -webkit-transform: translate(-50%, -50%);
	      display: inline-block;
	  }
	  .cart {
	      outline: none;
	      border: 0;
	      background: #ffa812;
	      padding: 5px;
	      color: #fff;
	      width: 100%;
	      position: relative;
	      z-index: 2;
	      cursor: pointer;
	      transition: 0.5s width;
	  }
	  .cart_clk {
	      width: calc(100% - 75px);
	  }
	  .crtdiv {
	      border: 1px solid #ffa812;
	      width: 140px;
	      border-radius: 4px;
	      position: relative;
	      background: #eee;
	  }
	  .fa {
	      font-size: 16px;
	  }
	  .cart .fa {
	      font-size: 26px;
	      position: relative;
	      padding: 0 5px 0 0;
	  }
	  .cart .fa:after {
	      position: absolute;
	      content: attr(data-before);
	      color: #000;
	      font-family: 'Lato', sans-serif;
	      left: 11px;
	      font-weight: bold;
	      top: 5px;
	      font-size: 12px;
	  }
	  .qty {
	      position: absolute;
	      right: 10px;
	      top: 50%;
	      transform: translateY(-50%);
	      -webkit-transform: translateY(-50%);
	      color: #000;
	  }
	  .dec, .inc {
	      cursor: pointer;
	  }
	  .num {
	      width: 22px;
	      display: inline-block;
	      text-align: Center;
	  }
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
	  .dtime
	  {
	      font-weight:bold;
	      font-size:15px;
	  }
      /* ............... */
        .crtdiv {
        width: 176px;

        }
        /* .qty {

            top: -41%;
        } */
        .foodimg {
            margin-top: 15px;
        }
        span#quantity
        {
            background: #800080;
            padding: 7px;
            border-radius: 5px !important;
        }
        .fodcontnt
        {
          width: 50%;
          float: left;
        }
        .remvcrt
        {
            margin-top:5px;   
        }
</style>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '2290') { $lbl2290 = $langlbl['title'] ; }
    if($langlbl['id'] == '2291') { $lbl2291 = $langlbl['title'] ; }
    if($langlbl['id'] == '2292') { $lbl2292 = $langlbl['title'] ; }
    if($langlbl['id'] == '2293') { $lbl2293 = $langlbl['title'] ; }
    if($langlbl['id'] == '2294') { $lbl2294 = $langlbl['title'] ; }
    if($langlbl['id'] == '2295') { $lbl2295 = $langlbl['title'] ; }
    if($langlbl['id'] == '2255') { $lbl2255 = $langlbl['title'] ; } 
    if($langlbl['id'] == '2289') { $lbl2289 = $langlbl['title'] ; }
} ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h1 class="col-lg-4" id="vendorname"><?= $vendor_details['vendor_company'] ?> </h1>
                    <h2 class="col-lg-4 align-center dtime">Date: <?= date("d-m-Y", $seldate) ?> & Timings: <?= $seltime ?></h2>
                    <h2 class="col-lg-4 align-right vendor-btn">
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
                <input type="hidden" id="selctdtime" value="<?= $seltime ?>">
                <input type="hidden" id="selctddate" value="<?= $seldate ?>">
                <!--<section class="canteen-bg"></section>-->
                <section class="meal-plan" id="vendorfoodlist">
                    <div class="row justify-content-center" id="chnagevendor">
                        <?php foreach($vendorfood_details as $value) { ?>
                        <div class="col-md-8 col-lg-4">  
                            <div class="box">
                                <!--<img src="https://you-me-globaleducation.org/h2_banner-1.png">-->
                                <img src="<?= $baseurl ?>canteen/<?= $value['canteen_vendor']['logo'] ?>" >
                                <div class="box-content">
                                    <h3 class="title"><?php echo $value['canteen_vendor']['vendor_company'] ?></h3>
                                    <a href="javascript:void(0);" class="vendorclick" data-strdate="<?= $seldate ?>" data-id="<?= $value['id'] ?>"><?= $lbl2289 ?></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </section>
            </div>
          
            <div class="body mb-4">
                <section class="meal-plan" id="meallist">
                    <h4><?= $lbl2291 ?></h4>
                    <div class="row  mb-2" id="foodlistvndr">
                    <?php foreach($food_details as $value) { //print_r($value); ?>
                        <div class="col-md-3 col-6 align-center foodimg">  
                            <a class="example-image-link" href="<?= $baseurl ?>c_food/<?= $value['food_item']['food_img'] ?>" data-lightbox="example-1">
                                <img src="../../../c_food/<?= $value['food_item']['food_img'] ?>"  class="example-image img">
                            </a>
                        </div>
                        <div class="col-md-3 col-6 foodcontent ">  
                            
                            <div class="fodcontnt">
                                <span><?= $value['food_item']['food_name'] ?></span>
                                <br>
                                <span>$<?= $value['price'] ?></span>
                            </div>
                            <div class="remvcrt">
                                <a href="javascript:void(0)" data-fooddt ="<?= $value['food_item']['details'] ?>" title="view food details" class="btn btn-outline-secondary removecart" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                            </div>
                        
                        
                        
                            <br>
                            <div class="cont">
                                <div class="crtdiv">
                                    <span class="qty">
                                        <span class="dec<?= $value['food_id'] ?>">
                                            <i class="fa fa-minus-square" aria-hidden="true"></i>
                                        </span>
                                        <span class="num<?= $value['food_id'] ?>">
                                        1
                                        </span>
                                        <span class="inc<?= $value['food_id'] ?>">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        </span>
                                    </span>
                                    <button id="btn<?= $value['food_id'] ?>" type="button" class="cart" data-foodid="<?= $value['food_id'] ?>" data-foodprice ="<?= $value['price'] ?>" data-vndrid="<?= $vendor_details['id'] ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <?= $lbl2292 ?></button>
                                </div>
                            </div>
                        </div>
                    
                    <?php  } ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div> 
<footer class="fixed-bottom justify-content-center text-center">
    <div class="row container footer-cont">
        <div class="col-md-0"></div>
        <div class="col-md-8 col-lg-4 col-8 text-left quantity-box" style="margin-top: 5px;">
           <span id="quantity"><?= $tquantity ?> <?= $lbl2294 ?>: $<?= $tprice ?></span>
        </div>
        <div class="col-md-4 col-lg-4 col-4 text-right card-box">
            <a href="<?= $baseurl ?>canteen/viewcart?sdate=<?= $seldate ?>&stime=<?= $seltime ?>" style="color:#fff; font-weight:bold;" class="btn btn-success"><b><?= $lbl2293 ?></b></a>
        </div>
        <div class="col-md-0"></div>
    </div> 
</footer>

<div class="modal classmodal animated zoomIn" id="shwfooddtl" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl2295 ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
    		    </button>
    	    </div>
            <div class="modal-body">
                <div id="fooddtls"></div>
            </div>
             
        </div>
    </div>
</div>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    var lbl2294 = "<?php echo $lbl2294 ?>";
    $('#vendorfoodlist').on("click",".vendorclick",function() {
        var strdate = $(this).data('strdate');
        var id = $(this).data('id');
        $('#vendorname').html(""); 
        $('#chnagevendor').html(""); 
        $('#foodlistvndr').html(""); 
        var refscrf = $("input[name='_csrfToken']").val();
        $.ajax({ 
            url: baseurl +"/canteen/vendorfoodlist", 
            data: {"strdate":strdate, "id":id, _csrfToken : refscrf}, 
            type: 'post',success: function (result) 
            {       
                console.log(result);
                if (result) 
                {
                    $('#vendorname').html(result.vendorname); 
                    $('#chnagevendor').html(result.chnagevendor); 
                    $('#foodlistvndr').html(result.foodlistvndr); 
                }
            }
        });
    });
    $('#meallist').on("click",".cart",function() {
        var fid = $(this).data('foodid');
        var fp = $(this).data('foodprice');
        var vid = $(this).data('vndrid');
        //alert(fid);
        $(".card-box").hide();
        $('#btn'+fid).toggleClass("cart_clk");
        $('#btn'+fid+' .fa').attr('data-before', '1');
        $('#quantity').html("");
        $('#btn'+fid).html('<i class="fa fa-shopping-cart" aria-hidden="true"></i> ');
        
        var stime = $("#selctdtime").val();
        var sdate = $("#selctddate").val();
        
        var refscrf = $("input[name='_csrfToken']").val();
        $.ajax({ 
            url: baseurl +"/canteen/cartdata", 
            data: {"fid":fid, "fp":fp, 'vid':vid, 'stime':stime, 'sdate':sdate, 'qnty':1,  _csrfToken : refscrf}, 
            type: 'post',success: function (result) 
            {       
                if (result) 
                {
                    $('#quantity').html(result.quantity+" "+lbl2294+": $"+result.price);
                    $(".card-box").show();
                }
            }
        });
     
        var prnum = $('.num'+fid).text();
        $('.inc'+fid).click(function() {
            if (prnum > 0) {
                prnum++;
                $('.num'+fid).text(prnum);
                $('#btn'+fid+' .fa').attr('data-before', prnum);
                $('#foodqnty'+fid).val(prnum);
                
                $('#btn'+fid).html('<i class="fa fa-shopping-cart" aria-hidden="true"></i> ');
                $(".card-box").hide();
                
                $('#quantity').html("");
                var stime = $("#selctdtime").val();
                var sdate = $("#selctddate").val();
        
                var refscrf = $("input[name='_csrfToken']").val();
                $.ajax({ 
                    url: baseurl +"/canteen/cartdata", 
                    data: {"fid":fid, "fp":fp, 'vid':vid, 'stime':stime, 'sdate':sdate, 'qnty':prnum,  _csrfToken : refscrf}, 
                    type: 'post',success: function (result) 
                    {       
                        if (result) 
                        {
                            $('#quantity').html(result.quantity+" "+lbl2294+": $"+result.price);
                            $(".card-box").show();
                        }
                    }
                });                                                                    
            }
    
        });
        $('.dec'+fid).click(function() {
            if (prnum > 1) {
                prnum--;
                $('.num'+fid).text(prnum);
                $('#btn'+fid+' .fa').attr('data-before', prnum);
                $('#foodqnty'+fid).val(prnum);
                
                $('#btn'+fid).html('<i class="fa fa-shopping-cart" aria-hidden="true"></i> ');
                var stime = $("#selctdtime").val();
                var sdate = $("#selctddate").val();
                $('#quantity').html("");
                $(".card-box").hide();
                var refscrf = $("input[name='_csrfToken']").val();
                $.ajax({ 
                    url: baseurl +"/canteen/cartdata", 
                    data: {"fid":fid, "fp":fp, 'vid':vid, 'stime':stime, 'sdate':sdate, 'qnty':prnum,  _csrfToken : refscrf}, 
                    type: 'post',success: function (result) 
                    {       
                        if (result) 
                        {
                            $('#quantity').html(result.quantity+" "+lbl2294+": $"+result.price); 
                            $(".card-box").show();
                        }
                    }
                });     
            }
        });
    });
    $('#foodlistvndr').on("click",".removecart",function() {
        $("#fooddtls").html('');
        var fd = $(this).data('fooddt');
        //alert(fd);
        $("#shwfooddtl").modal("show");
        $("#fooddtls").html(fd);
    });
});
</script>
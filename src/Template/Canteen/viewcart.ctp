<style>
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

    .cont {
        position: relative;
        top: 15%;
        left: 25%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
        display: inline-block;
    }
    .cart_clk {
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
        width: calc(100% - 75px);
    }
    .crtdiv {
        border: 1px solid #ffa812;
        width: 190px;
        border-radius: 4px;
        position: relative;
        background: #eee;
    }
    .fa {
        font-size: 16px;
    }
    .cart_clk .fa {
        font-size: 26px;
        position: relative;
        padding: 0 5px 0 0;
    }
    .cart_clk .fa:after {
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
	  .fodcontnt
	  {
	      width: 50%;
          float: left;
	  }
	  .remvcrt
	  {
	        margin-top:5px;   
	  }
	  .remarknote
	  {
            /*background: #f5a4a4;
            border: 1px solid #ff0000;*/
            padding: 10px;
            /*color: #ff0000;*/
            color:#27a80a;
            font-weight:bold;
            /*border-radius: 5px;*/
	  }
      @media only screen and (max-width: 1024px){
        .foodimg img {
        width: 80%;
        }
        .cont {
    left: 28%;
 
}
.row.remark-section {
    margin-top: 2rem;
}
      }
      @media only screen and (max-width: 767px)
    {
        .foodimg img {
        width: 100%;
        }
        .cont {
        top: 22%;
        left: 50%;
        }
        .crtdiv {

        width: 300px;
        }
        .fodcontnt {
    width: 87% !important;
}
.remarknote {
    padding: 10px 20px 40px 20px;
}
.view-cart-btn{
    text-align:center;
    padding-top: 20px;
}
.view-cart-time{
    text-align:center;
}
.view-cart{
    text-align:center;
}
.place-btn{
    text-align:center !important;
    margin-top:12px;
}
.foodcontent {
    margin-bottom: 3rem;
}
    }
    @media only screen and (max-width: 767px){
        .remarknote {
    padding: 10px 20px 83px 20px;
}
.total-balance-btn{
    text-align:center !important;
    margin-bottom:1rem;
    width:100%;
}
    }
</style>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '2255') { $lbl2255 = $langlbl['title'] ; }
    if($langlbl['id'] == '95') { $lbl95 = $langlbl['title'] ; }
    if($langlbl['id'] == '2295') { $lbl2295 = $langlbl['title'] ; }
    if($langlbl['id'] == '2296') { $lbl2296 = $langlbl['title'] ; }
    if($langlbl['id'] == '2297') { $lbl2297 = $langlbl['title'] ; }
    if($langlbl['id'] == '2298') { $lbl2298 = $langlbl['title'] ; }
    if($langlbl['id'] == '2299') { $lbl2299 = $langlbl['title'] ; }
    if($langlbl['id'] == '2292') { $lbl2292 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2300') { $lbl2300 = $langlbl['title'] ; }
    if($langlbl['id'] == '2301') { $lbl2301 = $langlbl['title'] ; }
    if($langlbl['id'] == '2302') { $lbl2302 = $langlbl['title'] ; }
    if($langlbl['id'] == '2303') { $lbl2303 = $langlbl['title'] ; }
    if($langlbl['id'] == '2304') { $lbl2304 = $langlbl['title'] ; }
    if($langlbl['id'] == '2305') { $lbl2305 = $langlbl['title'] ; }
    if($langlbl['id'] == '2306') { $lbl2306 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2307') { $lbl2307 = $langlbl['title'] ; }
    if($langlbl['id'] == '2308') { $lbl2308 = $langlbl['title'] ; }
    if($langlbl['id'] == '2309') { $lbl2309 = $langlbl['title'] ; }
    if($langlbl['id'] == '2310') { $lbl2310 = $langlbl['title'] ; }
    if($langlbl['id'] == '2311') { $lbl2311 = $langlbl['title'] ; }
    if($langlbl['id'] == '1595') { $lbl1595 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '2313') { $lbl2313 = $langlbl['title'] ; }
    if($langlbl['id'] == '2314') { $lbl2314 = $langlbl['title'] ; }
    if($langlbl['id'] == '2315') { $lbl2315 = $langlbl['title'] ; }
    if($langlbl['id'] == '2316') { $lbl2316 = $langlbl['title'] ; }
    if($langlbl['id'] == '2317') { $lbl2317 = $langlbl['title'] ; }
    if($langlbl['id'] == '2318') { $lbl2318 = $langlbl['title'] ; }
    if($langlbl['id'] == '2319') { $lbl2319 = $langlbl['title'] ; }
    if($langlbl['id'] == '2320') { $lbl2320 = $langlbl['title'] ; }
    if($langlbl['id'] == '2321') { $lbl2321 = $langlbl['title'] ; }
    if($langlbl['id'] == '2322') { $lbl2322 = $langlbl['title'] ; }
    if($langlbl['id'] == '2323') { $lbl2323 = $langlbl['title'] ; }
    if($langlbl['id'] == '2324') { $lbl2324 = $langlbl['title'] ; }
    if($langlbl['id'] == '2325') { $lbl2325 = $langlbl['title'] ; }
    if($langlbl['id'] == '2326') { $lbl2326 = $langlbl['title'] ; }
    if($langlbl['id'] == '2327') { $lbl2327 = $langlbl['title'] ; }
    if($langlbl['id'] == '2328') { $lbl2328 = $langlbl['title'] ; }
} ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h1 class="col-lg-4 view-cart"><?= $lbl2296 ?></h1>
                    <h2 class="col-lg-4 align-center view-cart-time"><b>Date: <?= date("d-m-Y", $seldate) ?> & Timings: <?= $seltime ?></b></h2>
                    <h2 class="col-lg-4 align-right view-cart-btn">
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
                <!--<section class="canteen-bg"></section>-->
            </div>
            <input type="hidden" id="selctdtime" value="<?= $seltime ?>">
            <input type="hidden" id="selctddate" value="<?= $seldate ?>">
            <div class="body mb-4">
                <section class="meal-plan" id="meallistd">
                    
                    <div class="row">
                    <?php foreach($cartdata as $value) { ?>
                        <div class="col-md-6 col-lg-3  mb-2 col-12 align-center foodimg" id="imgfood<?= $value['food_id'].$value['vendor_id'] ?>">  
                            <a class="example-image-link" href="<?= $baseurl ?>c_food/<?= $value['food_item']['food_img'] ?>" data-lightbox="example-1">
                                <img src="<?= $baseurl ?>c_food/<?= $value['food_item']['food_img'] ?>"  class="example-image img">
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3 col-sm-4 foodcontent" id="contentfood<?= $value['food_id'].$value['vendor_id']  ?>"> 
                        
                            <span><?= $value['canteen_vendor']['vendor_company'] ?></span>
                            <br>
                            <div class="fodcontnt">
                                <span><?= $value['food_item']['food_name'] ?></span>
                                <br>
                                <span><?= $lbl2297 ?>: $<?= $value['price'] ?></span>
                                <br>
                                <span><?= $lbl2298 ?>: <span class="cp<?= $value['food_id'].$value['vendor_id'] ?>">$<?= $value['quantity']*$value['price'] ?></span></span>
                            </div>
                            <div class="remvcrt">
                                <a href="javascript:void(0)" data-id="<?= $value['id'] ?>" data-foodid="<?= $value['food_id'] ?>" data-vndrid="<?= $value['vendor_id'] ?>" class="btn btn-outline-danger removecart" ><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </div>
                        
                            <br>
                            <div class="cont">
                                <div class="crtdiv">
                                    <span class="qty">
                                        <span id="dec<?= $value['food_id'].$value['vendor_id']  ?>" class="dec_cart" data-foodid="<?= $value['food_id'] ?>" data-foodprice ="<?= $value['price'] ?>" data-vndrid="<?= $value['vendor_id'] ?>">
                                            <i class="fa fa-minus-square" aria-hidden="true"></i>
                                        </span>
                                        <span class="num<?= $value['food_id'].$value['vendor_id'] ?>">
                                        <?= $value['quantity'] ?>
                                        </span>
                                        <span id="inc<?= $value['food_id'].$value['vendor_id'] ?>"  class="inc_cart" data-foodid="<?= $value['food_id'] ?>" data-foodprice ="<?= $value['price'] ?>" data-vndrid="<?= $value['vendor_id'] ?>">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        </span>
                                    </span>
                                    <button id="btn<?= $value['food_id'].$value['vendor_id'] ?>" type="button" class="cart_clk" data-foodid="<?= $value['food_id'] ?>" data-foodprice ="<?= $value['price'] ?>" data-vndrid="<?= $value['vendor_id'] ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    
                    <?php } ?>
                    </div>
                </section>
                
                <div class="row remark-section">
                    <div class="col-md-12 mb-4">
                        <label><?= $lbl2299 ?>*:</label>
                        <textarea class="form-control" name="remarks" rows ="3" id="remark"></textarea>
                        <span class="wordleft"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 remarknote mb-4">
                        <!--Avertissement ! Veuillez nous informer de toute(s) allergie(s) ou préférence(s) alimentaire(s), le cas échéant, lors de votre commande. Nous ne serons pas responsables des réactions allergiques ou des problèmes pouvant résulter de la consommation de nos aliments contenant des traces éventuelles de gluten, de fruits à coque, de soja, de lait et d'arachides dans nos produits .-->
                        Mise en garde: “Nous utilisons actuellement différents produits alimentaires qui peuvent provoquer des réactions allergiques. Nous conseillons donc à notre précieuse clientèle de bien vouloir nous informer de toute allergie ou préférences alimentaires, le cas échéant, lors de votre commande. Nous déclinons toutes les responsabilités pouvant résulter de la consommation de nos aliments contenant des traces éventuelles de gluten, de fruits à coque, de soja, de lait et d'arachides dans nos produits. Certains de nos plats peuvent contenir du blé, des crustacés, poisson, des œufs, des noix et du lait ou même entrer en contact avec ces aliments.”
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div> 
<footer class="fixed-bottom justify-content-center text-center">
    <div class="row container">
        <div class="col-md-3"></div>
        <div class="col-md-3 text-left total-balance-btn">
           <span id="bg" class="btn btn-success"><?= $lbl2302 ?>: <?= "$".$balance ?></span>
           <input type="hidden" id="tbalance" value="<?= $balance ?>">
           <input type="hidden" id="dailylimit" value="<?= $dailylimt ?>">
           <input type="hidden" id="ttspentdayamt" value="<?= $ttspentdayamt ?>">
           <input type="hidden" id="cpin" value="<?= $canteenpin ?>">
        </div>
        <div class="col-md-3 text-center">
           <span id="bg" class="btn btn-success"><?= $lbl2301 ?>: <span id="cartprice"><?= "$".$cartamt ?></span></span>
           <input type="hidden" id="pricecart" value="<?= $cartamt ?>">
        </div>
        <div class="col-md-3 col-12 place-btn text-right">
            <a href="javascript:void(0)" style="color:#fff;" class="btn btn-success" id="placeorder"><?= $lbl2300 ?></a>
        </div>
    </div> 
</footer>

<!------------------ Add Class --------------------->

<div class="modal classmodal animated zoomIn" id="verifypin" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl2304 ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['id' => "addcanteenform" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?= $lbl2305 ?>*</label>
                            <input type="text" class="form-control" id="orderpin" maxlength="4" minlength="4"  required placeholder="<?= $lbl2306 ?>*">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary ordercpin" id="ordercpin" style="margin-right: 10px;"><?= $lbl95 ?></button>
                    </div>
                </div>
            <?php echo $this->Form->end(); ?>
            </div>
             
        </div>
    </div>
</div>          

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
var lbl2303 = "<?php echo $lbl2303 ?>";
var lbl2307 = "<?php echo $lbl2307 ?>";
var lbl2308 = "<?php echo $lbl2308 ?>";
var lbl2309 = "<?php echo $lbl2309 ?>";
var lbl2310 = "<?php echo $lbl2310 ?>";
var lbl2311 = "<?php echo $lbl2311 ?>";
var lbl1595 = "<?php echo $lbl1595 ?>";

var lbl2313 = "<?php echo $lbl2313 ?>";
var lbl2314 = "<?php echo $lbl2314 ?>";
var lbl2315 = "<?php echo $lbl2315 ?>";
var lbl2316 = "<?php echo $lbl2316 ?>";
var lbl2317 = "<?php echo $lbl2317 ?>";
var lbl2318 = "<?php echo $lbl2318 ?>";
var lbl2319 = "<?php echo $lbl2319 ?>";
var lbl2320 = "<?php echo $lbl2320 ?>";

var lbl2321 = "<?php echo $lbl2321 ?>";
var lbl2322 = "<?php echo $lbl2322 ?>";
var lbl2323 = "<?php echo $lbl2323 ?>";
var lbl2324 = "<?php echo $lbl2324 ?>";
var lbl2325 = "<?php echo $lbl2325 ?>";
var lbl2326 = "<?php echo $lbl2326 ?>";
var lbl2327 = "<?php echo $lbl2327 ?>";
var lbl2328 = "<?php echo $lbl2328 ?>";
var wordLeng = 30,
lengt; // Maximum word length
$('#remark').keydown(function(event) {	
	lengt = $('#remark').val().split(/[\s]+/);
	if (lengt.length > wordLeng) { 
		if ( event.keyCode == 46 || event.keyCode == 8 ) {// Allow backspace and delete buttons
        } else if (event.keyCode < 48 || event.keyCode > 57 ) {//all other buttons
        	event.preventDefault();
        }
	}
	console.log(lengt.length + " words are typed out of an available " + wordLeng);
	wordsLeft = (wordLeng) - lengt.length;
	$('.wordleft').html(wordsLeft+ ' words left');
	if(wordsLeft == 0) {
		$('.wordleft').css({ 'background':'red' }).prepend('<i class="fa fa-exclamation-triangle"></i>');
	}
});
$(document).ready(function() {
    $('#placeorder').click(function(e) { 
        //alert(1);
        var tb = $("#tbalance").val();
        var pc = $("#pricecart").val();
        var stime = $("#selctdtime").val();
        var sdate = $("#selctddate").val();
        var bal = tb-pc;
        var dlimt = $("#dailylimit").val();
        var tdsa = $("#ttspentdayamt").val();
        
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var controller = window.location.pathname.split('/')[2];
        //alert(baseurl);
        if(parseInt(pc) == 0)
        {
            swal(lbl2323, lbl2324, "error");
        }
        else
        {
            if(bal >= 0)
            { 
                if(parseInt(tdsa) == 0)
                {
                    if(parseInt(dlimt) < parseInt(pc))
                    {
                        //alert("Cdd");
                        //swal("Alert!", "You have exceeded you daily limit amount of $"+dlimt+". Please adjust your order!", "error");
                        swal({
                            title: lbl2303,
                            type: "error",
                            text: "<span style='color:#ee2e2e'>"+lbl2315+" $"+dlimt+". "+lbl2316+"</span>",
                            html: true
                        });
                    }
                    else
                    {
                        $("#verifypin").modal("show");
                    }
                }
                else
                {
                    if(parseInt(tdsa) >= parseInt(dlimt))
                    {
                        swal({
                            title: lbl2303,
                            type: "error",
                            text: "<span style='color:#ee2e2e'>"+lbl2325+" $"+tdsa+".</span>",
                            html: true
                        });
                    }
                    else
                    {
                        var remamt = dlimt-tdsa;
                        if(parseInt(remamt) < parseInt(pc))
                        {
                            swal({
                                title: "Alert!",
                                type: "error",
                                text: "<span style='color:#ee2e2e'>"+lbl2326+" $"+dlimt+" "+lbl2327+" $"+tdsa+" . "+lbl2328+" $"+remamt+"!</span>",
                                html: true
                            });
                        }
                        else
                        {
                            $("#verifypin").modal("show");
                        }
                    }
                }
            }
            else
            {
                swal(lbl2318, lbl2317, "error");
            }
        }
    });
    
    $('#ordercpin').click(function(e) { 
        //alert(1);
        var cpin = $("#cpin").val();
        var opin = $("#orderpin").val();
        var tb = $("#tbalance").val();
        var pc = $("#pricecart").val();
        var stime = $("#selctdtime").val();
        var sdate = $("#selctddate").val();
        var bal = tb-pc;
        var dlimt = $("#dailylimit").val();
        
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var controller = window.location.pathname.split('/')[2];
        if(cpin == "")
        {
            swal(lbl2321, lbl2322, "error");
        }
        else
        {
            if(cpin == opin)
            { 
                
                $("#ordercpin").prop("disabled", true);
                var remark = $("#remark").val();
                var refscrf = $("input[name='_csrfToken']").val();
                $.ajax({
                    url: baseurl +"/canteen/placeorder", 
                    data: {"tb":tb, "pc":pc, 'stime':stime, 'sdate':sdate, _csrfToken:refscrf, 'remark':remark}, 
        			type: 'post',
                    success: function(html){
                        console.log(html);
                        $("#ordercpin").prop("disabled", false);
                        if (html.result === "success") 
                        {
                            swal({
                        		title: lbl2313,
                        		text: "<b>"+lbl2314+" "+html.orderno+"</b>",
                        		imageUrl: 'https://i.imgur.com/4NZ6uLY.jpg',
                        		html: true
                            }, function() {
                                location.href = "https://you-me-globaleducation.org/school/canteen/";
                        	});
                        	$("#verifypin").modal("hide");
                        	setTimeout(function(){ location.href = "https://you-me-globaleducation.org/school/canteen/"; }, 30000);
                        }
                        else{
                            swal(lbl2307,  "error");
                        }
                    }
                })
            }
            else
            {
                swal(lbl2319, lbl2320, "error");
            }
        }
    });
    
    $('#meallistd').on("click",".removecart",function() {
        var id = $(this).data('id');
        var fid = $(this).data('foodid');
        var vid = $(this).data('vndrid');
        var refscrf = $("input[name='_csrfToken']").val();
        swal({
            title: lbl1595,
            text: lbl2310,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            confirmButtonText: lbl2311,
            closeOnConfirm: false,
            cancelButtonText: cncl,  
            showLoaderOnConfirm: true
        }, function () {
            $.ajax({
                url: baseurl +"/canteen/removecart", 
                data: {"id":id,  _csrfToken : refscrf}, 
    			type: 'post',
                success: function(result){
                    if (result) 
                    {
                        //swal("Success","Item has been removed", "success");
                        swal(lbl2308, lbl2309, "success");
                        location.reload();
                        //$('#imgfood'+fid+vid).css('display','none');
                        //$('#contentfood'+fid+vid).css('display','none');
                    }
                    else{
                        swal(lbl2307,  "error");
                    }
                }
            })
        });
        
        
    });
    
    $('#meallistd').on("click",".inc_cart",function() {
        var fid = $(this).data('foodid');
        var fp = $(this).data('foodprice');
        var vid = $(this).data('vndrid');
        //alert("inc")
        var prnum = $('.num'+fid+vid).text();
        //$('#inc'+fid+vid).click(function() {
            if (prnum >= 0) {
                //alert(prnum);
                prnum++;
                //alert(prnum);
                $('.num'+fid+vid).text(prnum);
                $('#btn'+fid+vid+' .fa').attr('data-before', prnum);
                $('#foodqnty'+fid+vid).val(prnum);
                
                $('#btn'+fid+vid).html('<i class="fa fa-shopping-cart" aria-hidden="true"></i> ');
                var stime = $("#selctdtime").val();
                var sdate = $("#selctddate").val();
                $('#cartprice').html("");
                $('.cp'+fid+vid).html("");
                var refscrf = $("input[name='_csrfToken']").val();
                $.ajax({ 
                    url: baseurl +"/canteen/cartdata", 
                    data: {"fid":fid, "fp":fp, 'vid':vid, 'stime':stime, 'sdate':sdate, 'qnty':prnum,  _csrfToken : refscrf}, 
                    type: 'post',success: function (result) 
                    {       
                        if (result) 
                        {
                            $('#pricecart').val("");
                            $('#cartprice').html("$"+result.price); 
                            $('#pricecart').val(result.price);
                            $('.cp'+fid+vid).html("$"+result.foodprice);
                        }
                    }
                });                                                                    
            }
    
        //});
    });
    
    $('#meallistd').on("click",".dec_cart",function() {
        var fid = $(this).data('foodid');
        var fp = $(this).data('foodprice');
        var vid = $(this).data('vndrid');
        //alert("Dec")
        var prnum = $('.num'+fid+vid).text();
        //$('#dec'+fid+vid).click(function() {
            if (prnum > 0 ) {
                //alert(prnum);
                prnum--;
                //alert(prnum)
                $('.num'+fid+vid).text(prnum);
                $('#btn'+fid+vid+' .fa').attr('data-before', prnum);
                $('#foodqnty'+fid+vid).val(prnum);
                var stime = $("#selctdtime").val();
                var sdate = $("#selctddate").val();
                $('#btn'+fid+vid).html('<i class="fa fa-shopping-cart" aria-hidden="true"></i> ');
                
                if(prnum == 0)
                {
                    $('#imgfood'+fid+vid).css('display','none');
                    $('#contentfood'+fid+vid).css('display','none');
                }
                $('.cp'+fid+vid).html("");
                $('#cartprice').html("");
                var refscrf = $("input[name='_csrfToken']").val();
                $.ajax({ 
                    url: baseurl +"/canteen/cartdata", 
                    data: {"fid":fid, "fp":fp, 'vid':vid, 'stime':stime, 'sdate':sdate, 'qnty':prnum,  _csrfToken : refscrf}, 
                    type: 'post',success: function (result) 
                    {       
                        if (result) 
                        {
                            $('#pricecart').val("");
                            $('#cartprice').html("$"+result.price); 
                            $('#pricecart').val(result.price);
                            $('.cp'+fid+vid).html("$"+result.foodprice);
                        }
                    }
                });     
            }
        //});
    });

});
</script>
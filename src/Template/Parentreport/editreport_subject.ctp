<?php

    $tchr = array('No','Yes' );

    $emp = array('No','Yes' );

?>

<style>

  .theme-orange .sidebar-nav .metismenu > li.active > a, .theme-orange .sidebar-nav .metismenu > li.active i {

    border-left-color: #1f1f4e !important;

    color: #f1f1f1 !important;

    background-color: #1f1f4e !important;

}
.subect-sec input{
    height: 52px !important;
}
.subect-sec label{
   
}
    .hide

    {

        display:none;

    }

    .input-group-text{

        font-size:0.8em;

    }



    table.dataTable {

    clear: none !important;

    margin-top: 0px !important;

    margin-bottom: 0px !important;

    max-width: none !important;

    border-collapse: collapse !important;

}



button, input {

    overflow: visible;

    border-color: transparent;

}



.report-but-yel{

    background: -webkit-linear-gradient(top, #1f1f4e, #1f1f4e);

    width: 130px !important;

    height: 50px !important;

    font-size: 16px !important;

     color: #fff;

}

.report-but-yel:hover{

    background-color: #FBC358;

     background: -webkit-linear-gradient(top, #FBC358, #FBC358);

    color: #000;

}



.report-but-blue{

background: linear-gradient( 180deg, #1f1f4e 0%, #1f1f4e 50%, #1f1f4e 100%);

    width: 130px !important;

    color: #fff;

    height: 50px !important;

    font-size: 16px !important;

}

.report-but-yel:hover{

   background-color: #FBC358;

   background: linear-gradient( 180deg, #FBC358 0%, #FBC358 50%, #FBC358 100%);

    color: #000;

}





/* Chrome, Safari, Edge, Opera */

input::-webkit-outer-spin-button,

input::-webkit-inner-spin-button {

  -webkit-appearance: none;

  margin: 0;

}



/* Firefox */

input[type=number] {

  -moz-appearance: textfield;

}



.container {

    max-width: 100% !important;

}

#left-sidebar { left: -250px;}
#main-content { width: 100%; }


</style>

<link rel='stylesheet' type='text/css' media='screen' href='<?= $baseurl ?>css/style-report.css'>

<div class="row clearfix">

    <div class="col-lg-12 col-md-12 col-sm-12">

        <div class="card">

            <div class="header row">

                <h2 class="col-md-6 align-left heading"><?php echo $classes_name['c_name'] ."-" . $classes_name['c_section']." (" . $classes_name['school_sections'].")";?></h2>

                <h2 class="col-md-6 align-right"><a href="<?= $baseurl ?>teacherclass/classallSubjects?classid=<?=$_GET['classid'];?>&subid=<?=$_GET['subid'];?>" title="Back" class=" btn btn-primary" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1162') { echo $langlbl['title'] ; } } ?> </a></h2>

            </div>



<!----Form starts ---------------->

<?php echo $this->Form->create(false , ['url' => ['action' => 'subreportsubject'] , 'id' => "subreportform" , 'method' => "post"  ]); ?>



<input type="hidden" name="studentid" value="<?=$_GET['studentid'];?>">

<input type="hidden" name="classid" value="<?=$_GET['classid'];?>">

<input type="hidden" name="subiding" value="<?=$_GET['subid'];?>">



            <div class="body body-bg" style="text-transform: uppercase;">

              

                <div class="row clearfix">

                    <div class="col-sm-12" style="padding: 0px;">

                        <div class="table-responsive">

                         

                         <div class="container">

<div class="row header-sec">

<div class="col-md-2">

 <div class="logo">

 <img src="<?= $baseurl ?>img/R-icon-2.png">  

 </div> 

</div>

<div class="col-md-8">

  <div class="main-head">

   <h3 style="font-size:  24px !important; color: #000;line-height: 42px;text-transform: uppercase;font-weight: 600;font-family: ui-serif"><span style="font-size: 29px !important;
    color: #000;
    line-height: 42px;
    text-transform: capitalize;
    font-weight: 600;
    font-family: Ubuntu,sans-serif">République Démocratique Du Congo</span><br>

  Ministere De L'enseignement Primaire<span style="font-family: STIX Two Math script=latin rev=2;">,</span> Secondaire <br>Et Technique

</h3>

</div>

</div>

<div class="col-md-2"> <img src="<?= $baseurl ?>img/R-icon-1.png">  </div>  

</div>   

</div>

<div class="container">

<div class="up-header">

<div class="pol-md-12">

  <table id="example" cellpadding="0" cellspacing="0" class="display nowrap no-footer head-bot" style="width: 100%;" role="grid" aria-describedby="example_info">

                        <thead> </thead>

                        <tbody>

                        <tr class="odd">

                        <td style="width: 141px;">N ID.</td>

                     <?php if($report_marks['nid'] == ""){?>  



                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nid[]" maxlength="1" required="required"></td>

                 <?php }else{ ?>

                        <?php 

                            $exnid = $report_marks['nid'];

                            $arrexnid = explode(",", $exnid);

                            foreach ($arrexnid as $val) { 

                        ?>     

                        <td><input type="text" name="nid[]" value="<?=$val;?>" readonly="readonly" maxlength="1" required="required" style="font-weight: bold;"></td>

                        <?php }  ?>

                <?php } ?>

                   

                      </tr>



                    </tbody>



                                    </table>

</div>





<div class="pol-md-12">

  <table id="example" class="display nowrap no-footer head-bot" style="width: 100%; border-bottom: 2px solid #333;" role="grid" aria-describedby="example_info">

<tbody>

<tr class="prov-td">

  <td style="width: 30px;">Province:</td>

  <td style="border-right: 2px solid #333 !important;"><input type="text" name="province" value="<?=$report_marks['province'];?>" readonly="readonly" required="required" class="" style="width: 100%; font-weight:bold;"></td>

</tr>

</tbody>

                                    </table>

</div>



<div class="row">



<div class="col-md-6">

<div class="inline">  

<div class="lable">

  Ville:

</div>

<div class="ville-bot">

<input type="text" name="" value="<?=$school_name['city'];?>"  readonly style="font-weight: bold;">

</div>

</div>



<div class="inline">  

<div class="lable">

 Commune / Ter.(1)

</div>

<div class="ville-bot">

<input type="text" name="" value="<?=$school_name['ph_no'];?>" readonly style="font-weight: bold;">



</div>

</div>



<div class="inline">  

<div class="lable">

Ecole

</div>

<div class="ville-bot">

<input type="text" name="" value="<?=$school_name['comp_name'];?>" readonly style="font-weight: bold;">

</div>

</div>



<div class="inline">  

<div class="lable" style="margin-top: 22px;">

Code

</div>

<div class="ville-bot">

<table id="example" class="display nowrap dataTable no-footer head-bot" style="width: 100%; margin-top: 6px;" role="grid" aria-describedby="example_info">

                        <thead> </thead>

                        <tbody>

                        <tr class="odd">

                    <?php if($report_marks['code'] == ""){?>     

                        <td><input type="text" name="code[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="code[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="code[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="code[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="code[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="code[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="code[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="code[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="code[]" maxlength="1" required="required"></td>

                      <?php }else{ ?>

                        <?php 

                            $excode = $report_marks['code'];

                            $arrcode = explode(",", $excode);

                            foreach ($arrcode as $val) { 

                        ?>     

                        <td><input type="text" name="code[]" maxlength="1" value="<?=$val;?>" readonly="readonly" required="required" style="font-weight: bold;"></td>

                        <?php }  ?>

                <?php } ?>   

                      </tr>



                    </tbody>



                                    </table>

</div>

</div>



</div>



<div class="col-md-6 border-main-left">

<div class="row">

<div class="col-md-9">



<div class="inline">  

<div class="lable">

Eleve

</div>

<div class="ville-bot">

<input type="text" name="studentname" style="font-weight: bold;" value="<?php echo $student_name['f_name'];?> <?php echo $student_name['l_name'];?>" readonly>

</div>

</div>

</div>



<div class="col-md-3">



<div class="inline">  

<div class="lable">

Sexe

</div>

<div class="ville-bot">

<input type="text" name=""  value="<?php echo $student_name['gender'];?>" readonly>

</div>

</div>

</div>



<div class="col-md-8">



<div class="inline">  

<div class="lable">

NE (E)A:

</div>

<div class="ville-bot">

<input type="text" name="neea" value="<?=$report_marks['neea'];?>" style="font-weight: bold;" readonly="readonly"  required="required">

</div>

</div>

</div>



<div class="col-md-4">



<div class="inline">  

<div class="lable">

LE

</div>

<?php

$exlethe = explode(",", $report_marks['lethe']);

?>

<div class="ville-bot">

<?php if($exlethe[0] == ""){?>

<input type="text" name="lethe[]" maxlength="2" required="required" readonly="readonly">

<?php }else{?> 

<input type="text" name="lethe[]" maxlength="2" value="<?=$exlethe[0];?>" style="font-weight: bold;" required="required" readonly="readonly">

<?php } ?>

</div>

<div class="lable">

/

</div>

<div class="ville-bot">

<?php if($exlethe[0] == ""){?>

<input type="text" name="lethe[]" maxlength="2" required="required">

<?php }else{?> 

<input type="text" name="lethe[]" maxlength="2" value="<?=$exlethe[1];?>" style="font-weight: bold;" required="required" readonly="readonly">

<?php } ?>

</div>



<div class="lable">

/

</div>

<div class="ville-bot">

<?php if($exlethe[0] == ""){?>

<input type="text" name="lethe[]" maxlength="4" required="required" style="font-weight: bold; font-weight: bold;width: 34px;">

<?php }else{?> 

<input type="text" name="lethe[]" maxlength="4" value="<?=$exlethe[2];?>" style="font-weight: bold; font-weight: bold;width: 34px;" required="required" readonly="readonly">

<?php } ?>

</div>

</div>

</div>



<div class="col-md-12">



<div class="inline">  

<div class="lable">

CLASSE

</div>

<div class="ville-bot">

<input type="text" name="eleve" value="<?php echo $classes_name['c_name'] ."-" . $classes_name['c_section']." (" . $classes_name['school_sections'].")";?>" style="font-weight: bold;" required="required" readonly="readonly">

</div>

</div>

</div>

<div class="col-md-12">

<div class="inline">  

<div class="lable" style="margin-top: 22px;">

N* PERM

</div>

<div class="ville-bot">

<table id="example" class="display nowrap dataTable no-footer head-bot" style="width: 100%;    margin-top: 6px;" role="grid" aria-describedby="example_info">

                        <thead> </thead>

                        <tbody>

                        <tr class="odd">

                        <?php if($report_marks['nperm'] == ""){?>    

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <td><input type="text" name="nperm[]" maxlength="1" required="required"></td>

                        <?php }else{ ?>

                        <?php 

                            $exnperm = $report_marks['nperm'];

                            $arrnperm = explode(",", $exnperm);

                            foreach ($arrnperm as $val) { 

                        ?>     

                        <td><input type="text" name="nperm[]" value="<?=$val;?>" maxlength="1" required="required"  readonly="readonly" style="font-weight: bold;"></td>

                        <?php }  ?>

                <?php } ?> 

                      </tr>



                    </tbody>



                                    </table>

</div>

</div>

</div>

</div>

</div>





</div>







<div class="col-md-12 p-0">

  <div class="smal-sec">

    <h5>BULLETIN DE LA <?php echo $classes_name['c_name'] ." Annee ". $classes_name['school_sections'];?> Annee Scolaire 20

    <input type="text" name="" value="20" style="border-bottom: 1px dashed #333;">

    20

    <input type="text" name="" value="21" style="border-bottom: 1px dashed #333;">

  </h5>

</div>

</div>

</div>

</div>





<div class="container">

<div class="pol big-section">

<div class="pol-md-6">

 <div class="pol">

 <div class="pol-md-4 text-center"><label>Branches</label></div>



 <div class="pol-md-8 border-inner text-center" style="

    border-right: none;

"><label>Premier Semestre</label>

 <div class="pol border-inner1">

 <div class="pol-md-2 ">

  <label>MAX.</label>

 </div>

<div class="pol-md-4 border-left-inn">

  <label style="margin-bottom: 0;">Travauk</label><br>

   <label>Journal</label>

   <div class="pol border-inner2">

 <div class="pol-md-6 ">

  <label style="font-size: 12px;padding: 0 3px;">1 ere P</label>

 </div>

 <div class="pol-md-6 border-left-inn">

   <label style="font-size: 12px;padding: 0 3px;">2nd P</label>

 </div>

</div>





 </div>

<div class="pol-md-3 border-left-inn">

  <label>Max Exam</label>

 </div>

 <div class="pol-md-3 border-left-inn">

  <label>Total</label>

 </div>

</div>

 </div>  

 </div> 

</div> 



<div class="pol-md-6">

 <div class="pol">



 <div class="pol-md-8 border-inner text-center " ><label>Second Semestre</label>

 <div class="pol border-inner1">

 <div class="pol-md-2 ">

  <label>MAX.</label>

 </div>

<div class="pol-md-4 border-left-inn">

  <label style="margin-bottom: 0;">Travauk</label><br>

   <label>Journal</label>

   <div class="pol border-inner2">

 <div class="pol-md-6 ">

  <label style="font-size: 12px;padding: 0 3px;">3 rd P</label>

 </div>

 <div class="pol-md-6 border-left-inn">

   <label style="font-size: 12px;padding: 0 3px;">4 th P</label>

 </div>

</div>





 </div>

<div class="pol-md-3 border-left-inn">

  <label>Max Exam</label>

 </div>

 <div class="pol-md-3 border-left-inn">

  <label>Total General</label>

 </div>

</div>

 </div>



 <div class="pol-md-4 text-center">

 <div class="pol">

 <div class="pol-md-2 " style="

    background: #071a2a;    height: 104px

">

  <label></label>

 </div>

 <div class="pol-md-10 border-left-inn">

   <label style="    padding: 14px 10px;">Examen De Repechage</label>

   <div class="pol border-inner2">

 <div class="pol-md-4 ">

  <label>%</label>

 </div>

 <div class="pol-md-8 border-left-inn">

   <label style="font-size: 12px;padding: 0 3px;">Sign. Prof.</label>

 </div>

</div>

 </div>

</div>  



 </div>  

 </div> 

</div>



</div>  

</div>



<div class="container">

<div class="pol">  

<div class="pol-md-10">

  <table id="example" class="display nowrap dataTable no-footer" style="width: 100%; border-bottom: 2px solid #333;" role="grid" aria-describedby="example_info">

<tbody>

<tr class="domain-green">

  <td><label>&nbsp;</label></td>

  

</tr>



</tbody>

                                    </table>

</div>

<div class="pol-md-2" style="background: #071a2a;margin-top: -1px;height: 46px;">

  

</div>

</div>  

</div>



 <?php  

 $i = 1;

 $d = 0;

 $marks = array(0,20,20,20,70,20,10,20,10,10,10,10,10,10,10,30,20,20,20,20,20);

 if($report_marks['max2'] == ''){

     $arrmax2 = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

 }else{

    $arrmax2  =  explode(",", $report_marks['max2']);

 }

 if($report_marks['max3'] == ''){

     $arrmax3 = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

 }else{

    $arrmax3  =  explode(",", $report_marks['max3']);

 }

  if($report_marks['max22'] == ''){

     $arrmax22 = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

 }else{

    $arrmax22  =  explode(",", $report_marks['max22']);

 }

 if($report_marks['nmax2'] == ''){

     $arrnmax2 = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

 }else{

    $arrnmax2  =  explode(",", $report_marks['nmax2']);

 }

 if($report_marks['nmax3'] == ''){

     $arrnmax3 = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

 }else{

    $arrnmax3  =  explode(",", $report_marks['nmax3']);

 }

if($report_marks['nmax22'] == ''){

     $arrnmax22 = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

 }else{

    $arrnmax22  =  explode(",", $report_marks['nmax22']);

 }

 if($report_marks['signprof'] == ''){

     $arrsignprof = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

 }else{

    $arrsignprof  =  explode(",", $report_marks['signprof']);

 }

 foreach($subject_names as $value) { ?>





<?php

if (in_array($value['id'], $subjectsids)){

  $blury = "";

  $edit = "";

}else{

  $blury = "filter: blur(3px);";

  $edit = "readonly";

}



?>



<div class="container">

<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333; <?=$blury;?>">

<div class="pol-md-6">

 <div class="pol">

 <div class="pol-md-4"><label><?= $value['subject_name'];?></label></div>



 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;

">

 <div class="pol">

 <div class="pol-md-2">

  <input type="number" name="max1[]" class="max1" id="highmarks<?=$i;?>" value="<?=$marks[$i];?>" readonly min="0">

 </div>

<div class="pol-md-2 border-left-sub">

  <?php if($arrmax2[$d] == "0"){?>

  <input type="number" name="max2[]" class="max2 mymarks1<?=$i;?>" id="<?=$i;?>" <?=$edit;?> value="<?=$arrmax2[$d];?>" min="0" style="width: 60px;text-align: center;">

  <?php }else{ ?>  

  <input type="number" name="max2[]" class="max2 mymarks1<?=$i;?>" id="<?=$i;?>" readonly value="<?=$arrmax2[$d];?>" min="0" style="width: 60px;text-align: center;">

  <?php } ?>

 </div>

 <div class="pol-md-2 border-left-sub">

   <?php if($arrmax3[$d] == "0"){?>

   <input type="number" name="max3[]" class="max3 mymarks2<?=$i;?>" id="<?=$i;?>" <?=$edit;?> value="<?=$arrmax3[$d];?>" min="0" style="width: 60px;text-align: center;">

   <?php }else{ ?>  

    <input type="number" name="max3[]" class="max3 mymarks2<?=$i;?>" id="<?=$i;?>" readonly value="<?=$arrmax3[$d];?>" min="0" style="width: 60px;text-align: center;">

   <?php } ?>



 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 ">

 <input type="number" name="max21[]" class="max21" id="highmarks2<?=$i;?>" value="<?=$marks[$i]*2;?>" readonly min="0">

 </div>

 <div class="pol-md-6 border-left-sub">

    <?php if($arrmax22[$d] == "0"){?>

 <input type="number" name="max22[]" class="max22 mymarks3<?=$i;?>" id="<?=$i;?>" <?=$edit;?> value="<?=$arrmax22[$d];?>" min="0" style="width: 45px;text-align: center;">

    <?php }else{ ?>

  <input type="number" name="max22[]" class="max22 mymarks3<?=$i;?>" id="<?=$i;?>"  value="<?=$arrmax22[$d];?>" readonly min="0" style="width: 45px;text-align: center;">    

     <?php } ?>

 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 ">

 <input type="number" name="maxtot[]" class="maxtot  mymaxtot<?=$i;?>" value="" min="0" readonly="">

 </div>

 <div class="pol-md-6 border-left-sub">

   <input type="number" name="maxgettot[]" class="maxgettot mymaxget<?=$i;?>" value="" min="0" readonly="" style="width: 44px; text-align: center;">

 </div>

</div>

 </div>

</div>

 </div>  

 </div> 

</div> 





<div class="pol-md-6">

 <div class="pol">





 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;    border-left: 0;

">

 <div class="pol">

 <div class="pol-md-2">

   <input type="number" name="nmax1[]" class="nmax1" id="nhighmarks<?=$i;?>" value="<?=$marks[$i];?>" readonly min="0">

 </div>

<div class="pol-md-2 border-left-sub">

  <?php if($arrnmax2[$d] == "0"){?>  

  <input type="number" name="nmax2[]" class="nmax2 nmymarks1<?=$i;?>" id="<?=$i;?>" <?=$edit;?> value="<?=$arrnmax2[$d];?>" min="0" style="width: 60px; text-align: center;">

 <?php }else{ ?>

  <input type="number" name="nmax2[]" class="nmax2 nmymarks1<?=$i;?>" id="<?=$i;?>" value="<?=$arrnmax2[$d];?>" readonly min="0" style="width: 60px; text-align: center;">

 <?php } ?> 

 </div>

 <div class="pol-md-2 border-left-sub">

   <?php if($arrnmax3[$d] == "0"){?>  

  <input type="number" name="nmax3[]" class="nmax3 nmymarks2<?=$i;?>" id="<?=$i;?>" <?=$edit;?> value="<?=$arrnmax3[$d];?>" min="0" style="width: 60px;text-align: center;">

   <?php }else{ ?>

     <input type="number" name="nmax3[]" class="nmax3 nmymarks2<?=$i;?>" id="<?=$i;?>" value="<?=$arrnmax3[$d];?>" readonly min="0" style="width: 60px;text-align: center;">

   <?php } ?>  

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 ">

  <input type="number" name="nmax21[]" class="nmax21" id="nhighmarks2<?=$i;?>" value="<?=$marks[$i]*2;?>" readonly min="0">

 </div>

 <div class="pol-md-6 border-left-sub">

<?php if($arrnmax22[$d] == "0"){?>    

 <input type="number" name="nmax22[]" class="nmax22 nmymarks3<?=$i;?>" id="<?=$i;?>" <?=$edit;?> value="0" min="0" style="width: 45px; text-align: center;">

 <?php }else{ ?>

<input type="number" name="nmax22[]" class="nmax22 nmymarks3<?=$i;?>" id="<?=$i;?>"  value="<?=$arrnmax22[$d];?>" readonly min="0" style="width: 45px; text-align: center;">

  <?php } ?>    



 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 ">

<input type="number" name="nmaxtot[]" class="nmaxtot mynmaxtot<?=$i;?>" value="" min="0" readonly="">

 </div>

 <div class="pol-md-6 border-left-sub">

   <input type="number" name="nmaxgettot[]" class="nmaxgettot mynmaxget<?=$i;?>" <?=$edit;?> value="" min="0" readonly="" style="width: 45px; text-align: center;">

 </div>

</div>

 </div>

</div>

 </div>



   <div class="pol-md-4">

  <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;">



 </div>

  <div class="pol-md-4 border-inner-subect">

    <input type="text" name="percent" class="allpercent" value="" style="width: 100%;text-align: center;">

 </div>

  <div class="pol-md-6" >

<?php if($arrsignprof[$d] == "0"){?>    

    <input type="text" name="signprof[]" style="width: 100%;" value="">

<?php }else{ ?>

    <input type="text" name="signprof[]" style="width: 100%;" value="<?=$arrsignprof[$d];?>">

<?php } ?> 

 </div>

</div>   

   </div> 

 </div> 

</div>





</div>  

</div>

<?php $i++; $d++; } ?>





<div class="container">

<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">

<div class="pol-md-6">

 <div class="pol">

 <div class="pol-md-4"><label><strong>Maxima Generaux</strong></label></div>



 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;

">

 <div class="pol">

 <div class="pol-md-2">

  <input type="text" name="totalmax1" value="<?=array_sum($marks);?>" class="font-weight" id="totalmax1"  readonly="">

 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="totalmax2" class="font-weight" id="totalmax2" value="" readonly="">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text"  name="totalmax3" class="font-weight" id="totalmax3" value="" readonly="">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 ">

  <input type="text" name="totalmax21" value="<?=array_sum($marks)*2;?>" class="font-weight" id="totalmax21" readonly="">

 </div>

 <div class="pol-md-6 border-left-sub">

  <input type="text" name="totalmax22" class="font-weight" id="totalmax22" value="" readonly="">

 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 ">

<input type="text" name="hightotalmax" class="font-weight" id="totalhighmax" value="" readonly="">

 </div>

 <div class="pol-md-6 border-left-sub">

   <input type="text" name="gettotalmax" class="font-weight" id="totalgetmax" value="" readonly="">

 </div>

</div>

 </div>

</div>

 </div>  

 </div> 

</div> 





<div class="pol-md-6">

 <div class="pol">





 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;    border-left: 0;

">

 <div class="pol">

 <div class="pol-md-2">

  <input type="text" name="ntotalmax1" value="<?=array_sum($marks);?>" class="font-weight" id="ntotalmax1"  readonly="">

 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="ntotalmax2" class="font-weight" id="ntotalmax2" value="" readonly="">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text" name="ntotalmax3" class="font-weight" id="ntotalmax3" value="" readonly="">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 ">

 <input type="text" name="ntotalmax21" value="<?=array_sum($marks)*2;?>" class="font-weight" id="ntotalmax21" readonly="">

 </div>

 <div class="pol-md-6 border-left-sub">

 <input type="text" name="ntotalmax22" class="font-weight" id="ntotalmax22" value="" readonly="">

 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 ">

  <input type="text" name="nhightotalmax" class="font-weight" id="ntotalhighmax" value="" readonly="">

 </div>

 <div class="pol-md-6 border-left-sub">

   <input type="text" name="ngettotalmax" class="font-weight" id="ntotalgetmax" value="" readonly="">

 </div>

</div>

 </div>

</div>

 </div>



   <div class="pol-md-4" style="background: #071a2a;margin-top: -1px;height: 53px;">

  

</div> 

 </div> 

</div>





</div>  

</div>





<div class="container">

<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">

<div class="pol-md-6">

 <div class="pol">

 <div class="pol-md-4"><label><strong>Totaux</strong></label></div>



 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

  

 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;height:53px">



 </div>

 <div class="pol-md-6 border-left-sub">

 <input type="text" name="" class="font-weight">

 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

   <input type="text" name="" class="font-weight">

 </div>

</div>

 </div>

</div>

 </div>  

 </div> 

</div> 





<div class="pol-md-6">

 <div class="pol">





 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;    border-left: 0;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">



 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

 <input type="text" name="" class="font-weight">

 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

   <input type="text" name="" class="font-weight">

 </div>

</div>

 </div>

</div>

 </div>



   <div class="pol-md-4">

  <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px; height:53px">



 </div>

  <div class="pol-md-10 border-inner-subect">

    <label>* Passe(1)</label>

 </div>



</div>   

   </div> 

 </div> 

</div>





</div>  

</div>











<div class="container">

<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">

<div class="pol-md-6">

 <div class="pol">

 <div class="pol-md-4"><label><strong>Pourcentage</strong></label></div>



 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

  

 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="trisemper1" class="font-weight" id="trisemper1" class="trisemper1" style="width: 100%;text-align: center;">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text" name="trisemper2" class="font-weight" id="trisemper2" class="trisemper2" style="width: 100%;text-align: center;">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

 <input type="text" name="trisemper3" class="font-weight" id="trisemper3" class="trisemper3" style="width: 100%;text-align: center;">

 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

    <input type="text" name="trisemper4" class="font-weight" id="trisemper4" class="trisemper4" style="width: 100%;text-align: center;">

 </div>

</div>

 </div>

</div>

 </div>  

 </div> 

</div> 





<div class="pol-md-6">

 <div class="pol">





 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;    border-left: 0;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">



 </div>

<div class="pol-md-2 border-left-sub">

   <input type="text" name="trisemper5" class="font-weight" id="trisemper5" class="trisemper5" style="width: 100%;text-align: center;">

 </div>

 <div class="pol-md-2 border-left-sub">

   <input type="text" name="trisemper6" class="font-weight" id="trisemper6" class="trisemper6" style="width: 100%;text-align: center;">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

  <input type="text" name="trisemper7" class="font-weight" id="trisemper7" class="trisemper7" style="width: 100%;text-align: center;">

 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

    <input type="text" name="trisemper8" class="font-weight" id="trisemper8" class="trisemper8" style="width: 100%;text-align: center;">

 </div>

</div>

 </div>

</div>

 </div>



   <div class="pol-md-4">

  <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;    height: 53px;">



 </div>

  <div class="pol-md-10 border-inner-subect">

    <label>* Double(1)</label>

 </div>



</div>   

   </div> 

 </div> 

</div>

</div>  

</div>





<div class="container">

<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">

<div class="pol-md-6">

 <div class="pol">

 <div class="pol-md-4"><label><strong>Place/ Near D'eleves</strong></label></div>



 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

  

 </div>

<div class="pol-md-2 border-left-sub">

/

 </div>

 <div class="pol-md-2 border-left-sub">

/

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

 <input type="text" name="" class="font-weight">

 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

   <input type="text" name="" class="font-weight">

 </div>

</div>

 </div>

</div>

 </div>  

 </div> 

</div> 





<div class="pol-md-6">

 <div class="pol">





 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;    border-left: 0;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">



 </div>

<div class="pol-md-2 border-left-sub">

  /

 </div>

 <div class="pol-md-2 border-left-sub">

  /

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

 <input type="text" name="" class="font-weight">

 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub">

   <input type="text" name="" class="font-weight">

 </div>

</div>

 </div>

</div>

 </div>



   <div class="pol-md-4">

  <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;    height: 53px;">



 </div>

  <div class="pol-md-10 border-inner-subect">

    <label>LE..../ .../ 20</label>

 </div>



</div>   

   </div> 

 </div> 

</div>

</div>  

</div>



<div class="container">

<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">

<div class="pol-md-6">

 <div class="pol">

 <div class="pol-md-4"><label><strong>Application</strong></label></div>



 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

  

 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

</div>

 </div>

</div>

 </div>  

 </div> 

</div> 





<div class="pol-md-6">

 <div class="pol">





 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;    border-left: 0;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">



 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf" >

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

</div>

 </div>

</div>

 </div>



   <div class="pol-md-4">

  <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;    height: 53px;">



 </div>

  <div class="pol-md-10 border-inner-subect">

    <label>Sceau de</label>

 </div>



</div>   

   </div> 

 </div> 

</div>

</div>  

</div>





<div class="container">

<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">

<div class="pol-md-6">

 <div class="pol">

 <div class="pol-md-4"><label><strong>Conduite</strong></label></div>



 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

  

 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

</div>

 </div>

</div>

 </div>  

 </div> 

</div> 





<div class="pol-md-6">

 <div class="pol">





 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;    border-left: 0;

">

 <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">



 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text" name="" class="font-weight">

 </div>

<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf" >

  <div class="pol border-inner-subect2">

 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">



 </div>

 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 53px;">



 </div>

</div>

 </div>

</div>

 </div>



   <div class="pol-md-4">

  <div class="pol">

 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px; height: 53px;">



 </div>

  <div class="pol-md-10 border-inner-subect">

    <label></label>

 </div>



</div>   

   </div> 

 </div> 

</div>

</div>  

</div>







<div class="container">

<div class="pol big-section subect-sec" style="border-bottom: 2px solid #333;">

<div class="pol-md-6">

 <div class="pol">

 <div class="pol-md-4"><label>Signature</label></div>



 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;

">

<div class="pol">

 <div class="pol-md-6">

  <input type="text" name="">

 </div>



<div class="pol-md-3 border-left-sub">

  <div class="pol border-inner-subect2">

 

 

</div>

 </div>

 <div class="pol-md-3 border-left-sub border-left-subhalf">

  

 </div>

</div>

 </div>  

 </div> 

</div> 





<div class="pol-md-6">

 <div class="pol">





 <div class="pol-md-8 border-inner-subect text-center" style="

    border-right: none;    border-left: 0;

">

 <div class="pol">

 <div class="pol-md-2">

  <input type="text" name="">

 </div>

<div class="pol-md-2 border-left-sub">

  <input type="text" name="">

 </div>

 <div class="pol-md-2 border-left-sub">

  <input type="text" name="">

 </div>

<div class="pol-md-6 border-left-sub">

  <div class="pol border-inner-subect2">

 <div class="pol-md-12 ">

 <input type="text" name="">

 </div>



</div>

 </div>



</div>

 </div>



   <div class="pol-md-4">

  <div class="pol">

 <div class="pol-md-2">



 </div>

<div class="pol-md-10 border-inner-subect" style="

    border-left: none;">

    <label></label>

 </div>

</div>   

   </div> 

 </div> 

</div>





</div>  

</div>







<div class="container">

<div class="row">

<div class="col-md-12">

  <div class="smal-sec1">

    <ul>

      <li><p style="text-transform: full-size-kana;">Lélève ne pourra passer dans la classe superieure s'il n'a subi avec succès un examen de repêchage en<br>

  <?php 

  if($report_marks['bull'] == ""){?>       

    <input type="text" name="bull[]" style="border-bottom: 1px dashed #333;">

    <input type="text" name="bull[]" style="border-bottom: 1px dashed #333;">

  <?php }else{ ?>

      <?php 

          $exbull= $report_marks['bull'];

          $arrbull = explode(",", $exbull);

          foreach ($arrbull as $val) { 

      ?>     

      <input type="text" name="bull[]" value="<?=$val;?>" style="border-bottom: 1px dashed #333; font-weight: bold;">

      <?php }  ?>

  <?php } ?>   



  </p></li>

   <li style="text-transform: full-size-kana;">Lélève passe dans la classe supérieure(1)</li>

   <li style="text-transform: full-size-kana;">Lélève double la classe(1)</li>

    </ul>

  

</div>

</div>  

</div>



<div class="row">

  <div class="col-md-5"></div>

<div class="col-md-7">

  <div class="smal-sec1">

<?php $faltdat = explode(",", $report_marks['faltdat']); ?>



    <p style="text-transform: full-size-kana;">Falt à <input type="text" name="falt" value="<?=$report_marks['falt'];?>" style="border-bottom: 1px dashed #333;width: 230px; font-weight: bold;">

    le 

  <?php  if($faltdat[0] == ""){ ?> 

    <input type="text" name="faltdat[]" value="" style="border-bottom: 1px dashed #333;width: 60px;">

  <?php }else{?>

    <input type="text" name="faltdat[]" value="<?=$faltdat[0];?>" style="border-bottom: 1px dashed #333;width: 60px; font-weight: bold;">

  <?php } ?> 

    / 

  <?php  if($faltdat[1] == ""){ ?> 

    <input type="text" name="faltdat[]" value="" style="border-bottom: 1px dashed #333;width: 60px;">

  <?php }else{?>

    <input type="text" name="faltdat[]" value="<?=$faltdat[1];?>" style="border-bottom: 1px dashed #333;width: 60px; font-weight: bold;">

  <?php } ?> 

   /20 

     <?php  if($faltdat[2] == ""){ ?> 

    <input type="text" name="faltdat[]" value="" style="border-bottom: 1px dashed #333;width: 60px;">

  <?php }else{?>

    <input type="text" name="faltdat[]" value="<?=$faltdat[2];?>" style="border-bottom: 1px dashed #333;width: 60px; font-weight: bold;">

  <?php } ?> 

  </p>

  

</div>

</div>  

</div>



<div class="row text-center mt-5" style="text-transform: full-size-kana; margin-bottom: 40px;">

  <div class="col-md-4">

    <strong>Signature de lélève</strong>

  </div>

<div class="col-md-4">

  <strong>Sceau de l'école</strong>

</div> 

<div class="col-md-4">

  <strong>Le Chef d'Esteblishment</strong>

</div> 

</div>



<div class="row text-center mt-5" style="text-transform: full-size-kana; margin-bottom: 40px;">

  <div class="col-md-4">

    &nbsp;

  </div>

<div class="col-md-4">

   &nbsp;

</div> 

<div class="col-md-4">

  <strong>Nom et Signature</strong>

</div> 

</div>



</div>
                        </div>

                    </div>

                    

                </div>

            </div>

        </div>

    </div>

</div>   





<?php echo $this->Form->end(); ?>



<!----Form ends ---------------->





<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>



<script type="text/javascript">



$(document).ready(function(){





        $(document).on('blur', "#highmarks1",function () {

            var txt;

            var total = 0;

            var myval = $('#highmarks1').val();

            //var r = confirm("Do you want to add same marks in all subject");

            if (r == true) {

              $('.max1').val(myval);

              $('.max2').val(" ");

              $('.max3').val(" ");

              $('.max2').css("background-color", "#fff");

              $('.max3').css("background-color", "#fff");

              $('.max1').each(function(){

                  if($('.max1').val() != ""){

                  total += parseFloat($('.max1').val());

                  }

               })  

              $('#totalmax1').val(total);

            }

        });



         $(document).on('blur', "#highmarks21",function () {

            var txt;

            var total = 0;

            var myval = $('#highmarks21').val();

            //var r = confirm("Do you want to add same marks in all subject");

            if (r == true) {

              $('.max21').val(myval);

              $('.max22').val(" ");

              $('.max22').css("background-color", "#fff");

              $('.max21').each(function(){  

                  if($('.max21').val() != ""){

                  total += parseFloat($('.max21').val());

                  }

               })  

              $('#totalmax21').val(total);

            }

        });

    

        $(document).on('keyup', ".max1",function () {

            var total = 0;

           $('.max1').each(function(){

            if($(this).val() != ""){

                 total += parseFloat($(this).val());

             }

           })  

          $('#totalmax1').val(total);

        });



        $(document).on('keyup', ".max21",function () {

            var total = 0;

           $('.max21').each(function(){

            if($(this).val() != ""){

                 total += parseFloat($(this).val());

             }

           })  

          $('#totalmax21').val(total);

        });

       



        $(document).on('blur', ".max2",function () {

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#highmarks"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      



           $('.max2').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#totalmax2').val(total1);

        });





         $(document).on('blur', ".nmax2",function () {

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#nhighmarks"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      



           $('.nmax2').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#ntotalmax2').val(total1);

        });







        $(document).on('blur', ".max3",function () {

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#highmarks"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

              $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      



           $('.max3').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#totalmax3').val(total1);

        });







        $(document).on('blur', ".nmax3",function () {

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#nhighmarks"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

              $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      



           $('.nmax3').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#ntotalmax3').val(total1);

        });





        $(document).on('blur', ".max22",function () {

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#highmarks2"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      



           $('.max22').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#totalmax22').val(total1);

        });





        $(document).on('blur', ".nmax22",function () {

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#nhighmarks2"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      



           $('.nmax22').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#ntotalmax22').val(total1);

        });



     

      setInterval( function(){ 

         var i = 1;

         var j = 1;

         var total1;



        $('.maxtot').each(function(){

          var highmark2 = $("#highmarks"+i).val() * 2;

          var highmark1 = $("#highmarks2"+i).val();

          var tohigh =  Number(highmark1) + Number(highmark2);  

          $(this).val(tohigh); 

          i++;

          }); 

        

        var i = 1;  

        $('.nmaxtot').each(function(){

          var highmark2 = $("#nhighmarks"+i).val() * 2;

          var highmark1 = $("#nhighmarks2"+i).val();

          var tohigh =  Number(highmark1) + Number(highmark2);  

          $(this).val(tohigh); 

          i++;

        }); 

  

  <?php if($report_marks['max2'] != ""){ ?>

         $('.max2').each(function(){

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#highmarks"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      

           $('.max2').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#totalmax2').val(total1);

         });

   <?php } ?>





     <?php if($report_marks['nmax2'] != ""){ ?>

         $('.nmax2').each(function(){

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#nhighmarks"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      

           $('.nmax2').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#ntotalmax2').val(total1);

         });

   <?php } ?>  

  







  <?php if($report_marks['max22'] != ""){ ?>

         $('.max22').each(function(){

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#highmarks2"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      

           $('.max22').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#totalmax22').val(total1);

         });

   <?php } ?> 





   <?php if($report_marks['max3'] != ""){ ?>

         $('.max3').each(function(){

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#highmarks"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      

           $('.max3').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#totalmax3').val(total1);

         });

   <?php } ?>     



    <?php if($report_marks['nmax3'] != ""){ ?>

         $('.nmax3').each(function(){

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#nhighmarks"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      

           $('.nmax3').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#ntotalmax3').val(total1);

         });

   <?php } ?>





       <?php if($report_marks['nmax22'] != ""){ ?>

         $('.nmax22').each(function(){

           var total1 = 0;

           var curmar = $(this).val();

           var classfild = $(this).attr('id');

           var maxmar = $("#nhighmarks2"+classfild).val();

           var passmarks = maxmar/2;

           if(parseFloat(curmar) > parseFloat(maxmar)){

              alert("Marks Can not be greater than Max marks");

               $(this).val(" ");

           }else{

               if(passmarks > curmar){

                      $(this).css("color", "red");

                 }else{

                     $(this).css("color", "blue");

                 }

           }      

           $('.nmax22').each(function(){

              if($(this).val() != ""){

                 total1 += parseFloat($(this).val());

              }

           })  

            $('#ntotalmax22').val(total1);

         });

   <?php } ?>





         $('.maxgettot').each(function(){

            var studentm1 = $(".mymarks1"+j).val();

            var studentm2 = $(".mymarks2"+j).val();

            var studentm3 = $(".mymarks3"+j).val();

            var passingmark = $(".mymaxtot"+j).val()/2;

            var totalmarksper = Number(studentm1) + Number(studentm2) + Number(studentm3); 

         if(studentm1 != "" && studentm2 != "" && studentm3 != ""){   

            if(passingmark > totalmarksper){

                  $(this).css("color", "red");

            }else{

                 $(this).css("color", "blue");

            }

          }  

            $(this).val(totalmarksper); 

         j++;

         });

        var p = 1;
        var j = 1; 

         $('.nmaxgettot').each(function(){

            var studentm1 = $(".nmymarks1"+j).val();

            var studentm2 = $(".nmymarks2"+j).val();

            var studentm3 = $(".nmymarks3"+j).val();

            var passingmark = $(".mynmaxtot"+j).val()/2;

            var totalmarksper = Number(studentm1) + Number(studentm2) + Number(studentm3); 

            if(studentm1 != "" && studentm2 != "" && studentm3 != ""){   

            if(passingmark > totalmarksper){

                  $(this).css("color", "red");

            }else{

                 $(this).css("color", "blue");

            }

          } 

            $(this).val(totalmarksper); 

         j++;

         });



          var tothighmark2 = $("#totalmax1").val();

          var tothighmark1 = $("#totalmax21").val();

          var tohighmarks = Number(tothighmark1) + Number(tothighmark2);  

          $("#totalhighmax").val(tohighmarks);



          var tothighmark2 = $("#ntotalmax1").val();

          var tothighmark1 = $("#ntotalmax21").val();

          var tohighmarks = Number(tothighmark1) + Number(tothighmark2);  

          $("#ntotalhighmax").val(tohighmarks);



          var totgetmark1 = $("#totalmax2").val();

          var totgetmark2 = $("#totalmax3").val();

          var totgetmark3 = $("#totalmax22").val();

          var tohighmarks = Number(totgetmark1) + Number(totgetmark2) + Number(totgetmark3); 

          $("#totalgetmax").val(tohighmarks);



          var totgetmark1 = $("#ntotalmax2").val();

          var totgetmark2 = $("#ntotalmax3").val();

          var totgetmark3 = $("#ntotalmax22").val();

          var tohighmarks = Number(totgetmark1) + Number(totgetmark2) + Number(totgetmark3); 

          $("#ntotalgetmax").val(tohighmarks);



          var totalmax1 = $("#totalmax1").val();

          var totalmax2 = $("#totalmax2").val();

          var totalmax3 = $("#totalmax3").val();

          var totalmax21 = $("#totalmax21").val();

          var totalmax22 = $("#totalmax22").val();

          var totalhighmax = $("#totalhighmax").val();

          var totalgetmax = $("#totalgetmax").val();

          var ntotalmax1 = $("#ntotalmax1").val();

          var ntotalmax2 = $("#ntotalmax2").val();

          var ntotalmax3 = $("#ntotalmax3").val();

          var ntotalmax21 = $("#ntotalmax21").val();

          var ntotalmax22 = $("#ntotalmax22").val();

          var ntotalhighmax = $("#ntotalhighmax").val();

          var ntotalgetmax = $("#ntotalgetmax").val();

          var percent1 = totalmax2/totalmax1 * 100;

          var percent2 = totalmax3/totalmax1 * 100;

          var percent3 = totalmax22/totalmax21 * 100;

          var percent4 = totalgetmax/totalhighmax * 100;

          var percent5 = ntotalmax2/ntotalmax1 * 100;

          var percent6 = ntotalmax3/ntotalmax1 * 100;

          var percent7 = ntotalmax22/ntotalmax21 * 100;

          var percent8 = ntotalgetmax/ntotalhighmax * 100;

          $("#trisemper1").val(percent1.toFixed(1)+"%");

          $("#trisemper2").val(percent2.toFixed(1)+"%");

          $("#trisemper3").val(percent3.toFixed(1)+"%"); 

          $("#trisemper4").val(percent4.toFixed(1)+"%"); 

          $("#trisemper5").val(percent5.toFixed(1)+"%");

          $("#trisemper6").val(percent6.toFixed(1)+"%");

          $("#trisemper7").val(percent7.toFixed(1)+"%"); 

          $("#trisemper8").val(percent8.toFixed(1)+"%");



      }  , 2000 );





});  

</script> 
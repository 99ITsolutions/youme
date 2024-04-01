<style>
input[type="password"] {display:none;}
#left-sidebar { left: -250px;}
#main-content { width: 100%; }
.hide
{
    display:none;
}
.input-group-text{
    font-size:0.8em;
}
.inputtext {
    font-weight: bold;
    width: 35px;
    border: none;
    color: #3e22ad;
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
input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
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


table.dataTable tbody th, table.dataTable tbody td {
    padding: 0px !important;
}

label {
    display: initial !important;
    margin-bottom: 0px !important;
}

.big-section label {
    font-weight: 600 !important;
    padding: 0px !important;
}
.row.header-sec {
    justify-content: center;
}

td.id_name {
    border: 0px !important;
    border-top: 2px solid #fff !important;
    border-bottom: 2px solid #fff !important;
}
.dark_border {
    position: relative;
    background: #000;
    height: 3px;
    margin: 15px 0;
}
.body.body-bg {
    padding: 15px !important;
}
.three_section {
    border-bottom: 3px solid;
    padding: 11.5px 0;
}
.row-header,.row-header-shim {
    background: #f8f9fa
}

div.column-headers-background,th.column-headers-background,div.row-headers-background,th.row-headers-background {
    background: #f8f9fa;
    color: #5f6368;
}

th.freezebar-origin-ltr {
    background: no-repeat url("//ssl.gstatic.com/docs/spreadsheets/waffle_sprite53.png") -205px 0
}

th.freezebar-origin-ltr,th.freezebar-origin-rtl {
    background-color: #eee;
    position: relative
}
th.row-headers-background {
    background: #eee;
    position: relative
}
th.column-headers-background {
    background: #eee;
    position: relative
}
th.row-header {
    background-color: #eee;
    width: 45px;
    text-align: center;
    vertical-align: middle;
    font-size: 8pt;
    color: #333;
    line-height: inherit;
    overflow: hidden
}
.waffle th,.grid-fixed-table th {
    font-weight: normal;
    background: transparent;
    text-align: center;
    vertical-align: middle;
    font-size: 8pt;
    color: #222;
    height: 23px;
    border: solid 1px #ccc;
    border-width: 0 1px 1px 0;
    overflow: hidden;
    padding: 0
}
.waffle td,.grid-fixed-table td {
    overflow: hidden;
    border: 1px #e5e5e5 solid;
    border-color: rgba(0,0,0,0.15);
    border-width: 0 1px 1px 0;
    vertical-align: bottom;
    line-height: inherit;
    background-color: #fff;
    padding: 0 3px
}

.waffle .softmerge {
    overflow: visible
}

.softmerge-inner {
    white-space: normal;
    overflow: hidden;
    text-overflow: hidden;
    position: relative
}
.column-headers-background,.row-headers-background {
    z-index: 1
}



.waffle,.grid-fixed-table {
    font-size: 13px;
    table-layout: fixed;
    border-collapse: separate;
    border-style: none;
    border-spacing: 0;
    width: 0;
    cursor: default
}

.grid-container  {
    height: 100%;
    width: 100%;
    background-color: #eee;
    overflow-x: scroll !important;
    position: relative;
    z-index: 0
}
.grid-container1 {
    width: 100%;
    overflow-x: scroll !important;
}

  .ritz .waffle a {
    color: inherit;
  }

  .ritz .waffle .s9 {
    border-left: none;
    border-right: none;
    border-bottom: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s1 {
    border-bottom: 3px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;
    font-size: 18pt;
    vertical-align: middle;
    white-space: normal;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s5 {
    border-bottom: 1px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: left;
    font-weight: bold;
    color: #000000;

    font-size: 14pt;
    vertical-align: middle;
    white-space: normal;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }
  .ritz .waffle .s13 {
    border-bottom: 3px SOLID #000000;
    border-right: 1px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 12pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s2 {
    border-bottom: 3px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 20pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s16 {
    border-bottom: 3px SOLID #000000;
    border-right: 2px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 12pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s11 {
    border-bottom: 3px SOLID #000000;
    border-right: 1px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }


  .ritz .waffle .s18 {
    border-bottom: 1px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: left;
    color: #000000;
    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s8 {
    border-left: none;
    border-right: none;
    border-bottom: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 11pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }
  .ritz .waffle .s7 {
    border-right: none;
    border-bottom: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: left;
    font-weight: bold;
    color: #000000;

    font-size: 14pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }
  .ritz .waffle .s0 {
    border-bottom: 3px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 12pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s15 {
    border-bottom: 3px SOLID #000000;
    border-right: 1px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;
    font-size: 12pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s3 {
    border-bottom: 3px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 11pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s20 {
    border-bottom: 1px SOLID #000000;
    border-right: 1px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s12 {
    border-bottom: 3px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s10 {
    border-left: none;
    border-bottom: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s19 {
    border-bottom: 1px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #000000;
    text-align: center;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s22 {
    border-bottom: 1px SOLID #000000;
    border-right: 1px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s6 {
    border-bottom: 1px SOLID #000000;
    border-right: 1px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s14 {
    border-bottom: 3px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 12pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s32 {
    border-bottom: 3px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s4 {
    border-bottom: 3px SOLID #000000;
    border-right: 2px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 11pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s28 {
    border-bottom: 2px SOLID #000000;
    border-right: 3px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    color: #000000;

    font-size: 10pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  .ritz .waffle .s17 {
    border-bottom: 3px SOLID #000000;
    border-right: 2px SOLID #000000;
    background-color: #ffffff;
    text-align: center;
    font-weight: bold;
    color: #000000;

    font-size: 12pt;
    vertical-align: middle;
    white-space: nowrap;
    direction: ltr;
    padding: 2px 3px 2px 3px;
  }

  tbody {
    margin-top: -10px;
    position: relative;
    top: -23px;
  }

  thead {
    opacity: 0;
    position: relative;
  }

  td.s3>div,
  td.s4>div,
  td.start_date>div,
  .softmerge-inner>div,
  .softmerge-inner.king {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    white-space: nowrap;
  }

  td.s8.softmerge {
    border: 3px solid #000 !important;
    border-left: 0 !important;
  }

  td.s7.softmerge {
    border-right: 3px solid #000 !important;
  }

  td.s9.softmerge {
    border: 3px solid #000 !important;
    border-left: 0 !important;
    /* border-top: 0 !important; */
  }

  tbody tr th {
    display: none;
  }
  
  .waffle1
  {
      width:100% !important;
      overflow-x: scroll;
  }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=STIX+Two+Math&display=swap" rel="stylesheet"> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 ">
        <div class="card mt-4" style="padding-bottom: 1rem;">
            <div class="header">
                <div class="row">
                    <h2 class="col-md-6 text-left heading"></h2>
                    <h2 class="col-md-6 text-right"><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()">Back</a></h2>
                </div>
            </div>
            <div class="body">
                
                
<!----Form starts ---------------->
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
}

$hostname = "localhost";
$username = "youmeglo_globaluser";
$password = "DFmp)9_p%Kql";
$database = "youmeglo_globalweb";
$con = mysqli_connect($hostname, $username, $password, $database); 
if(mysqli_connect_error($con)){ echo "Connection Error."; die();}
$section = '';
$countperiod1 = 6;
$countperiod2 = 6;
$countperiod3 = 6;
$countperiod4 = 6;
$countperiod5 = 6;
$countperiod6 = 6;

if(!empty($retrieve_class->c_section)) { $section  = " - ".$retrieve_class->c_section; }
$sclsctn = strtolower($retrieve_class->school_sections);
$totl ='';
if($sclsctn == "maternelle" || $sclsctn == "creche")
{
    $countperiod1 = 8;
    $countperiod2 = 8;
    $countperiod3 = 8;

    if(count($assesment) > 5) {  $countperiod1 = count($assesment)+3; }
    if(count($assesment2) > 5) {  $countperiod2 = count($assesment2)+3; }
    if(count($assesment3) > 5) {  $countperiod3 = count($assesment3)+3; }
    
    $totl = $countperiod1+$countperiod2+$countperiod3;
}
elseif($sclsctn == "primaire")
{
    if(count($assesment) > 4) {  $countperiod1 = count($assesment)+2; }
    if(count($assesment2) > 4) {  $countperiod2 = count($assesment2)+2; }
    if(count($assesment3) > 4) {  $countperiod3 = count($assesment3)+2; }
    if(count($assesment4) > 4) {  $countperiod4 = count($assesment4)+2; }
    if(count($assesment5) > 4) {  $countperiod5 = count($assesment5)+2; }
    if(count($assesment6) > 4) {  $countperiod6 = count($assesment6)+2; }
    
    $sem1 = $countperiod1+$countperiod2+2;
    $sem2 = $countperiod3+$countperiod4+2;
    $sem3 = $countperiod5+$countperiod6+2;
    
    $totl = $sem1+$sem2+$sem3;
}
else
{
    if(count($assesment) > 4) {  $countperiod1 = count($assesment)+2; }
    if(count($assesment2) > 4) {  $countperiod2 = count($assesment2)+2; }
    if(count($assesment3) > 4) {  $countperiod3 = count($assesment3)+2; }
    if(count($assesment4) > 4) {  $countperiod4 = count($assesment4)+2; }
    
    $sem1 = $countperiod1+$countperiod2+2;
    $sem2 = $countperiod3+$countperiod4+2;
    
    $totl = $sem1+$sem2;
}
$clsname = strtolower($retrieve_class->c_name."-"."(".$retrieve_class->school_sections.")");
$subjectname = $subject_names->subject_name;

echo $this->Form->create(false , ['url' => ['action' => 'subrecoder'] , 'id' => "subrecoderform" , 'method' => "post"  ]); 
?>
    <input type="hidden" name="studentid" id="studentid" value="<?= $stid ?>" >
    <input type="hidden" name="classid" id="classid" value="<?=$_GET['classid'];?>">
    <input type="hidden" name="subjectid" id="subjectid" value="<?=$subject_names->id;?>">
    <input type="hidden" name="subjectname" id="subjectname" value="<?= $subject_names->subject_name;?>">
    <div class="body body-bg" style="color: #444;padding: 20px;font-weight: 400;border: 2px solid #333;margin: 20px; width: 100%; margin: 0 auto;">
        <div class="row ">
            <div id="grid-container1" style="overflow-x:auto;"><div style="overflow-x:auto;"></div></div>
            <div class="ritz grid-container" id="grid-container" dir="ltr" style="overflow-x:auto;">
                <table class="waffle" id="select" cellspacing="0" cellpadding="0">
                <thead>
                  <tr>
                    <th class="row-header freezebar-origin-ltr" style="display: none;"></th>
                    <th id="1281374097C0" style="width:267px;" class="column-headers-background">A</th>
                    <th id="1281374097C1" style="width:40px;" class="column-headers-background">B</th>
                    <?php for($i=1; $i<= $totl; $i++) { ?>
                    <th id="1281374097C2" style="width:39px;" class="column-headers-background">C</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                    <tr style="height: 49px">
                    <?php if($sclsctn == "maternelle" || $sclsctn == "creche") { ?>
                        <th id="1281374097R0" style="height: 49px;" class="row-headers-background">
                            <div class="row-header-wrapper" style="line-height: 49px">1</div>
                        </th>
                        <td class="s0" dir="ltr" colspan="2" rowspan="1">x</td>
                        <td class="s1" dir="ltr" colspan="<?= $countperiod1 ?>">PREMIER TRIMESTRE</td>
                        <td class="s1" dir="ltr" colspan="<?= $countperiod2 ?>">DEUXIEME TRIMESTRE</td>
                        <td class="s1" dir="ltr" colspan="<?= $countperiod3 ?>">TROISIEME TRIMESTRE</td>
                    <?php } elseif($sclsctn == "primaire") {  ?>
                            <th id="1281374097R0" style="height: 49px;" class="row-headers-background">
                                <div class="row-header-wrapper" style="line-height: 49px">1</div>
                            </th>
                            <td class="s0" dir="ltr" colspan="2" rowspan="2">x</td>
                            <td class="s1" dir="ltr" colspan="<?= $sem1 ?>">PREMIER TRIMESTRE</td>
                            <td class="s1" dir="ltr" colspan="<?= $sem2 ?>">DEUXIEME TRIMESTRE</td>
                            <td class="s1" dir="ltr" colspan="<?= $sem3 ?>">TROISIEME TRIMESTRE</td>
                    <?php } else {  ?>
                            <th id="1281374097R0" style="height: 49px;" class="row-headers-background">
                                <div class="row-header-wrapper" style="line-height: 49px">1</div>
                            </th>
                            <td class="s0" dir="ltr" colspan="2" rowspan="2">x</td>
                            <td class="s1" dir="ltr" colspan="<?= $sem1 ?>">PREMIER SEMESTRE</td>
                            <td class="s1" dir="ltr" colspan="<?= $sem2 ?>">SECOND SEMESTRE</td>
                    <?php } ?>
                    </tr>
                    
                    <?php if($sclsctn == "maternelle" || $sclsctn == "creche") { ?>
                    <?php } elseif($sclsctn == "primaire") {  ?>
                        <tr style="height: 59px">
                            <th id="1281374097R1" style="height: 59px;" class="row-headers-background">
                                <div class="row-header-wrapper" style="line-height: 59px">2</div>
                            </th>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod1 ?>">1ère PERIODE</td>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod2 ?>">2ème PERIODE</td>
                            <td class="s3" dir="ltr" rowspan="3">
                                <div>EXAMEN DU PREMIER TRIMESTRE</div>
                            </td>
                            <td class="s4" dir="ltr" rowspan="3">
                                <div>TOTAL PREMIER TRIMESTRE</div>
                            </td>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod3 ?>">3ème PERIODE</td>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod4 ?>">4ème PERIODE</td>
                            <td class="s3" dir="ltr" rowspan="3">
                                <div>EXAMEN DU DEUXIEME TRIMESTRE</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="3">
                                <div>TOTAL DEUXIEME TRIMESTRE</div>
                            </td>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod3 ?>">5ème PERIODE</td>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod4 ?>">6ème PERIODE</td>
                            <td class="s3" dir="ltr" rowspan="3">
                                <div>EXAMEN DU TROISIEME TRIMESTRE</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="3">
                                <div>TOTAL TROISIEME TRIMESTRE</div>
                            </td>
                        </tr>
                    <?php } else {  ?>
                        <tr style="height: 59px">
                            <th id="1281374097R1" style="height: 59px;" class="row-headers-background">
                                <div class="row-header-wrapper" style="line-height: 59px">2</div>
                            </th>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod1 ?>">1ère PERIODE</td>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod2 ?>">2ème PERIODE</td>
                            <td class="s3" dir="ltr" rowspan="3">
                                <div>EXAMEN DU 1er SEMESTRE</div>
                            </td>
                            <td class="s4" dir="ltr" rowspan="3">
                                <div>TOTAL 1er SEMESTRE</div>
                            </td>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod3 ?>">3ème PERIODE</td>
                            <td class="s2" dir="ltr" colspan="<?= $countperiod4 ?>">4ème PERIODE</td>
                            <td class="s3" dir="ltr" rowspan="3">
                                <div>EXAMEN DU SECOND SEMESTRE</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="3">
                                <div>TOTAL SECOND SEMESTRE</div>
                            </td>
                        </tr>
                    <?php } ?>
                    
                    <tr style="height: 81px">
                        <th id="1281374097R2" style="height: 81px;" class="row-headers-background">
                            <div class="row-header-wrapper" style="line-height: 81px">3</div>
                        </th>
                        <td class="s5" dir="ltr">Classe : <?= ucfirst($retrieve_class->c_name).$section." (".ucfirst($retrieve_class->school_sections).")"; ?></td>
                        <td class="s3" dir="ltr">
                            <div>Date</div>
                        </td>
                        
                        <?php if($sclsctn == "maternelle" || $sclsctn == "creche") { ?>
                            <?php if(!empty($assesment)) {
                                $ass = count($assesment);
                                if($ass > 5) {
                                foreach($assesment as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass;$k<5;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } else { 
                                for($j=0; $j<5; $j++) { ?>
                                    <td class="s3"><div></div></td>
                            <?php } } ?>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>EXAM. DU PREMIER TRIMESTRE</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>TOTAL PREMIER TRIMESTRE</div>
                            </td>
                            <?php if(!empty($assesment2)) {
                                $ass2 = count($assesment2);
                                if($ass2 > 5) {
                                foreach($assesment2 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment2 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass2;$k<5;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } }  
                            else { 
                                for($j=0; $j<5; $j++) { ?>
                                    <td class="s3"><div></div></td>
                            <?php } } ?>
                           
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>EXAM. DU PREMIER TRIMESTRE</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>TOTAL PREMIER TRIMESTRE</div>
                            </td>
                            <?php if(!empty($assesment3)) {
                                $ass3 = count($assesment3);
                                if($ass3 > 5) {
                                foreach($assesment3 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment3 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass3;$k<5;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<5; $j++) { ?>
                                    <td class="s3"><div></div></td>
                            <?php } } ?>
                            
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>EXAM. DU TROISIEME TRIMESTRE</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>TOTAL TROISIEME TRIMESTRE</div>
                            </td>
                        
                        <?php } elseif($sclsctn == "primaire") { ?>
                            <?php if(!empty($assesment)) {
                                $ass = count($assesment);
                                if($ass > 4) {
                                foreach($assesment as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s3"><div></div></td>
                            <?php } } ?>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>TOTAL 1ère PERIODE</div>
                            </td>
                            <?php if(!empty($assesment2)) {
                                $ass2 = count($assesment2);
                                if($ass2 > 4) {
                                foreach($assesment2 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment2 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass2;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } }  
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s3"><div></div></td>
                            <?php } } ?>
                           
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>TOTAL 2ème PERIODE</div>
                            </td>
                            <?php if(!empty($assesment3)) {
                                $ass3 = count($assesment3);
                                if($ass3 > 4) {
                                foreach($assesment3 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment3 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass3;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s3"><div></div></td>
                            <?php } } ?>
                            
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>TOTAL 3ème PERIODE</div>
                            </td>
                            <?php if(!empty($assesment4)) {
                                $ass4 = count($assesment4);
                                if($ass4 > 4) {
                                foreach($assesment4 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment4 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass4;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                    for($j=0; $j<4; $j++) { ?>
                                        <td class="s3"><div></div></td>
                                <?php } } ?>
                           
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>TOTAL 4ème PERIODE</div>
                            </td>
                            
                            <?php if(!empty($assesment5)) {
                                $ass5 = count($assesment5);
                                if($ass5 > 4) {
                                foreach($assesment5 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment5 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass5;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                    for($j=0; $j<4; $j++) { ?>
                                        <td class="s3"><div></div></td>
                                <?php } } ?>
                           
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>TOTAL 5ème PERIODE</div>
                            </td>
                            
                            <?php if(!empty($assesment6)) {
                                $ass6 = count($assesment6);
                                if($ass6 > 4) {
                                foreach($assesment6 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment6 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass6;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                    for($j=0; $j<4; $j++) { ?>
                                        <td class="s3"><div></div></td>
                                <?php } } ?>
                           
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>TOTAL 6ème PERIODE</div>
                            </td>
                            
                        <?php } else { ?>
                            <?php if(!empty($assesment)) {
                                $ass = count($assesment);
                                if($ass > 4) {
                                foreach($assesment as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s3"><div></div></td>
                            <?php } } ?>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>TOTAL 1ère PERIODE</div>
                            </td>
                            <?php if(!empty($assesment2)) {
                                $ass2 = count($assesment2);
                                if($ass2 > 4) {
                                foreach($assesment2 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment2 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass2;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } }  
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s3"><div></div></td>
                            <?php } } ?>
                           
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                                <div>TOTAL 2ème PERIODE</div>
                            </td>
                            <?php if(!empty($assesment3)) {
                                $ass3 = count($assesment3);
                                if($ass3 > 4) {
                                foreach($assesment3 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment3 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass3;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s3"><div></div></td>
                            <?php } } ?>
                            
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>TOTAL 3ème PERIODE</div>
                            </td>
                            <?php if(!empty($assesment4)) {
                                $ass4 = count($assesment4);
                                if($ass4 > 4) {
                                foreach($assesment4 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php } } else {
                                foreach($assesment4 as $key)
                                {
                                    $date = date("d M", strtotime($key['start_date']))." - ".date("d M Y", strtotime($key['end_date']))
                                    ?>
                                    <td class="s6 start_date"><div><?= $date ?></div></td>
                            <?php }
                                for($k=$ass4;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                    for($j=0; $j<4; $j++) { ?>
                                        <td class="s3"><div></div></td>
                                <?php } } ?>
                           
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>Total des travaux</div>
                            </td>
                            <td class="s3" dir="ltr" rowspan="2">
                              <div>TOTAL 4ème PERIODE</div>
                            </td>
                        <?php } ?>
                    </tr>
                    <tr style="height: 136px">
                        <th id="1281374097R3" style="height: 136px;" class="row-headers-background">
                            <div class="row-header-wrapper" style="line-height: 136px">4</div>
                        </th>
                        <td class="s7 softmerge" dir="ltr">
                            <div class="softmerge-inner" style="width:264px;left:-1px">Branche : <?= $subject_names->subject_name; ?></div>
                        </td>
                        <td class="s8 softmerge" dir="ltr">
                            <div class="softmerge-inner king" style="width:39px;left:-3px">TRAVAUX</div>
                        </td>
                        
                        <?php if($sclsctn == "maternelle" || $sclsctn == "creche") { 
                            if(!empty($assesment)) {
                                $ass = count($assesment);
                                if($ass > 5) {
                                foreach($assesment as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }
                                    if($key['type'] == "Exams") { $type = $exmlbl; }
                                    if($key['type'] == "Quiz") { $type = $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }
                                    if($key['type'] == "Exams") { $type = $exmlbl; }
                                    if($key['type'] == "Quiz") { $type = $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass;$k<5;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } }
                            else { 
                                for($j=0; $j<5; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            if(!empty($assesment2)) {
                                $ass2 = count($assesment2);
                                if($ass2 > 5) {
                                foreach($assesment2 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }
                                    if($key['type'] == "Exams") { $type = $exmlbl; }
                                    if($key['type'] == "Quiz") { $type = $quizlbl; }
                                    ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment2 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }
                                    if($key['type'] == "Exams") { $type = $exmlbl; }
                                    if($key['type'] == "Quiz") { $type = $quizlbl; }
                                    ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass2;$k<5;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<5; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            if(!empty($assesment3)) {
                                $ass3 = count($assesment3);
                                if($ass3 > 5) {
                                foreach($assesment3 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }
                                    if($key['type'] == "Exams") { $type = $exmlbl; }
                                    if($key['type'] == "Quiz") { $type = $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment3 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }
                                    if($key['type'] == "Exams") { $type = $exmlbl; }
                                    if($key['type'] == "Quiz") { $type = $quizlbl; }
                                    ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass3;$k<5;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<5; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } }
                        } elseif($sclsctn == "primaire") { 
                            if(!empty($assesment)) {
                                $ass = count($assesment);
                                if($ass > 4) {
                                foreach($assesment as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }
                                    if($key['type'] == "Exams") { $type = $exmlbl; }
                                    if($key['type'] == "Quiz") { $type = $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                     
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                     
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } }
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            if(!empty($assesment2)) {
                                $ass2 = count($assesment2);
                                if($ass2 > 4) {
                                foreach($assesment2 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                     
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                     
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment2 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                     
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                     
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass2;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            if(!empty($assesment3)) {
                                $ass3 = count($assesment3);
                                if($ass3 > 4) {
                                foreach($assesment3 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                     
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                     
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment3 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                     
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                     
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass3;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            if(!empty($assesment4)) {
                                $ass4 = count($assesment4);
                                if($ass4 > 4) {
                                foreach($assesment4 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                                                          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment4 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                                                          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                                                          
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass4;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            
                            if(!empty($assesment5)) {
                                $ass5 = count($assesment5);
                                if($ass5 > 4) {
                                foreach($assesment5 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }   
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                                                          
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment5 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                                                          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                                                          
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass5;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            
                            if(!empty($assesment6)) {
                                $ass6 = count($assesment6);
                                if($ass6 > 4) {
                                foreach($assesment6 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }   
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }       
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment6 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }     
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }         
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass6;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                        } else 
                        {  
                            if(!empty($assesment)) {
                                $ass = count($assesment);
                                if($ass > 4) {
                                foreach($assesment as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }           
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } }
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            if(!empty($assesment2)) {
                                $ass2 = count($assesment2);
                                if($ass2 > 4) {
                                foreach($assesment2 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                                                          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                                                          
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment2 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                                                          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                                                          
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass2;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            if(!empty($assesment3)) {
                                $ass3 = count($assesment3);
                                if($ass3 > 4) {
                                foreach($assesment3 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                                                          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                                                          
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment3 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                                                          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                                                          
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass3;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                            if(!empty($assesment4)) {
                                $ass4 = count($assesment4);
                                if($ass4 > 4) {
                                foreach($assesment4 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                                                          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                                                          
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                    ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php } } else {
                                foreach($assesment4 as $key)
                                { 
                                    if($key['type'] == "Assessment") { $type = $asslbl; }                                                                          
                                    if($key['type'] == "Exams") { $type =  $exmlbl; }                                                                          
                                    if($key['type'] == "Quiz") { $type =  $quizlbl; }
                                    ?>
                                    <td class="s9 softmerge" dir="ltr">
                                       <div class="softmerge-inner king" style="width:75px;left:-3px"><?= $type. " (". $key['title'].")"?></div>
                                    </td>                         
                            <?php }
                                for($k=$ass4;$k<4;$k++)    
                                { ?>
                                    <td class="s6"><div></div></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s9 softmerge" dir="ltr"><div></div></td>
                            <?php } } 
                        } ?>
                    
                    </tr>
                    
                    
                    <tr style="height: 40px">
                        <th id="1281374097R4" style="height: 40px;" class="row-headers-background">
                            <div class="row-header-wrapper" style="line-height: 40px">5</div>
                        </th>
                        <td class="s0" style="width:267px;" dir="ltr">NOMS ET PRENOMS</td>
                        <td class="s12" style="width:40px;" dir="ltr">MAX</td>
                        
                        <?php 
                        $as1 = [];
                        $as2 = [];
                        $as3 = [];
                        $as4 = [];
                        $as5 = [];
                        $as6 = [];
                        if($sclsctn == "maternelle" || $sclsctn == "creche") { 
                            if(!empty($assesment)) {
                                $ass = count($assesment);
                                if($ass > 5) {
                                    foreach($assesment as $key)
                                    {
                                        $as1[] = $key['max_marks'];
                                    ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment as $key)
                                    {
                                        $as1[] = $key['max_marks'];
                                        if($key['type'] == "Assessment") { $type = $asslbl; }                                     
                                        if($key['type'] == "Exams") { $type = $exmlbl; }                                     
                                        if($key['type'] == "Quiz") { $type = $quizlbl; }
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass;$k<5;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<5; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($as1) ?></td>
                            <td class="s13" id="recordermarks" style="width:37px;" dir="ltr">
                                <?php
                                if(($clsname == "1ère-(creche)" || $clsname == "1ère-(maternelle)") && ( $subjectname == "Activités sensorielles" || $subjectname == "Activités de schémas corporels" || $subjectname == "Activités de vie pratique" || $subjectname == "Activités d'arts plastiques" || $subjectname == "Activités musicales" || $subjectname == "Activités de comportement" || $subjectname == "Activités Physiques" || $subjectname == "Activités de structuration spatiale" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss" readonly value="8">
                                <?php }
                                elseif(($clsname == "1ère-(creche)" || $clsname == "1ère-(maternelle)") && ($subjectname == "Activités de langage" || $subjectname == "Activités exploratrices" || $subjectname == "Activités mathematiques" || $subjectname == "Activités mathématiques" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss" readonly value="12">
                                <?php }
                                elseif(($clsname == "1ère-(creche)" || $clsname == "1ère-(maternelle)") && ($subjectname == "Activités libres" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss" readonly value="20">
                                <?php } 
                                
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ( $subjectname == "Activités de schéma corporel" ||  $subjectname == "Activités de structuration spatiale" || $subjectname == "Activités d'orientation temporelle" || $subjectname == "Activités de latéralité"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss" readonly value="4">
                                <?php }
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ( $subjectname == "Activités musicales" || $subjectname == "Activités de vie pratiques" || $subjectname == "Activités d'arts plastiques" || $subjectname == "Activités Physiques" || $subjectname == "Activités sensorielles" || $subjectname == "Activités de comportement" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss" readonly value="8">
                                <?php }
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ($subjectname == "Activités exploratrices" || $subjectname == "Activités de language" || $subjectname == "Activités mathematiques" || $subjectname == "Activités mathématiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss" readonly value="12">
                                <?php }
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ($subjectname == "Activités libres" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss" readonly value="20">
                                <?php } ?>
                            </td>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#k1").html("");
                                    var kas1 = "<?php echo array_sum($as1) ?>";
                                    var mrk = $("#maxmarkss").val();
                                    //var mxmrk = parseInt(kas1) + parseInt(mrk);
                                    $("#k1").html(mrk);
                                });
                            </script>
                            <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="k1"></span></td>
                        
                            <?php if(!empty($assesment2)) {
                                $ass2 = count($assesment2);
                                if($ass2 > 5) {
                                    foreach($assesment2 as $key)
                                    { $as2[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment2 as $key)
                                    { $as2[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass2;$k<5;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<5; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($as2) ?></td>
                            <td class="s13" id="recordermarks" style="width:37px;" dir="ltr">
                               <?php
                                if(($clsname == "1ère-(creche)" || $clsname == "1ère-(maternelle)") && ( $subjectname == "Activités sensorielles" || $subjectname == "Activités de schémas corporels" || $subjectname == "Activités de vie pratique" || $subjectname == "Activités d'arts plastiques" || $subjectname == "Activités musicales" || $subjectname == "Activités de comportement" || $subjectname == "Activités Physiques" || $subjectname == "Activités de structuration spatiale" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss1" readonly value="8">
                                <?php }
                                elseif(($clsname == "1ère-(creche)" || $clsname == "1ère-(maternelle)") && ($subjectname == "Activités de langage" || $subjectname == "Activités exploratrices" || $subjectname == "Activités mathematiques" || $subjectname == "Activités mathématiques" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss1" readonly value="12">
                                <?php }
                                elseif(($clsname == "1ère-(creche)" || $clsname == "1ère-(maternelle)") && ($subjectname == "Activités libres" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss1" readonly value="20">
                                <?php } 
                                
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ( $subjectname == "Activités de schéma corporel" ||  $subjectname == "Activités de structuration spatiale" || $subjectname == "Activités d'orientation temporelle" || $subjectname == "Activités de latéralité"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss1" readonly value="4">
                                <?php }
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ( $subjectname == "Activités musicales" || $subjectname == "Activités de vie pratiques" || $subjectname == "Activités d'arts plastiques" || $subjectname == "Activités Physiques" || $subjectname == "Activités sensorielles" || $subjectname == "Activités de comportement" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss1" readonly value="8">
                                <?php }
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ($subjectname == "Activités exploratrices" || $subjectname == "Activités de language" || $subjectname == "Activités mathematiques" || $subjectname == "Activités mathématiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss1" readonly value="12">
                                <?php }
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ($subjectname == "Activités libres" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss1" readonly value="20">
                                <?php } 
                                ?>
                            </td>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#k2").html("");
                                    var kas2 = "<?php echo array_sum($as2) ?>";
                                    var mrk = $("#maxmarkss1").val();
                                    //var mxmrk = parseInt(kas2) + parseInt(mrk);
                                    $("#k2").html(mrk);
                                });
                            </script>
                            <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="k2"></span></td>
                        
                            <?php if(!empty($assesment3)) {
                                $ass3 = count($assesment3);
                                if($ass3 > 5) {
                                    foreach($assesment3 as $key)
                                    { $as3[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment3 as $key)
                                    { $as3[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass3;$k<5;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<5; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px;  background:#cccccc;" dir="ltr"><?= array_sum($as3) ?></td>
                            <td class="s13" id="recordermarks" style="width:37px;" dir="ltr">
                                <?php
                                if(($clsname == "1ère-(creche)" || $clsname == "1ère-(maternelle)") && ( $subjectname == "Activités sensorielles" || $subjectname == "Activités de schémas corporels" || $subjectname == "Activités de vie pratique" || $subjectname == "Activités d'arts plastiques" || $subjectname == "Activités musicales" || $subjectname == "Activités de comportement" || $subjectname == "Activités Physiques" || $subjectname == "Activités de structuration spatiale" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss2" readonly value="8">
                                <?php }
                                elseif(($clsname == "1ère-(creche)" || $clsname == "1ère-(maternelle)") && ($subjectname == "Activités de langage" || $subjectname == "Activités exploratrices" || $subjectname == "Activités mathematiques" || $subjectname == "Activités mathématiques" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss2" readonly value="12">
                                <?php }
                                elseif(($clsname == "1ère-(creche)" || $clsname == "1ère-(maternelle)") && ($subjectname == "Activités libres" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss2" readonly value="20">
                                <?php } 
                                
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ( $subjectname == "Activités de schéma corporel" ||  $subjectname == "Activités de structuration spatiale" || $subjectname == "Activités d'orientation temporelle" || $subjectname == "Activités de latéralité"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss2" readonly value="4">
                                <?php }
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ( $subjectname == "Activités musicales" || $subjectname == "Activités de vie pratiques" || $subjectname == "Activités d'arts plastiques" || $subjectname == "Activités Physiques" || $subjectname == "Activités sensorielles" || $subjectname == "Activités de comportement" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss2" readonly value="8">
                                <?php }
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ($subjectname == "Activités exploratrices" || $subjectname == "Activités de language" || $subjectname == "Activités mathematiques" || $subjectname == "Activités mathématiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss2" readonly value="12">
                                <?php }
                                elseif(($clsname == "2ème-(maternelle)" || $clsname == "3ème-(maternelle)") && ($subjectname == "Activités libres" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss2" readonly value="20">
                                <?php } 
                                ?>
                            </td>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#k3").html("");
                                    var kas3 = "<?php echo array_sum($as3) ?>";
                                    var mrk = $("#maxmarkss2").val();
                                    //var mxmrk = parseInt(kas3) + parseInt(mrk);
                                    $("#k3").html(mrk);
                                });
                            </script>
                            <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="k3"></span></td>
                            
                        <?php } elseif($sclsctn == "primaire") {
                            if(!empty($assesment)) {
                                $ass = count($assesment);
                                if($ass > 4) {
                                    foreach($assesment as $key)
                                    {
                                        $as1[] = $key['max_marks'];
                                        
                                    ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment as $key)
                                    {
                                        $as1[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($as1) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php 
                                if(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Expression Orale (FRANCAIS)" || $subjectname == "Expression Ecrite" || $subjectname == "Expression Orale (Langues Congolaises)" || $subjectname == "Problèmes" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Sciences d'éveil" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Religion" || $subjectname == "Technologie" || $subjectname == "Ed. phys. & sports" || $subjectname == "Grandeurs" || $subjectname == "Vocabulaire" || $subjectname == "Formes géométriques" || $subjectname == "Ed civ & morale" || $subjectname == "Ed. Santé Env." || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)" || $clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Langues Congolaises"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="30">
                                <?php }
                                
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Ed. Santé Env." || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Ed. phys. & sports" ||  $subjectname == "Zoologie - botanique & Info." || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Orth. phras. Ecrit. & réd" || $subjectname == "Exp. Orale - Récit. - Voc" || $subjectname == "Grammaire & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé & Env." || $subjectname == "Educ. civ & morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Orth. & Rédaction"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="5">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Problèmes" || $subjectname == "Technologie"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="15">
                                <?php }
                                
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Rédaction" || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Orthographe" || $subjectname == "Exp. Orale & Vocabulaire" || $subjectname == "Orth. & Rédaction" || $subjectname == "Gram. & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" ||  $subjectname == "Technologie" || $subjectname == "Phys.- Zool.- Info" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé Env." || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="10">
                                <?php } 
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Anatomie-Botanique" || $subjectname == "Langue Francaise" || $subjectname == "Langue Congolaises" || $subjectname == "Problèmes" || $subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="20">
                                <?php }
                                
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Orth. & Ecrit. /Calligr." || $subjectname == "Géographie"  || $subjectname == "Ed. phys. & sportive" || $subjectname == "Histoire" || $subjectname == "Religion (1)" || $subjectname == "Init. Trav. Prod." || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Ed. Santé & Env." || $subjectname == "Gram. & Analyse" || $subjectname == "Lecture (Français)" || $subjectname == "Rédaction" || $subjectname == "Gram. & Analye" || $subjectname == "Anatomie / Botanique" || $subjectname == "Mesures de grandeurs" || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Exp. Orale & Récitation" || $subjectname == "Problèmes" || $subjectname == "Physique / Zoologie" || $subjectname == "Formes géométriques" || $subjectname == "Numération" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="10">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Lecture (Langues Congolaises)"  || $subjectname == "Gram. & Conj."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="5">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Comp. Orale & Vocab." || $subjectname == "Techno. / Informatique (1)" || $subjectname == "Opérations"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="20">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Phras. Ecrite & Réda." || $subjectname = "Orthographe" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss5" readonly value="15">
                                <?php } ?>
                                
                            </td>
                        
                            <?php if(!empty($assesment2)) {
                                $ass2 = count($assesment2);
                                if($ass2 > 4) {
                                    foreach($assesment2 as $key)
                                    { $as2[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment2 as $key)
                                    { $as2[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass2;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($as2) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php 
                                if(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Expression Orale (FRANCAIS)" || $subjectname == "Expression Ecrite" || $subjectname == "Expression Orale (Langues Congolaises)" || $subjectname == "Problèmes" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Sciences d'éveil" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Religion" || $subjectname == "Technologie" || $subjectname == "Ed. phys. & sports" || $subjectname == "Grandeurs" || $subjectname == "Vocabulaire" || $subjectname == "Formes géométriques" || $subjectname == "Ed civ & morale" || $subjectname == "Ed. Santé Env." || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)" || $clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Langues Congolaises"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="30">
                                <?php }
                                
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Ed. Santé Env." || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Ed. phys. & sports" ||  $subjectname == "Zoologie - botanique & Info." || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Orth. phras. Ecrit. & réd" || $subjectname == "Exp. Orale - Récit. - Voc" || $subjectname == "Grammaire & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé & Env." || $subjectname == "Educ. civ & morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Orth. & Rédaction"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="5">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Problèmes" || $subjectname == "Technologie"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="15">
                                <?php }
                                
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Rédaction" || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Orthographe" || $subjectname == "Exp. Orale & Vocabulaire" || $subjectname == "Orth. & Rédaction" || $subjectname == "Gram. & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" ||  $subjectname == "Technologie" || $subjectname == "Phys.- Zool.- Info" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé Env." || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="10">
                                <?php } 
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Anatomie-Botanique" || $subjectname == "Langue Francaise" || $subjectname == "Langue Congolaises" || $subjectname == "Problèmes" || $subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="20">
                                <?php }
                                
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Orth. & Ecrit. /Calligr." || $subjectname == "Géographie"  || $subjectname == "Ed. phys. & sportive" || $subjectname == "Histoire" || $subjectname == "Religion (1)" || $subjectname == "Init. Trav. Prod." || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Ed. Santé & Env." || $subjectname == "Gram. & Analyse" || $subjectname == "Lecture (Français)" || $subjectname == "Rédaction" || $subjectname == "Gram. & Analye" || $subjectname == "Anatomie / Botanique" || $subjectname == "Mesures de grandeurs" || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Exp. Orale & Récitation" || $subjectname == "Problèmes" || $subjectname == "Physique / Zoologie" || $subjectname == "Formes géométriques" || $subjectname == "Numération" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="10">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Lecture (Langues Congolaises)"  || $subjectname == "Gram. & Conj."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="5">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Comp. Orale & Vocab." || $subjectname == "Techno. / Informatique (1)" || $subjectname == "Opérations"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="20">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Phras. Ecrite & Réda." || $subjectname = "Orthographe" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss3" readonly value="15">
                                <?php } ?>
                            </td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php 
                                if(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Expression Orale (FRANCAIS)" || $subjectname == "Expression Ecrite" || $subjectname == "Expression Orale (Langues Congolaises)" || $subjectname == "Problèmes" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Sciences d'éveil" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="40">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Religion" || $subjectname == "Technologie" || $subjectname == "Ed. phys. & sports" || $subjectname == "Grandeurs" || $subjectname == "Vocabulaire" || $subjectname == "Formes géométriques" || $subjectname == "Ed civ & morale" || $subjectname == "Ed. Santé Env." || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)" || $clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Langues Congolaises"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="60">
                                <?php }
                                
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Ed. Santé Env." || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Ed. phys. & sports" ||  $subjectname == "Zoologie - botanique & Info." || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Orth. phras. Ecrit. & réd" || $subjectname == "Exp. Orale - Récit. - Voc" || $subjectname == "Grammaire & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé & Env." || $subjectname == "Educ. civ & morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Orth. & Rédaction"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Problèmes" || $subjectname == "Technologie"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="40">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="30">
                                <?php }
                                
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Rédaction" || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Orthographe" || $subjectname == "Exp. Orale & Vocabulaire" || $subjectname == "Orth. & Rédaction" || $subjectname == "Gram. & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" ||  $subjectname == "Technologie" || $subjectname == "Phys.- Zool.- Info" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé Env." || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="20">
                                <?php } 
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Anatomie-Botanique" || $subjectname == "Langue Francaise" || $subjectname == "Langue Congolaises" || $subjectname == "Problèmes" || $subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="40">
                                <?php }
                                
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Orth. & Ecrit. /Calligr." || $subjectname == "Géographie"  || $subjectname == "Ed. phys. & sportive" || $subjectname == "Histoire" || $subjectname == "Religion (1)" || $subjectname == "Init. Trav. Prod." || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Ed. Santé & Env." || $subjectname == "Gram. & Analyse" || $subjectname == "Lecture (Français)" || $subjectname == "Rédaction" || $subjectname == "Gram. & Analye" || $subjectname == "Anatomie / Botanique" || $subjectname == "Mesures de grandeurs" || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Exp. Orale & Récitation" || $subjectname == "Problèmes" || $subjectname == "Physique / Zoologie" || $subjectname == "Formes géométriques" || $subjectname == "Numération" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="20">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Lecture (Langues Congolaises)"  || $subjectname == "Gram. & Conj."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="10">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Comp. Orale & Vocab." || $subjectname == "Techno. / Informatique (1)" || $subjectname == "Opérations"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="40">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Phras. Ecrite & Réda." || $subjectname = "Orthographe" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss4" readonly value="30">
                                <?php } ?>
                            </td>
                            <?php $sump = array_sum($as1)+array_sum($as2); ?>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#p1").html("");
                                    //var kas1 = "<?php echo $sump ?>";
                                    var mrk3 = $("#maxmarkss3").val();
                                    var mrk4 = $("#maxmarkss4").val();
                                    var mrk5 = $("#maxmarkss5").val();
                                    //var mxmrk = parseInt(kas1) + parseInt(mrk3) + parseInt(mrk4) + parseInt(mrk5);
                                    var mxmrk = parseInt(mrk3) + parseInt(mrk4) + parseInt(mrk5);
                                    $("#p1").html(mxmrk);
                                });
                            </script>
                            <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="p1"></span></td>
                        
                            <?php if(!empty($assesment3)) {
                                $ass3 = count($assesment3);
                                if($ass3 > 4) {
                                    foreach($assesment3 as $key)
                                    { $as3[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment3 as $key)
                                    { $as3[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass3;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px;  background:#cccccc;" dir="ltr"><?= array_sum($as3) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php 
                                if(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Expression Orale (FRANCAIS)" || $subjectname == "Expression Ecrite" || $subjectname == "Expression Orale (Langues Congolaises)" || $subjectname == "Problèmes" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Sciences d'éveil" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Religion" || $subjectname == "Technologie" || $subjectname == "Ed. phys. & sports" || $subjectname == "Grandeurs" || $subjectname == "Vocabulaire" || $subjectname == "Formes géométriques" || $subjectname == "Ed civ & morale" || $subjectname == "Ed. Santé Env." || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)" || $clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Langues Congolaises"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="30">
                                <?php }
                                
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Ed. Santé Env." || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Ed. phys. & sports" ||  $subjectname == "Zoologie - botanique & Info." || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Orth. phras. Ecrit. & réd" || $subjectname == "Exp. Orale - Récit. - Voc" || $subjectname == "Grammaire & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé & Env." || $subjectname == "Educ. civ & morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Orth. & Rédaction"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="5">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Problèmes" || $subjectname == "Technologie"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="15">
                                <?php }
                                
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Rédaction" || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Orthographe" || $subjectname == "Exp. Orale & Vocabulaire" || $subjectname == "Orth. & Rédaction" || $subjectname == "Gram. & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" ||  $subjectname == "Technologie" || $subjectname == "Phys.- Zool.- Info" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé Env." || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="10">
                                <?php } 
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Anatomie-Botanique" || $subjectname == "Langue Francaise" || $subjectname == "Langue Congolaises" || $subjectname == "Problèmes" || $subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="20">
                                <?php }
                                
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Orth. & Ecrit. /Calligr." || $subjectname == "Géographie"  || $subjectname == "Ed. phys. & sportive" || $subjectname == "Histoire" || $subjectname == "Religion (1)" || $subjectname == "Init. Trav. Prod." || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Ed. Santé & Env." || $subjectname == "Gram. & Analyse" || $subjectname == "Lecture (Français)" || $subjectname == "Rédaction" || $subjectname == "Gram. & Analye" || $subjectname == "Anatomie / Botanique" || $subjectname == "Mesures de grandeurs" || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Exp. Orale & Récitation" || $subjectname == "Problèmes" || $subjectname == "Physique / Zoologie" || $subjectname == "Formes géométriques" || $subjectname == "Numération" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="10">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Lecture (Langues Congolaises)"  || $subjectname == "Gram. & Conj."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="5">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Comp. Orale & Vocab." || $subjectname == "Techno. / Informatique (1)" || $subjectname == "Opérations"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="20">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Phras. Ecrite & Réda." || $subjectname = "Orthographe" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss6" readonly value="15">
                                <?php } ?>
                            </td>
                        
                            <?php if(!empty($assesment4)) {
                                $ass4 = count($assesment4);
                                if($ass4 > 4) {
                                    foreach($assesment4 as $key)
                                    { $as4[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment4 as $key)
                                    { $as4[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass4;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            
                            <td class="s13" style="width:37px;  background:#cccccc;" dir="ltr"><?= array_sum($as4) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php 
                                if(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Expression Orale (FRANCAIS)" || $subjectname == "Expression Ecrite" || $subjectname == "Expression Orale (Langues Congolaises)" || $subjectname == "Problèmes" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Sciences d'éveil" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Religion" || $subjectname == "Technologie" || $subjectname == "Ed. phys. & sports" || $subjectname == "Grandeurs" || $subjectname == "Vocabulaire" || $subjectname == "Formes géométriques" || $subjectname == "Ed civ & morale" || $subjectname == "Ed. Santé Env." || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)" || $clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Langues Congolaises"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="30">
                                <?php }
                                
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Ed. Santé Env." || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Ed. phys. & sports" ||  $subjectname == "Zoologie - botanique & Info." || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Orth. phras. Ecrit. & réd" || $subjectname == "Exp. Orale - Récit. - Voc" || $subjectname == "Grammaire & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé & Env." || $subjectname == "Educ. civ & morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Orth. & Rédaction"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="5">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Problèmes" || $subjectname == "Technologie"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="15">
                                <?php }
                                
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Rédaction" || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Orthographe" || $subjectname == "Exp. Orale & Vocabulaire" || $subjectname == "Orth. & Rédaction" || $subjectname == "Gram. & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" ||  $subjectname == "Technologie" || $subjectname == "Phys.- Zool.- Info" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé Env." || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="10">
                                <?php } 
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Anatomie-Botanique" || $subjectname == "Langue Francaise" || $subjectname == "Langue Congolaises" || $subjectname == "Problèmes" || $subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="20">
                                <?php }
                                
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Orth. & Ecrit. /Calligr." || $subjectname == "Géographie"  || $subjectname == "Ed. phys. & sportive" || $subjectname == "Histoire" || $subjectname == "Religion (1)" || $subjectname == "Init. Trav. Prod." || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Ed. Santé & Env." || $subjectname == "Gram. & Analyse" || $subjectname == "Lecture (Français)" || $subjectname == "Rédaction" || $subjectname == "Gram. & Analye" || $subjectname == "Anatomie / Botanique" || $subjectname == "Mesures de grandeurs" || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Exp. Orale & Récitation" || $subjectname == "Problèmes" || $subjectname == "Physique / Zoologie" || $subjectname == "Formes géométriques" || $subjectname == "Numération" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="10">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Lecture (Langues Congolaises)"  || $subjectname == "Gram. & Conj."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="5">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Comp. Orale & Vocab." || $subjectname == "Techno. / Informatique (1)" || $subjectname == "Opérations"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="20">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Phras. Ecrite & Réda." || $subjectname = "Orthographe" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss7" readonly value="15">
                                <?php } ?>
                            </td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php 
                                if(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Expression Orale (FRANCAIS)" || $subjectname == "Expression Ecrite" || $subjectname == "Expression Orale (Langues Congolaises)" || $subjectname == "Problèmes" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Sciences d'éveil" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="40">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Religion" || $subjectname == "Technologie" || $subjectname == "Ed. phys. & sports" || $subjectname == "Grandeurs" || $subjectname == "Vocabulaire" || $subjectname == "Formes géométriques" || $subjectname == "Ed civ & morale" || $subjectname == "Ed. Santé Env." || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)" || $clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Langues Congolaises"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="60">
                                <?php }
                                
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Ed. Santé Env." || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Ed. phys. & sports" ||  $subjectname == "Zoologie - botanique & Info." || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Orth. phras. Ecrit. & réd" || $subjectname == "Exp. Orale - Récit. - Voc" || $subjectname == "Grammaire & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé & Env." || $subjectname == "Educ. civ & morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Orth. & Rédaction"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Problèmes" || $subjectname == "Technologie"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="40">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="30">
                                <?php }
                                
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Rédaction" || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Orthographe" || $subjectname == "Exp. Orale & Vocabulaire" || $subjectname == "Orth. & Rédaction" || $subjectname == "Gram. & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" ||  $subjectname == "Technologie" || $subjectname == "Phys.- Zool.- Info" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé Env." || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="20">
                                <?php } 
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Anatomie-Botanique" || $subjectname == "Langue Francaise" || $subjectname == "Langue Congolaises" || $subjectname == "Problèmes" || $subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="40">
                                <?php }
                                
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Orth. & Ecrit. /Calligr." || $subjectname == "Géographie"  || $subjectname == "Ed. phys. & sportive" || $subjectname == "Histoire" || $subjectname == "Religion (1)" || $subjectname == "Init. Trav. Prod." || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Ed. Santé & Env." || $subjectname == "Gram. & Analyse" || $subjectname == "Lecture (Français)" || $subjectname == "Rédaction" || $subjectname == "Gram. & Analye" || $subjectname == "Anatomie / Botanique" || $subjectname == "Mesures de grandeurs" || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Exp. Orale & Récitation" || $subjectname == "Problèmes" || $subjectname == "Physique / Zoologie" || $subjectname == "Formes géométriques" || $subjectname == "Numération" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="20">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Lecture (Langues Congolaises)"  || $subjectname == "Gram. & Conj."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="10">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Comp. Orale & Vocab." || $subjectname == "Techno. / Informatique (1)" || $subjectname == "Opérations"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="40">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Phras. Ecrite & Réda." || $subjectname = "Orthographe" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss8" readonly value="30">
                                <?php } ?>
                            </td>
                            <?php $sump1 = array_sum($as3)+array_sum($as4); ?>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#p2").html("");
                                    //var kas1 = "<?php echo $sump1 ?>";
                                    var mrk6 = $("#maxmarkss6").val();
                                    var mrk7 = $("#maxmarkss7").val();
                                    var mrk8 = $("#maxmarkss8").val();
                                    //var mxmrk = parseInt(kas1) + parseInt(mrk6) + parseInt(mrk7) + parseInt(mrk8);
                                    var mxmrk = parseInt(mrk6) + parseInt(mrk7) + parseInt(mrk8);
                                    $("#p2").html(mxmrk);
                                });
                            </script>
                            <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="p2"></span></td>
                            
                            <?php if(!empty($assesment5)) {
                                $ass5 = count($assesment5);
                                if($ass5 > 4) {
                                    foreach($assesment5 as $key)
                                    { $as5[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment5 as $key)
                                    { $as5[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass5;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px;  background:#cccccc;" dir="ltr"><?= array_sum($as5) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php 
                                if(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Expression Orale (FRANCAIS)" || $subjectname == "Expression Ecrite" || $subjectname == "Expression Orale (Langues Congolaises)" || $subjectname == "Problèmes" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Sciences d'éveil" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Religion" || $subjectname == "Technologie" || $subjectname == "Ed. phys. & sports" || $subjectname == "Grandeurs" || $subjectname == "Vocabulaire" || $subjectname == "Formes géométriques" || $subjectname == "Ed civ & morale" || $subjectname == "Ed. Santé Env." || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)" || $clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Langues Congolaises"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="30">
                                <?php }
                                
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Ed. Santé Env." || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Ed. phys. & sports" ||  $subjectname == "Zoologie - botanique & Info." || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Orth. phras. Ecrit. & réd" || $subjectname == "Exp. Orale - Récit. - Voc" || $subjectname == "Grammaire & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé & Env." || $subjectname == "Educ. civ & morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Orth. & Rédaction"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="5">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Problèmes" || $subjectname == "Technologie"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="15">
                                <?php }
                                
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Rédaction" || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Orthographe" || $subjectname == "Exp. Orale & Vocabulaire" || $subjectname == "Orth. & Rédaction" || $subjectname == "Gram. & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" ||  $subjectname == "Technologie" || $subjectname == "Phys.- Zool.- Info" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé Env." || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="10">
                                <?php } 
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Anatomie-Botanique" || $subjectname == "Langue Francaise" || $subjectname == "Langue Congolaises" || $subjectname == "Problèmes" || $subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="20">
                                <?php }
                                
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Orth. & Ecrit. /Calligr." || $subjectname == "Géographie"  || $subjectname == "Ed. phys. & sportive" || $subjectname == "Histoire" || $subjectname == "Religion (1)" || $subjectname == "Init. Trav. Prod." || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Ed. Santé & Env." || $subjectname == "Gram. & Analyse" || $subjectname == "Lecture (Français)" || $subjectname == "Rédaction" || $subjectname == "Gram. & Analye" || $subjectname == "Anatomie / Botanique" || $subjectname == "Mesures de grandeurs" || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Exp. Orale & Récitation" || $subjectname == "Problèmes" || $subjectname == "Physique / Zoologie" || $subjectname == "Formes géométriques" || $subjectname == "Numération" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="10">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Lecture (Langues Congolaises)"  || $subjectname == "Gram. & Conj."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="5">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Comp. Orale & Vocab." || $subjectname == "Techno. / Informatique (1)" || $subjectname == "Opérations"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="20">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Phras. Ecrite & Réda." || $subjectname = "Orthographe" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss9" readonly value="15">
                                <?php } ?>
                            </td>
                        
                            <?php if(!empty($assesment6)) {
                                $ass6 = count($assesment6);
                                if($ass6 > 4) {
                                    foreach($assesment6 as $key)
                                    { $as6[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment6 as $key)
                                    { $as6[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass6;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            
                            <td class="s13" style="width:37px;  background:#cccccc;" dir="ltr"><?= array_sum($as5) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php 
                                if(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Expression Orale (FRANCAIS)" || $subjectname == "Expression Ecrite" || $subjectname == "Expression Orale (Langues Congolaises)" || $subjectname == "Problèmes" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Sciences d'éveil" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Religion" || $subjectname == "Technologie" || $subjectname == "Ed. phys. & sports" || $subjectname == "Grandeurs" || $subjectname == "Vocabulaire" || $subjectname == "Formes géométriques" || $subjectname == "Ed civ & morale" || $subjectname == "Ed. Santé Env." || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)" || $clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Langues Congolaises"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="30">
                                <?php }
                                
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Ed. Santé Env." || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Ed. phys. & sports" ||  $subjectname == "Zoologie - botanique & Info." || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Orth. phras. Ecrit. & réd" || $subjectname == "Exp. Orale - Récit. - Voc" || $subjectname == "Grammaire & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé & Env." || $subjectname == "Educ. civ & morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Orth. & Rédaction"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="5">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Problèmes" || $subjectname == "Technologie"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="15">
                                <?php }
                                
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Rédaction" || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Orthographe" || $subjectname == "Exp. Orale & Vocabulaire" || $subjectname == "Orth. & Rédaction" || $subjectname == "Gram. & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" ||  $subjectname == "Technologie" || $subjectname == "Phys.- Zool.- Info" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé Env." || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="10">
                                <?php } 
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Anatomie-Botanique" || $subjectname == "Langue Francaise" || $subjectname == "Langue Congolaises" || $subjectname == "Problèmes" || $subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="20">
                                <?php }
                                
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Orth. & Ecrit. /Calligr." || $subjectname == "Géographie"  || $subjectname == "Ed. phys. & sportive" || $subjectname == "Histoire" || $subjectname == "Religion (1)" || $subjectname == "Init. Trav. Prod." || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Ed. Santé & Env." || $subjectname == "Gram. & Analyse" || $subjectname == "Lecture (Français)" || $subjectname == "Rédaction" || $subjectname == "Gram. & Analye" || $subjectname == "Anatomie / Botanique" || $subjectname == "Mesures de grandeurs" || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Exp. Orale & Récitation" || $subjectname == "Problèmes" || $subjectname == "Physique / Zoologie" || $subjectname == "Formes géométriques" || $subjectname == "Numération" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="10">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Lecture (Langues Congolaises)"  || $subjectname == "Gram. & Conj."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="5">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Comp. Orale & Vocab." || $subjectname == "Techno. / Informatique (1)" || $subjectname == "Opérations"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="20">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Phras. Ecrite & Réda." || $subjectname = "Orthographe" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss10" readonly value="15">
                                <?php } ?>
                            </td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php 
                                if(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Expression Orale (FRANCAIS)" || $subjectname == "Expression Ecrite" || $subjectname == "Expression Orale (Langues Congolaises)" || $subjectname == "Problèmes" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Sciences d'éveil" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="40">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)") && ($subjectname == "Religion" || $subjectname == "Technologie" || $subjectname == "Ed. phys. & sports" || $subjectname == "Grandeurs" || $subjectname == "Vocabulaire" || $subjectname == "Formes géométriques" || $subjectname == "Ed civ & morale" || $subjectname == "Ed. Santé Env." || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère-(primaire)" || $clsname == "2ème-(primaire)" || $clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Langues Congolaises"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="60">
                                <?php }
                                
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Ed. Santé Env." || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Ed. phys. & sports" ||  $subjectname == "Zoologie - botanique & Info." || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Orth. phras. Ecrit. & réd" || $subjectname == "Exp. Orale - Récit. - Voc" || $subjectname == "Grammaire & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé & Env." || $subjectname == "Educ. civ & morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Orth. & Rédaction"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Problèmes" || $subjectname == "Technologie"  ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="40">
                                <?php }
                                elseif(($clsname == "4ème-(primaire)" || $clsname == "3ème-(primaire)") && ($subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="30">
                                <?php }
                                
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Rédaction" || $subjectname == "Ed. Phys. & Sport" || $subjectname == "Orthographe" || $subjectname == "Exp. Orale & Vocabulaire" || $subjectname == "Orth. & Rédaction" || $subjectname == "Gram. & Conj." || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Religion" ||  $subjectname == "Technologie" || $subjectname == "Phys.- Zool.- Info" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Santé Env." || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Formes géométriques" || $subjectname == "Grandeurs" || $subjectname == "Numération" || $subjectname == "Opérations" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques" || $subjectname == "Init. Trav. Prod."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="20">
                                <?php } 
                                elseif(($clsname == "5ème-(primaire)") && ($subjectname == "Anatomie-Botanique" || $subjectname == "Langue Francaise" || $subjectname == "Langue Congolaises" || $subjectname == "Problèmes" || $subjectname == "Gram. Conj. Analyse" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="40">
                                <?php }
                                
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Orth. & Ecrit. /Calligr." || $subjectname == "Géographie"  || $subjectname == "Ed. phys. & sportive" || $subjectname == "Histoire" || $subjectname == "Religion (1)" || $subjectname == "Init. Trav. Prod." || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Ed. Santé & Env." || $subjectname == "Gram. & Analyse" || $subjectname == "Lecture (Français)" || $subjectname == "Rédaction" || $subjectname == "Gram. & Analye" || $subjectname == "Anatomie / Botanique" || $subjectname == "Mesures de grandeurs" || $subjectname == "Exp. Orale & Vocab." || $subjectname == "Exp. Orale & Récitation" || $subjectname == "Problèmes" || $subjectname == "Physique / Zoologie" || $subjectname == "Formes géométriques" || $subjectname == "Numération" || $subjectname == "Arts plastiques" || $subjectname == "Arts dramatiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="20">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Lecture (Langues Congolaises)"  || $subjectname == "Gram. & Conj."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="10">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Comp. Orale & Vocab." || $subjectname == "Techno. / Informatique (1)" || $subjectname == "Opérations"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="40">
                                <?php }
                                elseif(($clsname == "6ème-(primaire)") && ($subjectname == "Phras. Ecrite & Réda." || $subjectname = "Orthographe" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss11" readonly value="30">
                                <?php } ?>
                            </td>
                            <?php $sump2 = array_sum($as5)+array_sum($as6); ?>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#p3").html("");
                                    //var kas1 = "<?php echo $sump2 ?>";
                                    var mrk9 = $("#maxmarkss9").val();
                                    var mrk10 = $("#maxmarkss10").val();
                                    var mrk11 = $("#maxmarkss11").val();
                                    //var mxmrk = parseInt(kas1) + parseInt(mrk9) + parseInt(mrk10) + parseInt(mrk11);
                                    var mxmrk =  parseInt(mrk9) + parseInt(mrk10) + parseInt(mrk11);
                                    $("#p3").html(mxmrk);
                                });
                            </script>
                            <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="p3"></span></td>
                            
                        <?php } else  { 
                            if(!empty($assesment)) {
                                $ass = count($assesment);
                                if($ass > 4) {
                                    foreach($assesment as $key)
                                    {
                                        $as1[] = $key['max_marks'];
                                    ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment as $key)
                                    {
                                        $as1[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($as1) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php
                                if($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Géométrie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Zoologie" ||  $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php }
                                 elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" || $subjectname == "Géographie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="30">
                                <?php }
                                 elseif(($clsname == "8ème-(cycle terminal de l'education de base (cteb))" || $clsname == "7ème-(cycle terminal de l'education de base (cteb))"  ) && ($subjectname == "Algèbre"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="40">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="50">
                                <?php }
                                
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Géométrie" || $subjectname == "Géographie" || $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Zoologie" ||  $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="30">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="70">
                                <?php }
                               
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Dessin Scientifique" || $subjectname == "Microbiologie" || $subjectname == "Géologie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Techn. d'Info. & Com. (TIC)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Géom. et Trigo" || $subjectname == "Religion (1)" || $subjectname == "Biologie Générale" || $subjectname == "Sociologie Africaine" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Civ. et Morale" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Chimie" || $subjectname == "Physique" || $subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="30">
                                <?php }
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Algèbre. Stat. et Analy." ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="40">
                                <?php }         
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="50">
                                <?php }
                                elseif(($clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Religion (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Dessin" || $subjectname == "Education Physique" || $subjectname == "Soc. Afri. / Ecopol (1)" || $subjectname == "Géographie" || $subjectname == "Histoire" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Anglais" || $subjectname == "Chimie" || $subjectname == "Biologie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="40">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Français" || $subjectname == "Mathématiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="50">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)") && ($subjectname == "Religion" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)") && ($subjectname == "Esthétique" || $subjectname == "Philosophie" || $subjectname == "Dessin Scientifique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php }  
                                
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Economie Politique" || $subjectname == "Education musica/théâtrale" || $subjectname == "Biologie/Micro" || $subjectname == "Dessin Pédagogique" || $subjectname == "Education Physique" || $subjectname == "Sociologie Africaine" || $subjectname == "Informatique" || $subjectname == "Géographie" || $subjectname == "Histoire" || $subjectname == "Biologie" || $subjectname == "Travaux Man./Ecriture" || $subjectname == "Education musicale/Théâtrale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)") && ($subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Physique" || $subjectname == "Chimie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" || $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="50">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Chimie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Trav. Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="40">
                                <?php } 
                                
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Education musicale /théâtrale" || $subjectname == "Physique" || $subjectname == "Ed. Musicale/Théâtre" || $subjectname == "Esthétique" || $subjectname == "Education Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Biologie" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Anglais" || $subjectname == "Langues nationales" || $subjectname == "Mathématiques" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="40">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Dessin Pédagogique" || $subjectname == "Trav./Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="50">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Pratique d'enseignement"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="100">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Religion" || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Droit" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Mathématiques Générales" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Correspondance Com. Franc." || $subjectname == "Correspondance Com. Angl."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Documentation Commerciale" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="30">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="60">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Pratique Professionnelle"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="100">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Religion (1)" || $subjectname == "Opérations des banques et des crédits" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Entreprenariat" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Correspondance Com. Française" || $subjectname == "Droit" || $subjectname == "Mathématiques Générales" || $subjectname ==  "Economie Politique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)" ) && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="30">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Analytique" || $subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="40">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Fiscalité" || $subjectname == "Finances Publiques" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname ==  "Déontologie Professionnelle" || $subjectname == "Correspondance Com. Française" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Economie de développement" || $subjectname == "Mathématiques Générales" || $subjectname == "Organisations des entreprises" || $subjectname == "Droit"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="50">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Education à la vie" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Religion"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Sociologie / Ecopol (1)" || $subjectname == "Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Microbiologie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Anglais" || $subjectname == "Anglais (1)" || $subjectname == "Grec (1)" || $subjectname == "Mathématiques (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="40">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Français" || $subjectname == "Latin"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="50">
                                <?php }
                                                                                            
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Religion (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="10">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Mathématiques" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Esthétique" || $subjectname == "Physique" || $subjectname == "Biologie" || $subjectname == "Philosophie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss13" readonly value="20">
                                <?php }
                                ?>
                            </td>
                        
                            <?php if(!empty($assesment2)) {
                                $ass2 = count($assesment2);
                                if($ass2 > 4) {
                                    foreach($assesment2 as $key)
                                    { $as2[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment2 as $key)
                                    { $as2[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass2;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($as2) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php
                                if($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Géométrie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Zoologie" ||  $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php }
                                 elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" || $subjectname == "Géographie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="30">
                                <?php }
                                 elseif(($clsname == "8ème-(cycle terminal de l'education de base (cteb))" || $clsname == "7ème-(cycle terminal de l'education de base (cteb))"  ) && ($subjectname == "Algèbre"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="40">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="50">
                                <?php }
                                
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Géométrie" || $subjectname == "Géographie" || $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Zoologie" ||  $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="30">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="70">
                                <?php }
                               
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Dessin Scientifique" || $subjectname == "Microbiologie" || $subjectname == "Géologie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Techn. d'Info. & Com. (TIC)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Géom. et Trigo" || $subjectname == "Religion (1)" || $subjectname == "Biologie Générale" || $subjectname == "Sociologie Africaine" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Civ. et Morale" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Chimie" || $subjectname == "Physique" || $subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="30">
                                <?php }
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Algèbre. Stat. et Analy." ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="40">
                                <?php }         
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="50">
                                <?php }
                                elseif(($clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Religion (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Dessin" || $subjectname == "Education Physique" || $subjectname == "Soc. Afri. / Ecopol (1)" || $subjectname == "Géographie" || $subjectname == "Histoire" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Anglais" || $subjectname == "Chimie" || $subjectname == "Biologie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="40">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Français" || $subjectname == "Mathématiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="50">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)") && ($subjectname == "Religion" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)") && ($subjectname == "Esthétique" || $subjectname == "Philosophie" || $subjectname == "Dessin Scientifique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php }  
                                
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Economie Politique" || $subjectname == "Education musica/théâtrale" || $subjectname == "Biologie/Micro" || $subjectname == "Dessin Pédagogique" || $subjectname == "Education Physique" || $subjectname == "Sociologie Africaine" || $subjectname == "Informatique" || $subjectname == "Géographie" || $subjectname == "Histoire" || $subjectname == "Biologie" || $subjectname == "Travaux Man./Ecriture" || $subjectname == "Education musicale/Théâtrale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)") && ($subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Physique" || $subjectname == "Chimie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" || $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="50">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Chimie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Trav. Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="40">
                                <?php } 
                                
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Education musicale /théâtrale" || $subjectname == "Physique" || $subjectname == "Ed. Musicale/Théâtre" || $subjectname == "Esthétique" || $subjectname == "Education Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Biologie" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Anglais" || $subjectname == "Langues nationales" || $subjectname == "Mathématiques" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="40">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Dessin Pédagogique" || $subjectname == "Trav./Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="50">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Pratique d'enseignement"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="100">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Religion" || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Droit" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Mathématiques Générales" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Correspondance Com. Franc." || $subjectname == "Correspondance Com. Angl."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Documentation Commerciale" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="30">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="60">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Pratique Professionnelle"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="100">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Religion (1)" || $subjectname == "Opérations des banques et des crédits" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Entreprenariat" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Correspondance Com. Française" || $subjectname == "Droit" || $subjectname == "Mathématiques Générales" || $subjectname ==  "Economie Politique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)" ) && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="30">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Analytique" || $subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="40">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Fiscalité" || $subjectname == "Finances Publiques" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname ==  "Déontologie Professionnelle" || $subjectname == "Correspondance Com. Française" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Economie de développement" || $subjectname == "Mathématiques Générales" || $subjectname == "Organisations des entreprises" || $subjectname == "Droit"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="50">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Education à la vie" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Religion"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Sociologie / Ecopol (1)" || $subjectname == "Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Microbiologie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Anglais" || $subjectname == "Anglais (1)" || $subjectname == "Grec (1)" || $subjectname == "Mathématiques (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="40">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Français" || $subjectname == "Latin"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="50">
                                <?php }
                                                                                            
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Religion (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="10">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Mathématiques" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Esthétique" || $subjectname == "Physique" || $subjectname == "Biologie" || $subjectname == "Philosophie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss14" readonly value="20">
                                <?php }
                                ?>
                            </td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php
                                if($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Géométrie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Zoologie" ||  $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php }
                                 elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" || $subjectname == "Géographie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="60">
                                <?php }
                                 elseif(($clsname == "8ème-(cycle terminal de l'education de base (cteb))" || $clsname == "7ème-(cycle terminal de l'education de base (cteb))"  ) && ($subjectname == "Algèbre"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="80">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="100">
                                <?php }
                                
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Géométrie" || $subjectname == "Géographie" || $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Zoologie" ||  $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="60">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="140">
                                <?php }
                               
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Dessin Scientifique" || $subjectname == "Microbiologie" || $subjectname == "Géologie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Techn. d'Info. & Com. (TIC)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Géom. et Trigo" || $subjectname == "Religion (1)" || $subjectname == "Biologie Générale" || $subjectname == "Sociologie Africaine" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Civ. et Morale" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Chimie" || $subjectname == "Physique" || $subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="60">
                                <?php }
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Algèbre. Stat. et Analy." ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="80">
                                <?php }         
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="100">
                                <?php }
                                elseif(($clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Religion (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Dessin" || $subjectname == "Education Physique" || $subjectname == "Soc. Afri. / Ecopol (1)" || $subjectname == "Géographie" || $subjectname == "Histoire" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Anglais" || $subjectname == "Chimie" || $subjectname == "Biologie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="80">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Français" || $subjectname == "Mathématiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="100">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)") && ($subjectname == "Religion" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)") && ($subjectname == "Esthétique" || $subjectname == "Philosophie" || $subjectname == "Dessin Scientifique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php }  
                                
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Economie Politique" || $subjectname == "Education musica/théâtrale" || $subjectname == "Biologie/Micro" || $subjectname == "Dessin Pédagogique" || $subjectname == "Education Physique" || $subjectname == "Sociologie Africaine" || $subjectname == "Informatique" || $subjectname == "Géographie" || $subjectname == "Histoire" || $subjectname == "Biologie" || $subjectname == "Travaux Man./Ecriture" || $subjectname == "Education musicale/Théâtrale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)") && ($subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Physique" || $subjectname == "Chimie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="80">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" || $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="100">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Chimie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Trav. Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="80">
                                <?php } 
                                
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Education musicale /théâtrale" || $subjectname == "Physique" || $subjectname == "Ed. Musicale/Théâtre" || $subjectname == "Esthétique" || $subjectname == "Education Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Biologie" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Anglais" || $subjectname == "Langues nationales" || $subjectname == "Mathématiques" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="80">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Dessin Pédagogique" || $subjectname == "Trav./Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="100">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Pratique d'enseignement"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Religion" || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Droit" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Mathématiques Générales" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Correspondance Com. Franc." || $subjectname == "Correspondance Com. Angl."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Documentation Commerciale" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="60">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="80">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="120">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Pratique Professionnelle"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Religion (1)" || $subjectname == "Opérations des banques et des crédits" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Entreprenariat" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Correspondance Com. Française" || $subjectname == "Droit" || $subjectname == "Mathématiques Générales" || $subjectname ==  "Economie Politique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)" ) && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="60">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Analytique" || $subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="80">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Fiscalité" || $subjectname == "Finances Publiques" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname ==  "Déontologie Professionnelle" || $subjectname == "Correspondance Com. Française" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Economie de développement" || $subjectname == "Mathématiques Générales" || $subjectname == "Organisations des entreprises" || $subjectname == "Droit"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="100">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Education à la vie" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Religion"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Sociologie / Ecopol (1)" || $subjectname == "Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Microbiologie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Anglais" || $subjectname == "Anglais (1)" || $subjectname == "Grec (1)" || $subjectname == "Mathématiques (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="80">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Français" || $subjectname == "Latin"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="100">
                                <?php }
                                                                                            
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Religion (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="20">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Mathématiques" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Esthétique" || $subjectname == "Physique" || $subjectname == "Biologie" || $subjectname == "Philosophie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss12" readonly value="40">
                                <?php }
                                ?>
                            </td>
                            <?php $sump = array_sum($as1)+array_sum($as2); ?>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#s1").html("");
                                    //var kas1 = "<?php echo $sump ?>";
                                    var mrk13 = $("#maxmarkss13").val();
                                    var mrk14 = $("#maxmarkss14").val();
                                    var mrk12 = $("#maxmarkss12").val();
                                    //var mxmrk = parseInt(kas1) + parseInt(mrk13) + parseInt(mrk14) + parseInt(mrk12);
                                    var mxmrk = parseInt(mrk13) + parseInt(mrk14) + parseInt(mrk12);
                                    $("#s1").html(mxmrk);
                                });
                            </script>
                            <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="s1"></span></td>
                        
                            <?php if(!empty($assesment3)) {
                                $ass3 = count($assesment3);
                                if($ass3 > 4) {
                                    foreach($assesment3 as $key)
                                    { $as3[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment3 as $key)
                                    { $as3[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass3;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            <td class="s13" style="width:37px;  background:#cccccc;" dir="ltr"><?= array_sum($as3) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php
                                if($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Géométrie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Zoologie" ||  $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php }
                                 elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" || $subjectname == "Géographie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="30">
                                <?php }
                                 elseif(($clsname == "8ème-(cycle terminal de l'education de base (cteb))" || $clsname == "7ème-(cycle terminal de l'education de base (cteb))"  ) && ($subjectname == "Algèbre"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="40">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="50">
                                <?php }
                                
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Géométrie" || $subjectname == "Géographie" || $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Zoologie" ||  $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="30">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="70">
                                <?php }
                               
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Dessin Scientifique" || $subjectname == "Microbiologie" || $subjectname == "Géologie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Techn. d'Info. & Com. (TIC)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Géom. et Trigo" || $subjectname == "Religion (1)" || $subjectname == "Biologie Générale" || $subjectname == "Sociologie Africaine" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Civ. et Morale" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Chimie" || $subjectname == "Physique" || $subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="30">
                                <?php }
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Algèbre. Stat. et Analy." ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="40">
                                <?php }         
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="50">
                                <?php }
                                elseif(($clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Religion (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Dessin" || $subjectname == "Education Physique" || $subjectname == "Soc. Afri. / Ecopol (1)" || $subjectname == "Géographie" || $subjectname == "Histoire" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Anglais" || $subjectname == "Chimie" || $subjectname == "Biologie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="40">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Français" || $subjectname == "Mathématiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="50">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)") && ($subjectname == "Religion" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)") && ($subjectname == "Esthétique" || $subjectname == "Philosophie" || $subjectname == "Dessin Scientifique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php }  
                                
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Economie Politique" || $subjectname == "Education musica/théâtrale" || $subjectname == "Biologie/Micro" || $subjectname == "Dessin Pédagogique" || $subjectname == "Education Physique" || $subjectname == "Sociologie Africaine" || $subjectname == "Informatique" || $subjectname == "Géographie" || $subjectname == "Histoire" || $subjectname == "Biologie" || $subjectname == "Travaux Man./Ecriture" || $subjectname == "Education musicale/Théâtrale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)") && ($subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Physique" || $subjectname == "Chimie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" || $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="50">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Chimie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Trav. Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="40">
                                <?php } 
                                
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Education musicale /théâtrale" || $subjectname == "Physique" || $subjectname == "Ed. Musicale/Théâtre" || $subjectname == "Esthétique" || $subjectname == "Education Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Biologie" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Anglais" || $subjectname == "Langues nationales" || $subjectname == "Mathématiques" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="40">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Dessin Pédagogique" || $subjectname == "Trav./Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="50">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Pratique d'enseignement"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="100">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Religion" || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Droit" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Mathématiques Générales" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Correspondance Com. Franc." || $subjectname == "Correspondance Com. Angl."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Documentation Commerciale" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="30">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="60">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Pratique Professionnelle"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="100">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Religion (1)" || $subjectname == "Opérations des banques et des crédits" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Entreprenariat" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Correspondance Com. Française" || $subjectname == "Droit" || $subjectname == "Mathématiques Générales" || $subjectname ==  "Economie Politique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)" ) && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="30">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Analytique" || $subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="40">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Fiscalité" || $subjectname == "Finances Publiques" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname ==  "Déontologie Professionnelle" || $subjectname == "Correspondance Com. Française" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Economie de développement" || $subjectname == "Mathématiques Générales" || $subjectname == "Organisations des entreprises" || $subjectname == "Droit"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="50">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Education à la vie" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Religion"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Sociologie / Ecopol (1)" || $subjectname == "Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Microbiologie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Anglais" || $subjectname == "Anglais (1)" || $subjectname == "Grec (1)" || $subjectname == "Mathématiques (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="40">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Français" || $subjectname == "Latin"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="50">
                                <?php }
                                                                                            
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Religion (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="10">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Mathématiques" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Esthétique" || $subjectname == "Physique" || $subjectname == "Biologie" || $subjectname == "Philosophie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss15" readonly value="20">
                                <?php }
                                ?>
                            </td>
                        
                            <?php if(!empty($assesment4)) {
                                $ass4 = count($assesment4);
                                if($ass4 > 4) {
                                    foreach($assesment4 as $key)
                                    { $as4[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php } } else {
                                    foreach($assesment4 as $key)
                                    { $as4[] = $key['max_marks'];
                                        ?>
                                        <td class="s13" style="width:37px;" dir="ltr"><?= $key['max_marks'] ?></td>
                                <?php }
                                    for($k=$ass4;$k<4;$k++)    
                                    { ?>
                                        <td class="s13" style="width:37px;" dir="ltr"></td>
                                <?php }
                            } } 
                            else { 
                                for($j=0; $j<4; $j++) { ?>
                                    <td class="s13" style="width:37px;" dir="ltr"></td>
                            <?php } } ?>
                            
                            <td class="s13" style="width:37px;  background:#cccccc;" dir="ltr"><?= array_sum($as4) ?></td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php
                                if($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Géométrie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Zoologie" ||  $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php }
                                 elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" || $subjectname == "Géographie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="30">
                                <?php }
                                 elseif(($clsname == "8ème-(cycle terminal de l'education de base (cteb))" || $clsname == "7ème-(cycle terminal de l'education de base (cteb))"  ) && ($subjectname == "Algèbre"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="40">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="50">
                                <?php }
                                
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Géométrie" || $subjectname == "Géographie" || $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Zoologie" ||  $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="30">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="70">
                                <?php }
                               
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Dessin Scientifique" || $subjectname == "Microbiologie" || $subjectname == "Géologie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Techn. d'Info. & Com. (TIC)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Géom. et Trigo" || $subjectname == "Religion (1)" || $subjectname == "Biologie Générale" || $subjectname == "Sociologie Africaine" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Civ. et Morale" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Chimie" || $subjectname == "Physique" || $subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="30">
                                <?php }
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Algèbre. Stat. et Analy." ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="40">
                                <?php }         
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="50">
                                <?php }
                                elseif(($clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Religion (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Dessin" || $subjectname == "Education Physique" || $subjectname == "Soc. Afri. / Ecopol (1)" || $subjectname == "Géographie" || $subjectname == "Histoire" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Anglais" || $subjectname == "Chimie" || $subjectname == "Biologie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="40">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Français" || $subjectname == "Mathématiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="50">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)") && ($subjectname == "Religion" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)") && ($subjectname == "Esthétique" || $subjectname == "Philosophie" || $subjectname == "Dessin Scientifique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php }  
                                
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Economie Politique" || $subjectname == "Education musica/théâtrale" || $subjectname == "Biologie/Micro" || $subjectname == "Dessin Pédagogique" || $subjectname == "Education Physique" || $subjectname == "Sociologie Africaine" || $subjectname == "Informatique" || $subjectname == "Géographie" || $subjectname == "Histoire" || $subjectname == "Biologie" || $subjectname == "Travaux Man./Ecriture" || $subjectname == "Education musicale/Théâtrale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)") && ($subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Physique" || $subjectname == "Chimie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" || $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="50">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Chimie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Trav. Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="40">
                                <?php } 
                                
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Education musicale /théâtrale" || $subjectname == "Physique" || $subjectname == "Ed. Musicale/Théâtre" || $subjectname == "Esthétique" || $subjectname == "Education Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Biologie" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Anglais" || $subjectname == "Langues nationales" || $subjectname == "Mathématiques" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="40">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Dessin Pédagogique" || $subjectname == "Trav./Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="50">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Pratique d'enseignement"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="100">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Religion" || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Droit" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Mathématiques Générales" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Correspondance Com. Franc." || $subjectname == "Correspondance Com. Angl."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Documentation Commerciale" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="30">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="60">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Pratique Professionnelle"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="100">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Religion (1)" || $subjectname == "Opérations des banques et des crédits" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Entreprenariat" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Correspondance Com. Française" || $subjectname == "Droit" || $subjectname == "Mathématiques Générales" || $subjectname ==  "Economie Politique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)" ) && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="30">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Analytique" || $subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="40">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Fiscalité" || $subjectname == "Finances Publiques" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname ==  "Déontologie Professionnelle" || $subjectname == "Correspondance Com. Française" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Economie de développement" || $subjectname == "Mathématiques Générales" || $subjectname == "Organisations des entreprises" || $subjectname == "Droit"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="50">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Education à la vie" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Religion"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Sociologie / Ecopol (1)" || $subjectname == "Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Microbiologie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Anglais" || $subjectname == "Anglais (1)" || $subjectname == "Grec (1)" || $subjectname == "Mathématiques (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="40">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Français" || $subjectname == "Latin"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="50">
                                <?php }
                                                                                            
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Religion (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="10">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Mathématiques" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Esthétique" || $subjectname == "Physique" || $subjectname == "Biologie" || $subjectname == "Philosophie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss16" readonly value="20">
                                <?php }
                                ?>
                            </td>
                            <td class="s13" style="width:37px;" dir="ltr">
                                <?php
                                if($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Géométrie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Zoologie" ||  $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php }
                                 elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" || $subjectname == "Géographie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="60">
                                <?php }
                                 elseif(($clsname == "8ème-(cycle terminal de l'education de base (cteb))" || $clsname == "7ème-(cycle terminal de l'education de base (cteb))"  ) && ($subjectname == "Algèbre"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="80">
                                <?php }
                                elseif($clsname == "8ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="100">
                                <?php }
                                
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Religion (1) (DOMAINE DES ARTS)" || $subjectname == "Education à la vie (1) (DOMAINE DES ARTS)" || $subjectname == "Histoire" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Géométrie" || $subjectname == "Géographie" || $subjectname == "Education Civique et Morale" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Arithmétique" || $subjectname == "Statistique" || $subjectname == "Anatomie" || $subjectname == "Botanique" || $subjectname == "Zoologie" ||  $subjectname == "Sciences Physiques" || $subjectname == "Technologie" || $subjectname == "Techn. d'Info. & Com. (TIC)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="60">
                                <?php }
                                elseif($clsname == "7ème-(cycle terminal de l'education de base (cteb))" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="140">
                                <?php }
                               
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Dessin Scientifique" || $subjectname == "Microbiologie" || $subjectname == "Géologie" || $subjectname == "Education Physique" || $subjectname == "Education à la vie (1)" || $subjectname == "Techn. d'Info. & Com. (TIC)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Géom. et Trigo" || $subjectname == "Religion (1)" || $subjectname == "Biologie Générale" || $subjectname == "Sociologie Africaine" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Ed. Civ. et Morale" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php } 
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Chimie" || $subjectname == "Physique" || $subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="60">
                                <?php }
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Algèbre. Stat. et Analy." ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="80">
                                <?php }         
                                elseif($clsname == "1ère année-(humanités scientifiques)" && ($subjectname == "Français"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="100">
                                <?php }
                                elseif(($clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Religion (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Dessin" || $subjectname == "Education Physique" || $subjectname == "Soc. Afri. / Ecopol (1)" || $subjectname == "Géographie" || $subjectname == "Histoire" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Anglais" || $subjectname == "Chimie" || $subjectname == "Biologie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="80">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanités scientifiques)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)" || $clsname == "2ème année-(humanités scientifiques)") && ($subjectname == "Français" || $subjectname == "Mathématiques"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="100">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)") && ($subjectname == "Religion" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Informatique (1)" || $subjectname == "Education à la vie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php }
                                elseif(($clsname == "4ème année-(humanités scientifiques)" || $clsname == "4ème année-(humanité chimie - biologie)" || $clsname == "4ème année-(humanité math - physique)" || $clsname == "3ème année-(humanité chimie - biologie)" || $clsname == "3ème année-(humanité math - physique)") && ($subjectname == "Esthétique" || $subjectname == "Philosophie" || $subjectname == "Dessin Scientifique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php }  
                                
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" ||  $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Economie Politique" || $subjectname == "Education musica/théâtrale" || $subjectname == "Biologie/Micro" || $subjectname == "Dessin Pédagogique" || $subjectname == "Education Physique" || $subjectname == "Sociologie Africaine" || $subjectname == "Informatique" || $subjectname == "Géographie" || $subjectname == "Histoire" || $subjectname == "Biologie" || $subjectname == "Travaux Man./Ecriture" || $subjectname == "Education musicale/Théâtrale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)") && ($subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Physique" || $subjectname == "Chimie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="80">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité pedagogie générale)" || $clsname == "2ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="100">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Chimie" || $subjectname == "Physique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php } 
                                elseif($clsname == "2ème année-(humanité pedagogie générale)" && ($subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Mathématiques" || $subjectname == "Trav. Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="80">
                                <?php } 
                                
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)") && ($subjectname == "Religion (1)" || $subjectname == "Education à la vie (1)" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Education musicale /théâtrale" || $subjectname == "Physique" || $subjectname == "Ed. Musicale/Théâtre" || $subjectname == "Esthétique" || $subjectname == "Education Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Biologie" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Anglais" || $subjectname == "Langues nationales" || $subjectname == "Mathématiques" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="80">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Français" || $subjectname == "Pédagogie" || $subjectname == "Psychologie" || $subjectname == "Didactique générale" || $subjectname == "Didactique des disciplines" || $subjectname == "Dessin Pédagogique" || $subjectname == "Trav./Man./Ecriture"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="100">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité pedagogie générale)" || $clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Pratique d'enseignement"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité pedagogie générale)") && ($subjectname == "Religion" || $subjectname == "Education à la vie" || $subjectname == "Ed. civ & morale" || $subjectname == "Informatique (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Droit" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Mathématiques Générales" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Correspondance Com. Franc." || $subjectname == "Correspondance Com. Angl."))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Documentation Commerciale" || $subjectname == "Anglais"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="60">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)") && ($subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="80">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="120">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité commerciale & gestion)" || $clsname == "2ème année-(humanité commerciale & gestion)" || $clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Pratique Professionnelle"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Religion (1)" || $subjectname == "Opérations des banques et des crédits" || $subjectname == "Mathématiques Financières" || $subjectname == "Fiscalité" || $subjectname == "Géographie économique" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)") && ($subjectname == "Entreprenariat" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Correspondance Com. Française" || $subjectname == "Droit" || $subjectname == "Mathématiques Générales" || $subjectname ==  "Economie Politique"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)" ) && ($subjectname == "Anglais" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="60">
                                <?php } 
                                elseif(($clsname == "3ème année-(humanité commerciale & gestion)" || $clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Analytique" || $subjectname == "Français" || $subjectname == "Informatique" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="80">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Activités Compl. (Visites guidées)" || $subjectname == "Fiscalité" || $subjectname == "Finances Publiques" || $subjectname == "Education Physique" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname ==  "Déontologie Professionnelle" || $subjectname == "Correspondance Com. Française" || $subjectname == "Correspondance Com. Anglaise" || $subjectname == "Religion (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Economie de développement" || $subjectname == "Mathématiques Générales" || $subjectname == "Organisations des entreprises" || $subjectname == "Droit"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php } 
                                elseif(($clsname == "4ème année-(humanité commerciale & gestion)") && ($subjectname == "Comptabilité Générale"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="100">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Education à la vie" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Religion"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php } 
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)") && ($subjectname == "Sociologie / Ecopol (1)" || $subjectname == "Physique" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Microbiologie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Anglais" || $subjectname == "Anglais (1)" || $subjectname == "Grec (1)" || $subjectname == "Mathématiques (1)"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="80">
                                <?php }
                                elseif(($clsname == "1ère année-(humanité littéraire)" || $clsname == "2ème année-(humanité littéraire)" || $clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Français" || $subjectname == "Latin"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="100">
                                <?php }
                                                                                            
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Informatique (1)" || $subjectname == "Ed. Civ. & Morale" || $subjectname == "Education à la vie (1)" || $subjectname == "Religion (1)" ))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="20">
                                <?php }
                                elseif(($clsname == "3ème année-(humanité littéraire)" || $clsname == "4ème année-(humanité littéraire)") && ($subjectname == "Mathématiques" || $subjectname == "Histoire" || $subjectname == "Géographie" || $subjectname == "Education Physique" || $subjectname == "Chimie" || $subjectname == "Esthétique" || $subjectname == "Physique" || $subjectname == "Biologie" || $subjectname == "Philosophie"))
                                { ?>
                                    <input type="text" class="inputtext" id="maxmarkss17" readonly value="40">
                                <?php }
                                ?>
                            </td>
                            <?php $sump4 = array_sum($as3)+array_sum($as4); ?>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#s2").html("");
                                    //var kas1 = "<?php echo $sump4 ?>";
                                    var mrk15 = $("#maxmarkss15").val();
                                    var mrk16 = $("#maxmarkss16").val();
                                    var mrk17 = $("#maxmarkss17").val();
                                    //var mxmrk = parseInt(kas1) + parseInt(mrk15) + parseInt(mrk16) + parseInt(mrk17);
                                    var mxmrk = parseInt(mrk15) + parseInt(mrk16) + parseInt(mrk17);
                                    $("#s2").html(mxmrk);
                                });
                            </script>
                            <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="s2"></span></td>
                        <?php } ?>
                        
                        
                    </tr>
                    
                    <?php if(!empty($studentdetails)) {
                        $stid = '';
                        $i = 1;
                        foreach($studentdetails as $value) {  
                            $stid = $value['id'];
                        ?>
                            
                            <tr style="height: 20px">
                                <th id="1281374097R5" style="height: 20px;" class="row-headers-background">
                                    <div class="row-header-wrapper" style="line-height: 20px">6</div>
                                </th>
                                <td class="s18" dir="ltr"> <?= $value['l_name']." ".$value['f_name']?></td>
                                <td class="s19" dir="ltr"></td>
                                <?php 
                                $ob1 = [];
                                $ob2 = [];
                                $ob3 = [];
                                $ob4 = [];
                                $ob5 = [];
                                $ob6 = [];
                                
                                if($sclsctn == "maternelle" || $sclsctn == "creche") { 
                                    if(!empty($assesment)) {
                                        $ass = count($assesment);
                                        $obmrks = '';
                                        if($ass > 5) {
                                            foreach($assesment as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks)) { $obmrks = ""; } else { $obmrks = $getmarks['marks']; }
                                                $ob1[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks1 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks1)) { $obmrks = ""; } else { $obmrks = $getmarks1['marks']; }
                                                $ob1[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass;$k<5;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    } } 
                                    else { 
                                        for($j=0; $j<5; $j++) { ?>
                                            <td class="s6" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob1) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext kmarks" id="kmarks'.$i.'"  data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid &&  $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "PREMIER TRIMESTRE") { 
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext kmarks" id="kmarks<?= $i ?>"  data-stud="<?= $stid ?>">
                                        <?php } ?>
                                        
                                    </td>
                                    <script>
                                        $(document).ready(function()
                                        {
                                            $("#sk").html("");
                                            var kas = "<?php echo array_sum($ob1) ?>";
                                            var mrk = $("#kmarks<?= $i ?>").val();
                                            var mxmrk = parseInt(kas) + parseInt(mrk);
                                            $("#sk").html(mxmrk);
                                        });
                                    </script>
                                    <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="sk"></span></td>
                                    
                                    
                                    <?php if(!empty($assesment2)) {
                                        $ass2 = count($assesment2);
                                        $obmrks ='';
                                        if($ass2 > 5) {
                                            foreach($assesment2 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks2 = mysqli_fetch_assoc(mysqli_query($con, $b = "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks2)) { $obmrks = ""; } else { $obmrks = $getmarks2['marks']; }
                                                $ob2[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment2 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks3 = mysqli_fetch_assoc(mysqli_query($con, $bc = "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks3)) { $obmrks = ""; } else { $obmrks = $getmarks3['marks']; }
                                                $ob2[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass2;$k<5;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    }  } 
                                    else { 
                                        for($j=0; $j<5; $j++) { ?>
                                            <td class="s13" style="width:37px;" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob2) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext k2marks" id="k2marks'.$i.'"  data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid &&  $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "DEUXIEME TRIMESTRE") { 
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext k2marks" id="k2marks<?= $i ?>"  data-stud="<?= $stid ?>">
                                        <?php } ?> 
                                    </td>
                                    <script>
                                        $(document).ready(function()
                                        {
                                            $("#sk1").html("");
                                            var kas = "<?php echo array_sum($ob2) ?>";
                                            var mrk = $("#k2marks<?= $i ?>").val();
                                            var mxmrk = parseInt(kas) + parseInt(mrk);
                                            $("#sk1").html(mxmrk);
                                        });
                                    </script>
                                    <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="sk1"></span></td>
                                    
                                    <?php if(!empty($assesment3)) {
                                        $ass3 = count($assesment3);
                                        $obmrks = '';
                                        if($ass3 > 5) {
                                            foreach($assesment3 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks6 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks6)) { $obmrks = ""; } else { $obmrks = $getmarks6['marks']; }
                                                $ob3[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment3 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks7 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks7)) { $obmrks = ""; } else { $obmrks = $getmarks7['marks']; }
                                                $ob3[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass3;$k<5;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    } } 
                                    else { 
                                        for($j=0; $j<5; $j++) { ?>
                                            <td class="s6" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob3) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext k3marks" id="k3marks'.$i.'"  data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid &&  $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "TROISIEME TRIMESTRE") { 
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext k3marks" id="k3marks<?= $i ?>"  data-stud="<?= $stid ?>">
                                        <?php } ?> 
                                    </td>
                                    <script>
                                        $(document).ready(function()
                                        {
                                            $("#sk2").html("");
                                            var kas = "<?php echo array_sum($ob3) ?>";
                                            var mrk = $("#k3marks<?= $i ?>").val();
                                            var mxmrk = parseInt(kas) + parseInt(mrk);
                                            $("#sk2").html(mxmrk);
                                        });
                                    </script>
                                    <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="sk2"></span></td>
                                    
                                <?php } elseif($sclsctn == "primaire") { 
                                    if(!empty($assesment)) {
                                        $ass = count($assesment);
                                        $obmrks = '';
                                        if($ass > 4) {
                                            foreach($assesment as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks)) { $obmrks = ""; } else { $obmrks = $getmarks['marks']; }
                                                $ob1[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks1 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks1)) { $obmrks = ""; } else { $obmrks = $getmarks1['marks']; }
                                                $ob1[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    } } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s6" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob1) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext pmarks" id="pmarks'.$i.'" data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid && $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "PREMIER TRIMESTRE" && $mr['period_name'] == "1ère PERIODE") {
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext pmarks" id="pmarks<?= $i ?>" data-stud="<?= $stid ?>" >
                                        <?php } ?>
                                        
                                    </td>
                                    
                                    <?php if(!empty($assesment2)) {
                                        $ass2 = count($assesment2);
                                        $obmrks ='';
                                        if($ass2 > 4) {
                                            foreach($assesment2 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks2 = mysqli_fetch_assoc(mysqli_query($con, $b = "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks2)) { $obmrks = ""; } else { $obmrks = $getmarks2['marks']; }
                                                $ob2[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment2 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks3 = mysqli_fetch_assoc(mysqli_query($con, $bc = "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks3)) { $obmrks = ""; } else { $obmrks = $getmarks3['marks']; }
                                                $ob2[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass2;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    }  } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s13" style="width:37px;" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob2) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext p2marks" id="p2marks'.$i.'" data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid && $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "PREMIER TRIMESTRE" && $mr['period_name'] == "2ème PERIODE") {
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext p2marks" id="p2marks<?= $i ?>" data-stud="<?= $stid ?>" >
                                        <?php } ?>
                                    </td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext epmarks" id="epmarks'.$i.'" data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid && $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "PREMIER TRIMESTRE" && $mr['period_name'] == "manual exams") {
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext epmarks" id="epmarks<?= $i ?>" data-stud="<?= $stid ?>" >
                                        <?php } ?>
                                    </td>
                                    <?php $psum =  array_sum($ob1) +  array_sum($ob2) ?>
                                    <script>
                                        $(document).ready(function()
                                        {
                                            $("#pa<?= $i ?>").html("");
                                            var psum = "<?php echo $psum ?>";
                                            if(psum == ""){ psum = 0; } 
                                            var pmrk = $("#pmarks<?= $i ?>").val();
                                            if(pmrk == ""){ pmrk = 0; } 
                                            var pmrk1 = $("#p2marks<?= $i ?>").val();
                                            if(pmrk1 == ""){ pmrk1 = 0; } 
                                            var pemark = $("#epmarks<?= $i ?>").val();
                                            if(pemark == ""){ pemark = 0; } 
                                            var mxmrk = parseInt(psum) + parseInt(pmrk) + parseInt(pmrk1) + parseInt(pemark);
                                           
                                            $("#pa<?= $i ?>").html(mxmrk);
                                        });
                                    </script>
                                    <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="pa<?= $i ?>"></span></td>
                                    
                                    <?php if(!empty($assesment3)) {
                                        $ass3 = count($assesment3);
                                        $obmrks = '';
                                        if($ass3 > 4) {
                                            foreach($assesment3 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks6 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks6)) { $obmrks = ""; } else { $obmrks = $getmarks6['marks']; }
                                                $ob3[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment3 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks7 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks7)) { $obmrks = ""; } else { $obmrks = $getmarks7['marks']; }
                                                $ob3[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass3;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    } } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s6" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob3) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext p3marks" id="p3marks'.$i.'" data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid && $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "DEUXIEME TRIMESTRE" && $mr['period_name'] == "3ème PERIODE") {
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext p3marks" id="p3marks<?= $i ?>" data-stud="<?= $stid ?>" >
                                        <?php } ?>   
                                    </td>
                                    
                                    <?php if(!empty($assesment4)) {
                                        $ass4 = count($assesment4);
                                        $obmrks ='';
                                        if($ass4 > 4) {
                                            foreach($assesment4 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks4 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks4)) { $obmrks = ""; } else { $obmrks = $getmarks4['marks']; }
                                                $ob4[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment4 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks5 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                
                                                if(empty($getmarks5)) { $obmrks = ""; } else { $obmrks = $getmarks5['marks']; }
                                                $ob4[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass4;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    }  } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s13" style="width:37px;" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob4) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext p4marks" id="p4marks'.$i.'" data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid && $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "DEUXIEME TRIMESTRE" && $mr['period_name'] == "4ème PERIODE") {
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext p4marks" id="p4marks<?= $i ?>" data-stud="<?= $stid ?>" >
                                        <?php } ?>   
                                    </td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext ep1marks" id="ep1marks'.$i.'" data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid && $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "DEUXIEME TRIMESTRE" && $mr['period_name'] == "manual exams") {
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext ep1marks" id="ep1marks<?= $i ?>" data-stud="<?= $stid ?>" >
                                        <?php } ?>
                                    </td>
                                    <?php $psum2 =  array_sum($ob4) +  array_sum($ob3) ?>
                                    <script>
                                        $(document).ready(function()
                                        {
                                            $("#pb<?= $i ?>").html("");
                                            var psum2 = "<?php echo $psum2 ?>";
                                            if(psum2 == ""){ psum2 = 0; } 
                                            var pmrk4 = $("#p3marks<?= $i ?>").val();
                                            if(pmrk4 == ""){ pmrk4 = 0; } 
                                            var pmrk5 = $("#p4marks<?= $i ?>").val();
                                            if(pmrk5 == ""){ pmrk5 = 0; } 
                                            var pemark2 = $("#ep1marks<?= $i ?>").val();
                                            if(pemark2 == ""){ pemark2 = 0; } 
                                            var mxmrk = parseInt(psum2) + parseInt(pmrk4) + parseInt(pmrk5) + parseInt(pemark2);
                                           
                                            $("#pb<?= $i ?>").html(mxmrk);
                                        });
                                    </script>
                                    <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="pb<?= $i ?>"></span></td>
                                    
                                    <?php if(!empty($assesment5)) {
                                        $ass5 = count($assesment5);
                                        $obmrks = '';
                                        if($ass5 > 4) {
                                            foreach($assesment5 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks6 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks6)) { $obmrks = ""; } else { $obmrks = $getmarks6['marks']; }
                                                $ob5[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment5 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks7 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks7)) { $obmrks = ""; } else { $obmrks = $getmarks7['marks']; }
                                                $ob5[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass5;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    } } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s6" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob5) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext p5marks" id="p5marks'.$i.'" data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid && $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "TROISIEME TRIMESTRE" && $mr['period_name'] == "5ème PERIODE") {
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext p5marks" id="p5marks<?= $i ?>" data-stud="<?= $stid ?>" >
                                        <?php } ?>   
                                    </td>
                                    
                                    <?php if(!empty($assesment6)) {
                                        $ass6 = count($assesment6);
                                        $obmrks ='';
                                        if($ass6 > 4) {
                                            foreach($assesment6 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks4 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks4)) { $obmrks = ""; } else { $obmrks = $getmarks4['marks']; }
                                                $ob6[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment6 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks5 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                
                                                if(empty($getmarks5)) { $obmrks = ""; } else { $obmrks = $getmarks5['marks']; }
                                                $ob6[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass4;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    }  } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s13" style="width:37px;" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob6) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext p6marks" id="p6marks'.$i.'" data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid && $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "TROISIEME TRIMESTRE" && $mr['period_name'] == "6ème PERIODE") {
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext p6marks" id="p6marks<?= $i ?>" data-stud="<?= $stid ?>" >
                                        <?php } ?>   
                                    </td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext ep2marks" id="ep2marks'.$i.'" data-stud="'. $stid .'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid && $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "TROISIEME TRIMESTRE" && $mr['period_name'] == "manual exams") {
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext ep2marks" id="ep2marks<?= $i ?>" data-stud="<?= $stid ?>" >
                                        <?php } ?>  
                                    </td>
                                    <?php $psum3 =  array_sum($ob5) +  array_sum($ob6) ?>
                                    <script>
                                        $(document).ready(function()
                                        {
                                            $("#pc<?= $i ?>").html("");
                                            var psum3 = "<?php echo $psum3 ?>";
                                            if(psum3 == ""){ psum3 = 0; } 
                                            var pmrk2 = $("#p5marks<?= $i ?>").val();
                                            if(pmrk2 == ""){ pmrk2 = 0; } 
                                            var pmrk3 = $("#p6marks<?= $i ?>").val();
                                            if(pmrk3 == ""){ pmrk3 = 0; } 
                                            var pemark3 = $("#ep2marks<?= $i ?>").val();
                                            if(pemark3 == ""){ pemark3 = 0; } 
                                            var mxmrk = parseInt(psum3) + parseInt(pmrk2) + parseInt(pmrk3) + parseInt(pemark3);
                                           
                                            $("#pc<?= $i ?>").html(mxmrk);
                                        });
                                    </script>
                                    <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="pc<?= $i ?>"></span></td>
                                    
                                    
                                <?php } else { 
                                    if(!empty($assesment)) {
                                        $ass = count($assesment);
                                        $obmrks = '';
                                        if($ass > 4) {
                                            foreach($assesment as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks)) { $obmrks = ""; } else { $obmrks = $getmarks['marks']; }
                                                $ob1[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks1 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks1)) { $obmrks = ""; } else { $obmrks = $getmarks1['marks']; }
                                                $ob1[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    } } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s6" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob1) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext smarks" id="smarks'.$i.'" data-stud="'. $stid.'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid &&  $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "PREMIER SEMESTRE" && $mr['period_name'] == "1ère PERIODE") { 
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext smarks" id="smarks<?= $i ?>" data-stud="<?= $stid ?>">
                                        <?php } ?>
                                        
                                    </td>
                                    
                                    <?php if(!empty($assesment2)) {
                                        $ass2 = count($assesment2);
                                        $obmrks ='';
                                        if($ass2 > 4) {
                                            foreach($assesment2 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks2 = mysqli_fetch_assoc(mysqli_query($con, $b = "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks2)) { $obmrks = ""; } else { $obmrks = $getmarks2['marks']; }
                                                $ob2[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment2 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks3 = mysqli_fetch_assoc(mysqli_query($con, $bc = "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks3)) { $obmrks = ""; } else { $obmrks = $getmarks3['marks']; }
                                                $ob2[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass2;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    }  } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s13" style="width:37px;" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob2) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext s2marks" id="s2marks'.$i.'" data-stud="'. $stid.'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid &&  $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "PREMIER SEMESTRE" && $mr['period_name'] == "2ème PERIODE") { 
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext s2marks" id="s2marks<?= $i ?>" data-stud="<?= $stid ?>">
                                        <?php } ?>
                                    </td>
                                    <!--<td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext esmarks" id="esmarks'.$i.'" data-stud="'. $stid.'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid &&  $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "PREMIER SEMESTRE" && $mr['period_name'] == "manual exams") { 
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext esmarks" id="esmarks<?= $i ?>" data-stud="<?= $stid ?>">
                                        <?php } ?>
                                    </td>-->
                                    <td class="s13" style="width:37px;" dir="ltr">
                                    <?php if(!empty($exams1)) {
                                        $emrks ='';
                                        foreach($exams1 as $key)
                                        {
                                            $examid = $key['id'];
                                            $getmarks2 = mysqli_fetch_assoc(mysqli_query($con, $b = "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                            if(empty($getmarks2)) { $emrks = ""; } else { $emrks = $getmarks2['marks']; }
                                        ?>
                                        <input type="text" class="inputtext esmarks" id="esmarks<?= $i ?>" data-stud="<?= $stid ?>" value="<?= $emrks ?>" >
                                    <?php } }  else {  ?>
                                        <input type="text" class="inputtext esmarks" id="esmarks<?= $i ?>" data-stud="<?= $stid ?>">
                                    <?php } ?>
                                    </td>
                                    <?php $datasum =  array_sum($ob1) +  array_sum($ob2) ?>
                                    <script>
                                        $(document).ready(function()
                                        {
                                            $("#ss<?= $i ?>").html("");
                                            var sas = "<?php echo $datasum ?>";
                                            if(sas == ""){ sas = 0; } 
                                            var smrk = $("#smarks<?= $i ?>").val();
                                            if(smrk == ""){ smrk = 0; } 
                                            var smrk2 = $("#s2marks<?= $i ?>").val();
                                            if(smrk2 == ""){ smrk2 = 0; } 
                                            var semark = $("#esmarks<?= $i ?>").val();
                                            if(semark == ""){ semark = 0; } 
                                            var mxmrk = parseInt(sas) + parseInt(smrk) + parseInt(smrk2) + parseInt(semark);
                                           
                                            $("#ss<?= $i ?>").html(mxmrk);
                                        });
                                    </script>
                                    <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="ss<?= $i ?>"></span></td>
                                    
                                    <?php if(!empty($assesment3)) {
                                        $ass3 = count($assesment3);
                                        $obmrks = '';
                                        if($ass3 > 4) {
                                            foreach($assesment3 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks6 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks6)) { $obmrks = ""; } else { $obmrks = $getmarks6['marks']; }
                                                $ob3[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment3 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks7 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks7)) { $obmrks = ""; } else { $obmrks = $getmarks7['marks']; }
                                                $ob3[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass3;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    } } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s6" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob3) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext s3marks" id="s3marks'.$i.'" data-stud="'. $stid.'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid &&  $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "SECOND SEMESTRE" && $mr['period_name'] == "3ème PERIODE") { 
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext s3marks" id="s3marks<?= $i ?>" data-stud="<?= $stid ?>">
                                        <?php } ?>
                                    </td>
                                    
                                    <?php if(!empty($assesment4)) {
                                        $ass4 = count($assesment4);
                                        $obmrks ='';
                                        if($ass4 > 4) {
                                            foreach($assesment4 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks4 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                if(empty($getmarks4)) { $obmrks = ""; } else { $obmrks = $getmarks4['marks']; }
                                                $ob4[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php } } else {
                                            foreach($assesment4 as $key)
                                            {
                                                $examid = $key['id'];
                                                $getmarks5 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `submit_exams` WHERE `student_id` = '".$stid."' AND `exam_id` = '".$examid."' "));
                                                
                                                if(empty($getmarks5)) { $obmrks = ""; } else { $obmrks = $getmarks5['marks']; }
                                                $ob4[] = $obmrks;
                                            ?>
                                                <td class="s6" dir="ltr"><?= $obmrks ?></td>
                                        <?php }
                                            for($k=$ass4;$k<4;$k++)    
                                            { ?>
                                                <td class="s6" dir="ltr"></td>
                                        <?php }
                                    }  } 
                                    else { 
                                        for($j=0; $j<4; $j++) { ?>
                                            <td class="s13" style="width:37px;" dir="ltr"></td>
                                    <?php } } ?>
                                    <td class="s13" style="width:37px; background:#cccccc;" dir="ltr"><?= array_sum($ob4) ?></td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext s4marks" id="s4marks'.$i.'" data-stud="'. $stid.'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid &&  $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "SECOND SEMESTRE" && $mr['period_name'] == "4ème PERIODE") { 
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext s4marks" id="s4marks<?= $i ?>" data-stud="<?= $stid ?>">
                                        <?php } ?>
                                    </td>
                                    <td class="s13" style="width:37px;" dir="ltr">
                                        <?php if(!empty($marksrecord)) 
                                        {
                                            $mrksinput1 = '<input type="text" class="inputtext es1marks" id="es1marks'.$i.'" data-stud="'. $stid.'"';
                                            $mrksinput = '';
                                            foreach($marksrecord as $mr)
                                            {
                                                if($mr['student_id'] == $stid &&  $mr['max_marks'] != '' && $mr['class_id'] == $_GET['classid'] && $mr['subject_id'] == $subject_names->id && $mr['semester_name'] == "SECOND SEMESTRE" && $mr['period_name'] == "manual exams") { 
                                                    $mrksinput .= 'value="'. $mr['max_marks'] .'">';
                                                }
                                            }
                                            if($mrksinput != '') {
                                                echo $mrksinput1.$mrksinput;
                                            }
                                            else
                                            {
                                                echo $mrksinput1.'>';
                                            }
                                            
                                        } else { ?>
                                        <input type="text" class="inputtext es1marks" id="es1marks<?= $i ?>" data-stud="<?= $stid ?>">
                                        <?php } ?>
                                    </td>
                                    <?php $datas = array_sum($ob3)+array_sum($ob4) ?>
                                    <script>
                                        $(document).ready(function()
                                        {
                                            $("#sss<?= $i ?>").html("");
                                            var sas1 = "<?php echo $datas ?>";
                                            if(sas1 == ""){ sas1 = 0; }
                                            var smrk1 = $("#s3marks<?= $i ?>").val();
                                            if(smrk1 == ""){ smrk1 = 0; }
                                            var smrk21 = $("#s4marks<?= $i ?>").val();
                                            if(smrk21 == ""){ smrk21 = 0; }
                                            var semark1 = $("#es1marks<?= $i ?>").val();
                                            if(semark1 == ""){ semark1 = 0; }
                                            
                                            var mxmrk = parseInt(sas1) + parseInt(smrk1) + parseInt(smrk21) + parseInt(semark1);
                                            $("#sss<?= $i ?>").html(mxmrk);
                                        });
                                    </script>
                                    <td class="s13" style="width:37px; background:#7e7a7a;" dir="ltr"><span id="sss<?= $i ?>"></span></td>
                                <?php } ?>

                            </tr>
                        <?php
                        $i++;
                        } } ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-sm-12">
            <div class="error" id="recordererror">
            </div>
            <div class="success" id="recordersuccess">
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-sm-12">
                
                <div class="mt-4 ml-4 text-right">
                    <button type="submit" id="recorderbtn" class="btn btn-primary recorderbtn">Submit</button>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<script>
/*$(function(){
    $(".grid-container").scroll(function(){
        $(".grid-container1")
            .scrollLeft($(".grid-container").scrollLeft());
    });
    $(".grid-container1").scroll(function(){
        $(".grid-container")
            .scrollLeft($(".grid-container1").scrollLeft());
    });
});*/

$("document").ready(function(){
    $("#grid-container1 div").width($("#select").width());
    $("#grid-container1 div").css("overflow-x", "scroll");
    $("#grid-container1 div").css("height", "1px");
    
    $("#grid-container1").on("scroll", function(){
        $("#grid-container").scrollLeft($(this).scrollLeft()); 
    });
    $("#grid-container").on("scroll", function(){
        $("#grid-container1").scrollLeft($(this).scrollLeft()); 
    });
});
</script>
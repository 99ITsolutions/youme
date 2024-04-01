<style>
    #summaryreportform, #asummaryreportform
    {
        display:inline;
        width:100% !important;
    }
</style>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <div class=" row">
                            <h2 class="col-md-7 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2153') { echo $langlbl['title'] ; } } ?></h2>
                            <h2 class="col-md-2"  <?= $downloadreport ?> ><button onclick="generate()" id="sumdownloadreport"  class="btn btn-sm btn-success" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2155') { echo $langlbl['title'] ; } } ?></button></h2>
                            
                            <h2 class="col-md-1"><a href="<?= $baseurl ?>Admissions" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                        </div>
                        
                    </div>
                    <div class="body" id="gen_pdf">
    	                <div class="row clearfix" id= "reportdata">
    	                    <?php echo $viewpage; ?>
    	                </div>
    	                <input type="hidden" id="adm_no" value="<?= $adm_no ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php   echo $this->Form->create(false , [ 'method' => "post"  ]);  echo $this->Form->end();?>

<!------------------ End --------------------->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<style>
    #gen_pdf, body, p{
        background: #fff;
        height:300%;
    }
    .card{
        background:#fff;
    }
    .card .body {
        padding: 10px 20px 120px 20px;
    }
</style>
<script>
    function generate(){
        var adm = $("#adm_no").val();
        var idcrd = 'StudentReg_'+adm+'.pdf'
        var doc = new jsPDF('p', 'pt', 'a4');
        doc.addHTML(document.getElementById('gen_pdf'), function() {
          doc.save(idcrd);
        });
    }
</script>

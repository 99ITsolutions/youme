<style>
    .bg-dash
    {
        background-color:#242E3B !important;
    }
</style>

<div class="row clearfix" style="background:#fff; width: 100%;">
    <div class="card">
        <div class="header">
            <h2 style="font-size: 1.2rem;">Notes: Here are the listing of School Approval Request for below particulars</h2>
            
           <!-- <h2><a href="<?=$baseurl?>schools/export" title="Export" class="btn btn-sm btn-success mt-4">Export in Excel</a> </h2>-->
        </div>
    </div>
    <div class="col-sm-3 col-xs-6 col-xs-6">
        <div class="card text-center bg-dash ">
            <div class="body">
                
                <div class="text-light">
                    <span><b><a style="color:#FFFFFF !important" href="../approveclasses/<?= $sclid ?>">Classes (<?= $class_sts?>) </a></b></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3 col-xs-6 col-xs-6">
        <div class="card text-center bg-dash">
            <?php	//echo $this->Form->create(false , ['url' => ['action' => 'approvesubjects'] , 'id' => "addsubform" , 'method' => "post"  ]); ?>
            <div class="body">
                <div class="text-light">
                    <span><b><a style="color:#FFFFFF !important" href="../approvesubjects/<?= $sclid ?>">Subjects (<?= $subject_sts?>) </a></b></span>
                    <!--<button type="submit" ><span><b>Subjects (<?= $subject_sts ?>) </b></span></button>-->
                </div>
            </div>
            <input type="hidden" name="scl_id" id="scl_id">
            <?php //echo $this->Form->end(); ?>
        </div>
    </div>
    <div class="col-sm-3 col-xs-6 col-xs-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="text-light">
                    <span><b><a style="color:#FFFFFF" href="../approveclasssubject/<?= $sclid ?>">Class Subjects (<?= $subjcls_sts ?>) </a></b></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3 col-xs-6 col-xs-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="text-light">
                    <span><b><a style="color:#FFFFFF" href="../approveteachers/<?= $sclid ?>">Teachers (<?= $teacher_sts?>) </a></b></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3 col-xs-6 col-xs-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="text-light">
                    <span><b><a style="color:#FFFFFF" href="../approvestudents/<?= $sclid ?>">Students (<?= $student_sts?>) </a></b></span>
                </div>
            </div>
        </div>
    </div>    
    <div class="col-sm-3 col-xs-6 col-xs-6">
        <div class="card text-center bg-dash">
            <div class="body">
                <div class="text-light">
                    <span><b><a style="color:#FFFFFF" href="../approveknowledgebase/<?= $sclid ?>">Knowledge Base (<?= $knowledge_sts?>) </a></b></span>
                </div>
            </div>
        </div>
    </div>    
                 
</div>



<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
var ctx = document.getElementById('d_bar_chart');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['April', 'May', 'June', 'July'],
        datasets: [{   
            label:'Deposit',
            barThickness: 60,
            backgroundColor:["rgba(255, 99, 132, 0.2)","rgba(255, 99, 132, 0.2)","rgba(255, 99, 132, 0.2)","rgba(255, 99, 132, 0.2)"],
            data: [22000,6000,7500,10000],
            color: [
                'rgba(0, 222, 27  , 1)'
            ],
            borderColor: [
                'rgba(0, 222, 27  , 1)'
            ],
            fill:false
        }
        ]
    },
    
    options: {
        responsive:true,
        
        toottips:{
            mode: 'index',
            intersect: false,
        },

        hover:{
            mode:'nearest',
            intersect:true
        },
        scales: {
            xAxes: [{
                display:true,
                scaleLabel:{
                    display:true,
                    labelString:'Month'
                }
            }],
            yAxes: [{
            	ticks:{beginAtZero:true},
                display:true,
                scaleLabel:{
                    display:true,
                    labelString:'Amount'
                }
            }]
        }
    }
});
</script>

<script>
var ctxe = document.getElementById('e_bar_chart');
var myChart = new Chart(ctxe, {
    type: 'bar',
    data: {
        labels: ['April', 'May', 'June', 'July'],
        datasets: [{   
            label:'Expenses',
            barThickness: 60,
            backgroundColor:["rgba(25, 99, 132, 0.2)","rgba(25, 99, 132, 0.2)","rgba(25, 99, 132, 0.2)","rgba(25, 99, 132, 0.2)"],
            data: [10000,7000,4000,7500],
            borderColor: [
                'rgba(0, 222, 27  , 8)'
            ],
            fill:false
        }
        ]
    },
    
    options: {
        responsive:true,
        
        toottips:{
            mode: 'index',
            intersect: false,
        },

        hover:{
            mode:'nearest',
            intersect:true
        },
        scales: {
            xAxes: [{
                display:true,
                scaleLabel:{
                    display:true,
                    labelString:'Month'
                }
            }],
            yAxes: [{
            	"ticks":{"beginAtZero":true},
                display:true,
                scaleLabel:{
                    display:true,
                    labelString:'Amount'
                }
            }]
        }
    }
});
</script>





            
     
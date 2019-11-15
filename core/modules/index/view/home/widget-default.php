<?php

if(isset($_SESSION["user_id"])):
    if($_SESSION['user_id']== "1"){ Core::redir('?view=company');}
    //$user = UserData::getById($_SESSION["user_id"]);
    
    $sumIncome=IncomeData::sumIncome($_SESSION["company_id"]);
    $sumExpenses= ExpensesData::sumExpenses($_SESSION["company_id"]);
?>  
<?php 
    function sum_incomes_month($month){
    $income=IncomeData::sumIncome_Month($month,$_SESSION["company_id"]);
    echo $total=number_format($income->total,2,'.','');
    }
    function sum_expenses_month($month){
        $expenses = ExpensesData::sumExpenses_Month($month,$_SESSION["company_id"]);
        echo $total=number_format($expenses->total,2,'.','');
    } 
?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> Dashboard</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text"> Ingresos totales</span>
                      <span class="info-box-number"><?php echo number_format($sumIncome->amount,2);  ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-credit-card"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Mis Egresos totales</span>
                      <span class="info-box-number"><?php echo number_format($sumExpenses->amount,2);  ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text"> Ingresos este mes</span>
                      <span class="info-box-number"><?php echo sum_incomes_month(date('m'));?></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-credit-card"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Mis Egresos este mes</span>
                      <span class="info-box-number"><?php echo sum_expenses_month(date('m'));?></span>
                    </div>
                  </div>
                </div>
            </div>
            <div class="box">
                <div class="box-header  with-border">
                    <h4>Ingresos vrs Egresos <small>Reporte mensual</small></h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                          <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar/Ocultar">
                          <i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body"><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px;"></iframe>
                    <canvas id="mybarChart2" style="width: 521px; height: 260px;" width="521" height="260"></canvas>
                </div>
            </div>
        </section> <!-- /.content -->
    </div><!-- /.content-wrapper -->
<?php else: Core::redir("./"); endif;?> 
<?php   include "res/resources/js.php"; ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
    <!-- <script src="res/plugins/chartjs/Chart.min.js"></script> -->
    <script>
    if($("#mybarChart2").length) {
            var f=document.getElementById("mybarChart2");
            new Chart(f,
            {
                type:"bar",
                data: {
                    labels:["Enero","Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto", "Septiembre","Octubre", "Noviembre","Diciembre"],
                    datasets:[ {
                        label: "Ingresos", backgroundColor: "#26B99A", data: [<?php echo sum_incomes_month(1); ?>, <?php echo sum_incomes_month(2); ?>, <?php echo sum_incomes_month(3); ?>, <?php echo sum_incomes_month(4); ?>, <?php echo sum_incomes_month(5); ?>, <?php echo sum_incomes_month(6); ?>, <?php echo sum_incomes_month(7); ?>,<?php echo sum_incomes_month(8); ?>,<?php echo sum_incomes_month(9); ?>,<?php echo sum_incomes_month(10); ?>,<?php echo sum_incomes_month(11); ?>,<?php echo sum_incomes_month(12); ?>]
                    }
                    ,
                    {
                        label: "Egresos", backgroundColor: "#03586A", data: [<?php echo sum_expenses_month(1);?>, <?php echo sum_expenses_month(2);?>, <?php echo sum_expenses_month(3);?>, <?php echo sum_expenses_month(4);?>, <?php echo sum_expenses_month(5);?>, <?php echo sum_expenses_month(6);?>, <?php echo sum_expenses_month(7);?>,<?php echo sum_expenses_month(8);?>,<?php echo sum_expenses_month(9);?>,<?php echo sum_expenses_month(10);?>,<?php echo sum_expenses_month(11);?>,<?php echo sum_expenses_month(12);?>]
                    }
                    ]
                }
                ,
                options: {
                    scales: {
                        yAxes:[ {
                            ticks: {
                                beginAtZero: !0
                            }
                        }
                        ]
                    }
                }
            }
            )
        }
</script>  
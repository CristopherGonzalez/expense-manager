<?php

if (isset($_SESSION["user_id"])) :
  if ($_SESSION['user_id'] == "1") {
    Core::redir('?view=company');
  }
  //$user = UserData::getById($_SESSION["user_id"]);

  $sumIncome = IncomeData::sumIncome($_SESSION["company_id"]);
  $sumExpenses = ExpensesData::sumExpenses($_SESSION["company_id"]);

  ?>
  <?php
    function sum_incomes_month($month)
    {
      $income = IncomeData::sumIncome_Month($month, $_SESSION["company_id"]);
      echo $total = number_format($income->total, 2, '.', '');
    }
    function sum_expenses_month($month)
    {
      $expenses = ExpensesData::sumExpenses_Month($month, $_SESSION["company_id"]);
      echo $total = number_format($expenses->total, 2, '.', '');
    }
    ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Informes</h1>
      <ol class="breadcrumb">
        <li><a href="?view=home"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Informes</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-2 col-sm-2 col-xs-12">
          <select name="month_find" id="month_find" class="form-control" onchange="load();">
            <option value="0" selected>Mes</option>
            <?php
              //Se crean opciones de meses y se selecciona el actual por defecto
              $months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
              foreach ($months as $index => $month) {
                ?>
              <option value="<?php echo $index + 1; ?>"><?php echo $month; ?></option>
            <?php
              }
              ?>
          </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12">
          <select name="year_find" id="year_find" class="form-control" style="width: 100%;" onchange="load();">
            <option value="0" selected>Año</option>
            <?php
              //Se crean opciones de años y se selecciona el actual por defecto
              $years = array(2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025);
              foreach ($years as $year) {
                ?>
              <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
            <?php
              }
              ?>
          </select>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <h4 class="col-md-6 col-sm-6"><b>Resultado : $<label id="mount">0</label></b></h4>
          <h4 class="col-md-6 col-sm-6"><label id="mountPercetage">0</label>%</h4>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12">
          <button class="btn btn-default" type="button" onclick='load();'><i class='fa fa-search'></i></button>
          <label>Calcular</label>
        </div>
      </div>
      <div id="resultados_ajax">
        <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          Debes seleccionar un año y un mes!
        </div>
      </div><!-- Resultados Ajax -->
    </section> <!-- /.content -->
  </div><!-- /.content-wrapper -->
<?php else : Core::redir("./");
endif; ?>
<?php include "res/resources/js.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
  $(function() {
    load();
  });

  function load() {
    //Se obtienen filtros de busqueda
    var month_find = $('#month_find option:selected').val();
    var year_find = $('#year_find option:selected').val();
    
    if (month_find != "0" && year_find != "0") {
      var parametros = {
        'month': month_find,
        'year': year_find
      }
      $.ajax({
        type: "POST",
        url: "./?action=loadreports",
        data: parametros,
        beforeSend: function(objeto) {
          $("#resultados_ajax").html("Enviando...");
        },
        success: function(datos) {
          $("#resultados_ajax").html(datos);
          $('#mount').html(response.totalSumMonthYear);
          $('#mountPercetage').html(response.percentageSumMonthYear);
          
          if(document.getElementById('doughnutIncome') != null && document.getElementById('doughnutIncome') != undefined){
            var ctx = document.getElementById('doughnutIncome').getContext('2d');
            var config = {
              type: 'doughnut',
              data: {
                datasets: [],
                labels: []
              },
              options: {
                responsive: true,
                legend: {
                  position:  false
                },
                title: {
                  display: true,
                  text: 'Ingresos'
                },
                animation: {
                  animateScale: true,
                  animateRotate: true
                }
              }
            };
            var newDataset = {
              backgroundColor: [],
              data: [],
              label: 'New dataset ',
            };
            var results = response.resultIncomeType;
            for (var index = 0; index < results.length; ++index) {
              newDataset.data.push(results[index]['value']);
              newDataset.backgroundColor.push(results[index]['color']);
              config.data.labels.push(results[index]['label']);
            }
            config.data.datasets.push(newDataset);
            window.myDoughnut = new Chart(ctx, config);
          }
          if(document.getElementById('doughnutExpenses') != null && document.getElementById('doughnutExpenses') != undefined){
            var ctx = document.getElementById('doughnutExpenses').getContext('2d');
            var config2 = {
              type: 'doughnut',
              data: {
                datasets: [],
                labels: []
              },
              options: {
                responsive: true,
                legend: {
                  position: false
                },
                title: {
                  display: true,
                  text: 'Egresos'
                },
                animation: {
                  animateScale: true,
                  animateRotate: true
                }
              }
            };
            var newDataset = {
              backgroundColor: [],
              data: [],
              label: 'New dataset ',
            };
            var results = response.resultExpensesType;
            for (var index = 0; index < results.length; ++index) {
              newDataset.data.push(results[index]['value']);
              newDataset.backgroundColor.push(results[index]['color']);
              config2.data.labels.push(results[index]['label']);
            }
            config2.data.datasets.push(newDataset);
            window.myDoughnut2 = new Chart(ctx, config2);
          }
          window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
              $(this).remove();
            });
          }, 5000);
        }
      });
      

    } else {
      $("#resultados_ajax").html("<div class='alert alert-info alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><strong>Debes seleccionar un año y un mes!</strong></div>");
      $('#mount').html(0);
      $('#mountPercetage').html(0);
    }
  }
</script>
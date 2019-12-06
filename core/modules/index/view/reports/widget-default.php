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


      <div class="box" style="background:#f5f5f5 !important;" id="reportExpense">
        <div class="box-header  with-border">
        </div>
        <!--<div class="box-body">
          <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
              <canvas id="doughnutExpenses" style="width: 500px; height: 500px;" width="500" height="500"></canvas>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
              <div id="faq" role="tablist" aria-multiselectable="true">

                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="questionOne">
                    <h5 class="panel-title">
                      <a data-toggle="collapse" data-parent="#faq" href="#answerOne" aria-expanded="false" aria-controls="answerOne">
                        Question 1
                      </a>
                    </h5>
                  </div>
                  <div id="answerOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="questionOne">
                    <div class="panel-body">
                      Answer 1...
                    </div>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="questionTwo">
                    <h5 class="panel-title">
                      <a class="collapsed" data-toggle="collapse" data-parent="#faq" href="#answerTwo" aria-expanded="false" aria-controls="answerTwo">
                        Question 2
                      </a>
                    </h5>
                  </div>
                  <div id="answerTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="questionTwo">
                    <div class="panel-body">
                      Answer 2...
                    </div>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="questionThree">
                    <h5 class="panel-title">
                      <a class="collapsed" data-toggle="collapse" data-parent="#faq" href="#answerThree" aria-expanded="true" aria-controls="answerThree">
                        Question 3
                      </a>
                    </h5>
                  </div>
                  <div id="answerThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="questionThree">
                    <div class="panel-body">
                      Answer 3...
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>-->
        <div class="box-body">
          <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
              <canvas id="doughnutExpenses" style="width: 500px; height: 500px;" width="500" height="500"></canvas>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                      Egresos $800
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-1">
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <span style="float:right;">Impagos</span>
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-1">
                      $100
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-1">
                      25%
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-1">
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="panel-group" id="accordion">
                    <div class="panel panel-default" style="border:0px !important;">
                      <div class="panel-heading">
                        <div class="row">
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <button type="button" class="btn btn-primary">80%</button>
                          </div>
                          <div class="col-md-4 col-sm-4 col-xs-4">
                            Compras Infraestructura
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            $400
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            40%
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            70%
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            $100
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            25%
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <i class='fa fa-plus'></i>
                          </div>
                        </div>


                      </div>
                      <div id="collapse1" class="panel-collapse collapse in">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-2">

                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                              Empresas delivery
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              $70
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              7%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              70%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              $70
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              100%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              <i class="fa fa-minus"></i>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-2">

                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                              RevistaS
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              $30
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              3%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              30%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              $30
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              100%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="panel panel-default" style="border:0px !important;">
                      <div class="panel-heading">
                        <div class="row">
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <button type="button" class="btn btn-danger">10%</button>
                          </div>
                          <div class="col-md-4 col-sm-4 col-xs-4">
                            Clientes
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            $400
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            40%
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            70%
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            $100
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            25%
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <i class='fa fa-plus'></i>
                          </div>
                        </div>


                      </div>
                      <div id="collapse1" class="panel-collapse collapse in">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-2">

                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                              Empresas delivery
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              $70
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              7%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              70%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              $70
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              100%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              <i class="fa fa-minus"></i>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-2">

                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                              RevistaS
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              $30
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              3%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              30%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              $30
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              100%
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="panel panel-default" style="border:0px !important;">
                      <div class="panel-heading">
                        <div class="row">
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <button type="button" class="btn btn-warning">5%</button>
                          </div>
                          <div class="col-md-4 col-sm-4 col-xs-4">
                            Personal
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            $400
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            40%
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            70%
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            $100
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            25%
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <i class='fa fa-plus'></i>
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
      </div>
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
          $('#save_data').attr("disabled", false);
          load(1);
          window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
              $(this).remove();
            });
          }, 5000);
        }
      });


    } else {
     
      $('#alertReport').attr('style', 'display:block;');
    }

  }
</script>
<!-- <script src="res/plugins/chartjs/Chart.min.js"></script> -->
<script>
  var randomScalingFactor = function() {
    return Math.round(Math.random() * 100);
  };

  var config = {
    type: 'doughnut',
    data: {
      datasets: [{
        data: [
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
        ],
        backgroundColor: [
          'Red',
          'Orange',
          'Yellow',
          'Green',
          'Blue'
        ],
        label: 'Dataset 1'
      }],
      labels: [
        'Red',
        'Orange',
        'Yellow',
        'Green',
        'Blue'
      ]
    },
    options: {
      responsive: true,
      legend: {
        position: 'left'
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

  window.onload = function() {
    var ctx = document.getElementById('doughnutExpenses').getContext('2d');
    window.myDoughnut = new Chart(ctx, config);
    var ctx = document.getElementById('doughnutIncome').getContext('2d');
    window.myDoughnut = new Chart(ctx, config);
  };
  /*doughnutExpenses
  		document.getElementById('randomizeData').addEventListener('click', function() {
  			config.data.datasets.forEach(function(dataset) {
  				dataset.data = dataset.data.map(function() {
  					return randomScalingFactor();
  				});
  			});

  			window.myDoughnut.update();
  		});

  		var colorNames = Object.keys(window.chartColors);
  		document.getElementById('addDataset').addEventListener('click', function() {
  			var newDataset = {
  				backgroundColor: [],
  				data: [],
  				label: 'New dataset ' + config.data.datasets.length,
  			};

  			for (var index = 0; index < config.data.labels.length; ++index) {
  				newDataset.data.push(randomScalingFactor());

  				var colorName = colorNames[index % colorNames.length];
  				var newColor = window.chartColors[colorName];
  				newDataset.backgroundColor.push(newColor);
  			}

  			config.data.datasets.push(newDataset);
  			window.myDoughnut.update();
  		});

  		document.getElementById('addData').addEventListener('click', function() {
  			if (config.data.datasets.length > 0) {
  				config.data.labels.push('data #' + config.data.labels.length);

  				var colorName = colorNames[config.data.datasets[0].data.length % colorNames.length];
  				var newColor = window.chartColors[colorName];

  				config.data.datasets.forEach(function(dataset) {
  					dataset.data.push(randomScalingFactor());
  					dataset.backgroundColor.push(newColor);
  				});

  				window.myDoughnut.update();
  			}
  		});

  		document.getElementById('removeDataset').addEventListener('click', function() {
  			config.data.datasets.splice(0, 1);
  			window.myDoughnut.update();
  		});

  		document.getElementById('removeData').addEventListener('click', function() {
  			config.data.labels.splice(-1, 1); // remove the label first

  			config.data.datasets.forEach(function(dataset) {
  				dataset.data.pop();
  				dataset.backgroundColor.pop();
  			});

  			window.myDoughnut.update();
  		});

  		document.getElementById('changeCircleSize').addEventListener('click', function() {
  			if (window.myDoughnut.options.circumference === Math.PI) {
  				window.myDoughnut.options.circumference = 2 * Math.PI;
  				window.myDoughnut.options.rotation = -Math.PI / 2;
  			} else {
  				window.myDoughnut.options.circumference = Math.PI;
  				window.myDoughnut.options.rotation = -Math.PI;
  			}

  			window.myDoughnut.update();
  		});
  	
  */
</script>
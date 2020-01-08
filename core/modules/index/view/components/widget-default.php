<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Components</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
      <li class="active">Components</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="panel-group" id="accordionExpense">
        <div class="panel panel-default" style="border:0px !important;">
          <!-- De aca en adelante se debe repetir de acuedo a titulos de accordeon, descripcion y titulo de subaccordion-->
          <!-- inicio de titulo por titulo en el accordion-->
          <div class="panel-heading">
            <div class="row panel-title">
              <div class="col-md-2 col-sm-2 col-xs-2">
                <button type="button" class="btn" style="color:#f5f5f5;background-color:blue;">
                  10.9
                </button>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-4">
                TITULO DE TIPO
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                $1000
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                12%
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                1
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                $5
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                14%S
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                <i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp1"></i>
              </div>
            </div>
          </div>
          <!-- Aca termina titulo pero debe declararse las descripciones/titulos de subaccordion -->
          <div id="collapseExp1" class="panel-collapse collapse">
            <div class="panel-body">
              <div class="panel-group" id="accordionExpenseUnit">
                <div class="panel panel-default" style="border:0px !important;">
                  <!-- Inicio de primera descripcion entidad-->
                  <div class="panel">
                    <div class="row panel-title">
                      <div class="col-md-2 col-sm-2 col-xs-2">
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-4">
                        descripcion de entidad y titulo de subgrid
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        $1000
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        12%
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        1
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        $5
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        %
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        <i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp2"></i>
                      </div>
                    </div>
                  </div>
                  <!-- Aca se declara descripcion del subaccordion-->
                  <div id="collapseExp2" class="panel-collapse collapse">
                    <div class="panel-body">
                      <!-- este row es el que se repite-->
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          descripcion sub grid
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $1000
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          12%
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          1
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          890
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          descripcion sub grid
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $1000
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          12%
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          1
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          890
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          descripcion sub grid
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $1000
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          12%
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          1
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          890
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="panel">
                    <div class="row panel-title">
                      <div class="col-md-2 col-sm-2 col-xs-2">

                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-4">
                        descripcion 2
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        $10002
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        12%2

                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        12
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        $52
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        %2
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        <i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp3"></i>
                      </div>
                    </div>
                  </div>
                  <div id="collapseExp3" class="panel-collapse collapse">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">1
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">2
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">3
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">4
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">6
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">7
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">8
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">2
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">3
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">4
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">6
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">7
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">8
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">9
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">3
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">4
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">6
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">7
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">8
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">9
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">0
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel panel-default" style="border:0px !important;">
          <!-- De aca en adelante se debe repetir de acuedo a titulos de accordeon, descripcion y titulo de subaccordion-->
          <!-- inicio de titulo por titulo en el accordion-->
          <div class="panel-heading">
            <div class="row panel-title">
              <div class="col-md-2 col-sm-2 col-xs-2">
                <button type="button" class="btn" style="color:#f5f5f5;background-color:blue;">
                  10.9
                </button>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-4">
                TITULO DE TIPO
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                $1000
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                12%
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                1
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                $5
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                14%S
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                <i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp6"></i>
              </div>
            </div>
          </div>
          <!-- Aca termina titulo pero debe declararse las descripciones/titulos de subaccordion -->
          <div id="collapseExp6" class="panel-collapse collapse">
            <div class="panel-body">
              <div class="panel-group" id="accordionExpenseUnit">
                <div class="panel panel-default" style="border:0px !important;">
                  <!-- Inicio de primera descripcion entidad-->
                  <div class="panel">
                    <div class="row panel-title">
                      <div class="col-md-2 col-sm-2 col-xs-2">
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-4">
                        descripcion de entidad y titulo de subgrid
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        $1000
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        12%
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        1
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        $5
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        %
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        <i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp5"></i>
                      </div>
                    </div>
                  </div>
                  <!-- Aca se declara descripcion del subaccordion-->
                  <div id="collapseExp5" class="panel-collapse collapse">
                    <div class="panel-body">
                      <!-- este row es el que se repite-->
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          descripcion sub grid
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $1000
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          12%
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          1
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          890
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          descripcion sub grid
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $1000
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          12%
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          1
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          890
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          descripcion sub grid
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $1000
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          12%
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          1
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          $5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                          890
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="panel">
                    <div class="row panel-title">
                      <div class="col-md-2 col-sm-2 col-xs-2">

                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-4">
                        descripcion 2
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        $10002
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        12%2

                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        12
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        $52
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        %2
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-1">
                        <i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp0"></i>
                      </div>
                    </div>
                  </div>
                  <div id="collapseExp0" class="panel-collapse collapse">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">1
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">2
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">3
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">4
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">6
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">7
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">8
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">2
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">3
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">4
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">6
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">7
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">8
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">9
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">3
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">4
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">5
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">6
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">7
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">8
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">9
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">0
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
<?php include "res/resources/js.php"; ?>
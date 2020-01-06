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
          <div class="panel-heading">
            <div class="row panel-title">
              <div class="col-md-2 col-sm-2 col-xs-2">
                <button type="button" class="btn" style="color:#f5f5f5;background-color:yellow;">
                  boton
                </button>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-4">
                descripcion
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
                <i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp1"></i>
              </div>
            </div>
          </div>
          <div id="collapseExp1" class="panel-collapse collapse">
            <div class="panel-body">
              <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-2">
                  2
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                  4
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  1
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  1
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  1
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  1
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  1
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  <i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp1"></i>
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
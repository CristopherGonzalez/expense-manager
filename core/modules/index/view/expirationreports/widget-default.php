<?php

if (isset($_SESSION["user_id"])) :
  if ($_SESSION['user_id'] == "1") {
    Core::redir('?view=company');
  }
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Informes de Vencimientos</h1>
      <ol class="breadcrumb">
        <li><a href="?view=home"><i class="fa fa-home"></i> Home</a></li>
        <li>Informes</li>
        <li class="active">Vencimientos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-8">
          <div class="form-group">
            <!-- Se agregan nuevos filtros de mes, año, tipo de egreso y cambio en categoria del egreso -->
            <div class="col-md-3 form-group">
              <select name="month_find" id="month_find" class="form-control" style="width: 100%;" onchange="load(1);">
                <option value="0">Mes</option>
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
            <div class="col-md-2 form-group">
              <select name="year_find" id="year_find" class="form-control" style="width: 100%;" onchange="load(1);">
                <option value="0">Año</option>
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
            <div class="col-md-3 form-group">
              <select name="type_document_find" id="type_document_find" class="form-control" style="width: 100%;" onchange="load(1);">
                <option value="0">Todos</option>
                <option value="income">Ingresos</option>
                <option value="expense">Egresos</option>
                <option value="partner">Socios</option>
                <option value="debt">Deudas</option>
              </select>
            </div>
            <div class="col-md-5 form-group">
              <input type="text" class="form-control" name="find_text" id="find_text" style="width: 100%;" placeholder="Buscar en entidad" title="Ingresa algun texto para realizar la busqueda" onkeyup="load(1);">
            </div>
            <div class="col-md-4 form-group">
              <input type="checkbox" id="inactive" name="inactive" onchange="load(1);">
              <label for="inactive"><b>Ver eliminados</b></label>
            </div>
            <div class="col-md-3 form-group">
              <label style="font-size: 24px;" id="total_expiration">$0</label>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <div class="col-xs-1">
              <div id="loader" class="text-center"></div>
            </div>
            <!-- <div class="col-md-offset-10"> -->
            <div class=" pull-right">
              <button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
              <div class="btn-group">
                <a style="margin-right: 3px" target="_blank" href="reports/reportExpense.php" class="btn btn-default pull-right">
                  <span class="fa fa-file-excel-o"></span> Descargar
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="resultados_ajax">

      </div><!-- Resultados Ajax -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Vencimientos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
          <div class="outer_div">
            <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              Puedes seleccionar algun filtro de busqueda!
            </div>
          </div><!-- Datos ajax Final -->
        </div>
        <!-- /.box-body -->
      </div>
    </section> <!-- /.content -->
  </div><!-- /.content-wrapper -->
<?php else : Core::redir("./");
endif; ?>
<?php include "res/resources/js.php"; ?>
<script>
  $(function() {
    load();
  });

  function load() {
    //Se obtienen filtros de busqueda
    let month_find = $('#month_find option:selected').val();
    let year_find = $('#year_find option:selected').val();
    let type_document_find = $('#type_document_find option:selected').val();
    let find_text = $('#find_text').val();
    let inactive = $('#inactive').is(":checked");

    let parametros = {
      'month': month_find,
      'year': year_find,
      'type_doc': type_document_find,
      'text': find_text.trim(),
      'inactive': inactive
    };
    if (month_find == "0" && year_find == "0" && type_document_find == "0" && find_text == "") {
      parametros = {};
      $(".outer_div").html('<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Puedes seleccionar algun filtro de busqueda!</div>');
    } else {
      $.ajax({
        type: "POST",
        url: "./?action=loadreport_expiration",
        data: parametros,
        beforeSend: function(objeto) {
          $("#resultados_ajax").html("Enviando...");
        },
        success: function(datos) {
          $("#resultados_ajax").html("");
          $(".outer_div").html(datos);
          $("#total_expiration").html("$"+total);
        }
      });
    }


  }
</script>
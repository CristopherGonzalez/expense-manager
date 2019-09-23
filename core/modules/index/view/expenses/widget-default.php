<?php 
/*-------------------------
    Autor: Amner Saucedo Sosa
    Web: www.abisoftgt.net
    E-Mail: waptoing7@gmail.com
---------------------------*/
if(isset($_SESSION["user_id"])):
?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Gastos</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="form-group">
                <div class="col-md-3">
                    <select name="q" id="q" class="form-control select2" style="width: 100%;" onchange="load(1);">
                    <option >Buscar por Categoria</option>
                        <?php
                            $categories_expense=CategoryExpenseData::getAll($_SESSION["user_id"]);
                            foreach($categories_expense as $row){
                        ?>
                            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
                    </span>
                </div>

                <div class="col-xs-1">
                    <div id="loader" class="text-center"></div>
                </div>
                <!-- <div class="col-md-offset-10"> -->
                <div class=" pull-right">
<button class="btn btn-primary" data-toggle="modal" data-target="#formModal"><i class='fa fa-plus'></i> Nuevo</button>
    <!-- Form Modal -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <!-- form  -->
        <form class="form-horizontal" role="form" method="post" id="add_register" name="add_register">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"> Nuevo Gasto</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Descripción: </label>
                    <div class="col-sm-10">
                        <textarea type="text" class="form-control" id="description" name="description" placeholder="Descripción: "></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="amount" class="col-sm-2 control-label">Cantidad: </label>
                    <div class="col-sm-10">
                        <input type="text" required class="form-control" id="amount" name="amount" placeholder="Cantidad: " pattern="^[0-9]{1,5}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8">
                    </div>
                </div>
                <div class="form-group">
                    <label for="category" class="col-sm-2 control-label">Categoria: </label>
                    <div class="col-sm-10">
                        <select class="form-control select2" style="width: 100%" name="category" id="category" >
                            <option >---SELECCIONA---</option>
                        <?php
                            $category=CategoryExpenseData::getAll($_SESSION["user_id"]);
                            foreach($category as $cat){
                        ?>
                            <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                        <?php 
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date" class="col-sm-2 control-label">Fecha: </label>
                    <div class="col-sm-10">
                        <input type="date" required class="form-control" id="date" name="date" placeholder="Fecha: ">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="save_data" class="btn btn-primary">Agregar</button>
            </div>
        </form>
        <!-- /end form  -->
        </div>
    </div>
</div>
<!-- End Form Modal -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            Mostrar <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li class='active' onclick='per_page(15);' id='15'><a href="#">15</a></li>
                            <li  onclick='per_page(25);' id='25'><a href="#">25</a></li>
                            <li onclick='per_page(50);' id='50'><a href="#">50</a></li>
                            <li onclick='per_page(100);' id='100'><a href="#">100</a></li>
                            <li onclick='per_page(1000000);' id='1000000'><a href="#">Todos</a></li>
                        </ul>
                    </div>
                    <input type='hidden' id='per_page' value='15'>
                    <?php $expenses_data=ExpensesData::getAllCount($_SESSION['user_id']);
                        if($expenses_data->count!=0):
                    ?>
                    <div class="btn-group">
                        <a style="margin-right: 3px" target="_blank" href="reports/reportExpense.php" class="btn btn-default pull-right">
                            <span class="fa fa-file-excel-o"></span> Descargar
                        </a>
                    </div> 
                    <?php endif; ?> 
                </div>
            </div>
            <!-- <div class="col-xs-3"></div> -->
        </div>
        <br>
        <div id="resultados_ajax"></div><!-- Resultados Ajax -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Historial Gastos</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <div class="outer_div"></div><!-- Datos ajax Final --> 
            </div>
            <!-- /.box-body -->
        </div><!-- /.box -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php   include "res/resources/js.php"; ?>

<script>
    $(function() {
        load(1);
    });
    function load(page){
            var query=$("#q").val();
            var per_page=$("#per_page").val();
            var parametros = {"page":page,'query':query,'per_page':per_page};
            //$.get("./?action=loadexpenses",parametros,function(data){
            $.get({
                url:"./?action=loadexpenses",
                data:parametros,
                beforeSend: function(data){
                    $("#loader").html("<img src='res/images/ajax-loader.gif'>");
                },
                //console.log(data);
                success:function(data){
                    $(".outer_div").html(data);
                    $("#loader").html("");
                }

            });
    }
    function per_page(valor){
        $("#per_page").val(valor);
        load(1);
        $('.dropdown-menu li' ).removeClass( "active" );
        $("#"+valor).addClass( "active" );
    }
</script>
<script>
    function eliminar(id){
        if(confirm('Esta acción  eliminará de forma permanente el gasto \n\n Desea continuar?')){
            var page=1;
            var query=$("#q").val();
            var per_page=$("#per_page").val();
            var parametros = {"page":page,"query":query,"per_page":per_page,"id":id};
            
            $.get({
                // method: "GET",
                url:'./?action=loadexpenses',
                data: parametros,
                beforeSend: function(objeto){
                $("#loader").html("<img src='res/images/ajax-loader.gif'>");
              },
                success:function(data){
                    $(".outer_div").html(data).fadeIn('slow');
                    $("#loader").html("");
                    window.setTimeout(function() {
                    $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();});}, 5000);
                }
            })
        }
    }
</script>
<script>
    $( "#add_register" ).submit(function( event ) {
        $('#save_data').attr("disabled", true);
        var parametros = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "./?action=addexpense",
            data: parametros,
                beforeSend: function(objeto){
                    $("#resultados_ajax").html("Enviando...");
                },
            success: function(datos){
            $("#resultados_ajax").html(datos);
            $('#save_data').attr("disabled", false);
            load(1);
            window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();});}, 5000);
            $('#formModal').modal('hide');
          }
        });
        event.preventDefault();
    })
</script>
<?php else: Core::redir("./"); endif;?> 
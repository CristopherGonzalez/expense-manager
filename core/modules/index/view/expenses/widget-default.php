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
            <div class="col-md-8">
                <div class="form-group">
                    <!-- Se agregan nuevos filtros de mes, año, tipo de gasto y cambio en categoria del gasto -->
                    <div class="col-md-3 form-group">
                        <select name="mes" id="mes" class="form-control" style="width: 100%;">
                            <option >Mes</option>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <select name="year" id="year" class="form-control" style="width: 100%;">
                            <option >Año</option>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <select name="tipo_gasto" id="tipo_gasto" class="form-control" style="width: 100%;">
                            <option >Buscar por Tipo de Gasto</option>
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <select name="q" id="q" class="form-control" style="width: 100%;" onchange="load(1);">
                        <option >Buscar por Categoria</option>
                            <?php
                                $categories_expense=CategoryExpenseData::getAll($_SESSION["user_id"]);
                                foreach($categories_expense as $row){
                            ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-5 form-group">
                        <input type="text"  class="form-control" name="buscar-texto" id="buscar-texto" style="width: 100%;" placeholder="Buscar en texto" title="Ingresa algun texto para realizar la busqueda">
                    </div>
                    <div class="col-md-5 form-group">
                        <label for="impagos">
                        <input type="checkbox" id="impagos" name="impagos"> Solo Impagos</label>
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
                                            <label for="tipo-gasto" class="col-sm-2 control-label">Tipo: </label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" style="width: 100%" name="tipo-gasto" id="tipo-gasto" >
                                                    <option >---SELECCIONA---</option>
                                                </select>
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
                                            <label for="entidad" class="col-sm-2 control-label">Entidad: </label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" style="width: 100%" name="entidad" id="entidad" >
                                                    <option >---SELECCIONA---</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="date" class="col-sm-2 control-label">Fecha: </label>
                                            <div class="col-sm-10">
                                                <input type="date" required class="form-control" id="date" name="date" placeholder="Fecha: ">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span class="col-md-2 col-sm-2 col-xs-12"></span>
                                            <label for="documento" class="col-sm-4">Documento:
                                                <input type="file" required class="form-control" accept="image/*" id="documento" name="documento">
                                            </label>
                                            <label for="pago" class="col-sm-4">Pago:
                                                <input type="file" required class="form-control" accept="image/*" id="pago" name="pago">
                                            </label>
                                            <label for="chk-pagado" class="col-sm-2">
                                                <input type="checkbox" id="chk-pagado" name="chk-pagado"> Pagado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-group">
                                            <span class="col-md-2 col-sm-2 col-xs-12"></span>
                                            <label class="col-md-6 col-sm-6" style="color:#999; font-weight:normal;">Registrado por Juan Prueba el 01/01/2020</label>
                                            <span class="col-md-4 col-sm-4 col-xs-12">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" id="save_data" class="btn btn-primary">Agregar</button>
                                            </span>
                                        </div>
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
            </div>    
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
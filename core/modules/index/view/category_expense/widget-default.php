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
        <h1>Categorias Gastos</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="form-group">

                <!--  Se agrega opcion para buscar por gastos-->
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Gastos" name="g" id='g' onkeyup="load(1);">
                </div>
                <div class="col-md-1">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
                    </span>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Nombre" name="q" id='q' onkeyup="load(1);">
                </div>
                <div class="col-md-1">
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
                                    <h4 class="modal-title" id="myModalLabel"> Nueva Categoria Gasto</h4>
                                </div>
                                <div class="modal-body">
                                    <!-- Se agrega desplegable para seleccionar el tipo de gasto -->
                                    <div class="form-group">
                                        <label for="type_expense" class="col-sm-2 control-label">Gasto: </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" style="width: 100%" name="type_expense" id="type_expense" >
                                                <option >---SELECCIONA---</option>
                                                <?php
                                                    $gasto=TypeData::getAllExpense();
                                                    foreach($gasto as $cat){
                                                ?>
                                                    <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                                                <?php 
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Nombre: </label>
                                        <div class="col-sm-10">
                                            <input type="text" required class="form-control" id="name" name="name" placeholder="Nombre: ">
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
                </div>
            </div>
            <!-- <div class="col-xs-3"></div> -->
        </div>
        <br>
        <div id="resultados_ajax"></div><!-- Resultados Ajax -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Historial Categorias de Gastos</h3>
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
            //Se cambia para hacer busqueda por gasto y/o nombre
            var type_expense = $("#f_type_expense").val();
            var name = $("#f_name").val();
            var per_page=$("#per_page").val();
            var parametros = {"page":page,'f_type_expense':type_expense,'f_name':name,'per_page':per_page};
            //$.get("./?action=loadexpenses",parametros,function(data){
            $.get({
                url:"./?action=loadcategory_expense",
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
        if(confirm('Esta acción  eliminará de forma permanente la categoria \n\n Desea continuar?')){
            var page=1;
            //Se cambia para mantener estandar de envio de parametros
            var type_expense = $("#f_type_expense").val();
            var name = $("#f_name").val();
            var per_page=$("#per_page").val();
            var parametros = {"page":page,'f_type_expense':type_expense,'f_name':name,'per_page':per_page, 'id':id};
            
            $.get({
                // method: "GET",
                url:'./?action=loadcategory_expense',
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
            url: "./?action=addcategory_expense",
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
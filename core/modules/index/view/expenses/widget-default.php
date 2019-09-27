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
                        <select name="month" id="month" class="form-control" style="width: 100%;">
                            <?php
                                //Se crean opciones de meses y se selecciona el actual por defecto
                                $months=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                                foreach($months as $index => $month){
                            ?>
                                <option value="<?php echo $index; ?>"  <?php if(($index+1) == date("n")) echo "selected"; ?> ><?php echo $month; ?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <select name="year" id="year" class="form-control" style="width: 100%;">
                            <?php
                                //Se crean opciones de años y se selecciona el actual por defecto
                                $years=[2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025];
                                foreach($years as $year){
                            ?>
                                <option value="<?php echo $year; ?>"  <?php if($year == date("Y")) echo "selected"; ?> ><?php echo $year; ?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <select name="tipo_gasto" id="tipo_gasto" class="form-control" style="width: 100%;">
                            <option >Buscar por Tipo de Gasto</option>
                            <?php
                                //Se carga con tipos de gastos
                                $tipos=TypeData::getAllExpense();
                                foreach($tipos as $tipo){
                            ?>
                                <option value="<?php echo $tipo->id; ?>"><?php echo $tipo->name; ?></option>
                            <?php 
                                }
                            ?>
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
                        <input type="checkbox" id="impagos" name="impagos"> 
                        <label for="impagos">Solo Impagos</label>
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
                                <form class="form-horizontal" role="form" method="post" id="add_register" name="add_register" enctype="multipart/form-data"> 
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
                                                <input type="text" required class="form-control" id="amount" name="amount" placeholder="Cantidad: " pattern="^[0-9]{1,9}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="type_expense" class="col-sm-2 control-label">Tipo: </label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" style="width: 100%" name="type_expense" id="type_expense" >
                                                    <option >---SELECCIONA---</option>
                                                <?php
                                                    //Se carga datos de tipos de gasto en modal
                                                    $types=TypeData::getAllExpense();
                                                    foreach($types as $type){
                                                ?>
                                                    <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                                                <?php 
                                                    }
                                                ?>
                                                </select>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category" class="col-sm-2 control-label">Categoria: </label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" style="width: 100%" name="category" id="category" >
                                                    <option >---SELECCIONA---</option>
                                                    <?php
                                                        //Se carga datos de tipos de categoria en modal
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
                                                <select class="form-control select2" style="width: 100%" name="entity" id="entity" >
                                                    <option >---SELECCIONA---</option>
                                                    <?php
                                                        //Se carga datos de entidades en modal
                                                        $entities=EntityData::getAll($_SESSION["user_id"]);
                                                        foreach($entities as $ent){
                                                    ?>
                                                        <option value="<?php echo $ent->id; ?>"><?php echo $ent->name; ?></option>
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
                                        <div class="form-group">
                                            <span class="col-md-2 col-sm-2 col-xs-12"></span>
                                            <label for="document" class="col-sm-4">Documento:
                                                <input type="file" class="form-control" accept="image/*" id="document" name="document">
                                            </label>
                                            <label for="payment" class="col-sm-4">Pago:
                                                <input type="file" class="form-control" accept="image/*" id="payment" name="payment">
                                            </label>
                                            <label for="paid_out" class="col-sm-2">
                                                <input type="checkbox" id="paid_out" name="paid_out" value="paid_out"> Pagado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-group">
                                            <span class="col-md-2 col-sm-2 col-xs-12"></span>
                                            <label class="col-md-6 col-sm-6" style="color:#999; font-weight:normal;">Registrado por  <?php $user_session=UserData::getById($_SESSION["user_id"]); echo $user_session->name  ?></label>
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
        var date = new Date();
        date.getMonth()
        date.getFullYear()
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
    //Cambio en formato de envio al controlador para poder enviar imagenes


    /*function upload_files(src_file){
    var fd = new FormData();
    var files = src_file[0].files[0];
    var name = $.trim($('#txt_name').val()) == "" ? "test_image":$.trim($('#txt_name').val());
    fd.append('file',files);
    fd.append('category',src_file[0].name);
    fd.append('name',name);
    $.ajax({
        type: 'POST',
        url: '/upload_files',
        dataType: "json",
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response.file_path);
        },
        error: function(response) {
            console.log(response.responseText);
        }
    });
}*/



    $( "#add_register" ).submit(function( event ) {
        debugger;
        $('#save_data').attr("disabled", true);
        //Se cambia forma de envio de formulario para soportar envio de imagenes
        var fd = new FormData($(this)[0]);
        var pay_out = $('#paid_out').is(":checked");
        fd.append("pay_out",pay_out);
   
        $.ajax({
            type: "POST",
            url: "./?action=addexpense",
            data: fd,
            contentType: false,
            processData: false,
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
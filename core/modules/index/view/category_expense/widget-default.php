<?php 

if(isset($_SESSION["user_id"]) && $_SESSION['user_id']!= "1"):
?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Categorias de Egreso</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="form-group">

                <!--  Se agrega opcion para buscar por egresos-->
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Egresos" name="f_type_expense" id='f_type_expense' onkeyup="load(1);">
                </div>
                <div class="col-md-1">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
                    </span>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Nombre" name="f_name" id='f_name' onkeyup="load(1);">
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
                <button class='btn btn-primary' data-toggle='modal' data-target='#formModal'><i class='fa fa-plus'></i> Nuevo</button>   
                    <?php $modal_content = new Modal("Ingreso de Egresos","formModal",UserData::getById($_SESSION['user_id']));
                        echo $modal_content->renderInit();?>
                        <div class="form-group">
                            <?php 
                                $type_expense_select = new SelectList("type_expense","Egresos",TypeData::getAllExpense());
                                echo $type_expense_select->renderLabel('col-sm-2');
                            ?>
                            <div class="col-sm-10">
                                <?php echo $type_expense_select->render(); ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?php 
                                $input_text = new InputText("name","Nombre");
                                echo $input_text->renderLabel('col-sm-2');
                            ?>
                            <div class="col-sm-10">
                                <?php echo $input_text->render(); ?>
                            </div>
                        </div>
                    <?php echo $modal_content->renderEnd();?>  
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
                <h3 class="box-title">Historial Categorias de Egresos</h3>
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
            //Se cambia para hacer busqueda por egreso y/o nombre
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
                clear_modal('add_register');
            }
        });
        event.preventDefault();
    })
</script>
<?php else: Core::redir("./"); endif;?> 
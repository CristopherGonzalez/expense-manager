<?php 
if(isset($_SESSION["user_id"]) && $_SESSION['user_id']!= "1"):
?> 
<?php  
    //Se obtienen datos para llenado de desplegables
    $categories_expense=CategoryExpenseData::getAll($_SESSION["company_id"]);
    $categories_income=CategoryIncomeData::getAll($_SESSION["company_id"]);
    $categories_partner=array('Socios','Otros');
    $types=TypeData::getAllType();
 ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Entidades</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <!-- Se agregan nuevos filtros de tipo, categoria y busqueda por texto -->
                    <div class="col-md-4 form-group">
                        <select name="type_find" id="type_find" class="form-control" style="width: 100%;" onchange="load(1);">
                            <option value="0">Buscar por Tipo</option>
                            <optgroup label="Gasto">
                                <?php 
                                $type_category="Gasto";
                                foreach($types as $type){ 
                                    if(strcmp($type->tipo,$type_category)){?>
                                        </optgroup>
                                        <optgroup label="<?php echo $type->tipo ?>">
                                    <?php }?>
                                    <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                                <?php 
                                    $type_category = $type->tipo;
                                }?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <select name="category_find" id="category_find" class="form-control" style="width: 100%;" onchange="load(1);">
                            <option value="0">Buscar por Categoria</option>
                            <optgroup label="Gasto"> 
                                <?php
                                    foreach($categories_expense as $category){
                                        ?>
                                            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                        <?php 
                                    }
                                ?>
                            </optgroup>
                            <optgroup label="Ingreso"> 
                                <?php
                                    foreach($categories_income as $category){
                                        ?>
                                            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                        <?php 
                                    }
                                ?>
                            </optgroup>
                            <optgroup label="Socio"> 
                                <?php
                                    foreach($categories_partner as $index=>$category){
                                        ?>
                                            <option value="<?php echo $index+1; ?>"><?php echo $category; ?></option>
                                        <?php 
                                    }
                                ?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="text"  class="form-control" name="find_text" id="find_text" style="width: 100%;" placeholder="Buscar en texto" title="Ingresa algun texto para realizar la busqueda"  onkeyup="load(1);">
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
                                        <h4 class="modal-title" id="myModalLabel"> Nueva Entidad</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="type" class="col-sm-4 control-label">Origen </label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" style="width: 100%" name="origin" id="origin" onchange="change_origin(this);" required>
                                                    <option value="origin_default">---SELECCIONA---</option>
                                                    <option value="origin_expense">Egresos</option>
                                                    <option value="origin_income">Ingresos</option>
                                                    <option value="origin_partner">Socio</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="type" class="col-sm-4 control-label">Tipo </label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" style="width: 100%" name="type" id="type" required disabled>
                                                    <option value=0>---SELECCIONA---</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category_expense" class="col-sm-4 control-label">Categoria de gastos </label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" style="width: 100%" name="category_expense" id="category_expense" disabled>
                                                    <option value=0>---SELECCIONA---</option>
                                                    <?php
                                                        //Se carga datos de tipos de categoria por gasto en modal
                                                        foreach($categories_expense as $category){
                                                            ?>
                                                                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                                            <?php 
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category_income" class="col-sm-4 control-label">Categoria de ingreso </label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" style="width: 100%" name="category_income" id="category_income" disabled>
                                                    <option value=0>---SELECCIONA---</option>
                                                    <?php
                                                        //Se carga datos de tipos de categoria por ingreso en modal
                                                        foreach($categories_income as $category){
                                                            ?>
                                                                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                                            <?php 
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category_partner" class="col-sm-4 control-label">Categoria socio </label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" style="width: 100%" name="category_partner" id="category_partner" disabled>
                                                    <option value=0>---SELECCIONA---</option>
                                                    <option value=1>Socio</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name_entity" class="col-sm-4 control-label">Nombre </label>
                                            <div class="col-sm-8">
                                                <textarea type="text" class="form-control" id="name_entity" name="name_entity" placeholder="Nombre"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-group">
                                            <span class="col-md-1 col-sm-1 col-xs-12"></span>
                                            <label class="col-md-7 col-sm-7 col-xs-12" style="color:#999; font-weight:normal;">Registrado por  <?php $user_session=UserData::getById($_SESSION["user_id"]); echo $user_session->name  ?> el <?php echo date("Y-m-d");  ?></label>
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
                        <?php $entity_data=EntityData::getAll($_SESSION['company_id']);
                            if(count($entity_data)!=0):
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
                <h3 class="box-title">Historial Entidades</h3>
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
       //Se obtienen filtros de busqueda
        var category_find = $('#category_find option:selected');
        var category_type = category_find.closest('optgroup').attr('label');
        var type_find = $('#type_find option:selected').val();
        var find_text = $('#find_text').val();

        var per_page=$("#per_page").val();
        var parametros = {
            "page":page,
            'type':type_find,
            'category_type': (category_type==undefined? "" : category_type),
            'category':category_find[0].value,
            'text':find_text,
            'per_page':per_page };
        $.get({
            url:"./?action=loadentity",
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
        if(confirm('Esta acción  eliminará de forma permanente la entidad \n\n Desea continuar?')){
            //Se obtienen filtros de busqueda para recarga y por estandar
            var category_find = $('#category_find option:selected').val();
            var type_find = $('#type_find option:selected').val();
            var find_text = $('#find_text').val();
            var page=1;

            var per_page=$("#per_page").val();
            var parametros = {
                "page":page,
                'type':type_find,
                'category':category_find,
                'text':find_text,
                'per_page':per_page,
                "id":id
             };

            $.get({
                // method: "GET",
                url:'./?action=loadentity',
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
        var category_expense = $('#category_expense option:selected').val();
        var category_income = $('#category_income option:selected').val();
        var category_partner = $('#category_partner option:selected').val();
        var category= (category_expense>0)? category_expense : (category_income>0)? category_income : category_partner ; ;
        //Se cambia forma de envio de formulario para soportar envio de imagenes
        var fd = new FormData($(this)[0]);
        fd.append('category',category);
        $.ajax({
            type: "POST",
            url: "./?action=addentity",
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

   //Funcion para cambiar visibilidad dependiendo de la opcion de origin
   function change_origin(event){
        var origin_type = event.value;
        $('#type option').each(function(){ $(this).remove()});
        $('#type').prop('disabled', false);
        $('#type').append($('<option></option>').text("---SELECCIONA---").attr("value",0));
        $('#category_expense').val("0").change();
        $('#category_income').val("0").change();
        $('#category_partner').val("0").change();
        $type_category = "";
        //Se carga datos dependiendo de la opcion de origen de la modal
        if (origin_type === "origin_default"){
            $('#type').prop('disabled', 'disabled');
            $('#category_expense').prop('disabled', 'disabled');
            $('#category_income').prop('disabled', 'disabled');
            $('#category_partner').prop('disabled', 'disabled');
            $type_category = "";
         }
        if (origin_type === "origin_expense"){
            $('#category_expense').prop('disabled', false);
            $('#category_income').prop('disabled', 'disabled');
            $('#category_partner').prop('disabled', 'disabled');
            $type_category = "Gasto";
        }
        if (origin_type === "origin_income"){
            $('#category_income').prop('disabled', false);
            $('#category_partner').prop('disabled', 'disabled');
            $('#category_expense').prop('disabled', 'disabled');
            $type_category = "Ingreso";
        }
        if (origin_type === "origin_partner"){
            $('#category_partner').prop('disabled', false);
            $('#category_expense').prop('disabled', 'disabled');
            $('#category_income').prop('disabled', 'disabled');
            $type_category = "Socio";
        }

        if($type_category!=""){
            <?php foreach($types as $type){ ?>
                if("<?php echo $type->tipo; ?>" == $type_category){
                    $('#type').append($('<option></option>').attr("value",<?php echo $type->id; ?>).text("<?php echo $type->name; ?>"));
                }
            <?php }?>
        }

    }
</script>
<?php else: Core::redir("./"); endif;?> 
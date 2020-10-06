<?php
if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != "1") :
?>
    <?php
    //Se obtienen datos para llenado de desplegables
    $categories_expense = CategoryExpenseData::getAll($_SESSION["company_id"]);
    $categories_income = CategoryIncomeData::getAll($_SESSION["company_id"]);
    $categories_partner = array('Socios', 'Otros');
    $types = TypeData::getAllType();
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
                                <optgroup label="Egreso">
                                    <?php
                                    $type_category = "Egreso";
                                    foreach ($types as $type) {
                                        if (strcmp($type->tipo, $type_category)) { ?>
                                </optgroup>
                                <optgroup label="<?php echo $type->tipo ?>">
                                <?php } ?>
                                <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                            <?php
                                        $type_category = $type->tipo;
                                    } ?>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <select name="category_find" id="category_find" class="form-control" style="width: 100%;" onchange="load(1);">
                                <option value="0">Buscar por Categoria</option>
                                <optgroup label="Egreso">
                                    <?php
                                    foreach ($categories_expense as $category) {
                                    ?>
                                        <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </optgroup>
                                <optgroup label="Ingreso">
                                    <?php
                                    foreach ($categories_income as $category) {
                                    ?>
                                        <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </optgroup>
                                <optgroup label="Socio">
                                    <?php
                                    foreach ($categories_partner as $index => $category) {
                                    ?>
                                        <option value="<?php echo $index + 1; ?>"><?php echo $category; ?></option>
                                    <?php
                                    }
                                    ?>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <input type="text" class="form-control" name="find_text" id="find_text" style="width: 100%;" placeholder="Buscar en texto" title="Ingresa algun texto para realizar la busqueda" onkeyup="load(1);">
                        </div>
                        <div class="col-md-5 form-group">
                            <input type="checkbox" id="inactive" name="inactive" onchange="load(1);">
                            <label for="inactive">Ver inactivos</label>
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
                                                        <select class="form-control" style="width: 100%" name="origin" id="origin" onchange="change_origin(this);" required>
                                                            <option value="">Selecciona una Opcion</option>
                                                            <option value="origin_expense">Egresos</option>
                                                            <option value="origin_income">Ingresos</option>
                                                            <option value="origin_partner">Socio</option>
                                                            <option value="origin_debt">Deudas</option>
                                                            <option value="origin_stock">Valores</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="type" class="col-sm-4 control-label">Tipo </label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" style="width: 100%" name="type" id="type" required onchange="change_categories(this);" disabled>
                                                            <option value=0>Selecciona una Opcion</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="category_expense" class="col-sm-4 control-label">Categoria de egresos </label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" style="width: 100%" name="category_expense" id="category_expense" disabled>
                                                            <option value=0>Selecciona una Opcion</option>
                                                            <?php
                                                            //Se carga datos de tipos de categoria por egreso en modal
                                                            foreach ($categories_expense as $category) {
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
                                                        <select class="form-control" style="width: 100%" name="category_income" id="category_income" disabled>
                                                            <option value=0>Selecciona una Opcion</option>
                                                            <?php
                                                            //Se carga datos de tipos de categoria por ingreso en modal
                                                            foreach ($categories_income as $category) {
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
                                                        <select class="form-control" style="width: 100%" name="category_partner" id="category_partner" disabled>
                                                            <option value=0>Selecciona una Opcion</option>
                                                            <option value=1>Socio</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="name_entity" class="col-sm-4 control-label">Nombre </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="name_entity" name="name_entity" placeholder="Nombre" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="document_number" class="col-sm-4 control-label">Numero de Documento </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="document_number" name="document_number" placeholder="Numero de Documento">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description" class="col-sm-4 control-label">Descripción </label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text" class="form-control" id="description" name="description" required placeholder="Descripción "></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="document" class="col-sm-4 control-label">Documento </label>
                                                    <div class="col-sm-6">
                                                        <input type="file" class="form-control" accept="image/*" id="document" name="document" onchange="load_image(this);">
                                                        <input type="button" class="btn btn-default" id="btn_webcam_document" name="btn_webcam_document" value="Sacar Foto" data-toggle="modal" href="#frmwebcamdocument" onclick="add_parameters_from_webcam('document')">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <img src="res/images/default_image.jpg" alt="Imagen en blanco a la espera de que carga de documento" class="img-thumbnail" id="document_image" height="60" width="75">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12 col-sm-12 col-xs-12" id="alert_add" style="border:5px;"></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group">
                                                    <span class="col-md-1 col-sm-1 col-xs-12"></span>
                                                    <label class="col-md-7 col-sm-7 col-xs-12" style="color:#999; font-weight:normal;">Registrado por <?php $user_session = UserData::getById($_SESSION["user_id"]);
                                                                                                                                                        echo $user_session->name  ?> el <?php echo date("Y-m-d");  ?></label>
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
                            <?php
                            $webcamdocument = new Webcam('document');
                            echo $webcamdocument->renderModalImageCam();
                            ?>
                            <!-- End Form Modal -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    Mostrar <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li class='active' onclick='per_page(15);' id='15'><a href="#">15</a></li>
                                    <li onclick='per_page(25);' id='25'><a href="#">25</a></li>
                                    <li onclick='per_page(50);' id='50'><a href="#">50</a></li>
                                    <li onclick='per_page(100);' id='100'><a href="#">100</a></li>
                                    <li onclick='per_page(1000000);' id='1000000'><a href="#">Todos</a></li>
                                </ul>
                            </div>
                            <input type='hidden' id='per_page' value='15'>
                            <?php $entity_data = EntityData::getAll($_SESSION['company_id']);
                            if (count($entity_data) != 0) :
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
    <?php include "res/resources/js.php"; ?>
    <script type="text/javascript" src="res/plugins/webcam/webcam.js"></script>
    <script type="text/javascript" src="res/plugins/multimodal/multimodal.js"></script>
    <script>
        $(function() {
            load(1);
            let date = new Date();
            date.getMonth()
            date.getFullYear()
        });

        function load(page) {
            //Se obtienen filtros de busqueda
            let category_find = $('#category_find option:selected');
            let category_type = category_find.closest('optgroup').attr('label');
            let type_find = $('#type_find option:selected').val();
            let find_text = $('#find_text').val();
            let inactive = $('#inactive').is(":checked");


            let per_page = $("#per_page").val();
            let parametros = {
                "page": page,
                'type': type_find,
                'category_type': (category_type == undefined ? "" : category_type),
                'category': category_find[0].value,
                'text': find_text,
                'inactive': inactive,
                'per_page': per_page
            };
            $.get({
                method: "POST",
                url: "./?action=loadentity",
                data: parametros,
                beforeSend: function(data) {
                    $("#loader").html("<img src='res/images/ajax-loader.gif'>");
                },
                //console.log(data);
                success: function(data) {
                    $(".outer_div").html(data);
                    $("#loader").html("");
                }

            });
        }

        function per_page(valor) {
            $("#per_page").val(valor);
            load(1);
            $('.dropdown-menu li').removeClass("active");
            $("#" + valor).addClass("active");
        }

        function eliminar(id) {
            if (confirm('Esta acción  eliminará de forma permanente la entidad \n\n Desea continuar?')) {
                //Se obtienen filtros de busqueda para recarga y por estandar
                let category_find = $('#category_find option:selected').val();
                let type_find = $('#type_find option:selected').val();
                let find_text = $('#find_text').val();
                let page = 1;
                let inactive = $('#inactive').is(":checked");

                let per_page = $("#per_page").val();
                let parametros = {
                    "page": page,
                    'type': type_find,
                    'category': category_find,
                    'category_type': "",
                    'text': find_text,
                    'inactive': inactive,
                    'per_page': per_page,
                    "id": id
                };

                $.get({
                    // method: "GET",
                    method: "POST",
                    url: './?action=loadentity',
                    data: parametros,
                    beforeSend: function(objeto) {
                        $("#loader").html("<img src='res/images/ajax-loader.gif'>");
                    },
                    success: function(data) {
                        $(".outer_div").html(data).fadeIn('slow');
                        $("#loader").html("");
                        window.setTimeout(function() {
                            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                                $(this).remove();
                            });
                        }, 5000);
                    }
                })
            }
        }
    </script>
    <script>
        $("#add_register").submit(function(event) {

            $('#save_data').attr("disabled", true);
            let category_expense = $('#category_expense option:selected').val();
            let category_income = $('#category_income option:selected').val();
            let category_partner = $('#category_partner option:selected').val();
            let category = (category_expense > 0 || category_expense > "") ? category_expense : (category_income > 0 || category_income > "") ? category_income : category_partner;;
            let origin_type = $('#origin option:selected').val();
            if (origin_type != "origin_debt" && origin_type != "origin_stock") {
                if ((category == "" || category == null || category == undefined || category == 0)) {
                    $('#alert_add').html("");
                    $('#alert_add').html(
                        "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>" +
                        "No se ha seleccionado ninguna categoría, si no tiene ninguna para seleccionar asegúrese de haber creado al menos una en la categoría correspondiente.</div>"
                    );
                    $('#save_data').attr("disabled", false);
                    return false;
                }
            } else {
                category = 0;
            }
            //Se cambia forma de envio de formulario para soportar envio de imagenes
            let fd = new FormData($(this)[0]);
            fd.append('category', category);
            fd.append("document_image", $('#document_image').attr('src'));

            $.ajax({
                type: "POST",
                url: "./?action=addentity",
                data: fd,
                contentType: false,
                processData: false,
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
                    $('#formModal').modal('hide');
                    clear_modal('add_register');
                    $('#type').prop('disabled', 'disabled');
                    $('#category_expense').prop('disabled', 'disabled');
                    $('#category_income').prop('disabled', 'disabled');
                    $('#category_partner').prop('disabled', 'disabled');
                    $('#alert_add').html("");
                }
            });
            event.preventDefault();
        })

        function change_categories(event) {
            let type_value = event.value;
            let origin_type = $('#origin').val();
            let categories = '';
            $('#category_expense option').each(function() {
                $(this).remove()
            });
            $('#category_income option').each(function() {
                $(this).remove()
            });
            $('#category_partner option').each(function() {
                $(this).remove()
            });
            $('#category_expense').append($('<option></option>').text("Selecciona una Opcion").attr("value", ""));
            $('#category_income').append($('<option></option>').text("Selecciona una Opcion").attr("value", ""));
            $('#category_partner').append($('<option></option>').text("Selecciona una Opcion").attr("value", ""));
            $('#category_partner').prop('disabled', true);
            $('#category_income').prop('disabled', true);
            $('#category_expense').prop('disabled', true);
            $('#category_partner').prop('required', false);
            $('#category_income').prop('required', false);
            $('#category_expense').prop('required', false);
            let exists_category = 0;
            if (origin_type === "origin_expense") {
                categories = 'category_expense';
                <?php foreach ($categories_expense as $cat) { ?>
                    if ("<?php echo $cat->tipo; ?>" == type_value) {
                        $('#' + categories).prop('disabled', false);
                        $('#' + categories).append($('<option></option>').attr("value", <?php echo $cat->id; ?>).text("<?php echo $cat->name; ?>"));
                    }
                <?php } ?>
                $('#' + categories).prop('required', true);
            }
            if (origin_type === "origin_income") {
                categories = 'category_income';
                $('#' + categories).prop('required', true);
                <?php foreach ($categories_income as $cat) { ?>
                    if ("<?php echo $cat->tipo; ?>" == type_value) {
                        $('#' + categories).prop('disabled', false);
                        $('#' + categories).append($('<option></option>').attr("value", <?php echo $cat->id; ?>).text("<?php echo $cat->name; ?>"));
                    }
                <?php } ?>
            }
            if (origin_type === "origin_partner") {
                categories = 'category_partner';
                $('#' + categories).prop('required', true);
                $('#' + categories).prop('disabled', false);
                $('#' + categories).append($('<option></option>').attr("value", 1).text("Socio"));
                $('#' + categories).val('1').change();
                }
            }
            //Funcion para cambiar visibilidad dependiendo de la opcion de origin
            function change_origin(event) {
                let origin_type = event.value;
                $('#type option').each(function() {
                    $(this).remove()
                });
                $('#type').append($('<option></option>').text("Selecciona una Opcion").attr("value", ""));
                $('#category_expense option').each(function() {
                    $(this).remove()
                });
                $('#category_expense').append($('<option></option>').text("Selecciona una Opcion").attr("value", ""));
                $('#category_income option').each(function() {
                    $(this).remove()
                });
                $('#category_income').append($('<option></option>').text("Selecciona una Opcion").attr("value", ""));
                $('#category_partner option').each(function() {
                    $(this).remove()
                });
                $('#category_partner').append($('<option></option>').text("Selecciona una Opcion").attr("value", ""));
                $('#category_partner').prop('disabled', true);
                $('#category_income').prop('disabled', true);
                $('#category_expense').prop('disabled', true);
                $('#type').prop('disabled', true);
                $('#type').prop('required', true);
                type_category = "";
                //Se carga datos dependiendo de la opcion de origen de la modal
                switch (origin_type) {
                    case "origin_default":
                        type_category = "";
                        break;
                    case "origin_expense":
                        $('#type').prop('disabled', false);
                        type_category = "Egreso";
                        break;
                    case "origin_income":
                        $('#type').prop('disabled', false);
                        type_category = "Ingreso";
                        break;
                    case "origin_partner":
                        $('#type').prop('disabled', false);
                        type_category = "Socio";
                        break;
                    case "origin_debt":
                        $('#type').prop('disabled', false);
                        type_category = "Deudas";
                        break;
                    case "origin_stock":
                        $('#type').prop('disabled', false);
                        type_category = "Valores";
                        break;
                }

                if (type_category != "") {
                    <?php foreach ($types as $type) { ?>
                        if ("<?php echo $type->tipo; ?>" == type_category) {
                            $('#type').append($('<option></option>').attr("value", <?php echo $type->id; ?>).text("<?php echo $type->name; ?>"));
                        }
                    <?php } ?>
                }

            }
    </script>
<?php else : Core::redir("./");
endif; ?>
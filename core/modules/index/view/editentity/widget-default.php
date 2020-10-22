<?php
if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != "1") :
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET["id"];
    } else {
        Core::redir("./?view=entities");
    }

    $entity = EntityData::getById($id);
    $categories_expense = CategoryExpenseData::getAll($_SESSION["company_id"]);
    $categories_income = CategoryIncomeData::getAll($_SESSION["company_id"]);
    $types = TypeData::getAllType();
    if (!isset($entity) && empty($entity)) {
        Core::redir("./?view=entities");
    }
    $type_entity = EntityData::getType($entity->tipo);
    if (isset($entity->documento) && !empty($entity->documento)) {
        $img_doc = $entity->documento;
    }
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-edit"></i>Editar entidad</h1>
    </section>

        <!-- Main content -->
        <section class="content">
            <form role="form" method="post" name="upd" id="upd">
                <!-- form start -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- left column -->
                        <div class="box box-primary">
                            <!-- general form elements -->
                            <div class="box-header with-border">
                                <h3 class="box-title">Editar Entidad</h3>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="type" class="control-label">Origen: </label>
                                    <select class="form-control" name="origin" id="origin" onchange="change_origin(this);" required>
                                        <option value="origin_expense">Egresos</option>
                                        <option value="origin_income">Ingresos</option>
                                        <option value="origin_partner">Socio</option>
                                        <option value="origin_debt">Deudas</option>
                                        <option value="origin_stock">Valores</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="type" class="control-label">Tipo: </label>
                                    <select class="form-control" name="type" id="type" onchange="change_categories(this);" required disabled>
                                        <option value="">Selecciona una Opcion</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category_expense" class="control-label">Categoria de egresos: </label>
                                    <select class="form-control " name="category_expense" id="category_expense" disabled>
                                        <option value="">Selecciona una Opcion</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category_income" class="control-label">Categoria de ingreso: </label>
                                    <select class="form-control " name="category_income" id="category_income" disabled>
                                        <option value="">Selecciona una Opcion</option>

                                </select>
                            </div>

                                <div class="form-group">
                                    <label for="category_partner" class="control-label">Categoria socio: </label>
                                    <select class="form-control" name="category_partner" id="category_partner" disabled>
                                        <option value=0>Selecciona una Opcion</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name_entity" class="control-label">Nombre: </label>
                                    <input type="text" class="form-control" id="name_entity" name="name_entity" placeholder="Nombre: " value="<?php echo $entity->name; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="document_number" class="control-label">Numero de Documento </label>
                                    <input type="text" class="form-control" id="document_number" name="document_number" placeholder="Numero de Documento" value="<?php echo $entity->document_number; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="description" class="control-label">Descripción </label>
                                    <textarea type="text" class="form-control" id="description" name="description" required placeholder="Descripción "><?php echo $entity->description; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="control-label">Documento </label>
                                    <input type="file" class="form-control" accept="image/*" id="document" name="document" onchange="load_image(this);">
                                    <input type="button" class="btn btn-default" id="btn_webcam_document" name="btn_webcam_document" value="Sacar Foto" data-toggle="modal" href="#frmwebcamdocument" onclick="add_parameters_from_webcam('document')">
                                    <img src="<?php echo (isset($img_doc) ? $img_doc : "res/images/default_image.jpg"); ?>" alt="Imagen en blanco a la espera de que carga de documento" class="img-thumbnail" id="document_image" height="60" width="75" data-toggle="modal" data-target="#formModalImage" onclick="image_load(this);">

                                </div>
                                <div class="modal fade" id="formModalImage" tabindex="-1" role="dialog" aria-labelledby="ModalImage" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="ModalImage"> Imagen</h4>
                                            </div>
                                            <div class="modal-body" style="display: inline-block;">
                                                <div class="form-group">
                                                    <div class="col-md-2 col-sm-2 col-xs-12"></div>
                                                    <div class="col-md-8 col-sm-8 col-xs-12" style="display: inline-block;">
                                                        <img id="image_modal" style="width: min-content;height: min-content;" class="img-thumbnail" alt="Imagen del documento">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group">
                                                    <label class="col-md-8 col-sm-8 col-xs-12" style="color:#999; font-weight:normal;">Registrado por <?php $user_session = UserData::getById($_SESSION["user_id"]);
                                                                                                                                                        echo $user_session->name  ?> el <?php echo date("Y-m-d"); ?></label>
                                                    <span class="col-md-4 col-sm-4 col-xs-12">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="active" name="active" <?php echo isset($entity->active) && $entity->active == 1 ? "checked" : ""; ?>>
                                    <label for="active">Activo</label>
                                </div>
                                <div class="form-group">
                                    <span>
                                        <?php
                                        $lblchange_log = new lblChangeLog($entity->id, "entity");
                                        echo $lblchange_log->renderLabel();
                                        $modal_content = new Modal("Listado de Cambios", "frmentity", UserData::getById($_SESSION['user_id']));
                                        echo $modal_content->renderInit();
                                        ?>
                                    <div class="form-group table-responsive">
                                        <div id="chn_log"></div>
                                    </div>
                                    <?php echo $modal_content->renderEnd(false); ?>
                                </span>
                            </div>
                        </div> <!-- /.box -->
                        <?php
                        $webcamdocument = new Webcam('document');
                        echo $webcamdocument->renderModalImageCam();
                        ?>
                        <div id="result"></div>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <?php include "res/resources/js.php"; ?>
    <script type="text/javascript" src="res/plugins/webcam/webcam.js"></script>

    <script>
        $(function() {
            load();
            load_change_log('<?php echo $entity->id; ?>', "entity", "chn_log");
        });
        $("#upd").submit(function(event) {
            var result = false;
            var category_expense = $('#category_expense option:selected').val();
            var category_income = $('#category_income option:selected').val();
            var category_partner = $('#category_partner option:selected').val();
            var active = $('#active').is(":checked");
            var origin_type = $('#origin option:selected').val();
            var category = (category_expense > 0 || category_expense > "") ? category_expense : (category_income > 0 || category_income > "") ? category_income : category_partner;;
            if (origin_type != "origin_debt" && origin_type != "origin_stock") {
                if (category == "" || category == null || category == undefined || category == 0) {
                    $('#result').html("Mensaje: Cargando...");
                    $('#result').html(
                        "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>" +
                        "No se ha seleccionado ninguna categoría, si no tiene ninguna para seleccionar asegúrese de haber creado al menos una en la categoría correspondiente.</div>"
                    );
                    $('#save_data').attr("disabled", false);
                    return false;
                }
            } else {
                category = 0;
            }
            var parametros = new FormData($(this)[0]);
            parametros.append('category_id', category);
            parametros.append("document_image", $('#document_image').attr('src'));
            $.ajax({
                type: "POST",
                url: "./?action=updentity",
                data: parametros,
                contentType: false,
                processData: false,
                beforeSend: function(objeto) {
                    $("#result").html("Mensaje: Cargando...");
                },
                success: function(datos) {
                    $("#result").html(datos);
                    $('#upd_data').attr("disabled", false);
                    window.setTimeout(function() {
                        $(".alert").fadeTo(500, 0).slideUp(500, function() {
                            $(this).remove();
                        });
                    }, 2000);
                    result = true;
                }
            });
            event.preventDefault();
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 2000);
            result = true;
        }
    });
    event.preventDefault();
    window.setTimeout(function() {
        if (result) {
            window.location.href = "./?view=entities";
        }
        //Funcion para recargar imagen cuando se cambia de valor la imagen del documento o del pago
        function image_load(input) {
            if ((input.files && input.files[0])) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var type_image = "";
                    if (input.name == "document" || input.name == "doc_image") {
                        type_image = "doc_image";
                    }
                    if (input.name == "payment" || input.name == "pago_image") {
                        type_image = "pago_image";
                    }
                    if (type_image != "") {
                        $('#' + type_image).attr('style', 'display:block;');
                        $('#' + type_image).attr('style', 'visibility:visible;');
                        $('#' + type_image).attr('src', e.target.result);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                var type_image = "";
                if (input.name == "document" || input.name == "doc_image") {
                    type_image = "doc_image";
                }
                if (input.name == "payment" || input.name == "pago_image") {
                    type_image = "pago_image";
                }
                if (type_image != "" && input.src == "") {
                    $('#' + type_image).attr('style', 'display:none;');
                    $('#' + type_image).attr('style', 'visibility:hidden;');
                    $('#' + type_image).attr('src', "");
                    $('#image_modal').attr('style', 'visibility:hidden;');
                    $('#image_modal').attr('style', 'display:none;');
                    $('#image_modal').attr('src', "");
                } else {
                    $('#image_modal').attr('style', 'visibility:visible;');
                    $('#image_modal').attr('style', 'display:block;');
                    $('#image_modal').attr('src', input.src);
                }
            }
        }
        //Funcion para cambiar visibilidad dependiendo de la opcion de origin
        function change_origin(event) {
            var origin_type = event.value;


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

    //Se carga datos dependiendo de la opcion de origen de la modal
    if (origin_type === "origin_default") {
        $('#category_expense').prop('disabled', 'disabled');
        $('#category_income').prop('disabled', 'disabled');
        $('#category_partner').prop('disabled', 'disabled');
        $('#category_expense option[value=0]').attr("selected", "selected");
        $('#category_income option[value=0]').attr("selected", "selected");
        $('#category_partner option[value=0]').attr("selected", "selected");
    }
    if (origin_type === "origin_expense") {
        $('#type').prop('disabled', false);

        <
        ?
        php
        foreach($types as $type) {
            if (!strcmp($type - > tipo, "Egreso")) {
                ?
                >
                $('#type').append($('<option></option>').attr("value", < ? php echo $type - > id; ? > ).text(
                    "<?php echo $type->name; ?>")); <
                ?
                php
            }
        }

        foreach($categories_expense as $category) {
                if ($category - > tipo == $entity - > tipo) {
                    ?
                    >
                    $('#category_expense').append($('<option></option>').attr("value", < ? php echo $category - > id; ?
                        >
                    ).text("<?php echo $category->name; ?>")); <
                    ?
                    php
                }
            } ?
            >

    }
    if (origin_type === "origin_income") {
        $('#type').prop('disabled', false);

        <
        ?
        php
        foreach($types as $type) {
            if (!strcmp($type - > tipo, "Ingreso")) {
                ?
                >
                $('#type').append($('<option></option>').attr("value", < ? php echo $type - > id; ? > ).text(
                    "<?php echo $type->name; ?>")); <
                ?
                php
            }
        }

        foreach($categories_income as $category) {
                if ($category - > tipo == $entity - > tipo) {
                    ?
                    >
                    $('#category_income').append($('<option></option>').attr("value", < ? php echo $category - > id; ? >
                        ).text("<?php echo $category->name; ?>")); <
                    ?
                    php
                }
            } ?
            >
    }
    if (origin_type === "origin_partner") {
        $('#type').prop('disabled', false); <
        ?
        php
        foreach($types as $type) {
                if (!strcmp($type - > tipo, "Socio")) {
                    ?
                    >
                    $('#type').append($('<option></option>').attr("value", < ? php echo $type - > id; ? > ).text(
                        "<?php echo $type->name; ?>")); <
                    ?
                    php
                }
            } ?
            >

                $('#category_partner').append($('<option></option>').attr("value", 1).text("Socio"));
                $('#category_partner option: selected ').val(1);

            }
            if (origin_type === "origin_debt") {
                $('#type').prop('disabled', false);
                <?php
                foreach ($types as $type) {
                    if (!strcmp($type->tipo, "Deudas")) { ?>
                        $('#type').append($('<option></option>').attr("value", <?php echo $type->id; ?>).text("<?php echo $type->name; ?>"));
                <?php }
                }
            } ?
            >
    }
    if (origin_type === "origin_stock") {
        $('#type').prop('disabled', false); <
        ?
        php
        foreach($types as $type) {
                if (!strcmp($type - > tipo, "Valores")) {
                    ?
                    >
                    $('#type').append($('<option></option>').attr("value", < ? php echo $type - > id; ? > ).text(
                        "<?php echo $type->name; ?>")); <
                    ?
                    php
                }
            } ?
            >
    }
}

        function change_categories(event) {
            var type_value = event.value;
            var origin_type = $('#origin').val();
            var categories = '';
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
            var exists_category = 0;
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
                $('#' + categories).append($('<option></option>').attr("value", < ? php echo $cat - > id; ? > )
                    .text("<?php echo $cat->name; ?>"));
            } <
            ?
            php
        } ? >
    }
    if (origin_type === "origin_partner") {
        categories = 'category_partner';
        $('#' + categories).prop('required', true);
        $('#' + categories).prop('disabled', false);
        $('#' + categories).append($('<option></option>').attr("value", 1).text("Socio"));
    }






}
</script>
<?php else : Core::redir("./");
endif; ?>
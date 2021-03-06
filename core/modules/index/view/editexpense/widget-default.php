<?php

if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != "1") :
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET["id"];
    } else {
        Core::redir("./?view=expenses");
    }

    //query
    $expense = ExpensesData::getById($id);
    //Se obtienen datos para llenado de desplegables
    $types = TypeData::getAllExpense();
    $category = CategoryExpenseData::getAll($_SESSION["company_id"]);
    $entities = EntityData::getByType('Egreso', $_SESSION["company_id"]);

    if (!isset($expense) && empty($expense)) {
        Core::redir("./?view=expenses");
    }
    if (isset($expense->pago) && !empty($expense->pago)) {
        $img_pago = $expense->pago;
    }
    if (isset($expense->documento) && !empty($expense->documento)) {
        $img_doc = $expense->documento;
    }
?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><i class="fa fa-edit"></i>Editar Egreso</h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <!-- left column -->
                    <form role="form" method="post" name="upd" id="upd">
                        <!-- form start -->
                        <div class="box box-primary">
                            <!-- general form elements -->
                            <div class="box-header with-border">
                                <h3 class="box-title">Editar Egreso</h3>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-6">
                                        <label for="date">Fecha: </label>
                                        <input type="date" required class="form-control" id="date" name="date" placeholder="Fecha: " value="<?php echo date('Y-m-d',  strtotime($expense->fecha));  ?>">
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <label for="date">Vence: </label>
                                        <input type="date" required class="form-control" id="date_expires" name="date_expires" placeholder="Fecha " value="<?php echo date('Y-m-d',  strtotime($expense->fecha_vence));  ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="date">Numero Documento: </label>
                                        <input type="text" class="form-control" id="document_number" name="document_number" placeholder="Numero Documento" value="<?php echo $expense->document_number; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="description" class="control-label">Descripción: </label>
                                        <textarea type="text" class="form-control" id="description" name="description" placeholder="Descripción: "><?php echo $expense->description ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="amount" class="control-label">Importe: </label>
                                        <input type="text" required class="form-control" id="amount" name="amount" placeholder="Importe: " pattern="^[0-9]{1,10}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8" value="<?php echo $expense->amount ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="entidad" class="control-label">Entidad: </label>
                                        <select class="form-control  style=" width: 100%" name="entity" id="entity" onchange="change_entity('type_expense','category');">
                                            <?php
                                            //Se carga datos de entidades en modal
                                            foreach ($entities as $entity) {
                                            ?>
                                                <option <?php if ($expense->entidad == $entity->id) {
                                                            echo "selected";
                                                        } ?> value="<?php echo $entity->id; ?>"><?php echo $entity->name; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="category" class="control-label">Categoria: </label>
                                        <select class="form-control  style=" width: 100%" name="category" id="category">
                                            <?php
                                            foreach ($category as $cat) {
                                            ?>
                                                <option <?php if ($expense->category_id == $cat->id) {
                                                            echo "selected";
                                                        } ?> value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="type_expense" class="control-label">Tipo: </label>
                                        <select class="form-control  style=" width: 100%" name="type_expense" id="type_expense">
                                            <?php
                                            //Se carga datos de tipos de egreso en modal
                                            foreach ($types as $type) {
                                            ?>
                                                <option <?php if ($expense->tipo == $type->id) {
                                                            echo "selected";
                                                        } ?> value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="document" class="col-md-8 col-sm-8 col-xs-12">Documento
                                        <input type="file" class="form-control" accept="image/*" id="document" name="document" onchange="load_image(this);">
                                        <input type="button" class="btn btn-default" id="btn_webcam_document" name="btn_webcam_document" value="Sacar Foto" data-toggle="modal" href="#frmwebcamdocument" onclick="add_parameters_from_webcam('document')">
                                    </label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <img src="<?php echo (isset($img_doc) ? $img_doc : "res/images/default_image.jpg"); ?>" alt="Imagen en blanco a la espera de que carga de documento" class="img-thumbnail" id="document_image" height="60" width="75" data-toggle="modal" data-target="#formModalImage" onclick="image_load(this);">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="payment" class="col-md-8 col-sm-8 col-xs-12">Pago
                                        <input type="file" class="form-control" accept="image/*" id="payment" name="payment" onchange="load_image(this);">
                                        <input type="button" class="btn btn-default" id="btn_webcam_payment" name="btn_webcam_payment" value="Sacar Foto" data-toggle="modal" href="#frmwebcampayment" onclick="add_parameters_from_webcam('payment')">
                                    </label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <img src="<?php echo (isset($img_pago) ? $img_pago : "res/images/default_image.jpg"); ?>" alt="Imagen en blanco a la espera de que carga de documento" class="img-thumbnail" id="payment_image" height="60" width="75" data-toggle="modal" data-target="#formModalImage" onclick="image_load(this);">
                                    </div>
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
                                    <div id="div_pay_date" style="display:none;" class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="payment_date">Fecha de Pago</label>
                                        <input type="date" class="form-control" id="payment_date" style="width:100%; margin-bottom:5px;" name="payment_date" value="<?php echo isset($expense->payment_date) && !empty($expense->payment_date) && strtotime($expense->payment_date) > 0 ? $expense->payment_date : date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group justify-content-between">
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <div class="form-group">
                                                    <input type="checkbox" id="paid_out" name="paid_out" class="col-md-1 col-sm-1 col-xs-1" <?php if ($expense->pagado) {
                                                                                                                                                echo "checked";
                                                                                                                                            } ?> onchange="change_payment_status(this.checked)">
                                                    <label for="paid_out" class="col-md-2 col-sm-4 col-xs-4">Pagado</label>
                                                    <div class="col-md-9 col-sm-7 col-xs-7">
                                                        <div id="div_pay_with" style="display:none;" class="row">
                                                            <label for="pay_with" class="col-md-1 col-sm-1 col-xs-1">con</label>
                                                            <input type="text" class="col-md-9 col-sm-9 col-xs-9" style="float:right;" name="pay_with" id="pay_with" placeholder="Tipo de Pago" value="<?php echo $expense->pagado_con; ?>">
                                                            <div class="col-xs-12 pull-right" style="margin-top:5px;padding:0;">
                                                                <?php if (isset($expense->deuda_id)) { ?>
                                                                    <button type="button" class="btn btn-default show pull-right" id="btn_new_debt" name="btn_new_debt" data-toggle="modal" href="#frmdebt" onclick="change_modal_debt('disabled');"><i class="fa fa-money"></i> Ver deuda documentada</button>
                                                                <?php } else { ?>
                                                                    <button type="button" class="btn btn-default pull-right" id="btn_generate_debt" name="btn_generate_debt" data-toggle="modal" href="#frmdebt" onclick="change_modal_debt(false);"><i class="fa fa-money"></i> Generar deuda documentada</button>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>

                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <span style="float:right;">
                                                    <?php
                                                    $lblchange_log = new lblChangeLog($expense->id, "expenses");
                                                    echo $lblchange_log->renderLabel();
                                                    $modal_content = new Modal("Listado de Cambios", "frmexpenses", UserData::getById($_SESSION['user_id']));
                                                    echo $modal_content->renderInit();
                                                    ?>
                                                    <div class="form-group table-responsive">
                                                        <div id="chn_log"></div>
                                                    </div>
                                                    <?php echo $modal_content->renderEnd(false); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- mod id -->
                                <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $expense->id; ?>">
                            </div><!-- /.box-body -->
                            <div class="box-footer text-right">
                                <label style="color:#999; font-weight:normal;">Registrado por <?php $creator_user = UserData::getById($expense->user_id);
                                                                                                echo $creator_user->name  ?> el <?php echo $expense->created_at;  ?></label>
                                <span style="margin-left:10px;">
                                    <a href="./?view=expenses" class="btn btn-default">Volver</a>
                                    <button type="submit" id="upd_data" class="btn btn-success">Actualizar</button>
                                </span>
                            </div>
                        </div> <!-- /.box -->
                        <?php
                        $webcamdocument = new Webcam('document');
                        echo $webcamdocument->renderModalImageCam();
                        $webcampayment = new Webcam('payment');
                        echo $webcampayment->renderModalImageCam();
                        $modalDebt = new MdDebt('debt');
                        $modalDebt->disabled = 'disabled';
                        echo $modalDebt->renderFormulario();
                        ?>
                        <div id="result"></div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <?php include "res/resources/js.php"; ?>
    <script type="text/javascript" src="res/plugins/webcam/webcam.js"></script>

    <script type="text/javascript" src="res/plugins/common/generateDebt.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
    <script>
        $(function() {
            load_change_log('<?php echo $expense->id; ?>', "expenses", "chn_log");
            change_payment_status($('#paid_out').is(":checked"));
        });

        function change_modal_debt(disabled) {
            debugger;
            $('#debt_date').attr("disabled", disabled);
            $('#debt_document_number').attr("disabled", disabled);
            $('#debt_description').attr("disabled", disabled);
            $('#debt_amount').attr("disabled", disabled);
            $('#debt_payment_fees').attr("disabled", disabled);
            $('#debt_entity').attr("disabled", disabled);
            $('#debt_type').attr("disabled", disabled);
            $('#debt_document').attr("disabled", disabled);
            $('#debt_payment').attr("disabled", disabled);
            $('#btn_save_debt').removeClass(!disabled ? 'hidden' : 'show');
            $('#btn_save_debt').addClass(disabled ? 'hidden' : 'show');
            cargaDatosDeuda();
        }
        $("#upd").submit(function(event) {
            fd = new FormData($(this)[0]);
            var pay_out = $('#paid_out').is(":checked");
            fd.append("pay_out", pay_out);
            fd.append("document_image", $('#document_image').attr('src'));
            fd.append("payment_image", $('#payment_image').attr('src'));
            fd.append("type_expense", $('#type_expense').val());
            fd.append("category", $('#category').val());
            var result = false;
            $.ajax({
                type: "POST",
                url: "./?action=updexpense",
                data: fd,
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
                if (result) {
                    window.location.href = "./?view=expenses";
                }
            }, 2000);
        })
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
    </script>
<?php else : Core::redir("./");
endif; ?>
<?php

if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != "1") :
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET["id"];
    } else {
        Core::redir("./?view=detailexpiration");
    }
    if (isset($_GET['type']) && !empty($_GET['type'])) {
        $type = $_GET["type"];
    } else {
        Core::redir("./?view=detailexpiration");
    }
    //query
    $object;
    switch ($type) {
        case 'Ingreso':
            $object = IncomeData::getById($id);
            $payment_date = $object->payment_date;
            $pay_with = $object->pagado_con;
            $form = "income";
            break;
        case 'Egreso':
            $object = ExpensesData::getById($id);
            $payment_date = $object->payment_date;
            $pay_with = $object->pagado_con;
            $form = "expenses";
            break;
        case 'Deuda':
            $object = DebtsData::getById($id);
            $payment_date = $object->fecha_pago;
            $pay_with;
            $form = "debts";
            break;
        case 'Socio':
            $object = ResultData::getById($id);
            $payment_date = $object->payment_date;
            $pay_with = $object->pagado_con;
            $form = "result";
            break;
        default:
            Core::redir("./?view=detailexpiration");
            break;
    }
    //Se obtienen datos para llenado de desplegables

    if (!isset($object) && empty($object)) {
        Core::redir("./?view=detailexpiration");
    }
    if (isset($object->pago) && !empty($object->pago)) {
        $img_pago = $object->pago;
    }
    if (isset($object->documento) && !empty($object->documento)) {
        $img_doc = $object->documento;
    }
?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><i class="fa fa-edit"></i>Vencimiento</h1>
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
                                <h3 class="box-title">Detalle del vencimiento</h3>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="date">Fecha documento: </label>
                                        <input type="date" class="form-control" id="date" name="date" disabled value="<?php echo $object->fecha;  ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="amount" class="control-label">Importe: </label>
                                        <input type="text" disabled class="form-control" id="amount" name="amount" placeholder="Importe: " value="<?php echo $object->amount ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="entidad" class="control-label">Entidad: </label>
                                        <input type="text" disabled class="form-control" id="entity" name="entity" value="<?php echo EntityData::getById($object->entidad)->name; ?>">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="date">Numero Documento: </label>
                                        <input type="text" disabled class="form-control" id="document_number" name="document_number" value="<?php echo $object->document_number; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label for="description" class="control-label">Detalle: </label>
                                        <input type="text" disabled class="form-control" id="description" name="description" value="<?php echo $object->description ?>">
                                    </div>
                                </div>
                               

                                <div class="form-group" style="text-align: center; margin-top: 10px; margin-bottom:10px;">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label for="document" class="col-12">Imagen documento
                                        </label>
                                        <div class="col-12">
                                            <img src="<?php echo (isset($img_doc) ? $img_doc : "res/images/default_image.jpg"); ?>" alt="Imagen en blanco a la espera de que carga de documento" class="img-thumbnail" id="document_image" height="60" width="75" data-toggle="modal" data-target="#formModalImage" onclick="image_load(this);">
                                        </div>
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
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group justify-content-between">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <span>
                                                    <?php
                                                    $lblchange_log = new lblChangeLog($object->id, $form);
                                                    echo $lblchange_log->renderLabel();
                                                    $modal_content = new Modal("Listado de Cambios", "frm".$form, UserData::getById($_SESSION['user_id']));
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
                                <input type="hidden" class="form-control" id="mod_id" name="mod_id" value="<?php echo $object->id; ?>">
                            </div><!-- /.box-body -->
                            <div class="box-footer text-right">
                                <label style="color:#999; font-weight:normal;">Registrado por <?php $creator_user = UserData::getById($object->user_id);
                                                                                                echo $creator_user->name  ?> el <?php echo $object->created_at;  ?></label>
                                <span style="margin-left:10px;">
                                    <a href="javascript: history.go(-1)" class="btn btn-default">Volver</a>
                                </span>
                            </div>
                        </div> <!-- /.box -->
                        <?php
                        $webcamdocument = new Webcam('document');
                        echo $webcamdocument->renderModalImageCam();
                        $webcampayment = new Webcam('payment');
                        echo $webcampayment->renderModalImageCam();
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

    <script>
        $(function() {
            load_change_log('<?php echo $object->id; ?>', '<?php echo $form; ?>', "chn_log");

        });
    </script>
<?php else : Core::redir("./");
endif; ?>
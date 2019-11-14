<?php 

if(isset($_SESSION["user_id"]) && $_SESSION['user_id']!= "1"):
    if (isset($_GET['id']) && !empty($_GET['id'])){
        $id=$_GET["id"];
    }else{
        Core::redir("./?view=partners");
    }

    //query
    $partner=ResultData::getById($id);
//Se obtienen datos para llenado de desplegables
    $entities=EntityData::getAll($_SESSION["company_id"]);
    if(!isset($partner) && empty($partner)){
        Core::redir("./?view=partners");
    }
    if(isset($partner->pago) && !empty($partner->pago)){ 
        $img_pago = $partner->pago;
    }
    if(isset($partner->documento) && !empty($partner->documento)){ 
        $img_doc = $partner->documento;
    }
?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-edit"></i>Editar Ingreso</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6"><!-- left column -->
                <form role="form" method="post" name="upd" id="upd">
                    <div class="box box-primary"> <!-- general form elements -->
                        <div class="box-header with-border">
                            <h3 class="box-title">Editar Ingreso</h3>
                        </div><!-- /.box-header -->
                    <!-- form start -->
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="description" class="control-label">Descripción: </label>
                                    <textarea type="text" class="form-control" id="description" name="description" placeholder="Descripción: "><?php echo $partner->description ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="amount" class="control-label">Importe: </label>
                                    <input type="text" required class="form-control" id="amount" name="amount" placeholder="Importe: " pattern="^[0-9]{1,10}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="10" value="<?php echo $partner->amount ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="entidad" class="control-label">Entidad: </label>
                                    <select class="form-control select2" style="width: 100%" name="entity" id="entity" >
                                    <?php
                                        //Se carga datos de entidades en modal
                                        foreach($entities as $entity){
                                    ?>
                                        <option <?php if($partner->entidad==$entity->id){echo"selected";} ?> value="<?php echo $entity->id; ?>"><?php echo $entity->name; ?></option>
                                    <?php 
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="date">Fecha: </label>
                                    <input type="date" required class="form-control" id="date" name="date" placeholder="Fecha: " value="<?php echo $partner->fecha; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="document" class="col-md-8 col-sm-8 col-xs-12">Documento
                                    <input type="file" class="form-control" accept="image/*" id="document" name="document" onchange="load_image(this);">
                                    <input type="button" class="btn btn-default" id="btn_webcam_document" name="btn_webcam_document" value="Sacar Foto" data-toggle="modal" href="#frmwebcamdocument" onclick="add_parameters_from_webcam('document')">
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <img src="<?php echo(isset($img_doc)? $img_doc : "res/images/default_image.jpg"); ?>" alt="Imagen en blanco a la espera de que carga de documento" class="img-thumbnail" id="document_image"  height="60" width="75" data-toggle="modal" data-target="#formModalImage" onclick="image_load(this);">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payment" class="col-md-8 col-sm-8 col-xs-12">Pago
                                    <input type="file" class="form-control" accept="image/*" id="payment" name="payment" onchange="load_image(this);">
                                    <input type="button" class="btn btn-default" id="btn_webcam_payment" name="btn_webcam_payment" value="Sacar Foto" data-toggle="modal" href="#frmwebcampayment" onclick="add_parameters_from_webcam('payment')">
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <img src="<?php echo(isset($img_pago)? $img_pago : "res/images/default_image.jpg"); ?>" alt="Imagen en blanco a la espera de que carga de documento" class="img-thumbnail" id="payment_image"  height="60" width="75" data-toggle="modal" data-target="#formModalImage" onclick="image_load(this);">
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
                                                <div class="col-md-8 col-sm-8 col-xs-12"  style="display: inline-block;">
                                                    <img id="image_modal"  style="width: min-content;height: min-content;" class="img-thumbnail" alt="Imagen del documento">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="form-group">
                                                <label class="col-md-8 col-sm-8 col-xs-12" style="color:#999; font-weight:normal;">Registrado por <?php $user_session=UserData::getById($_SESSION["user_id"]); echo $user_session->name  ?> el <?php echo date("Y-m-d");?></label>
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

                                    <label for="paid_out">
                                        <input type="checkbox" id="paid_out" name="paid_out" <?php if($partner->pagado){echo "checked";} ?> > Pagado
                                    </label>
                                    <span style="float:right;">
                                        <?php 
                                            $lblchange_log = new lblChangeLog($partner->id, "result");
                                            echo $lblchange_log->renderLabel();
                                            $modal_content = new ModalCategory("Listado de Cambios","frmpartner",UserData::getById($_SESSION['user_id']));
                                            echo $modal_content->renderInit();
                                        ?>
                                            <div class="form-group">
                                                <div id="chn_log"></div>
                                            </div>
                                        <?php echo $modal_content->renderEnd(false);?>  
                                    </span>
                                </div>
                            </div>
                            <!-- mod id -->
                            <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $partner->id; ?>">
                        </div><!-- /.box-body -->
                        <div class="box-footer text-right">
                            <label style="color:#999; font-weight:normal;">Registrado por  <?php $creator_user=UserData::getById($partner->user_id); echo $creator_user->name  ?> el <?php echo $partner->created_at;  ?></label>
                            <span style="margin-left:10px;">
                            <button type="submit" id="upd_data" class="btn btn-success">Actualizar</button>
                            </span>
                        </div>
                        <div id="result"></div>
                        <?php 
                            $webcamdocument = new Webcam('document');
                            echo $webcamdocument->renderModalImageCam();
                            $webcampayment = new Webcam('payment');
                            echo $webcampayment->renderModalImageCam();
                        ?>
                    </div> <!-- /.box -->
                </div>
            </form>
        </div>
    </section>     
</div>
<!-- /.content-wrapper -->
<?php include "res/resources/js.php"; ?>
<script type="text/javascript" src="res/plugins/webcam/webcam.js"></script>
<script>
    $(function(){
        load_change_log(<?php echo $partner->id; ?>, "result", "chn_log");
    });
    $( "#upd" ).submit(function( event ) {
        $('#upd_data').attr("disabled", true);
        var fd = new FormData($(this)[0]);
        var pay_out = $('#paid_out').is(":checked");
        fd.append("pay_out",pay_out);
        fd.append("document_image", $('#document_image').attr('src'));
        fd.append("payment_image",$('#payment_image').attr('src'));

        var result = false;
        $.ajax({
            type: "POST",
            url: "./?action=updpartner",
            data: fd,
            contentType: false,
            processData: false,
             beforeSend: function(objeto){
                $("#result").html("Mensaje: Cargando...");
              },
            success: function(datos){
                $("#result").html(datos);
                $('#upd_data').attr("disabled", false);
                window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();});}, 2000);
                result = true;
            }
        });
        event.preventDefault();
        window.setTimeout(function(){
            if (result){
                window.location.href="./?view=partners";
            }
        }, 2000);
    })
    //Funcion para recargar imagen cuando se cambia de valor la imagen del documento o del pago
    function image_load(input){
        if(input != "" && input != undefined && input != null && input.src != "" && input.src != undefined && input.src != null){
            $('#image_modal').attr('style', 'visibility:visible;');
            $('#image_modal').attr('style', 'display:block;');
            $('#image_modal').attr('src', input.src);
        }
    }
</script>
<?php else: Core::redir("./"); endif;?> 
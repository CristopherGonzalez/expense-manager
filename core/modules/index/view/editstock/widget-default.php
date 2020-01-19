<?php 

if(isset($_SESSION["user_id"]) && $_SESSION['user_id']!= "1"):
    if (isset($_GET['id']) && !empty($_GET['id'])){
        $id=$_GET["id"];
    }else{
        Core::redir("./?view=stocks");
    }

    //query
    $stock=StockData::getById($id);
    //Se obtienen datos para llenado de desplegables
    $types=TypeData::getAllStocks();
    $entities=EntityData::getByType('Valores', $_SESSION["company_id"]);
    
if(!isset($stock) && empty($stock)){
        Core::redir("./?view=stocks");
    }
    if(isset($stock->pago) && !empty($stock->pago)){ 
        $img_pago = $stock->pago;
    }
    if(isset($stock->documento) && !empty($stock->documento)){ 
        $img_doc = $stock->documento;
    }
?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-edit"></i>Editar Valor</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6"><!-- left column -->
                <form role="form" method="post" name="upd" id="upd"><!-- form start -->
                    <div class="box box-primary"> <!-- general form elements -->
                        <div class="box-header with-border">
                            <h3 class="box-title">Editar Valor</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="date">Fecha: </label>
                                    <input type="date" required class="form-control" id="date" name="date" placeholder="Fecha: " value="<?php echo $stock->fecha; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="date">Numero Documento: </label>
                                    <input type="text" class="form-control" id="document_number" name="document_number" placeholder="Numero Documento" value="<?php echo $stock->document_number; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="description" class="control-label">Descripción: </label>
                                    <textarea type="text" class="form-control" id="description" name="description" placeholder="Descripción: "><?php echo $stock->description ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="amount" class="control-label">Importe: </label>
                                    <input type="text" required class="form-control" id="amount" name="amount" placeholder="Importe: " pattern="^[0-9]{1,10}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8" value="<?php echo $stock->amount ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="entidad" class="control-label">Entidad: </label>
                                    <select class="form-control  style="width: 100%" name="entity" id="entity" onchange="change_entity('type_stock','category');">
                                    <?php
                                        //Se carga datos de entidades en modal
                                        foreach($entities as $entity){
                                    ?>
                                        <option <?php if($stock->entidad==$entity->id){echo"selected";} ?> value="<?php echo $entity->id; ?>"><?php echo $entity->name; ?></option>
                                    <?php 
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="type_stock" class="control-label">Tipo: </label>
                                    <select class="form-control  style="width: 100%" name="type_stock" id="type_stock" >
                                    <?php
                                        //Se carga datos de tipos de egreso en modal
                                        $entity_stock = EntityData::getById($stock->entidad);
                                        foreach($types as $type){
                                    ?>
                                        <option <?php if($entity_stock->tipo==$type->id){echo"selected";} ?> value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
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
                                <div id="div_pay_date" style="display:none;" class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="payment_date">Fecha de Pago</label>
                                    <input type="date" class="form-control" id="payment_date" style="width:100%; margin-bottom:5px;" name="payment_date" value="<?php echo isset($stock->payment_date) && !empty($stock->payment_date) && strtotime($stock->payment_date) > 0 ? $stock->payment_date : date('Y-m-d') ;?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group justify-content-between">
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <div class="form-group">
                                                <input type="checkbox" id="paid_out" name="paid_out" class="col-md-1 col-sm-1 col-xs-1" <?php if($stock->pagado){echo "checked";} ?> onchange="change_payment_status(this.checked)"> 
                                                <label for="paid_out" class="col-md-2 col-sm-4 col-xs-4">Pagado</label>
                                                <div class="col-md-9 col-sm-7 col-xs-7">
                                                    
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <span style="float:right;">
                                                <?php 
                                                    $lblchange_log = new lblChangeLog($stock->id, "stocks");
                                                    echo $lblchange_log->renderLabel();
                                                    $modal_content = new Modal("Listado de Cambios","frmstocks",UserData::getById($_SESSION['user_id']));
                                                    echo $modal_content->renderInit();
                                                ?>
                                                    <div class="form-group table-responsive">
                                                        <div id="chn_log"></div>
                                                    </div>
                                                <?php echo $modal_content->renderEnd(false);?>  
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- mod id -->
                            <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $stock->id; ?>">
                        </div><!-- /.box-body -->
                        <div class="box-footer text-right">
                            <label style="color:#999; font-weight:normal;">Registrado por  <?php $creator_user=UserData::getById($stock->user_id); echo $creator_user->name  ?> el <?php echo $stock->created_at;  ?></label>
                            <span style="margin-left:10px;">
                                <a href="./?view=stocks" class="btn btn-default" >Volver</a>
                                <button type="submit" id="upd_data" class="btn btn-success">Actualizar</button>
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
    $(function(){
        load_change_log('<?php echo $stock->id; ?>', "stocks", "chn_log");
        change_payment_status($('#paid_out').is(":checked"));
    });
    $( "#upd" ).submit(function( event ) {
        fd = new FormData($(this)[0]);
        var pay_out = $('#paid_out').is(":checked");
        fd.append("pay_out",pay_out);
        fd.append("document_image", $('#document_image').attr('src'));
        fd.append("payment_image",$('#payment_image').attr('src'));
        fd.append("type_stock",$('#type_stock').val());
        
        var result = false;
        $.ajax({
            type: "POST",
            url: "./?action=addstock",
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
                window.location.href="./?view=stocks";
            }
        }, 2000);                                                                                                               
    })
    //Funcion para recargar imagen cuando se cambia de valor la imagen del documento o del pago
    function image_load(input){
        if((input.files && input.files[0])){
            var reader = new FileReader();
            reader.onload = function(e) {
                var type_image = "";
                if(input.name == "document" || input.name == "doc_image"){
                    type_image = "doc_image";
                }
                if(input.name == "payment" || input.name == "pago_image"){
                    type_image = "pago_image";
                }
                if(type_image!=""){
                    $('#'+type_image).attr('style', 'display:block;');
                    $('#'+type_image).attr('style', 'visibility:visible;');
                    $('#'+type_image).attr('src', e.target.result);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            var type_image = "";
            if(input.name == "document" || input.name == "doc_image"){
                type_image = "doc_image";
            }
            if(input.name == "payment" || input.name == "pago_image"){
                type_image = "pago_image";
            }
            if(type_image!="" && input.src==""){
                $('#'+type_image).attr('style', 'display:none;');
                $('#'+type_image).attr('style', 'visibility:hidden;');
                $('#'+type_image).attr('src', "");
                $('#image_modal').attr('style', 'visibility:hidden;');
                $('#image_modal').attr('style', 'display:none;');
                $('#image_modal').attr('src', "");
            }else{
                $('#image_modal').attr('style', 'visibility:visible;');
                $('#image_modal').attr('style', 'display:block;');
                $('#image_modal').attr('src', input.src);
            }
        }
    }
</script>
<?php else: Core::redir("./"); endif;?> 
<?php 

if(isset($_SESSION["user_id"]) && $_SESSION['user_id']!= "1"):
    if (isset($_GET['id']) && !empty($_GET['id'])){
        $id=$_GET["id"];
    }else{
        Core::redir("./?view=expenses");
    }

    //query
    $expense=ExpensesData::getById($id);
    //Se obtienen datos para llenado de desplegables
    $types=TypeData::getAllExpense();
    $category=CategoryExpenseData::getAll($_SESSION["company_id"]);
    $entities=EntityData::getAll($_SESSION["company_id"]);
if(!isset($expense) && empty($expense)){
        Core::redir("./?view=expenses");
    }
    if(isset($expense->pago) && !empty($expense->pago)){ 
        $img_pago = "data:image/jpeg;base64,".base64_encode($expense->pago);
    }
    if(isset($expense->documento) && !empty($expense->documento)){ 
        $img_doc = "data:image/jpeg;base64,".base64_encode($expense->documento);
    }
?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-edit"></i>Editar Gasto</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6"><!-- left column -->
                <div class="box box-primary"> <!-- general form elements -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Gasto</h3>
                    </div><!-- /.box-header -->
                    <form role="form" method="post" name="upd" id="upd"><!-- form start -->
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="description" class="control-label">Descripción: </label>
                                    <textarea type="text" class="form-control" id="description" name="description" placeholder="Descripción: "><?php echo $expense->description ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="amount" class="control-label">Importe: </label>
                                    <input type="text" required class="form-control" id="amount" name="amount" placeholder="Importe: " pattern="^[0-9]{1,5}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8" value="<?php echo $expense->amount ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="category" class="control-label">Categoria: </label>
                                    <select class="form-control select2" style="width: 100%" name="category" id="category" >
                                    <?php
                                        foreach($category as $cat){
                                    ?>
                                        <option <?php if($expense->category_id==$cat->id){echo"selected";} ?> value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                                    <?php 
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="type_expense" class="control-label">Tipo: </label>
                                    <select class="form-control select2" style="width: 100%" name="type_expense" id="type_expense" >
                                    <?php
                                        //Se carga datos de tipos de gasto en modal
                                        foreach($types as $type){
                                    ?>
                                        <option <?php if($expense->tipo==$type->id){echo"selected";} ?> value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                                    <?php 
                                        }
                                    ?>
                                    </select>
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
                                        <option <?php if($expense->entidad==$entity->id){echo"selected";} ?> value="<?php echo $entity->id; ?>"><?php echo $entity->name; ?></option>
                                    <?php 
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="date">Fecha: </label>
                                    <input type="date" required class="form-control" id="date" name="date" placeholder="Fecha: " value="<?php echo $expense->fecha; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <label for="document">Documento:
                                        <input type="file" class="form-control" accept="image/*" id="document" name="document" onchange="load_image(this)">
                                    </label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <img src="<?php echo(isset($img_doc)? $img_doc : "#"); ?>" style="<?php echo(!isset($img_doc)? "visibility:hidden;display:none;" : "#"); ?>" id="doc_image" height="60" width="75" class="img-thumbnail" alt="Imagen del documento"  data-toggle="modal" data-target="#formModalImage" onclick="load_image(this);">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <label for="payment">Pago:
                                        <input type="file" class="form-control" accept="image/*" id="payment" name="payment" onchange="load_image(this)">
                                    </label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <img src="<?php echo(isset($img_pago)? $img_pago : "#"); ?>" style="<?php echo(!isset($img_pago)? "visibility:hidden;display:none;" : "#"); ?>" id="pago_image" height="60" width="75" class="img-thumbnail" alt="Imagen del Pago" data-toggle="modal" data-target="#formModalImage" onclick="load_image(this);">
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
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <label for="paid_out">
                                        <input type="checkbox" id="paid_out" name="paid_out" <?php if($expense->pagado){echo "checked";} ?> > Pagado
                                    </label>
                                </div>
                            </div>
                             <!-- mod id -->
                            <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $expense->id; ?>">
                        </div><!-- /.box-body -->
                        <div class="box-footer text-right">
                            <label style="color:#999; font-weight:normal;">Registrado por  <?php $creator_user=UserData::getById($expense->user_id); echo $creator_user->name  ?> el <?php echo $expense->created_at;  ?></label>
                            <span style="margin-left:10px;">
                                <button type="submit" id="upd_data" class="btn btn-success">Actualizar</button>
                            </span>
                        </div>
                    </form>
                </div> <!-- /.box -->
                <div id="result"></div>
            </div>
        </div>
    </section>     
</div>
<!-- /.content-wrapper -->
<?php include "res/resources/js.php"; ?>
<script>
    $( "#upd" ).submit(function( event ) {
        fd = new FormData($(this)[0]);
        var pay_out = $('#paid_out').is(":checked");
        fd.append("pay_out",pay_out);
        var result = false;
        $.ajax({
            type: "POST",
            url: "./?action=updexpense",
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
                window.location.href="./?view=expenses";
            }
        }, 2000);                                                                                                               
    })
    //Funcion para recargar imagen cuando se cambia de valor la imagen del documento o del pago
    function load_image(input){
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
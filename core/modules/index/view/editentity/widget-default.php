<?php 
/*-------------------------
    Autor: Amner Saucedo Sosa
    Web: www.abisoftgt.net
    E-Mail: waptoing7@gmail.com
---------------------------*/
if(isset($_SESSION["user_id"])):
    if (isset($_GET['id']) && !empty($_GET['id'])){
        $id=$_GET["id"];
    }else{
        Core::redir("./?view=entities");
    }

    //query
    $expense=ExpensesData::getById($id);
    //Se obtienen datos para llenado de desplegables
    $types=TypeData::getAllExpense();
    $category=CategoryExpenseData::getAll($_SESSION["user_id"]);
    $entities=EntityData::getAll($_SESSION["user_id"]);
    if(!isset($expense) && empty($expense)){
        //Core::redir("./?view=expenses");
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


                                <form class="form-horizontal" role="form" method="post" id="add_register" name="add_register" enctype="multipart/form-data"> 
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="type" class="col-sm-4 control-label">Origen: </label>
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
                                            <label for="category_expense" class="col-sm-4 control-label">Categoria de gastos: </label>
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
                                            <label for="category_income" class="col-sm-4 control-label">Categoria de ingreso: </label>
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
                                            <label for="category_partner" class="col-sm-4 control-label">Categoria socio: </label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" style="width: 100%" name="category_partner" id="category_partner" disabled>
                                                    <option value=0>---SELECCIONA---</option>
                                                    <option value=1>Socio</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="type" class="col-sm-4 control-label">Tipo: </label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" style="width: 100%" name="type" id="type" required disabled>
                                                    <option value=0>---SELECCIONA---</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="name_entity" class="col-sm-4 control-label">Nombre: </label>
                                            <div class="col-sm-8">
                                                <textarea type="text" class="form-control" id="name_entity" name="name_entity" placeholder="Nombre: "></textarea>
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

                    <form role="form" method="post" name="upd" id="upd"><!-- form start -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="description" class="control-label">Descripción: </label>
                                <textarea type="text" class="form-control" id="description" name="description" placeholder="Descripción: "><?php echo $expense->description ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="amount" class="control-label">Importe: </label>
                                <input type="text" required class="form-control" id="amount" name="amount" placeholder="Importe: " pattern="^[0-9]{1,5}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8" value="<?php echo $expense->amount ?>">

                            </div>
                            <div class="form-group">
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

                            <div class="form-group">
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
                            <div class="form-group">
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
                            <div class="form-group">
                                <label for="date">Fecha: </label>
                                <input type="date" required class="form-control" id="date" name="date" placeholder="Fecha: " value="<?php echo $expense->fecha; ?>">
                            </div>
                            <div class="form-group">
                                <label for="document">Documento:
                                    <input type="file" class="form-control" accept="image/*" id="document" name="document" onchange="load_image(this)">
                                </label>
                                <a href="<?php echo(isset($img_doc)? $img_doc : "#"); ?>" id="doc_download" download="documento">
                                    <img src="<?php echo(isset($img_doc)? $img_doc : "#"); ?>" style="<?php echo(!isset($img_doc)? "visibility:hidden;" : "#"); ?>" id="doc_image" height="60" width="75" class="img-thumbnail" alt="Imagen del documento">
                                </a>
                            </div>
                            <div class="form-group">
                                <label for="payment">Pago:
                                    <input type="file" class="form-control" accept="image/*" id="payment" name="payment" onchange="load_image(this)">
                                </label>
                                <a href="<?php echo(isset($img_pago)? $img_pago : "#"); ?>" id="pago_download" download="pago">
                                    <img src="<?php echo(isset($img_pago)? $img_pago : "#"); ?>" style="<?php echo(!isset($img_pago)? "visibility:hidden;" : "#"); ?>" id="pago_image" height="60" width="75" class="img-thumbnail" alt="Imagen del Pago">
                                </a>
                            </div>
                            <div class="form-group">
                                <label for="paid_out">
                                    <input type="checkbox" id="paid_out" name="paid_out" <?php if($expense->pagado){echo "checked";} ?> > Pagado
                                </label>
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
                window.location.href="./?view=entities";
            }
        }, 2000);                                                                                                               
    })
    
</script>
<?php else: Core::redir("./"); endif;?> 
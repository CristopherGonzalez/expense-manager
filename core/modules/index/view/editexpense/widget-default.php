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
        Core::redir("./?view=expenses");
    }

    //query
    $expense=ExpensesData::getById($id);
    //Se obtienen datos para llenado de desplegables
    $types=TypeData::getAllExpense();
    $category=CategoryExpenseData::getAll($_SESSION["user_id"]);
    $entities=EntityData::getAll($_SESSION["user_id"]);

    if(!isset($expense)){
        Core::redir("./?view=expenses");
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
                <div id="result"></div>
                <div class="box box-primary"> <!-- general form elements -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Gasto</h3>
                    </div><!-- /.box-header -->
                    <form role="form" method="post" name="upd" id="upd"><!-- form start -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="description" class="control-label">Descripción: </label>
                                <textarea type="text" class="form-control" id="description" name="description" placeholder="Descripción: "><?php echo $expense->description ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="amount" class="control-label">Cantidad: </label>
                                <input type="text" required class="form-control" id="amount" name="amount" placeholder="Cantidad: " pattern="^[0-9]{1,5}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8" value="<?php echo $expense->amount ?>">

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
                                <label for="type_expense" class="col-sm-2 control-label">Tipo: </label>
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
                                <label for="entidad" class="col-sm-2 control-label">Entidad: </label>
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
                                <span class="col-md-2 col-sm-2 col-xs-12"></span>
                                <label for="document" class="col-sm-4">Documento:
                                    <input type="file" class="form-control" accept="image/*" id="document" name="document">
                                </label>
                                <label for="payment" class="col-sm-4">Pago:
                                    <input type="file" class="form-control" accept="image/*" id="payment" name="payment">
                                </label>
                                <label for="paid_out" class="col-sm-2">
                                    <input type="checkbox" id="paid_out" name="paid_out" <?php if($expense->pagado){echo "checked";} ?> > Pagado
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="date" class="control-label">Fecha: </label>
                                <input type="date" required class="form-control" id="date" name="date" placeholder="Fecha: " value="<?php echo $expense->created_at; ?>">
                            </div>
                            <!-- mod id -->
                            <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $expense->id; ?>">
                        </div><!-- /.box-body -->
                        <div class="box-footer text-right">
                            <button type="submit" id="upd_data" class="btn btn-success">Actualizar</button>
                        </div>
                    </form>
                </div> <!-- /.box -->
            </div>
        </div>
    </section>     
</div>
<!-- /.content-wrapper -->
<?php include "res/resources/js.php"; ?>
<script>
    $( "#upd" ).submit(function( event ) {
      $('#upd_data').attr("disabled", true);
      
     var parametros = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "./?action=updexpense",
            data: parametros,
             beforeSend: function(objeto){
                $("#result").html("Mensaje: Cargando...");
              },
            success: function(datos){
            $("#result").html(datos);
            $('#upd_data').attr("disabled", false);
            window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();});}, 2000);
            }
        });
      event.preventDefault();
    })
</script>
<?php else: Core::redir("./"); endif;?> 
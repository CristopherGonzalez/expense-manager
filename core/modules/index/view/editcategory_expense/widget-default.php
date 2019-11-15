<?php 

if(isset($_SESSION["user_id"]) && $_SESSION['user_id']!= "1"):
    if (isset($_GET['id']) && !empty($_GET['id'])){
        $id=$_GET["id"];
    }else{
        Core::redir("./?view=category_expense");
    }

    //query
    $category_expense=CategoryExpenseData::getById($id);

    if(!isset($category_expense)){
        Core::redir("./?view=category_expense");
    }

?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-edit"></i>Editar Categoria de Egreso</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6"><!-- left column -->
                <div class="box box-primary"> <!-- general form elements -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Categoria de Egreso</h3>
                    </div><!-- /.box-header -->
                    <form role="form" method="post" name="upd" id="upd"><!-- form start -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name" class="control-label">Nombre: </label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Nombre: " value="<?php echo $category_expense->name; ?>">
                            </div>
                            <!-- Se agrega nueva opcion de egreso-->
                            <div class="form-group">
                                <label for="egreso" class="control-label">Egreso: </label>
                                    <select class="form-control" name="egreso" id="egreso" >
                                        <?php
                                            $egreso=TypeData::getAllExpense();
                                            foreach($egreso as $cat){
                                        ?>
                                            <option value="<?php echo $cat->id; ?>" <?php if($cat->id == $category_expense->tipo) echo "selected"; ?>  ><?php echo $cat->name; ?></option>
                                        <?php 
                                            }
                                        ?>
                                    </select>
                                        </div>
                                    </div>
                            <!-- mod id -->
                            <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $category_expense->id; ?>">
                        </div><!-- /.box-body -->
                        <div class="box-footer text-right">
                            <label style="color:#999; font-weight:normal;">Registrado por  <?php $creator_user=UserData::getById($category_expense->user_id); echo $creator_user->name  ?> el <?php echo date("Y-d-m",strtotime($category_expense->created_at));  ?></label>
                            <button type="submit" id="upd_data" class="btn btn-success">Actualizar</button>
                        </div>
                    </form>
                    <div id="result"></div>
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
     var result = false;
        $.ajax({
            type: "POST",
            url: "./?action=updcategory_expense",
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
            result = true;
            }
        });
        event.preventDefault();
        window.setTimeout(function(){
            if (result){
                window.location.href="./?view=category_expense";
            }
        }, 2000);
    })
</script>
<?php else: Core::redir("./"); endif;?> 
<?php 
if(isset($_SESSION["user_id"])):
    if (isset($_GET['id']) && !empty($_GET['id'])){
        $id=$_GET["id"];
    }else{
        Core::redir("./?view=entities");
    }

    $entity=EntityData::getById($id);
    //Se obtienen datos para llenado de desplegables
    $categories_expense=CategoryExpenseData::getAll($_SESSION["user_id"]);
    $categories_income=CategoryIncomeData::getAll($_SESSION["user_id"]);
    $types=TypeData::getAllType();
    if(!isset($entity) && empty($entity)){
        Core::redir("./?view=entities");
    }
    $type_entity = EntityData::getType($entity->tipo);
?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-edit"></i>Editar entidad</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6"><!-- left column -->
                <div class="box box-primary"> <!-- general form elements -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Editar Entidad</h3>
                    </div><!-- /.box-header -->
                    <form role="form" method="post" name="upd" id="upd"><!-- form start -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="type" class="control-label">Origen: </label>
                                <select class="form-control" name="origin" id="origin" onchange="change_origin(this);" required>
                                    <option value="origin_expense">Egresos</option>
                                    <option value="origin_income">Ingresos</option>
                                    <option value="origin_partner">Socio</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category_expense" class="control-label">Categoria de gastos: </label>
                                <select class="form-control " name="category_expense" id="category_expense" disabled>
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
                            <div class="form-group">
                                <label for="category_income" class="control-label">Categoria de ingreso: </label>
                                <select class="form-control " name="category_income" id="category_income" disabled>
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

                            <div class="form-group">
                                <label for="category_partner" class="control-label">Categoria socio: </label>
                                <select class="form-control" name="category_partner" id="category_partner" disabled>
                                    <option value=0>---SELECCIONA---</option>
                                    <option value=1>Socio</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type" class="control-label">Tipo: </label>
                                <select class="form-control" name="type" id="type" required disabled>
                                    <option value=0>---SELECCIONA---</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name_entity" class="control-label">Nombre: </label>
                                <input type="text" class="form-control" id="name_entity" name="name_entity" placeholder="Nombre: " value="<?php echo $entity->name; ?>">
                            </div>
                             <!-- mod id -->
                            <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $entity->id; ?>">
                        </div><!-- /.box-body -->
                        <div class="box-footer text-right">
                            <label style="color:#999; font-weight:normal;">Registrado por  <?php $creator_user=UserData::getById($entity->user_id); echo $creator_user->name  ?> el <?php echo $entity->created_at;  ?></label>
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
    $(function() {
        load();
    });
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
    function load(){
        var origin_type = "<?php echo $type_entity->tipo;?>";
        if (origin_type == null || origin_type == undefined || origin_type.length == 0) { window.location='./?view=entities';};
        if (origin_type == "Gasto"){ $('#origin option[value=origin_expense]').attr("selected","selected"); }
        if (origin_type == "Ingreso"){ $('#origin option[value=origin_income]').attr("selected","selected"); }
        if (origin_type == "Socio"){ $('#origin option[value=origin_partner]').attr("selected","selected"); }
        change_origin($('#origin')[0]);
        if (origin_type == "Gasto"){ $('#category_expense option[value=<?php echo $entity->category_id ?>]').attr("selected","selected"); }
        if (origin_type == "Ingreso"){ $('#category_income option[value=<?php echo $entity->category_id ?>]').attr("selected","selected"); }
        if (origin_type == "Socio") {$('#category_partner option[value=1]').attr("selected","selected");}
        $('#type option[value=<?php echo $entity->tipo ?>]').attr("selected","selected"); 
    }
    //Funcion para cambiar visibilidad dependiendo de la opcion de origin
    function change_origin(event){
        var origin_type = event.value;
        $('#type option').each(function(){ $(this).remove()});
        $('#type').prop('disabled', 'disabled');
        $('#type').append($('<option></option>').text("---SELECCIONA---").attr("value",0));
        //Se carga datos dependiendo de la opcion de origen de la modal
        if (origin_type === "origin_default"){
            $('#category_expense').prop('disabled', 'disabled');
            $('#category_income').prop('disabled', 'disabled');
            $('#category_partner').prop('disabled', 'disabled');
            $('#category_expense option[value=0]').attr("selected","selected");
            $('#category_income option[value=0]').attr("selected","selected");
            $('#category_partner option[value=0]').attr("selected","selected");
         }
        if (origin_type === "origin_expense"){
            $('#category_expense').prop('disabled', false);
            $('#category_income').prop('disabled', 'disabled');
            $('#category_partner').prop('disabled', 'disabled');
            $('#category_income option[value=0]').attr("selected","selected");
            $('#category_partner option[value=0]').attr("selected","selected");
            $('#type').prop('disabled', false);
            <?php 
            foreach($types as $type){ 
                if(!strcmp($type->tipo,"Gasto")){
            ?>
                $('#type').append($('<option></option>').attr("value",<?php echo $type->id; ?>).text("<?php echo $type->name; ?>"));
            <?php 
                }
            }?>
        }
        if (origin_type === "origin_income"){
            $('#category_expense').prop('disabled', 'disabled');
            $('#category_income').prop('disabled', false);
            $('#category_partner').prop('disabled', 'disabled');
            $('#category_expense option[value=0]').attr("selected","selected");
            $('#category_partner option[value=0]').attr("selected","selected");
            $('#type').prop('disabled', false);
            <?php 
            foreach($types as $type){ 
                if(!strcmp($type->tipo,"Ingreso")){
            ?>
                $('#type').append($('<option></option>').attr("value",<?php echo $type->id; ?>).text("<?php echo $type->name; ?>"));
            <?php 
                }
            }?>
        }
        if (origin_type === "origin_partner"){
            $('#category_expense').prop('disabled', 'disabled');
            $('#category_income').prop('disabled', 'disabled');
            $('#category_expense option[value=0]').attr("selected","selected");
            $('#category_income option[value=0]').attr("selected","selected");
            $('#category_partner').prop('disabled', false);
            $('#type').prop('disabled', false);
            <?php 
            foreach($types as $type){ 
                if(!strcmp($type->tipo,"Socio")){
            ?>
                $('#type').append($('<option></option>').attr("value",<?php echo $type->id; ?>).text("<?php echo $type->name; ?>"));
            <?php 
                }
            }?>
        }
    }

</script>
<?php else: Core::redir("./"); endif;?> 
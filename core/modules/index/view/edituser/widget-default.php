<?php

if (isset($_SESSION["user_id"])) :
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET["id"];
    } else {
        Core::redir("./?view=users");
    }

    //query
    $user = UserData::getById($id);

    if (!isset($user)) {
        Core::redir("./?view=users");
    }

?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><i class="fa fa-edit"></i>Editar Usuario</h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <!-- left column -->
                    <div class="box box-primary">
                        <!-- general form elements -->
                        <div class="box-header with-border">
                            <h3 class="box-title">Editar Usuario</h3>
                        </div><!-- /.box-header -->
                        <form role="form" method="post" name="upd" id="upd">
                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nombre</label>
                                    <input type="text" required class="form-control" id="name" name="name" placeholder="Nombre: " value="<?php echo $user->name; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="email" required class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $user->email; ?>">
                                </div>
                                <div class="form-group hidden">
                                    <label for="password" class="control-label">Contraseña</label>
                                    <input type="hidden" required class="form-control" id="password" name="password" placeholder="Contraseña" value="<?php echo $user->password; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="disabled" class="control-label">
                                        <input type="checkbox" id="disabled" name="disabled" <?php if ($user->status != 3) {
                                                                                                    echo "checked";
                                                                                                } ?>> Inhabilitar
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="is_admin" class="control-label">
                                        <input type="checkbox" id="is_admin" name="is_admin" <?php if ($user->is_admin == "1") {
                                                                                                    echo "checked";
                                                                                                } ?>> Administrador
                                    </label>
                                </div>
                                <!-- mod id -->
                                <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $user->id; ?>">
                            </div><!-- /.box-body -->
                            <div class="box-footer text-right">
                                <label style="color:#999; font-weight:normal;">Registrado por <?php echo $user->name  ?> el <?php echo date("Y-d-m", strtotime($user->created_at));  ?></label>
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
        $("#upd").submit(function(event) {
            $('#upd_data').attr("disabled", true);

            var parametros = $(this).serialize();
            var disabled = $('#disabled').is(":checked");
            parametros += "&disabled=" + disabled;
            var is_admin = $('#is_admin').is(":checked");
            parametros += "&is_admin=" + is_admin;
            var result = false;
            $.ajax({
                type: "POST",
                url: "./?action=upduser",
                data: parametros,
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
                    window.location.href = "./?view=users";
                }
            }, 2000);
        })
    </script>
<?php else : Core::redir("./");
endif; ?>
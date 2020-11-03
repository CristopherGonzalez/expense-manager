<?php

if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != "1") :
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET["id"];
    } else {
        Core::redir("./?view=task");
    }

    //query
    $task = TaskData::getById($id);

    if (!isset($task)) {
        Core::redir("./?view=task");
    }

?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><i class="fa fa-edit"></i>Editar Tarea</h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <form role="form" method="post" name="upd" id="upd">

                <div class="row">
                    <div class="col-md-8">
                        <!-- left column -->
                        <div class="box box-primary">
                            <!-- general form elements -->
                            <div class="box-header with-border">
                                <h3 class="box-title">Editar Tarea</h3>
                            </div><!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tarea </label>
                                    <textarea type="text" class="form-control" required id="task_name" name="task_name" placeholder="Descripción de la Tarea"><?php echo $task->tarea; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="task_done" name="task_done" value="task_done" <?php if ($task->hecho == true) {
                                                                                                                    echo "checked";
                                                                                                                }  ?>> Hecho
                                </div>
                            </div>
                            <!-- mod id -->
                            <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $task->id; ?>">
                        </div><!-- /.box-body -->
                        <div class="box-footer text-right">
                            <label style="color:#999; font-weight:normal;">Registrado por <?php $creator_user = UserData::getById($task->user_id);
                                                                                            echo $creator_user->name  ?></label>
                            <a href="./?view=task" class="btn btn-default">Volver</a>
                            <button type="submit" id="upd_data" class="btn btn-success">Actualizar</button>
                        </div>
                        <div id="result"></div>
                    </div> <!-- /.box -->
                </div>
    </div>
    </form>

    </section>
    </div>
    <!-- /.content-wrapper -->
    <?php include "res/resources/js.php"; ?>
    <script>
        $("#upd").submit(function(event) {
            $('#upd_data').attr("disabled", true);
            let mod_id = $("#mod_id").val();
            let task_name = $("#task_name").val();
            let task_done = $('#task_done').is(":checked");
            let parametros = {
                "mod_id": mod_id,
                "task_name": task_name,
                'task_done': task_done
            };
            let result = false;
            $.ajax({
                type: "POST",
                url: "./?action=updtask",
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
                    window.location.href = "./?view=task";
                }
            }, 2000);
        })
    </script>
<?php else : Core::redir("./");
endif; ?>
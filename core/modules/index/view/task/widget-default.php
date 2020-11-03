<?php

if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != "1") :
?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Tareas</h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="form-group">

                    <!--  Se agrega opcion para buscar por egresos-->
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Tarea" name="find_task" id='find_task' onkeyup="load(1);">
                    </div>
                    <div class="col-md-1">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
                        </span>
                    </div>
                    <div class="col-md-3 form-group">
                        <input type="checkbox" id="only_pendings" name="only_pendings" onchange="load(1);">
                        <label for="only_pendings">Solo Pendientes</label>
                    </div>

                    <div class="col-xs-1">
                        <div id="loader" class="text-center"></div>
                    </div>
                    <!-- <div class="col-md-offset-10"> -->
                    <div class=" pull-right">
                        <button class='btn btn-primary' data-toggle='modal' data-target='#formModal'><i class='fa fa-plus'></i> Nuevo</button>
                        <!-- Form Modal -->
                        <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- form  -->
                                    <form class="form-horizontal" role="form" method="post" id="add_task" name="add_task" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel"> Nueva Tarea</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="description" class="col-sm-2 control-label">Tarea </label>
                                                <div class="col-sm-10">
                                                    <textarea type="text" class="form-control" required id="task_name" name="task_name" placeholder="Descripción de la Tarea"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="description" class="col-sm-2 control-label"></label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" id="task_done" name="task_done" value="task_done"> Hecho
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <div class="form-group">
                                                <span class="col-md-1 col-sm-1 col-xs-12"></span>
                                                <label class="col-md-7 col-sm-7" style="color:#999; font-weight:normal;">Registrado por <?php $user_session = UserData::getById($_SESSION["user_id"]);
                                                                                                                                        echo $user_session->name  ?> el <?php echo date("Y-m-d");  ?></label>
                                                <span class="col-md-4 col-sm-4 col-xs-12">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" id="save_data" class="btn btn-primary">Agregar</button>
                                                </span>
                                            </div>
                                        </div>
                                </div>
                                </form>
                                <!-- /end form  -->
                            </div>
                        </div>
                        <!-- End Form Modal -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                Mostrar <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li class='active' onclick='per_page(15);' id='15'><a href="#">15</a></li>
                                <li onclick='per_page(25);' id='25'><a href="#">25</a></li>
                                <li onclick='per_page(50);' id='50'><a href="#">50</a></li>
                                <li onclick='per_page(100);' id='100'><a href="#">100</a></li>
                                <li onclick='per_page(1000000);' id='1000000'><a href="#">Todos</a></li>
                            </ul>
                        </div>
                        <input type='hidden' id='per_page' value='15'>
                    </div>
                </div>
                <!-- <div class="col-xs-3"></div> -->
            </div>
            <br>
            <div id="resultados_ajax"></div><!-- Resultados Ajax -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Historial Categorias de Tareas</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <div class="outer_div"></div><!-- Datos ajax Final -->
                </div>
                <!-- /.box-body -->
            </div><!-- /.box -->
        </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include "res/resources/js.php"; ?>

    <script>
        $(function() {
            load(1);
        });

        function load(page) {
            //Se cambia para hacer busqueda por egreso y/o nombre
            let name_task = $("#find_task").val();
            let only_pendings = $('#only_pendings').is(":checked");
            let per_page = $("#per_page").val();
            let parametros = {
                "page": page,
                'name_task': name_task,
                'only_pendings': only_pendings,
                'per_page': per_page
            };
            $.get({
                url: "./?action=load_task",
                data: parametros,
                beforeSend: function(data) {
                    $("#loader").html("<img src='res/images/ajax-loader.gif'>");
                },
                //console.log(data);
                success: function(data) {
                    $(".outer_div").html(data);
                    $("#loader").html("");
                }

            });
        }

        function per_page(valor) {
            $("#per_page").val(valor);
            load(1);
            $('.dropdown-menu li').removeClass("active");
            $("#" + valor).addClass("active");
        }

        function eliminar(id) {
            if (confirm('Esta acción  eliminará de forma permanente la tarea \n\n Desea continuar?')) {
                let page = 1;
                //Se cambia para mantener estandar de envio de parametros
                //Se cambia para hacer busqueda por egreso y/o nombre
                let name_task = $("#find_task").val();
                let only_pendings = $('#only_pendings').is(":checked");
                let per_page = $("#per_page").val();
                let parametros = {
                    "page": page,
                    'name_task': name_task,
                    'only_pendings': only_pendings,
                    'per_page': per_page,
                    'id': id

                };

                $.get({
                    // method: "GET",
                    url: './?action=load_task',
                    data: parametros,
                    beforeSend: function(objeto) {
                        $("#loader").html("<img src='res/images/ajax-loader.gif'>");
                    },
                    success: function(data) {
                        $(".outer_div").html(data).fadeIn('slow');
                        $("#loader").html("");
                        debugger;
                        window.setTimeout(function() {
                            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                                $(this).remove();
                            });
                        }, 5000);
                    }
                })
            }
        }
        $("#add_task").submit(function(event) {
            $('#save_data').attr("disabled", true);

            let task_name = $("#task_name").val();
            let task_done = $('#task_done').is(":checked");
            let parametros = {
                "task_name": task_name,
                'task_done': task_done
            };
            $.ajax({
                type: "POST",
                url: "./?action=addtask",
                data: parametros,
                beforeSend: function(objeto) {
                    $("#resultados_ajax").html("Enviando...");
                },
                success: function(datos) {
                    $("#resultados_ajax").html(datos);
                    $('#save_data').attr("disabled", false);
                    debugger;
                    load(1);
                    window.setTimeout(function() {
                        $(".alert").fadeTo(500, 0).slideUp(500, function() {
                            $(this).remove();
                        });
                    }, 5000);
                    $('#formModal').modal('hide');
                    clear_modal('add_task');
                }
            });
            event.preventDefault();
        })
    </script>

<?php else : Core::redir("./");
endif; ?>
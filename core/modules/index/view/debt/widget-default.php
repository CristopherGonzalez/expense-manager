<?php
if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != "1") :
?>
    <?php
    //Se obtienen datos para llenado de desplegables
    $entities = EntityData::getByType('Deudas', $_SESSION["company_id"]);
    $types = TypeData::getAllDebts();
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Deudas</h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <!-- Se agregan nuevos filtros de mes, año, tipo de Deuda-->
                        <div class="col-md-3 form-group">
                            <select name="month_find" id="month_find" class="form-control" style="width: 100%;" onchange="load(1);">
                                <option value="0">Mes</option>
                                <?php
                                //Se crean opciones de meses y se selecciona el actual por defecto
                                $months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                foreach ($months as $index => $month) {
                                ?>
                                    <option value="<?php echo $index + 1; ?>" <?php if (($index + 1) == date("n")) echo "selected"; ?>><?php echo $month; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <select name="year_find" id="year_find" class="form-control" style="width: 100%;" onchange="load(1);">
                                <option value="0">Año</option>
                                <?php
                                //Se crean opciones de años y se selecciona el actual por defecto
                                $years = array(2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025);
                                foreach ($years as $year) {
                                ?>
                                    <option value="<?php echo $year; ?>" <?php if ($year == date("Y")) echo "selected"; ?>><?php echo $year; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <select name="type_debt_find" id="type_debt_find" class="form-control" style="width: 100%;" onchange="load(1);">
                                <option value="0">Tipo de Deuda</option>
                                <?php
                                //Se carga con tipos de Deudas
                                foreach ($types as $type_debt) {
                                ?>
                                    <option value="<?php echo $type_debt->id; ?>"><?php echo $type_debt->name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>


                        <div class="col-md-5 form-group">
                            <input type="text" class="form-control" name="find_text" id="find_text" style="width: 100%;" placeholder="Buscar en texto" title="Ingresa algun texto para realizar la busqueda" onkeyup="load(1);">
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="checkbox" id="not_paid" name="not_paid" onchange="load(1);">
                            <label for="not_paid">Solo Impagos</label>
                        </div>
                        <div class="col-md-4 form-group">
                            <input type="checkbox" id="inactive" name="inactive" onchange="load(1);">
                            <label for="inactive"><b>Ver eliminados</b></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-xs-1">
                            <div id="loader" class="text-center"></div>
                        </div>
                        <!-- <div class="col-md-offset-10"> -->
                        <div class=" pull-right">
                            <button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#formModal"><i class='fa fa-plus'></i> Nuevo</button>
                            <!-- Form Modal -->
                            <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- form  -->
                                        <form class="form-horizontal" role="form" method="post" id="add_register" name="add_register" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel"> Nueva Deuda</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="date" class="col-sm-2 control-label">Fecha</label>
                                                    <div class="col-sm-10">
                                                        <input type="date" required class="form-control" id="date" name="date" placeholder="Fecha " required value="<?php echo date("Y-m-d"); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="document_number" class="col-sm-2 control-label" style="padding-top:0px !important;">Numero de Documento </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="document_number" name="document_number" required placeholder="Numero de Documento">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description" class="col-sm-2 control-label">Descripción </label>
                                                    <div class="col-sm-10">
                                                        <textarea type="text" class="form-control" id="description" name="description" placeholder="Descripción" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="amount" class="col-sm-2 control-label">Importe </label>
                                                    <div class="col-sm-7">
                                                        <input type="number" required class="form-control" id="amount" name="amount" placeholder="Importe" pattern="^[0-9]{1,9}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8">
                                                    </div>
                                                    <label for="amount" class="col-sm-1 control-label">Cuotas </label>
                                                    <div class="col-sm-2">
                                                        <input type="number" required class="form-control" id="payment_fees" name="payment_fees" placeholder="Cuotas" min="1" max="60" title="Ingresa sólo números entre el 1 y 60" value="1">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <?php
                                                    $entity_select = new SelectList("entity", "Entidad", $entities);
                                                    $entity_select->funjs = "onchange=\"change_entity('type_debt','category');\"";
                                                    echo $entity_select->renderLabel('col-sm-2');
                                                    ?>
                                                    <div class="col-sm-10">
                                                        <?php echo $entity_select->render(); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <?php
                                                    $type_debt_select = new SelectList("type_debt", "Tipo", $types);
                                                    $type_debt_select->tag = "disabled";
                                                    echo $type_debt_select->renderLabel('col-sm-2');
                                                    ?>
                                                    <div class="col-sm-10">
                                                        <?php echo $type_debt_select->render(); ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <span class="col-md-2 col-sm-2 col-xs-12"></span>
                                                    <label for="document" class="col-sm-6">Documento
                                                        <input type="file" class="form-control" accept="image/*" id="document" name="document" onchange="load_image(this);">
                                                        <input type="button" class="btn btn-default" id="btn_webcam_document" name="btn_webcam_document" value="Sacar Foto" data-toggle="modal" href="#frmwebcamdocument" onclick="add_parameters_from_webcam('document')">
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <img src="res/images/default_image.jpg" alt="Imagen en blanco a la espera de que carga de documento" class="img-thumbnail" id="document_image" height="60" width="75">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <span class="col-md-2 col-sm-2 col-xs-12"></span>
                                                    <label for="payment" class="col-sm-6">Pago
                                                        <input type="file" class="form-control" accept="image/*" id="payment" name="payment" onchange="load_image(this);">
                                                        <input type="button" class="btn btn-default" id="btn_webcam_payment" name="btn_webcam_payment" value="Sacar Foto" data-toggle="modal" href="#frmwebcampayment" onclick="add_parameters_from_webcam('payment')">
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <img src="res/images/default_image.jpg" alt="Imagen en blanco a la espera de que carga de documento" class="img-thumbnail" id="payment_image" height="60" width="75">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <span class="col-md-2 col-sm-2 col-xs-12"></span>
                                                    <label for="paid_out" class="col-md-2 col-sm-2 col-xs-2">
                                                        <input type="checkbox" id="paid_out" name="paid_out" value="paid_out" onchange="change_payment_status(this.checked)"> Pagado
                                                    </label>
                                                    <div id="div_pay_with" style="display:none;">
                                                        <span class="col-md-3 col-sm-3 col-xs-3">
                                                            <label for="payment_date" style="float:right;">Fecha de Pago</label>
                                                        </span>
                                                        <span class="col-md-5 col-sm-5 col-xs-5">
                                                            <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?php echo date("Y-m-d"); ?>">
                                                        </span>
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
                                        </form>
                                        <!-- /end form  -->
                                    </div>
                                </div>
                            </div>
                            <?php
                            $webcamdocument = new Webcam('document');
                            echo $webcamdocument->renderModalImageCam();
                            $webcampayment = new Webcam('payment');
                            echo $webcampayment->renderModalImageCam();
                            ?>
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
                            <?php $debts_data = ExpensesData::getAllCount($_SESSION['company_id']);
                            if ($debts_data->count != 0) :
                            ?>
                                <div class="btn-group">
                                    <button class="btn btn-success" type="button" onclick='exportExcel(1);'><i class='fa fa-file-excel-o  margin-r-5'></i>Descargar</button>
                                </div>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div id="resultados_ajax"></div><!-- Resultados Ajax -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Historial Deudas</h3>
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
    <script type="text/javascript" src="res/plugins/webcam/webcam.js"></script>
    <script type="text/javascript" src="res/plugins/multimodal/multimodal.js"></script>

    <script>
        $(function() {
            load(1);
        });

        function load(page) {
            //Se obtienen filtros de busqueda
            var month_find = $('#month_find option:selected').val();
            var year_find = $('#year_find option:selected').val();
            var type_debt_find = $('#type_debt_find option:selected').val();
            var find_text = $('#find_text').val();
            var not_paid = $('#not_paid').is(":checked");
            var inactive = $('#inactive').is(":checked");

            var per_page = $("#per_page").val();
            var parametros = {
                "page": page,
                'month': month_find,
                'year': year_find,
                'type_debt': type_debt_find,
                'text': find_text,
                'payment': not_paid,
                'inactive': inactive,
                'per_page': per_page
            };
            $.get({
                url: "./?action=loaddebts",
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
        async function exportExcel(page) {
            //Se obtienen filtros de busqueda
            var month_find = $('#month_find option:selected').val();
            var year_find = $('#year_find option:selected').val();
            var type_debt_find = $('#type_debt_find option:selected').val();
            var find_text = $('#find_text').val();
            var not_paid = $('#not_paid').is(":checked");
            var inactive = $('#inactive').is(":checked");

            var per_page = $("#per_page").val();
            var parametros = {
                "page": page,
                'month': month_find,
                'year': year_find,
                'type_category': type_debt_find,
                'text': find_text,
                'payment': not_paid,
                'inactive': inactive,
                'per_page': per_page,
                'type': 'debt'
            };

            await $.get({
                url: "./?action=export_excel",
                data: parametros,
                beforeSend: function(data) {
                    $("#loader").html("<img src='res/images/ajax-loader.gif'>");
                },
                //console.log(data);
                success: function(data) {
                    let today = new Date();
                    let dd = String(today.getDate()).padStart(2, '0');
                    let mm = String(today.getMonth() + 1).padStart(2, '0');
                    let yyyy = today.getFullYear();
                    today = `${mm}_${dd}_${yyyy}`;
                    let hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + escape(data);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = `Deudas_${today}.csv`;
                    hiddenElement.click();
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
            if (confirm('Esta acción  eliminará de forma permanente la Deuda \n\n Desea continuar?')) {
                //Se obtienen filtros de busqueda para recarga y por estandar
                var month_find = $('#month_find option:selected').val();
                var year_find = $('#year_find option:selected').val();
                var type_debt_find = $('#type_debt_find option:selected').val();
                var find_text = $('#find_text').val();
                var not_paid = $('#not_paid').is(":checked");
                var inactive = $('#inactive').is(":checked");

                var page = 1;

                var per_page = $("#per_page").val();
                var parametros = {
                    "page": page,
                    'month': month_find,
                    'year': year_find,
                    'type_debt': type_debt_find,
                    'text': find_text,
                    'payment': not_paid,
                    'inactive': inactive,
                    'per_page': per_page,
                    "id": id
                };

                $.get({
                    // method: "GET",
                    url: './?action=loaddebts',
                    data: parametros,
                    beforeSend: function(objeto) {
                        $("#loader").html("<img src='res/images/ajax-loader.gif'>");
                    },
                    success: function(data) {
                        $(".outer_div").html(data).fadeIn('slow');
                        $("#loader").html("");
                        window.setTimeout(function() {
                            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                                $(this).remove();
                            });
                        }, 5000);
                    }
                })
            }
        }
        $("#add_register").submit(function(event) {
            $('#save_data').attr("disabled", true);
            //Se cambia forma de envio de formulario para soportar envio de imagenes
            var fd = new FormData($(this)[0]);
            var pay_out = $('#paid_out').is(":checked");
            fd.append("pay_out", pay_out);
            fd.append("document_image", $('#document_image').attr('src'));
            fd.append("payment_image", $('#payment_image').attr('src'));
            fd.append("type_debt", $('#type_debt').val());
            $.ajax({
                type: "POST",
                url: "./?action=adddebt",
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function(objeto) {
                    $("#resultados_ajax").html("Enviando...");
                },
                success: function(datos) {
                    $("#resultados_ajax").html(datos);
                    $('#save_data').attr("disabled", false);
                    load(1);
                    window.setTimeout(function() {
                        $(".alert").fadeTo(500, 0).slideUp(500, function() {
                            $(this).remove();
                        });
                    }, 5000);
                    $('#formModal').modal('hide');
                    clear_modal('add_register');
                    change_payment_status(false);
                }
            });
            event.preventDefault();
        })
    </script>
<?php else : Core::redir("./");
endif; ?>
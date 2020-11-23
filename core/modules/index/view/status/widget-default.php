<?php

if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != "1") :
    if (isset($_SESSION['company_id']) && !empty($_SESSION['company_id'])) {
        $id = $_SESSION['company_id'];
    } else {
        Core::redir("./?view=status");
    }

    //query
    $Where = " empresa = " . $_SESSION['company_id'] . "  and active=1 ";
    $stocks = StockData::dynamicQueryArray($Where . " and  MONTH(fecha) = MONTH(CURRENT_DATE())");
    $incomes = IncomeData::dynamicQueryArray($Where . " and pagado=0");
    $debts = DebtsData::dynamicQueryArray($Where . " and pagado=0 and   MONTH(fecha) = MONTH(CURRENT_DATE())");
    $expenses = ExpensesData::dynamicQueryArray($Where . " and pagado=0 and  MONTH(fecha_vence) = MONTH(CURRENT_DATE())");
    $last_month_expenses =  ExpensesData::dynamicQueryArray($Where . " and pagado=0 and  date_format(fecha_vence, '%Y-%m') = date_format(now() - interval 1 month, '%Y-%m')");
    $last_month_debts =  DebtsData::dynamicQueryArray($Where . " and pagado=0 and  date_format(fecha, '%Y-%m') = date_format(now() - interval 1 month, '%Y-%m')");
    $user = UserData::getById($_SESSION['user_id']);
?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- left column -->
                    <div class="box box-primary">
                        <!-- general form elements -->
                        <div class="box-header with-border">
                            <h3>Estado Empresa</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">

                            <?php if (count($stocks) > 0) {
                            ?>
                                <h4><i class="fa fa-bank" style="margin-right:10px;"></i>Valores</h4>

                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los egresos -->
                                        <th>Fecha</th>
                                        <th>Entidad</th>
                                        <th>Descripción</th>
                                        <th>Importe</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        foreach ($stocks as $stock) {
                                            $total += $stock->amount;

                                            $created_at = $stock->fecha;
                                            list($date) = explode(" ", $created_at);
                                            list($Y, $m, $d) = explode("-", $date);
                                            $date = $d . "-" . $m . "-" . $Y;
                                        ?>
                                            <tr>
                                                <!-- Se  muestran los nombres de los campos dependiendo de los id's -->
                                                <td><?php echo $date; ?></td>
                                                <td><?php if ($stock->entidad != null) {
                                                        echo EntityData::getById($stock->entidad)->name;
                                                    } else {
                                                        echo "<center>----</center>";
                                                    }  ?></td>
                                                <td><?php echo $stock->description; ?></td>
                                                <td><?php echo number_format($stock->amount, 2); ?></td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan='10'>
                                                <h4 class="pull-right">Total Valores $<?php echo $total; ?></h4>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php
                            } else {
                                echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	    <strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
                            }
                            ?>

                            <?php if (count($incomes) > 0) {
                            ?>
                                <h4><i class="fa fa-usd" style="margin-right:10px;"></i>Ingresos</h4>

                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los egresos -->
                                        <th>Descripcion</th>
                                        <th>Categoria</th>
                                        <th>Importe</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        foreach ($incomes as $income) {
                                            $total += $income->amount;
                                        ?>
                                            <tr>
                                                <!-- Se  muestran los nombres de los campos dependiendo de los id's -->
                                                <td><?php echo $income->description; ?></td>
                                                <td><?php echo $income->getCategory()->name; ?></td>
                                                <td><?php echo number_format($income->amount, 2); ?></td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan='10'>
                                                <h4 class="pull-right">Total Ingresos Pendientes $<?php echo $total; ?></h4>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php
                            } else {
                                echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	    <strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
                            }
                            ?>

                            <?php if (count($debts) > 0) {
                            ?>
                                <h4><i class="fa fa-money" style="margin-right:10px;"></i>Deudas Documentadas</h4>

                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los egresos -->
                                        <th>Fecha</th>
                                        <th>Entidad</th>
                                        <th>Descripción</th>
                                        <th>Importe</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        foreach ($debts as $debt) {

                                            $created_at = $debt->fecha;
                                            list($date) = explode(" ", $created_at);
                                            list($Y, $m, $d) = explode("-", $date);
                                            $date = $d . "-" . $m . "-" . $Y;
                                            $total += $debt->amount;
                                        ?>
                                            <tr>
                                                <td><?php echo $date; ?></td>
                                                <td><?php if ($debt->entidad != null) {
                                                        echo EntityData::getById($debt->entidad)->name;
                                                    } else {
                                                        echo "<center>----</center>";
                                                    }  ?></td>
                                                <td><?php echo $debt->description; ?></td>
                                                <td><?php echo number_format($debt->amount, 2); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan='10'>
                                                <h4 class="pull-right">Total Deudas Documentadas $<?php echo $total; ?></h4>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php
                            } else {
                                echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	    <strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
                            }
                            ?>

                            <?php if (count($expenses) > 0) {
                            ?>
                                <h4><i class="fa fa-credit-card-alt" style="margin-right:10px;"></i>Egresos</h4>

                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los egresos -->
                                        <!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los egresos -->
                                        <th>Descripcion</th>
                                        <th>Categoria</th>
                                        <th>Importe</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        foreach ($expenses as $expense) {
                                            $total += $expense->amount;
                                        ?>
                                            <tr>
                                                <!-- Se  muestran los nombres de los campos dependiendo de los id's -->
                                                <td><?php echo $expense->description; ?></td>
                                                <td><?php echo $expense->getCategory()->name; ?></td>
                                                <td><?php echo number_format($expense->amount, 2); ?></td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan='10'>
                                                <h4 class="pull-right">Total Egresos Pendientes $<?php echo $total; ?></h4>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php
                            } else {
                                echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	    <strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
                            }
                            ?>
                            <h4><i class="glyphicon glyphicon-calendar" style="margin-right:10px;"></i>Mes Anterior Pendiente de Pago</h4>
                            <table class="table table-bordered table-hover">
                                <?php ?>
                                <tr>
                                    <th>Deuda Documentada</th>
                                    <td>$<?php $total = 0;
                                            foreach ($last_month_debts as $debt) {
                                                $total += $debt->amount;
                                            }
                                            echo $total;
                                            ?></td>
                                </tr>
                                <tr>
                                    <th>Egresos pendientes de pago</th>
                                    <td>$<?php $total_expense = 0;
                                            foreach ($last_month_expenses as $expense) {
                                                $total_expense += $expense->amount;
                                            }
                                            echo $total_expense;
                                            ?></td>
                                </tr>
                                <tr>
                                    <th>Total Anterior Pendiente de Pago</th>
                                    <td>$<?php echo $total + $total_expense; ?></td>
                                </tr>
                            </table>
                            <form role="form" method="post" name="sendEmail" id="sendEmail" style="margin-top: 25px;">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <div class="form-group">
                                            <label>El correo sera enviado a nombre de <?php echo $user->name; ?></label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-3">
                                        <div class="form-group">
                                            <input type="email" class="form-control" required name="email_from" id="email_from" placeholder="Email">
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            <a href="?view=home"><button type="button" class="btn btn-default">Volver</button></a>
                                            <button type='submit' id='send_status' class='btn btn-primary'>Enviar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="result"></div>
                        </div>
                        <!-- mod id -->
                    </div><!-- /.box-body -->

                </div> <!-- /.box -->
            </div>

    </div>
    </section>
    </div>
    <!-- /.content-wrapper -->
    <?php include "res/resources/js.php"; ?>
    <script>
        $("#sendEmail").submit(function(event) {
            $('#send_status').attr("disabled", true);
            debugger;
            let parametros = {
                email: $('#email_from').val(),
                arbol: $(this)
            }
            let result = false;
            $.ajax({
                type: "POST",
                url: "./?action=sendstatus",
                data: parametros,
                beforeSend: function(objeto) {
                    $("#result").html("Mensaje: Cargando...");
                },
                success: function(datos) {
                    $("#result").html(datos);
                    $('#send_status').attr("disabled", false);
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
                    window.location.href = "./?view=home";
                }
            }, 2000);
        })
    </script>
<?php else : Core::redir("./");
endif; ?>
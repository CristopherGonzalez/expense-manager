<?php

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="res/images/logo_MRC.jpg" />
    <?php if (isset($_SESSION["user_id"])) : //si hay session 
    ?>
        <title>MRC Mi Negocio</title>
    <?php else : ?>
        <title>Iniciar Sesión</title><!-- si no hay session -->
    <?php endif; ?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="res/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="res/css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="res/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="res/dist/css/skins/_all-skins.min.css">
    <!-- select2 -->
    <link href="res/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
</head>
<?php if (isset($_SESSION["user_id"])) : ?>
    <?php
    //i am data
    $user_session = UserData::getById($_SESSION["user_id"]);
    ?>

    <body class="hold-transition <?php echo $user_session->getSkin()->value; ?> sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">
            <header class="main-header">
                <a href="?view=home" class="logo">
                    <!-- Logo -->
                    <span class="logo-mini"><b>MRC</b></span>
                    <span class="logo-lg">MRC <b>MiNegocio</b></span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="res/images/users/<?php echo $user_session->profile_pic ?>" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?php echo $user_session->name ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header">
                                        <?php
                                        $created_at = $user_session->created_at;
                                        list($date, $hora) = explode(" ", $created_at);
                                        list($Y, $m, $d) = explode("-", $date);
                                        $date_user = $d . "-" . $m . "-" . $Y;
                                        ?>
                                        <img src="res/images/users/<?php echo $user_session->profile_pic ?>" class="img-circle" alt="User Image">
                                        <p><?php echo $user_session->name ?>
                                            <!-- - (<?php echo $user_session->email ?>) --><small>Miembro desde: <?php echo $date_user; ?></small></p>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="./?view=profile" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-user"></i> Mi Cuenta</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="./?action=logout" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-off"></i> Cerrar Sesión</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="res/images/users/<?php echo $user_session->profile_pic ?>" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $user_session->name ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <ul class="sidebar-menu">
                        <!-- Se agregan las opciones socios, entidades e informes-->
                        <li class="header">ADMINISTRACIÓN 4.0</li>
                        <?php if (isset($_GET['view']) and $_GET['view'] != 'company' and $user_session->id <> 1) : ?>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'profile') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=profile"><i class="fa fa-user"></i> <span>Mi cuenta</span></a>
                            </li>
                            <?php if ($user_session->is_admin == "1") : //si hay session 
                            ?>
                                <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'users') {
                                                echo "active";
                                            } ?>">
                                    <a href="?view=users"><i class="fa fa-users"></i> <span>Usuarios</span></a>
                                </li>
                            <?php endif; ?>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'home') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=home"><i class="fa fa-home"></i> <span>Dashboard</span></a>
                            </li>

                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'income' or $_GET['view'] == 'editincome') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=income"><i class="fa fa-usd"></i> <span>Ingresos</span></a>
                            </li>

                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'expenses' or $_GET['view'] == 'editexpense') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=expenses"><i class="fa fa-credit-card-alt"></i> <span>Egresos</span></a>
                            </li>

                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'partners') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=partners"><i class="fa fa-users"></i> <span>Socios</span></a>
                            </li>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'stocks') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=stocks"><i class="fa fa-bank"></i> <span>Valores</span></a>
                            </li>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'debt') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=debt"><i class="fa fa-money"></i> <span>Deudas doc.</span></a>
                            </li>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'entities') {
                                            echo "active";
                                        } ?>">
                                <!--a href="?view=entidades"><i class="fa fa-home"></i> <span>Informes</span></a>-->
                                <a href="?view=entities"><i class="fa fa-building"></i> <span>Entidades</span></a>
                            </li>
                            <li class="header">Categorias</li>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'category_income' or $_GET['view'] == 'editcategory_income') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=category_income"> <i class="fa fa-folder-open-o"></i> <span>Ingresos</span></a>
                            </li>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'category_expense' or $_GET['view'] == 'editcategory_expense') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=category_expense"> <i class="fa fa-folder-open"></i> <span>Egresos</span></a>
                            </li>

                            <li class="header">Informes</li>


                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'reports') {
                                            echo "active";
                                        } ?>"> <a href="?view=reports"> <i class="fa fa-pie-chart"></i><span>Gestión</span></a></li>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'paymentreports') {
                                            echo "active";
                                        } ?>"> <a href="?view=paymentreports"><i class="fa fa-list-alt"></i><span>Pagos</span></a></li>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'expirationreports') {
                                            echo "active";
                                        } ?>"> <a href="?view=expirationreports"><i class="glyphicon glyphicon-calendar"></i><span>Vencimientos</span></a></li>

                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'reports') {
                                            echo "active";
                                        } ?>">

                            </li>


                        <?php else : ?>
                            <li class="<?php if (isset($_GET['view']) and $_GET['view'] == 'company') {
                                            echo "active";
                                        } ?>">
                                <a href="?view=company"><i class="fa fa-user"></i> <span>Empresas</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </section>
                <div class="image center-block" style="bottom:0;">
                    <img src="res/images/logo_MRC.jpg" class="img-rounded img-responsive  center-block" alt="MRC Image">
                </div>
            </aside>
        <?php endif; ?>
        <!-- - - - - - - - - - - - - - - -->
        <?php
        // if(!isset($_SESSION["user_id"]) and $_SESSION["user_id"]==null){
        View::load("index");
        // }else{
        //     View::load("home");
        // }
        ?>
        <!-- - - - - - - - - - - - - - - -->
        <?php if (!isset($_SESSION["user_id"])) : //si no hay session 
        ?>
            <script src="res/plugins/jQuery/jquery-2.2.3.min.js"></script>
            <script src="res/bootstrap/js/bootstrap.min.js"></script>
            <!-- script register -->
            <script>
                $("#add").submit(function(event) {
                    $('#save_data').attr("disabled", true);
                    var result = false;
                    var parametros = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: "./?action=register",
                        data: parametros,
                        beforeSend: function(objeto) {
                            $("#result").html("Mensaje: Cargando...");
                        },
                        success: function(datos) {
                            $("#result").html(datos);
                            $('#save_data').attr("disabled", false);
                            //load(1);
                            result = true;
                        }
                    });
                    event.preventDefault();

                })
            </script>
        <?php endif; ?>
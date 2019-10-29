<?php 
if(isset($_SESSION["user_id"])):
    if($_SESSION["user_id"]=="1"){
        Core::redir("./?view=company");
    }else{
        Core::redir("./?view=home");
    }
endif;
?>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="?view=index">
        <img src="res/images/logo_MRC.jpg" class="img-rounded img-responsive center-block" alt="MRC Image">
        </a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <?php  
            $type_alert = "danger";
            $title = "Error!";
            $description = "Datos invalidos o sin identificador.";
            if (empty($_GET['alert'])) {
            echo "";
            }else{
                if ($_GET['alert'] == 1) {
                    $title = "Login Falló!";
                    $description = "Nombre de usuario, Licencia de la empresa o contraseña incorrectos, vuelva a comprobar su nombre de usuario, licencia o contraseña.";
                }
                elseif ($_GET['alert'] == 2) {
                    $type_alert = "success";
                    $title = "Bien hecho!";
                    $description = " Ha cerrado la sesión con éxito.";
                }
                elseif ($_GET['alert'] == 3) {
                    $type_alert = "warning";
                    $title = "Error!";
                    $description = "Datos Vacios.";
                }
                elseif ($_GET['alert'] == 4) {
                    $type_alert = "warning";
                    $title = "Error!";
                    $description = " Usuario pendiente de activación por la empresa.";
                }
                elseif ($_GET['alert'] == 5) {
                    $type_alert = "warning";
                    $title = "Error!";
                    $description = " Usuario pendiente de activación por MRC.";
                }
                elseif ($_GET['alert'] == 6) {
                    $title = "Error!";
                    $description = " Usuario inhabilitado.";
                }
                elseif ($_GET['alert'] == 7) {
                    $title = "Error!";
                    $description = " Empresa eliminada o inhabilitada.";
                }
                echo "<div class='alert alert-".$type_alert." alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-flag'></i> ".$title."</h4>
                ".$description."
                </div>";
            }
        ?>
       
        <p class="login-box-msg">Inicia Sesion</p>

        <form method="post" action="./?action=processlogin" >
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Licencia" name="license" required>
                <span class="glyphicon glyphicon-briefcase glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Correo Electrónico" name="email" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Contraseña" name="password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <!-- <div class="row">
                <div class="col-xs-7">
                </div>
                <div class="col-xs-5">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Iniciar Sesion</button>
                </div>
            </div> -->
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Acceder</button>
                    <a href="./?view=register" class="btn btn-default btn-block btn-flat"> Registrarme</a>
                </div><!-- /.col -->
            </div>
        </form>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
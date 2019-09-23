<?php 
/*-------------------------
    Autor: Amner Saucedo Sosa
    Web: www.abisoftgt.net
    E-Mail: waptoing7@gmail.com
---------------------------*/
if(isset($_SESSION["user_id"])):
    Core::redir("./?view=home");
endif;
?>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="?view=index"><b>Abi</b>SOFT</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <?php  
            if (empty($_GET['alert'])) {
            echo "";
            } 
            elseif ($_GET['alert'] == 1) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4>  <i class='icon fa fa-times-circle'></i> Login Falló!</h4>
                    Nombre de usuario o contraseña incorrectos, vuelva a comprobar su nombre de usuario o contraseña.
                  </div>";
            }
            elseif ($_GET['alert'] == 2) {
            echo "<div class='alert alert-success alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4>  <i class='icon fa fa-check-circle'></i> Bien hecho!</h4>
                    Ha cerrado la sesión con éxito.
                  </div>";
            }
            elseif ($_GET['alert'] == 3) {
            echo "<div class='alert alert-warning alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4>  <i class='icon fa fa-check-circle'></i> Error!</h4>
                    Datos Vacios.
                  </div>";
            }
        ?>
        <p class="login-box-msg">Inicia Sesion</p>

        <form method="post" action="./?action=processlogin" >
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
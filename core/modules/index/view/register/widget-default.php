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
        <a href="./?view=index"><b>Abi</b>SOFT</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <div id="result"></div>
        <p class="login-box-msg">Registro</p>
        <form method="post" name="add" id="add">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Nombre y Apellido" name="name" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Correo Electrónico" name="email" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Contraseña" name="password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" id="save_data" class="btn btn-primary btn-block btn-flat">Registrarme</button>
                    <a href="./?view=index" class="btn btn-default btn-block btn-flat"><i class="fa fa-arrow-left"></i> Regresar</a>
                </div><!-- /.col -->
            </div>
        </form>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<?php 
if(isset($_GET['id'])){
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
                if (empty($_GET['id'])) {
                echo "";
                }else{
                    $decrypted_id = Core::encrypt_decrypt('decrypt', $_GET['id']);
                    $codes = unserialize($decrypted_id);
                    if(isset($codes) && !empty($codes) && is_array($codes)){
                        $step = $codes['step'];
                        $user = UserData::getById($codes['user_id']);
                        if(isset($user) && !empty($user) ){
                            if ($step == 1) {
                                $type_alert = "warning";
                                $title = "Verificación de empresa!";
                                $description = " Has verificado la cuenta del usuario, pero MRComanda debe verificar el nuevo perfil para acceder a Mi Negocio.";
                            }
                            elseif ($step == 2) {
                                $type_alert = "success";
                                $title = "Verificación de MRComanda!";
                                $description = "Has verificado la cuenta de usuario, ya puedes ingresar a Mi Negocio.";
                                //$mail = new Mail();
                                //$mail->send();
                            }
                            elseif ($step == 3) {
                                $title = "Deshabilitación de cuenta!";
                                $description = "Has deshabilitado la cuenta del usuario.";
                            }
                            $user->status = $step + 1;
                            $user->update_status();
                        }
                    }
                } 
                echo "<div class='alert alert-".$type_alert." alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4>  <i class='icon fa fa-times-circle'></i> ".$title."</h4>
                    ".$description."
                    </div>";
            ?>
            <div class="row">
                <div class="col-xs-12">
                    <a href="./?view=index" class="btn btn-default btn-block btn-flat"> Ir a Login</a>
                </div><!-- /.col -->
            </div>
        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
</body>
<?php }?>
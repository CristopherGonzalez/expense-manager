<?php 
if(isset($_GET['id']) && !isset($_SESSION["user_id"])){
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
                        $is_admin = (isset($codes['is_admin']) && !empty($codes['is_admin']) && $codes['is_admin']==true)? true : false;
                        $user = UserData::getById($codes['user_id']);
                        if(isset($user) && !empty($user) ){
                            if ($step == 1) {
                                $type_alert = "warning";
                                $title = "Verificación de empresa!";
                                $description = " Has verificado la cuenta de usuario, pero MRComanda debe verificar el nuevo perfil para acceder a Mi Negocio.";
                                $company= CompanyData::getById($user->empresa);
                                $codes = array(
                                    'user_id'=>$user->id,
                                    'step'=>2,
                                    'is_admin'=>false
                                );
                                $code = Core::encrypt_decrypt('encrypt', serialize($codes));
                                $mail = new Mail('cagv1992@gmail.com',2);
                                $mail->message= "\r\n"."Licencia : ".$company->licenciaMRC;
                                $mail->message.= "\r\n"."Nombre de Empresa : ".$company->name;
                                $mail->message.= "\r\n"."Nombre de Usuario : ".$user->name;
                                $mail->message.=  "\r\n"."Link para activacion de nueva cuenta.";
                                $mail->message.= "\r\n".'http://'.$_SERVER['HTTP_HOST'].'/MiNegocio/?view=activateaccount&id='.$code;
                                
                                $codes = array(
                                    'user_id'=>$user->id,
                                    'step'=>2,
                                    'is_admin'=>true
                                );
                                $code = Core::encrypt_decrypt('encrypt', serialize($codes));
                                $mail->message.=  "\r\n"."Link para activacion de nueva cuenta y convertir en administrador.";
                                $mail->message.= "\r\n".'http://'.$_SERVER['HTTP_HOST'].'/MiNegocio/?view=activateaccount&id='.$code;
                                
                                $codes = array(
                                    'user_id'=>$user->id,
                                    'step'=>3
                                );
                                $code = Core::encrypt_decrypt('encrypt', serialize($codes));
                                $mail->message.=  "\r\n"."Link para desactivacion de cuenta.";
                                $mail->message.= "\r\n".'http://'.$_SERVER['HTTP_HOST'].'/MiNegocio/?view=activateaccount&id='.$code;
                               
                                $resp_send = $mail->send();
                                if($resp_send){
                                    $description.= " Se envía correo exitosamente";
                                }else{
                                    $description.= $resp_send;
                                }
                                
                            }
                            elseif ($step == 2) {
                                $type_alert = "success";
                                $title = "Verificación de MRComanda!";
                                $description = "Has verificado la cuenta de usuario, ya puede ingresar a Mi Negocio.";
                                $user->is_admin = $is_admin ? 1 : 0;
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
                    <h4>  <i class='icon fa fa-flag'></i> ".$title."</h4>
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
<?php }
else{
    Core::alert("Debe cerrar su sesión actual.");
    Core::redir("./?view=home");
}?>
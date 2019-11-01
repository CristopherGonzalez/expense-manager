<?php
	if (empty($_POST['name'])){
			$errors[] = "Nombre está vacío.";
		}  elseif (empty($_POST['email'])) {
            $errors[] = "Correo Electrónico está vacío.";
        }  elseif (empty($_POST['password'])) {
            $errors[] = "Contraseña está vacío.";
        }  elseif (empty($_POST['license'])) {
            $errors[] = "La licencia de la empresa está vacío.";
        }  elseif (
        	!empty($_POST['name'])
        	&& !empty($_POST['email'])
        	&& !empty($_POST['license'])
        	&& !empty($_POST['password'])
        ){
			$con = Database::getCon(); 
			$user = new UserData();
			$user->name = mysqli_real_escape_string($con,(strip_tags($_POST["name"],ENT_QUOTES)));
			$user->password = sha1(md5(mysqli_real_escape_string($con,(strip_tags($_POST["password"],ENT_QUOTES)))));
			$user->email = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
			$license = mysqli_real_escape_string($con,(strip_tags($_POST["license"],ENT_QUOTES)));
			$company =  CompanyData::getByLicense($license);
			$query_new = "";
			if($company==null || empty($company)){ 
				$errors[] = "Lo sentimos, la licencia no es valida o no esta registrada a ninguna empresa";
			}else{
				$user->empresa = $company->id;
				$query_new=$user->add();
				if (!empty($query_new) && is_array($query_new) && $query_new[0] ) {
					$messages[] = "Registro con éxito! Pero debes esperar la activacion por parte de la empresa.";
					$codes = array(
						'user_id'=>$query_new[1],
						'step'=>1
					);
					$code = Core::encrypt_decrypt('encrypt', serialize($codes));
					$mail = new Mail($company->email,1);
					$mail->message=  "\r\n"."Link para activacion de nueva cuenta.";
					$mail->message.= "\r\n".'http://'.$_SERVER['HTTP_HOST'].'/MiNegocio/?view=activateaccount&id='.$code;
					$resp_send = $mail->send();
					if($resp_send){
						$messages[] = " Se envía correo exitosamente";
					}else{
						$error[] = $resp_send;
					}
					//$messages[] = $mail->message;
				} else {
					$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
				}
			}
		} else {
			$errors[] = "desconocido.";	
		}

	if (isset($errors)){		
?>
	<div class="alert alert-danger" role="alert">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Error!</strong> 
		<?php
			foreach ($errors as $error) {
				echo $error;
			}
		?>
	</div>
<?php
	}
	if (isset($messages)){
?>
		<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>¡Bien hecho!</strong>
			<?php
				foreach ($messages as $message) {
					echo $message;
				}
			?>
		</div>
<?php
	}
?>			
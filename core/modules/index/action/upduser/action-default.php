<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
//Se agregan validacion para nuevo campo de gasto
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['name']) || empty($_POST['password']) ) {
           $errors[] = "Todos los campos son requeridos";
        }else if (
        	!empty($_POST['mod_id'])
			&& !empty($_POST['name'])
			&& !empty($_POST['password'])
		){
		$response = false;
    	$con = Database::getCon(); 
		$id=intval($_POST['mod_id']);
		$user = UserData::getById($id);
		$user->name = mysqli_real_escape_string($con,(strip_tags($_POST["name"],ENT_QUOTES)));
		$response = $user->update_name();
		$user->password = sha1(md5(mysqli_real_escape_string($con,(strip_tags($_POST["password"],ENT_QUOTES)))));
		$response = $user->update_passwd();
		$user->is_admin = (isset($_POST['is_admin']) && $_POST['is_admin'] == "true") ? 1 : 0;
		$user->status = (isset($_POST['disabled']) && $_POST['disabled'] == "true") ? 4 : 3;
		$response = $user->update_status();

		if (!$response){
			$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
		} else{
			$messages[] = "El usuario ha sido actualizada satisfactoriamente.";
		}
	} else {
		$errors []= "Error desconocido.";
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
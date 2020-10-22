<?php

if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
//Se validan nuevos parametros de los egresos
if (empty($_POST['name_entity'])) {
	$errors[] = "No se ha ingresado nombre.";
} elseif (empty($_POST['type'])) {
	$errors[] = "No ha seleccionado el tipo";
} elseif (
	!empty($_POST['name_entity'])
	&& !empty($_POST['type'])
) {
	$con = Database::getCon();
	$entity = new EntityData();
	$entity->name = mysqli_real_escape_string($con, (strip_tags($_POST["name_entity"], ENT_QUOTES)));
	$entity->document_number = mysqli_real_escape_string($con, (strip_tags($_POST["document_number"], ENT_QUOTES)));
	$entity->description = mysqli_real_escape_string($con, (strip_tags($_POST["description"], ENT_QUOTES)));
	$entity->tipo = intval($_POST['type']);
	$entity->user_id = $_SESSION['user_id'];
	$entity->empresa = $_SESSION['company_id'];
	$entity->category_id = intval($_POST['category']);
	//Se capturan los nuevos datos de los egresos
	if (isset($_POST["document_image"]) && !empty($_POST["document_image"])) {
		$entity->documento = $_POST["document_image"];
	}
	$query_new = $entity->add();
	if ($query_new) {
		$messages[] = "La entidad ha sido agregada con éxito.";
		$change_log = new ChangeLogData();
		$change_log->tabla = "entity";
		$change_log->registro_id = $query_new[1];
		$change_log->description = $entity->name;
		$change_log->user_id = $entity->user_id;
		$change_log->tipo = $entity->tipo;
		$change_log->fecha = "NOW()";
		$change_log->document_number = $entity->document_number;
		$change_log->entidad = $entity->category_id;
		$result = $change_log->add();
		if (isset($result) && !empty($result) && is_array($result) && count($result)>1 && $result[1]>0) {
			$messages[] = " El registro de cambios ha sido actualizado satisfactoriamente.";
		} else {
			$errors[] = " Lo siento algo ha salido mal en el registro de errores.";
		}
	} else {
		$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
	}
} else {
	$errors[] = "desconocido.";
}

if (isset($errors)) {
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
if (isset($messages)) {
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
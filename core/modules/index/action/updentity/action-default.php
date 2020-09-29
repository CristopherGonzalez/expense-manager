<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
if (empty($_POST['mod_id'])) {
	$errors[] = "ID vacío";
} else if (empty($_POST['origin'])) {
	$errors[] = "Debes seleccionar un origen.";
} else if (empty($_POST['type'])) {
	$errors[] = "Debes seleccionar el tipo ";
} else if (empty($_POST['name_entity'])) {
	$errors[] = "Debes ingresar un nombre";
} else if (
	!empty($_POST['mod_id'])
	&& !empty($_POST['origin'])
	&& !empty($_POST['type'])
	&& !empty($_POST['name_entity'])
) {

	$con = Database::getCon();
	$id = intval($_POST['mod_id']);
	$entity = EntityData::getById($id);
	$entity->name = mysqli_real_escape_string($con, (strip_tags($_POST["name_entity"], ENT_QUOTES)));
	$entity->tipo = intval($_POST['type']);
	$entity->document_number = mysqli_real_escape_string($con, (strip_tags($_POST["document_number"], ENT_QUOTES)));
	$entity->description = mysqli_real_escape_string($con, (strip_tags($_POST["description"], ENT_QUOTES)));
	if (isset($_POST["document_image"]) && !empty($_POST["document_image"])) {
		$entity->documento = $_POST["document_image"];
	}
	$entity->category_id = intval($_POST['category_id']);
	$entity->active = (isset($_POST['active']) && $_POST['active'] == 'on') ? 1 : 0;
	$query_update = $entity->update();

	if ($query_update) {
		$messages[] = "La entidad ha sido actualizada satisfactoriamente.";
		if (boolval($entity->active)) {
			$status = 1;
		} else {
			$status = 0;
		}
		ExpensesData::updateStatusByEntity($status, $entity->id);
		IncomeData::updateStatusByEntity($status, $entity->id);
		ResultData::updateStatusByEntity($status, $entity->id);
		$change_log = new ChangeLogData();
		$change_log->tabla = "entity";
		$change_log->registro_id = $entity->id;
		$change_log->description = $entity->name;
		$change_log->user_id = $entity->user_id;
		$change_log->tipo = $entity->tipo;
		$change_log->entidad = $entity->category_id;
		$change_log->document_number =
		$entity->document_number;
		$change_log->active = $entity->active;
		$change_log->fecha = "NOW()";
		$result = $change_log->add();
		if (isset($result) && !empty($result) && is_array($result) && count($result) > 1 && $result[1] > 0) {
			$messages[] = " El registro de cambios ha sido actualizado satisfactoriamente.";
		} else {
			$errors[] = " Lo siento algo ha salido mal en el registro de errores.";
		}

		//print("<script>window.location='./?view=expenses'</script>");
	} else {
		$errors[] = "Lo siento algo ha salido mal intenta nuevamente.";
	}
} else {
	$errors[] = "Error desconocido.";
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
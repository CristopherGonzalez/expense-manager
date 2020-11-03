<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
//Se agregan validacion para nuevo campo de egreso
if (empty($_POST['mod_id'])) {
	$errors[] = "ID vacío";
} else if (empty($_POST['task_name']) && empty($_POST['task_done'])) {
	$errors[] = "Todos los campos son requeridos";
} else if (
	!empty($_POST['mod_id'])
	&& !empty($_POST['task_name'])
	&& !empty($_POST['task_done'])
) {
	$con = Database::getCon();
	$id = intval($_POST['mod_id']);
	$task = new TaskData();
	$task->tarea = mysqli_real_escape_string($con, (strip_tags($_POST["task_name"], ENT_QUOTES)));
	$task->hecho = $_POST['task_done'];
	$task->id = $id;
	$query_update = $task->update();
	if ($query_update) {
		$messages[] = "La tarea ha sido actualizada satisfactoriamente.";
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
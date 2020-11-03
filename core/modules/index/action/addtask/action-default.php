<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
if (empty($_POST['task_name']) && empty($_POST['task_done'])) {
	$errors[] = "Todos lo campos son requeridos";
} elseif (
	!empty($_POST['task_name']) && !empty($_POST['task_done'])
) {
	$con = Database::getCon();
	$task = new TaskData();
	$task->tarea = mysqli_real_escape_string($con, (strip_tags($_POST["task_name"], ENT_QUOTES)));
	$task->user_id = $_SESSION['user_id'];
	$task->hecho = $_POST['task_done'];
	$task->empresa = $_SESSION['company_id'];
	$query_new = $task->add();
	if ($query_new) {
		$messages[] = "La tarea ha sido agregada con exito.";
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
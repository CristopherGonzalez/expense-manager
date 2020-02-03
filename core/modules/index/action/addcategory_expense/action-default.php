<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
if (empty($_POST['name']) && empty($_POST['type_expense'])) {
	$errors[] = "Todos lo campos son requeridos";
} elseif (
	!empty($_POST['name']) && !empty($_POST['type_expense'])
) {
	$con = Database::getCon();
	$expense = new CategoryExpenseData();
	$expense->name = mysqli_real_escape_string($con, (strip_tags($_POST["name"], ENT_QUOTES)));
	$expense->user_id = $_SESSION['user_id'];
	$expense->tipo = $_POST['type_expense'];
	$expense->empresa = $_SESSION['company_id'];
	$query_new = $expense->add();
	if ($query_new) {
		$messages[] = "La categoria ha sido agregada con éxito.";
		$change_log = new ChangeLogData();
		$change_log->tabla = "category_expense";
		$change_log->registro_id = $query_new[1];
		$change_log->description = $expense->name;
		$change_log->tipo = $expense->tipo;
		$change_log->user_id = $expense->user_id;
		$result = $change_log->add();
		if (isset($result) && !empty($result) && $result[0]) {
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
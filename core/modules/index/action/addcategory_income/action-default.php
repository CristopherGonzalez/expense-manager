<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
if (empty($_POST['name']) && empty($_POST['type_income'])) {
	$errors[] = "Todos lo campos son requeridos";
} elseif (
	!empty($_POST['name']) && !empty($_POST['type_income'])
) {
	$con = Database::getCon();
	$income = new CategoryIncomeData();
	$income->name = mysqli_real_escape_string($con, (strip_tags($_POST["name"], ENT_QUOTES)));
	$income->user_id = $_SESSION['user_id'];
	$income->empresa = $_SESSION['company_id'];
	$income->tipo = $_POST['type_income'];
	$query_new = $income->add();
	if ($query_new) {
		$messages[] = "La categoria ha sido agregada con éxito.";
		$change_log = new ChangeLogData();
		$change_log->tabla = "category_income";
		$change_log->registro_id = $query_new[1];
		$change_log->description = $income->name;
		$change_log->user_id = $income->user_id;
		$change_log->tipo = $income->tipo;
		$result = $change_log->add();
		if (isset($result) && !empty($result) && $result) {
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
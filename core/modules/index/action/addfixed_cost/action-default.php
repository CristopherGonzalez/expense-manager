<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
//Se validan nuevos parametros de los egresos
if (empty($_REQUEST['year'])) {
	$errors[] = "No ha seleccionado el año.";
} elseif (empty($_REQUEST['month'])) {
	$errors[] = "No ha seleccionado el mes.";
} elseif (empty($_REQUEST['expenses'] || !is_array($_REQUEST['expenses']))) {
	$errors[] = "No se han cargado los Egresos.";
} elseif (
	!empty($_REQUEST['year'])
	&& !empty($_REQUEST['month'])
	&& !empty($_REQUEST['expenses'])
	&& is_array($_REQUEST['expenses'])
) {
	$con = Database::getCon();
	//'TODO traer expense por id, cambiar la fecha y agregar como nuevo'
	foreach ($_REQUEST['expenses'] as $expense_base) {
		$expense = "";
		$expense = new ExpensesData();
		$expense = $expense->getById($expense_base);
		$expense->user_id = $_SESSION['user_id'];
		$day = "";
		$day = date("d", strtotime($expense->fecha));
		if (intval($day) > 28) {
			$day = '28';
		};
		$date = new DateTime();
		$date->setDate($_REQUEST['year'], $_REQUEST['month'], $day);
		$expense->fecha = $date->format('Y-m-d');
		$expense->pagado = 0;
		$expense->created_at = "NOW()";
		//Se realiza guardado de imagenes de pago y documento
		$expense->document_number = "";
		$expense->documento = "";
		if ($expense->pagado) {
			$expense->pagado_con = mysqli_real_escape_string($con, (strip_tags($_POST["pay_with"], ENT_QUOTES)));
		} else {
			$expense->pagado_con = "";
		}
		$expense->pago = "";

		$query_new = $expense->add();
		if (isset($query_new) && is_array($query_new) && $query_new[0]) {
			$messages[] = "El egreso " . $expense->description . " ha sido agregado con éxito." . "\r\n";
			$change_log = new ChangeLogData();
			$change_log->tabla = "expenses";
			$change_log->registro_id = $query_new[1];
			$change_log->description = $expense->description;
			$change_log->amount = $expense->amount;
			$change_log->entidad = $expense->entidad;
			$change_log->fecha = $expense->fecha;
			$change_log->pagado = $expense->pagado;
			$change_log->document_number = $expense->document_number;
			$change_log->user_id = $expense->user_id;
			$result = $change_log->add();
			if (isset($result) && !empty($result) && $result) {
				$messages[] = " El registro de cambios ha sido actualizado satisfactoriamente.";
			} else {
				$errors[] = " Lo siento algo ha salido mal en el registro de errores.";
			}
		} else {
			$errors[] = "Lo sentimos, el registro " . $expense->description . " falló. Por favor, regrese y vuelva a intentarlo." . "\r\n";
		}
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
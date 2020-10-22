<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
//Se validan nuevos parametros de las deudas
if (empty($_POST['amount'])) {
	$errors[] = "Cantidad está vacío.";
} elseif (empty($_POST['date'])) {
	$errors[] = "Fecha está vacío.";
} elseif (!isset($_POST['payment_fees']) || (!is_numeric($_POST['payment_fees']) || $_POST['payment_fees'] < 1 || $_POST['payment_fees'] > 60)) {
	$errors[] = "No se han ingresado la cantidad de cuotas o el formato no es invalido.";
} elseif (empty($_POST['type_debt'])) {
	$errors[] = "No ha seleccionado el tipo de deuda";
} elseif (empty($_POST['entity'])) {
	$errors[] = "No ha seleccionado una entidad vacío.";
} elseif (
	!empty($_POST['amount'])
	&& !empty($_POST['date'])
	&& !empty($_POST['type_debt'])
	&& !empty($_POST['entity'])
) {
	$con = Database::getCon();
	$payment_fees = intval($_POST['payment_fees']);
	$payment_fees = $payment_fees == 0 ? 1 : $payment_fees;
	$amount = intval(mysqli_real_escape_string($con, (strip_tags($_POST["amount"], ENT_QUOTES))));
	$amount = ($amount / $payment_fees);
	$date_debts = mysqli_real_escape_string($con, (strip_tags($_POST["date"], ENT_QUOTES)));

	for ($i = 0; $i < $payment_fees; $i++) {
		$debt = new DebtsData();
		$debt->description = mysqli_real_escape_string($con, (strip_tags($_POST["description"], ENT_QUOTES)));
		$debt->description .= " (cuota " . ($i + 1) . " de " . $payment_fees . ")";
		$debt->amount = $amount;
		$debt->user_id = $_SESSION['user_id'];
		$debt->empresa = $_SESSION['company_id'];
		$debt->entidad = intval($_POST['entity']);
		$debt->tipo = intval($_POST['type_debt']);
		$debt->fecha = date("Y-m-d H:i:s", strtotime($date_debts . "+" . $i . " month"));
		$debt->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
		if ($debt->pagado == 1) {
			$debt->payment_specific_date = date('Y-m-d');
		} elseif ($debt->pagado == 0) {
			$debt->payment_specific_date = null;
		}
		//Se realiza guardado de imagenes de pago y documento
		$debt->documento = "";
		$debt->document_number = mysqli_real_escape_string($con, (strip_tags($_POST["document_number"], ENT_QUOTES)));
		$debt->pago = "";
		$debt->fecha_pago = mysqli_real_escape_string($con, (strip_tags($_POST["payment_date"], ENT_QUOTES)));
		if (isset($_POST["document_image"]) && !empty($_POST["document_image"])) {
			$debt->documento = $_POST["document_image"];
		}
		if (isset($_POST["payment_image"]) && !empty($_POST["payment_image"])) {
			$debt->pago = $_POST["payment_image"];
		}

		$query_new = $debt->add();
		if (isset($query_new) && is_array($query_new) && $query_new[0]) {
			$messages[] = "La cuota " . ($i + 1) . " de la deuda ha sido agregada con éxito y fecha $debt->fecha.\n";
			$change_log = new ChangeLogData();
			$change_log->tabla = "debts";
			$change_log->registro_id = $query_new[1];
			$change_log->description = $debt->description;
			$change_log->amount = $debt->amount;
			$change_log->entidad = $debt->entidad;
			$change_log->fecha = $debt->fecha;
			$change_log->pagado = $debt->pagado;
			$change_log->active = $debt->active;
			$change_log->document_number = $debt->document_number;
			$change_log->user_id = $debt->user_id;
			$change_log->payment_date = $debt->fecha_pago;
			$result = $change_log->add();
			if (isset($result) && !empty($result) && is_array($result) && count($result) > 1 && $result[1] > 0) {
				$messages[] = " El registro de cambios ha sido actualizado satisfactoriamente para la cuota " . ($i + 1) . ".\n";
			} else {
				$errors[] = " Lo siento algo ha salido mal en el registro de errores para la cuota " . ($i + 1) . ".\n";
			}
		} else {
			$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo para la cuota " . ($i + 1) . ".\n";
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
			echo $error . '<br>';
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
			echo $message . '<br>';
		}
		?>
	</div>
<?php
}
?>
<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
//Se validan nuevos parametros de los ingresos
if (empty($_POST['amount'])) {
	$errors[] = "Cantidad está vacío.";
} elseif (empty($_POST['date'])) {
	$errors[] = "Fecha está vacío.";
} elseif (empty($_POST['entity'])) {
	$errors[] = "No ha seleccionado una entidad vacío.";
} elseif (
	!empty($_POST['amount'])
	&& !empty($_POST['date'])
	&& !empty($_POST['entity'])
) {
	$con = Database::getCon();
	$partner = new ResultData();
	$partner->description = mysqli_real_escape_string($con, (strip_tags($_POST["description"], ENT_QUOTES)));
	$partner->amount = mysqli_real_escape_string($con, (strip_tags($_POST["amount"], ENT_QUOTES)));
	$partner->user_id = $_SESSION['user_id'];
	$partner->empresa = $_SESSION['company_id'];
	$partner->entidad = intval($_POST['entity']);
	$partner->fecha = mysqli_real_escape_string($con, (strip_tags($_POST["date"], ENT_QUOTES)));
	$partner->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
	//Se realiza guardado de imagenes de pago y documento
	$partner->documento = "";
	$partner->pago = "";
	if ($partner->pagado) {
		$partner->pagado_con = mysqli_real_escape_string($con, (strip_tags($_POST["pay_with"], ENT_QUOTES)));
		$partner->payment_date = mysqli_real_escape_string($con, (strip_tags($_POST["payment_date"], ENT_QUOTES)));
		$partner->payment_specific_date = date('Y-m-d');
	} else {
		$partner->pagado_con = "";
		$partner->payment_specific_date = date('Y-m-d');
	}
	if (isset($_POST["document_image"]) && !empty($_POST["document_image"])) {
		$partner->documento = $_POST["document_image"];
	}

	if (isset($_POST["payment_image"]) && !empty($_POST["payment_image"])) {
		$partner->pago = $_POST["payment_image"];
	}

	$query_new = $partner->add();
	if (isset($_POST["debt"]) && !empty($_POST["debt"])) {
		$new_debt = json_decode($_POST["debt"]);
		$payment_fees = intval($new_debt->payment_fees);
		$payment_fees = $payment_fees == 0 ? 1 : $payment_fees;
		$amount = intval(mysqli_real_escape_string($con, (strip_tags($new_debt->amount, ENT_QUOTES))));
		$amount = ($amount / $payment_fees);
		$date_debts = mysqli_real_escape_string($con, (strip_tags($new_debt->date, ENT_QUOTES)));

		for ($i = 0; $i < $payment_fees; $i++) {
			$debt = new DebtsData();
			$debt->description = mysqli_real_escape_string($con, (strip_tags($new_debt->description, ENT_QUOTES)));
			$debt->description .= " (cuota " . ($i + 1) . " de " . $payment_fees . ")";
			$debt->amount = $amount;
			$debt->user_id = $_SESSION['user_id'];
			$debt->empresa = $_SESSION['company_id'];
			$debt->entidad = intval($new_debt->entity);
			$debt->tipo = intval($new_debt->type);
			$debt->fecha = date("Y-m-d H:i:s", strtotime($date_debts . "+" . $i . " month"));
			$debt->pagado = 1;
			$debt->socio_id = $query_new[1];
			if ($debt->pagado == 1) {
				$debt->payment_specific_date = date('Y-m-d');
			} elseif ($debt->pagado == 0) {
				$debt->payment_specific_date = null;
			}
			//Se realiza guardado de imagenes de pago y documento
			$debt->documento = "";
			$debt->document_number = mysqli_real_escape_string($con, (strip_tags($new_debt->document_number, ENT_QUOTES)));
			$debt->pago = "";
			$debt->fecha_pago = mysqli_real_escape_string($con, (strip_tags($new_debt->date, ENT_QUOTES)));
			if (isset($new_debt->document_image) && !empty($new_debt->document_image)) {
				$debt->documento = $new_debt->document_image;
			}
			if (isset($new_debt->payment_image) && !empty($new_debt->payment_image)) {
				$debt->pago = $new_debt->payment_image;
			}

			$query_response = $debt->add();
			var_dump($query_response);
			if (isset($query_response) && is_array($query_response) && $query_response[0]) {
				$messages[] = "La cuota " . ($i + 1) . " de la deuda ha sido agregada con éxito y fecha $debt->fecha.\n";
				$change_log = new ChangeLogData();
				$change_log->tabla = "debts";
				$change_log->registro_id = $query_response[1];
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
	}


	if ($query_new) {
		$messages[] = "El socio ha sido agregado con éxito.";
		$change_log = new ChangeLogData();
		$change_log->tabla = "result";
		$change_log->registro_id = $query_new[1];
		$change_log->description = $partner->description;
		$change_log->amount = $partner->amount;
		$change_log->entidad = $partner->entidad;
		$change_log->fecha = $partner->fecha;
		$change_log->pagado = $partner->pagado;
		$change_log->active = $partner->active;
		$change_log->payment_date = $partner->payment_date;
		$change_log->user_id = $partner->user_id;
		$result = $change_log->add();
		if (isset($result) && !empty($result) && is_array($result) && count($result) > 1 && $result[1] > 0) {
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
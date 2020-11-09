<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
///TODO Modificar para editar o agregar dependiendo de si existe un registro por entidad/mes/año
//Se validan nuevos parametros de los valores
if (empty($_POST['amount'])) {
	$errors[] = "Cantidad está vacío.";
} elseif (empty($_POST['date'])) {
	$errors[] = "Fecha está vacío.";
} elseif (empty($_POST['type_stock'])) {
	$errors[] = "No ha seleccionado el tipo de valor";
} elseif (empty($_POST['entity'])) {
	$errors[] = "No ha seleccionado una entidad vacío.";
} elseif (
	!empty($_POST['amount'])
	&& !empty($_POST['date'])
	&& !empty($_POST['type_stock'])
	&& !empty($_POST['entity'])
) {
	$con = Database::getCon();
	$object = array(
		'id' => 0,
		'description' => mysqli_real_escape_string($con, (strip_tags($_POST["description"], ENT_QUOTES))),
		'amount' => mysqli_real_escape_string($con, (strip_tags($_POST["amount"], ENT_QUOTES))),
		'entidad' => mysqli_real_escape_string($con, (strip_tags($_POST["entity"], ENT_QUOTES))),
		'date' => mysqli_real_escape_string($con, (strip_tags($_POST["date"], ENT_QUOTES))),
		'payment_date' => mysqli_real_escape_string($con, (strip_tags($_POST["date"], ENT_QUOTES))),
		'pagado' => 1,
		'document_number' => mysqli_real_escape_string($con, (strip_tags($_POST["document_number"], ENT_QUOTES))),
		'documento' => $_POST["document_image"],
		'pago' => null,
		'empresa' => $_SESSION['company_id'],
		'user_id' => $_SESSION['user_id'],
		'active' => true
	);
	$entity = mysqli_real_escape_string($con, (strip_tags($_POST["entity"], ENT_QUOTES)));
	$object['payment_specific_date'] = $object['pagado'] == 1 ? new DateTime('NOW') : null;
	$date_stock = strtotime($_POST['date']);
	$month = date('m', $date_stock);
	$year = date('Y', $date_stock);
	$sWhere = " empresa=" . $_SESSION['company_id'];
	if (isset($month) && !empty($month)) {
		$sWhere .= " and month(fecha) =" . $month;
	}
	if (isset($year) && !empty($year)) {
		$sWhere .= " and year(fecha) = " . $year;
	}
	if (isset($entity) && !empty($entity)) {
		$sWhere .= " and entidad = " . $entity;
	}
	$exist_stock = StockData::dynamicQuery($sWhere);
	if (isset($exist_stock) && !empty($exist_stock)) {
		$object['id'] = $exist_stock->id;
		$stock = StockData::getById($object['id']);
	} else {
		$stock = new StockData();
	}
	$query = $stock->addOrUpdate($object);

	if (isset($query) && is_array($query) && $query[0]) {
		$messages[] = "El valor ha sido agregada con éxito.\n";
		$change_log = new ChangeLogData();
		$change_log->tabla = "stocks";
		$change_log->registro_id = $query[1];
		$change_log->description = $stock->description;
		$change_log->amount = $stock->amount;
		$change_log->entidad = $stock->entidad;
		$change_log->fecha = $stock->fecha;
		$change_log->pagado = $stock->pagado;
		$change_log->active = $stock->active;
		$change_log->document_number = $stock->document_number;
		$change_log->user_id = $stock->user_id;
		$change_log->payment_date = $stock->fecha_pago;
		$result = $change_log->add();
		if (isset($result) && !empty($result) && is_array($result) && count($result) > 1 && $result[1] > 0) {
			$messages[] = " El registro de cambios ha sido actualizado satisfactoriamente";
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
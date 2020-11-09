<?php
if (empty($_POST['email'])) {
	$errors[] = "Correo Electrónico está vacío.";
} elseif (
	!empty($_POST['email'])
) {
	$con = Database::getCon();
	$email = mysqli_real_escape_string($con, (strip_tags($_POST["email"], ENT_QUOTES)));
	$mail = new Mail($email);
	$Where = " empresa = " . $_SESSION['company_id'] . "  and active=1 ";
	$stocks = StockData::dynamicQueryArray($Where . " and  MONTH(fecha) = MONTH(CURRENT_DATE())");
	$incomes = IncomeData::dynamicQueryArray($Where . " and pagado=0");
	$debts = DebtsData::dynamicQueryArray($Where . " and pagado=0 and   MONTH(fecha) = MONTH(CURRENT_DATE())");
	$expenses = ExpensesData::dynamicQueryArray($Where . " and pagado=0 and  MONTH(fecha_vence) = MONTH(CURRENT_DATE())");
	$last_month_expenses =  ExpensesData::dynamicQueryArray($Where . " and pagado=0 and  date_format(fecha_vence, '%Y-%m') = date_format(now() - interval 1 month, '%Y-%m')");
	$last_month_debts =  DebtsData::dynamicQueryArray($Where . " and pagado=0 and  date_format(fecha, '%Y-%m') = date_format(now() - interval 1 month, '%Y-%m')");
	$user = UserData::getById($_SESSION['user_id']);

	if (count($stocks) > 0) {
		$mail->message = "
		<h4><i class='fa fa-bank' style='margin-right:10px;'></i>Valores</h4>
		<table class='table table-bordered table-hover'>
			<thead>
				<th>Fecha</th>
				<th>Entidad</th>
				<th>Descripción</th>
				<th>Importe</th>
			</thead>
			<tbody>";
		$total = 0;
		foreach ($stocks as $stock) {
			$total += $stock->amount;

			$created_at = $stock->fecha;
			list($date) = explode(" ", $created_at);
			list($Y, $m, $d) = explode("-", $date);
			$date = $d . "-" . $m . "-" . $Y;

			$mail->message .= "	<tr><td>" . $date . "</td><td>";
			if ($stock->entidad != null) {
				$mail->message .= 	EntityData::getById($stock->entidad)->name;
			} else {
				$mail->message .=  "<center>----</center>";
			}
			$mail->message .= " </td><td>" . $stock->description . "</td><td>" . number_format($stock->amount, 2) . "</td></tr>";
		}
		$mail->message .= "</tbody>
			<tfoot>
				<tr>
					<td colspan='10'>
						<h4 class='pull-right'>Total Valores $" . $total . "</h4>
					</td>
				</tr>
			</tfoot>
		</table>";
	} else {
		$mail->message .= "<div class='alert alert-info alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
	    <strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>";
	}
	if (count($incomes) > 0) {
		$mail->message .= "<h4><i class='fa fa-usd' style='margin-right:10px;'></i>Ingresos</h4>

		<table class='table table-bordered table-hover'>
			<thead>
				<th>Descripcion</th>
				<th>Categoria</th>
				<th>Importe</th>
			</thead>
			<tbody>";
		$total = 0;
		foreach ($incomes as $income) {
			$total += $income->amount;

			$mail->message .= "<tr>
						<!-- Se  muestran los nombres de los campos dependiendo de los id's -->
						<td>" . $income->description . "</td>
						<td>" . $income->getCategory()->name . "</td>
						<td>" . number_format($stock->amount, 2) . "</td>
					</tr>";
		}
		$mail->message .= "</tbody>
			<tfoot>
				<tr>
					<td colspan='10'>
						<h4 class='pull-right'>Total Ingresos Pendientes $" . $total . "</h4>
					</td>
				</tr>
			</tfoot>
		</table>";
	} else {
		$mail->message .= "<div class='alert alert-info alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
	    <strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>";
	}
	$mail->subject = "Status" . $_SESSION['company_id'];
	$mail->message = "<div class='alert alert-info alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
	    <strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>";
	$resp_send = $mail->send();
	if ($resp_send) {
		$messages[] = " Se envía correo exitosamente";
	} else {
		$errors[] = "Lo sentimos, hubo un error al enviar el correo";
	}
} else {
	$errors[] = "desconocido.";
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

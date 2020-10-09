<?php
if (isset($_POST)) {
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$month = $_POST['month'] ? $_POST['month'] : 0;
	$year = $_POST['year'] ? $_POST['year'] : 0;
	$type_doc = $_POST['type_doc'];
	$text = $_POST['text'];
	$inactive = $_POST['inactive'] ?  $_POST['inactive'] : 0;
	$company_id = $_SESSION["company_id"];
	$sWhere = " empresa=$company_id and pagado=1 ";
	//Se construye la consulta sql dependiendo de los filtros ingresados

	if ($month != 0) {
		$sWhere .= " and month(payment_specific_date) =" . $month;
	}
	if ($year != 0) {
		$sWhere .= " and year(payment_specific_date) = " . $year;
	}
	if ($inactive=="false") {
		$sWhere .= " and active = 1 ";
	}
	
	if ($text != "") {
		$entities = EntityData::getLikeName($text, $company_id);
		if (is_array($entities) && count($entities) > 0) {
			$sWhere .= " and ( ";
			foreach ($entities as $entity) {
				$sWhere .= " entidad = $entity->id or";
			}
			$sWhere = substr($sWhere, 0, -2);
			$sWhere .= " ) ";
		} 
	}
	$result = [];
	$countResult = 0;
	if ($type_doc === "0" || $type_doc === 'expense') {
		$expenses = ExpensesData::dinamycQuery($sWhere);
		$countResult += count($expenses);
		array_push($result, $expenses);
	}
	if ($type_doc === "0" || $type_doc === 'income') {
		$income = IncomeData::dinamycQuery($sWhere);
		$countResult += count($income);
		array_push($result, $income);
	}
	if ($type_doc === "0" || $type_doc === 'partner') {
		$partner = ResultData::dinamycQuery($sWhere);
		$countResult += count($partner);
		array_push($result, $partner);
	}
	if ($type_doc === "0" || $type_doc === 'debt') {
		$debt = DebtsData::dinamycQuery($sWhere);
		$countResult += count($debt);
		array_push($result, $debt);
	}
	//var_dump($result);

	// si hay registro
	if ($countResult > 0) {
?>
		<table class="table table-bordered table-hover">
			<thead>
				<!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los egresos -->
				<th>Fecha carga pago</th>
				<th>Fecha pago</th>
				<th>Origen</th>
				<th>Entidad</th>
				<th>Importe</th>
				<th>Fecha documento</th>
				<th>Documento</th>
				<th>Ver</th>
			</thead>
			<tbody>
				<?php foreach ($result as $res) {
					foreach ($res as $doc) { ?>
						<tr <?php if ($doc->active == 0 || !$doc->active) {
								echo "style='background-color:pink;'";
							} ?>>
							<!-- Se  muestran los nombres de los campos dependiendo de los id's -->
							<td><?php if ($doc->payment_specific_date != null) {
									echo $doc->payment_specific_date;
								} else {
									echo "<center>----</center>";
								}  ?></td>
							<td><?php
								if ($doc->tipo_doc != null && $doc->tipo_doc === 'Deuda') {
									echo $doc->fecha_pago;
								} else {
									echo $doc->payment_date;
								}
								?></td>
							<td><?php echo $doc->tipo_doc; ?></td>
							<td><?php if ($doc->entidad != null) {
									echo EntityData::getById($doc->entidad)->name;
								} else {
									echo "<center>----</center>";
								}  ?></td>
							<td><?php echo number_format($doc->amount, 2); ?></td>
							<td><?php echo $doc->fecha; ?></td>
							<td><?php if ($doc->description != null) {
									echo $doc->description;
								} else {
									echo "<center>----</center>";
								}  ?></td>

							<td class="text-right">
								<a href="./?view=reportdetail&id=<?php echo $doc->id ?>&type=<?php echo $doc->tipo_doc; ?>" class="btn btn-success btn-square btn-xs"><i class="fa fa-eye"></i></a>
							</td>
						</tr>

				<?php }
				} ?>
			</tbody>
		</table>
	<?php
	} else {
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
	}
	?>

<?php
} else {
	echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
}
?>
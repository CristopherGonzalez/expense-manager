<?php
if (isset($_POST)) {
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$month = $_POST['month'] ?? 0;
	$year = $_POST['year'] ?? 0;
	$type_doc = $_POST['type_doc'];
	$text = $_POST['text'];
	$inactive = $_POST['inactive'] ?? 0;
	$company_id = $_SESSION["company_id"];
	$sWhere = " empresa=$company_id";
	//Se construye la consulta sql dependiendo de los filtros ingresados

	if ($type_doc != 0) {
		$sWhere .= " and tipo=" . $type_expense;
	}
	if ($month != 0) {
		$sWhere .= " and month(fecha) =" . $month;
	}
	if ($year != 0) {
		$sWhere .= " and year(fecha) = " . $year;
	}
	if ($text != "") {
		$sWhere .= " and description LIKE '%" . $text . "%' ";
	}
	if (!$not_paid) {
		$sWhere .= " and pagado = " . $not_paid;
	}
	if ($inactive == 1) {
		$sWhere .= " and active = $inactive ";
	}
	include 'res/resources/pagination.php'; //include pagination file
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

	$count_query = ExpensesData::countQuery($sWhere);
	$numrows = $count_query->numrows;
	$total_pages = ceil($numrows / $per_page);
	$reload = './?view=expenses';

	$query = ExpensesData::query($sWhere, $offset, $per_page);

	// si hay registro
	if (count($query) > 0) {
?>
		<table class="table table-bordered table-hover">
			<thead>
				<!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los egresos -->
				<th>Fecha Carga pago</th>
				<th>Fecha Origen</th>
				<th>Origen</th>
				<th>Entidad</th>
				<th>Importe</th>
				<th>Fecha Documento</th>
				<th>Documento</th>
				<th>Ver</th>
			</thead>
			<tbody>

				<tr <?php if ($exp->active == 0 || !$exp->active) {
						echo "style='background-color:pink;'";
					} ?>>
					<!-- Se  muestran los nombres de los campos dependiendo de los id's -->
					<td><?php echo $date; ?></td>
					<td><?php if ($exp->entidad != null) {
							echo $exp->getEntity()->name;
						} else {
							echo "<center>----</center>";
						}  ?></td>
					<td><?php echo $exp->description; ?></td>
					<td><?php echo number_format($exp->amount, 2); ?></td>
					<td><?php if ($exp->document_number != null) {
							echo $exp->document_number;
						} else {
							echo "<center>----</center>";
						}  ?></td>
					<td><?php if ($exp->pagado != null && $exp->pagado) {
							echo "<span style='color: #00a65a;'>Pagado</span>";
						} else {
							echo "<span>Impago</span>";
						}  ?></td>
					<td class="text-right">
						<a href="./?view=editexpense&id=<?php echo $exp->id ?>" class="btn btn-warning btn-square btn-xs"><i class="fa fa-edit"></i></a>
						<button type="button" class="btn btn-danger btn-square btn-xs" onclick="eliminar('<?php echo $exp->id; ?>')"><i class="fa fa-trash-o"></i></button>
					</td>
				</tr>
			<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan='10'>
						<?php
						$inicios = $offset + 1;
						$finales += $inicios - 1;
						echo "Mostrando $inicios al $finales de $numrows registros";
						echo paginate($reload, $page, $total_pages, $adjacents);
						?>
					</td>
				</tr>
			</tfoot>
		</table>
	<?php
} else {
	echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
}
	?>
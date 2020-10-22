<?php
if (isset($_REQUEST["id"])) { //codigo para eliminar 
	$id = $_REQUEST["id"];
	$id = intval($id);
	$debt = DebtsData::getById($id);
	$delete = DebtsData::updateStatus($id, 0);
	if ($delete == 1) {
		$aviso = "Bien hecho!";
		$msj = "Datos eliminados satisfactoriamente.";
		$classM = "alert alert-success";
		$times = "&times;";
		$change_log = new ChangeLogData();
		$change_log->tabla = "debts";
		$change_log->registro_id = $id;
		$change_log->description = $debt->description;
		$change_log->amount = $debt->amount;
		$change_log->entidad = $debt->entidad;
		$change_log->fecha = $debt->fecha;
		$change_log->pagado = $debt->pagado;
		$change_log->active = 0;
		$change_log->document_number = $debt->document_number;
		$change_log->user_id = $debt->user_id;
		$change_log->payment_date = $debt->fecha_pago;

		$result = $change_log->add();
		if (isset($result) && !empty($result) && is_array($result) && count($result) > 1 && $result[1] > 0) {
			$messages[] = " El registro de cambios ha sido actualizado satisfactoriamente";
		} else {
			$errors[] = " Lo siento algo ha salido mal en el registro de errores.";
		}
	} else {
		$aviso = "Aviso!";
		$msj = "Error al eliminar los datos ";
		$classM = "alert alert-danger";
		$times = "&times;";
	}
}
?>
<?php
$con = Database::getCon();
//Se capturan los datos enviados por ajax
$month = intval($_REQUEST['month']);
$year = intval($_REQUEST['year']);
$type_debt = intval($_REQUEST['type_debt']);
$text = mysqli_real_escape_string($con, (strip_tags($_REQUEST['text'], ENT_QUOTES)));
$not_paid = (isset($_REQUEST['payment']) && $_REQUEST['payment'] == "true") ? 0 : 1;
$inactive = (isset($_REQUEST['inactive']) && $_REQUEST['inactive'] == "true") ? 0 : 1;
$company_id = $_SESSION["company_id"];
$sWhere = " empresa=$company_id";
if ($type_debt != "" || $type_debt != 0) {
	//Se busca en tabla entidades para obtener por tipo
	$result_entities = EntityData::getByIdType($type_debt, $company_id);
	$count = count($result_entities);
	//Se crea query dependiendo de los resultados
	$sWhere .= " and  ( ";
	if ($count > 0) {
		foreach ($result_entities as $index => $entity) {
			$sWhere .= " entidad = $entity->id or";
		}
		$sWhere = substr($sWhere, 0, -2);
	} else {
		$sWhere .= " entidad = 0 ";
	}
	$sWhere .= " ) ";
}

if ($month != 0) {
	$sWhere .= " and month(fecha) =" . $month;
}
if ($year != 0) {
	$sWhere .= " and year(fecha) = " . $year;
}
if ($text != "") {
	$sWhere .= " and (LOWER(description) LIKE LOWER('%" . $text . "%') or LOWER(document_number) LIKE LOWER('%" . $text . "%') ) ";
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

$count_query = DebtsData::countQuery($sWhere);
$numrows = $count_query->numrows;
$total_pages = ceil($numrows / $per_page);
$reload = './?view=debt';
$query = DebtsData::query($sWhere, $offset, $per_page);
?>
<?php
if (isset($_REQUEST["id"])) {
?>
	<div class="<?php echo $classM; ?>">
		<button type="button" class="close" data-dismiss="alert"><?php echo $times; ?></button>
		<strong><?php echo $aviso ?> </strong>
		<?php echo $msj; ?>
	</div>
<?php
}
// si hay registro
if (count($query) > 0) {
?>
	<table class="table table-bordered table-hover">
		<thead>
			<!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los egresos -->
			<th>Fecha</th>
			<th>Entidad</th>
			<th>Descripción</th>
			<th>Importe</th>
			<th>Documento</th>
			<th>Pago</th>
			<th></th>
		</thead>
		<tbody>
			<?php
			$finales = 0;
			foreach ($query as $debt) {

				$created_at = $debt->fecha;
				list($date) = explode(" ", $created_at);
				list($Y, $m, $d) = explode("-", $date);
				$date = $d . "-" . $m . "-" . $Y;

				$finales++;
			?>
				<tr <?php if ($debt->active == 0 || !$debt->active) {
						echo "style='background-color:pink;'";
					} ?>>
					<!-- Se  muestran los nombres de los campos dependiendo de los id's -->
					<td><?php echo $date; ?></td>
					<td><?php if ($debt->entidad != null) {
							echo EntityData::getById($debt->entidad)->name;
						} else {
							echo "<center>----</center>";
						}  ?></td>
					<td><?php echo $debt->description; ?></td>
					<td><?php echo number_format($debt->amount, 2); ?></td>
					<td><?php if ($debt->document_number != null) {
							echo $debt->document_number;
						} else {
							echo "<center>----</center>";
						}  ?></td>
					<td><?php if ($debt->pagado != null && $debt->pagado) {
							echo "<span style='color: #00a65a;'>Pagado</span>";
						} else {
							echo "<span>Impago</span>";
						}  ?></td>
					<td class="text-right">
						<a href="./?view=editdebt&id=<?php echo $debt->id ?>" class="btn btn-warning btn-square btn-xs"><i class="fa fa-edit"></i></a>
						<?php if ($debt->active == 1 || $debt->active) { ?>
							<button type="button" class="btn btn-danger btn-square btn-xs" onclick="eliminar('<?php echo $debt->id; ?>')"><i class="fa fa-trash-o"></i></button>
						<?php } ?>

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
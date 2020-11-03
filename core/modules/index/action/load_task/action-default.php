<?php
if (isset($_REQUEST["id"])) { //codigo para eliminar 
	$id = $_REQUEST["id"];
	$id = intval($id);

	$count = 0;
	$query_validate = Core::getQuantityLinkageElements(TaskData::getById($id));
	if (isset($query_validate) && !empty($query_validate) && $query_validate) {
		$count = 1;
	}

	if ($count == 0) {
		$delete = TaskData::delete($id);
		if ($delete == 1) {
			$aviso = "Bien hecho!";
			$msj = "Datos eliminados satisfactoriamente.";
			$classM = "alert alert-success";
			$times = "&times;";
		} else {
			$aviso = "Aviso!";
			$msj = "Error al eliminar los datos ";
			$classM = "alert alert-danger";
			$times = "&times;";
		}
	} else {
		$aviso = "Aviso!";
		$msj = "Error al eliminar los datos. la materia se encuentra vinculada con la tabla calificaciones.";
		$classM = "alert alert-danger";
		$times = "&times;";
	}
}
?>
<?php
$con = Database::getCon();
$name = mysqli_real_escape_string($con, (strip_tags($_REQUEST['name_task'], ENT_QUOTES)));
$pendings = $_REQUEST['only_pendings'];
$company_id = $_SESSION["company_id"];
//$sWhere=" user_id>0 ";
$sWhere = " empresa=$company_id ";
//Creacion de query por nombre y/o egreso
if ($name != "") {
	$sWhere .= " and (LOWER(tarea) LIKE LOWER('%" . $name . "%') )";
}
if ($pendings == 'true') {
	$sWhere .= " and hecho = false ";
}
include 'res/resources/pagination.php'; //include pagination file
$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
$per_page = intval($_REQUEST['per_page']); //how much records you want to show
$adjacents  = 4; //gap between pages after number of adjacents
$offset = ($page - 1) * $per_page;
$count_query = TaskData::countQuery($sWhere);
$numrows = $count_query->numrows;
$total_pages = ceil($numrows / $per_page);
$reload = './?view=task';

$tasks = TaskData::query($sWhere, $offset, $per_page);
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
if (count($tasks) > 0) {
?>
	<table class="table table-bordered table-hover">
		<thead>
			<th>Tarea</th>
			<th>Hecho</th>
			<th>Usuario</th>
			<th></th>
		</thead>
		<tbody>
			<?php
			$finales = 0;
			foreach ($tasks as $task) {

				$finales++;
			?>
				<tr>
					<td style="max-width: 350px;"><?php echo $task->tarea; ?></td>
					<td><input type="checkbox" disabled <?php if ($task->hecho == true) {
															echo "checked";
														}  ?>></td>

					<td><?php echo UserData::getById($task->user_id)->name; ?></td>

					<td class="text-right">
						<a href="./?view=edittask&id=<?php echo $task->id ?>" class="btn btn-warning btn-square btn-xs"><i class="fa fa-edit"></i></a>
						<button type="button" class="btn btn-danger btn-square btn-xs" onclick="eliminar('<?php echo $task->id; ?>')"><i class="fa fa-trash-o"></i></button>

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
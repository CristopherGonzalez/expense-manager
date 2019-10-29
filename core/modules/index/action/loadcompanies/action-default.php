<?php
if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);

	$delete=ExpensesData::delete($id);
	if($delete==1){
		$aviso="Bien hecho!";
		$msj="Datos eliminados satisfactoriamente.";
		$classM="alert alert-success";
		$times="&times;";	
	}else{
		$aviso="Aviso!";
		$msj="Error al eliminar los datos ";
		$classM="alert alert-danger";
		$times="&times;";					
	}
}
?>
<?php
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$type_bussiness = intval($_REQUEST['type_bussiness']);
	$text = mysqli_real_escape_string($con,(strip_tags($_REQUEST['text'], ENT_QUOTES)));
	$licenseMRC = mysqli_real_escape_string($con,(strip_tags($_REQUEST['license'], ENT_QUOTES)));
	//$sWhere=" user_id=$user_id ";
	$sWhere=" is_deleted = false ";
	//Se construye la consulta sql dependiendo de los filtros ingresados
	if($type_bussiness!=0){
		$sWhere.=" and tipo_negocio=".$type_bussiness;
	}
	if($licenseMRC!=""){
		$sWhere.=" and licenciaMRC LIKE '%".$licenseMRC."%' ";
	}
	if($text!=""){
		$sWhere.=" and name LIKE '%".$text."%' ";
	}
	

	include 'res/resources/pagination.php'; //include pagination file
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

	$count_query=CompanyData::countQuery($sWhere);
	$numrows = $count_query->numrows;
	$total_pages = ceil($numrows/$per_page);
	$reload = './?view=companies';

	$query=CompanyData::query($sWhere, $offset,$per_page);
?>
<?php 
	if (isset($_REQUEST["id"])){
?>
	<div class="<?php echo $classM;?>">
		<button type="button" class="close" data-dismiss="alert"><?php echo $times;?></button>
		<strong><?php echo $aviso?> </strong>
		<?php echo $msj;?>
	</div>	
<?php 
	}
	// si hay registro
	if(count($query)>0){ 
?>
<table class="table table-bordered table-hover">
	<thead>
		<!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los gastos -->
		<th>Nombre</th>
		<th>Licencia MRC</th>
		<th>Estado</th>
		<th>Tipo de Negocio</th>
		<th>Usuarios Activos</th>
		<th></th>
	</thead>
	<tbody>
		<?php
		 	$finales=0;
		 	foreach($query as $com){
			$users = " empresa = $com->id";
			$count_users = UserData::countQuery($users);

			$finales++;
		?>
		<tr>
			<!-- Se  muestran los nombres de los campos dependiendo de los id's -->
			<td><?php echo $com->name; ?></td>
			<td><?php echo $com->licenciaMRC; ?></td>
			<td><?php if($com->status!=null && ($com->status=="1") && $com->is_deleted!=null && $com->is_deleted == "0"){echo "Habilitada";}else{ echo "Inhabilitada"; }  ?></td>
			<td><?php if($com->tipo_negocio!=null && !empty($com->tipo_negocio)){echo BusinessTypeData::getById($com->tipo_negocio)->name;}else{ echo "<center>----</center>"; }  ?></td>
			<td><?php if($count_users!=null && !empty($count_users) && isset($count_users)){echo $count_users->numrows; }else{ echo "0"; }  ?></td>
			<td class="text-right">
                <a href="./?view=editcompany&id=<?php echo $com->id ?>" class="btn btn-warning btn-square btn-xs"><i class="fa fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-square btn-xs" onclick="eliminar('<?php echo $com->id;?>')"><i class="fa fa-trash-o"></i></button>
            </td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
        <tr>
			<td colspan='10'> 
				<?php 
					$inicios=$offset+1;
					$finales+=$inicios -1;
					echo "Mostrando $inicios al $finales de $numrows registros";
					echo paginate($reload, $page, $total_pages, $adjacents);
				?>
			</td>
		</tr>
	</tfoot>
</table>
<?php
}else{
	echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	    <strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
}
?>
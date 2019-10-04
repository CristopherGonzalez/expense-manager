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
	$month = intval($_REQUEST['month']);
	$year = intval($_REQUEST['year']);
	$type_expense = intval($_REQUEST['type_expense']);
	$category = intval($_REQUEST['category']);
	$text = mysqli_real_escape_string($con,(strip_tags($_REQUEST['text'], ENT_QUOTES)));
	$not_paid = (isset($_REQUEST['payment']) && $_REQUEST['payment'] == "true") ? 0 : 1;
	$user_id=$_SESSION["user_id"];
	$sWhere=" user_id=$user_id ";

	//Se construye la consulta sql dependiendo de los filtros ingresados
	if($category!=0){
		$sWhere.=" and category_id=".$category;
	}
	if($type_expense!=0){
		$sWhere.=" and tipo=".$type_expense;
	}
	if($month!=0){
		$sWhere.=" and month(fecha) =".$month;
	}
	if($year!=0){
		$sWhere.=" and year(fecha) = ".$year;
	}
	if($text!=""){
		$sWhere.=" and description LIKE '%".$text."%' ";
	}
	if(!$not_paid){
		$sWhere.=" and pagado = ".$not_paid;
	}

	include 'res/resources/pagination.php'; //include pagination file
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

	$count_query=ExpensesData::countQuery($sWhere);
	$numrows = $count_query->numrows;
	$total_pages = ceil($numrows/$per_page);
	$reload = './?view=expenses';

	$query=ExpensesData::query($sWhere, $offset,$per_page);
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
		<th>Fecha</th>
		<th>Descripción</th>
		<th>Importe</th>
		<th>Categoría</th>
		<th>Entidad</th>
		<th>Tipo de Gasto</th>
		<th>Estado Pago</th>
		<th></th>
	</thead>
	<tbody>
		<?php
		 	$finales=0;
		 	foreach($query as $exp){

		 	$created_at=$exp->created_at;
            list($date)=explode(" ",$created_at);
            list($Y,$m,$d)=explode("-",$date);
            $date=$d."-".$m."-".$Y;

			$finales++;
		?>
		<tr>
			<!-- Se  muestran los nombres de los campos dependiendo de los id's -->
			<td><?php echo $date; ?></td>
			<td><?php echo $exp->description; ?></td>
			<td><?php echo number_format($exp->amount,2); ?></td>
			<td><?php if($exp->category_id!=null){echo $exp->getCategory()->name;}else{ echo "<center>----</center>"; }  ?></td>
			<td><?php if($exp->entidad!=null){echo $exp->getEntity()->name;}else{ echo "<center>----</center>"; }  ?></td>
			<td><?php if($exp->tipo!=null){echo $exp->getTypeExpense()->name;}else{ echo "<center>----</center>"; }  ?></td>
			<td><?php if($exp->pagado!=null && $exp->pagado){echo "<center>Pagado</center>"; }else{ echo "<center>Impago</center>"; }  ?></td>
			<td class="text-right">
                <a href="./?view=editexpense&id=<?php echo $exp->id ?>" class="btn btn-warning btn-square btn-xs"><i class="fa fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-square btn-xs" onclick="eliminar('<?php echo $exp->id;?>')"><i class="fa fa-trash-o"></i></button>
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
<?php
if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);
	$income=IncomeData::getById($id);
	$delete=IncomeData::updateStatus($id,0);
	if($delete==1){
		$aviso="Bien hecho!";
		$msj="Datos eliminados satisfactoriamente.";
		$classM="alert alert-success";
		$times="&times;";	
		$change_log = new ChangeLogData();
		$change_log->tabla = "income";
		$change_log->registro_id = $id;
		$change_log->description = $income->description;
		$change_log->amount = $income->amount;
		$change_log->entidad = $income->entidad;
		$change_log->fecha = $income->fecha;
		$change_log->pagado = $income->pagado;
		$change_log->active = 0;
		$change_log->document_number = $income->document_number;
		$change_log->payment_date = $income->payment_date;
		$change_log->user_id = $income->user_id;
		$result = $change_log->add();
		if (isset($result) && !empty($result) && $result[0]){
			$messages[] = " El registro de cambios ha sido actualizado satisfactoriamente";
		} else{
			$errors []= " Lo siento algo ha salido mal en el registro de errores.";
		}
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
	$type_income = intval($_REQUEST['type_income']);
	$category = intval($_REQUEST['category']);
	$text = mysqli_real_escape_string($con,(strip_tags($_REQUEST['text'], ENT_QUOTES)));
	$inactive = (isset($_REQUEST['inactive']) && $_REQUEST['inactive'] == "true") ? 0 : 1;
	$not_paid = (isset($_REQUEST['payment']) && $_REQUEST['payment'] == "true") ? 0 : 1;
	//$user_id=$_SESSION["user_id"];
	//$sWhere=" user_id>0 ";
	//$sWhere=" user_id=$user_id ";
	$company_id=$_SESSION["company_id"];
	$sWhere=" empresa=$company_id";
	//Se construye la consulta sql dependiendo de los filtros ingresados
	if($category!=0){
		$sWhere.=" and category_id=".$category;
	}
	if($type_income!=0){
		$sWhere.=" and tipo=".$type_income;
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
	if($inactive==1){
		$sWhere.=" and active = $inactive ";
	}
	include 'res/resources/pagination.php'; //include pagination file
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

	$count_query=IncomeData::countQuery($sWhere);
	$numrows = $count_query->numrows;
	$total_pages = ceil($numrows/$per_page);
	$reload = './?view=income';

	$query_sql=IncomeData::query($sWhere, $offset,$per_page);
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
	if(count($query_sql)>0){ 
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
		 	$finales=0;
		 	foreach($query_sql as $inc){

		 	$created_at=$inc->fecha;
            list($date)=explode(" ",$created_at);
            list($Y,$m,$d)=explode("-",$date);
            $date=$d."-".$m."-".$Y;

			$finales++;
		?>
		<tr <?php if($inc->active==0 || !$inc->active ){echo "style='background-color:pink;'"; }?>>
			<!-- Se  muestran los nombres de los campos dependiendo de los id's -->
			<td><?php echo $date; ?></td>
			<td><?php if($inc->entidad!=null){echo $inc->getEntity()->name;}else{ echo "<center>----</center>"; }  ?></td>
			<td><?php echo $inc->description; ?></td>
			<td><?php echo number_format($inc->amount,2); ?></td>
			<td><?php if($inc->document_number!=null){echo $inc->document_number;}else{ echo "<center>----</center>"; }  ?></td>
			<td><?php if($inc->pagado!=null && $inc->pagado){echo "<center style='color: #00a65a;'>Pagado</center>"; }else{ echo "<center>Impago</center>"; }  ?></td>
			<td class="text-right">
                <a href="./?view=editincome&id=<?php echo $inc->id ?>" class="btn btn-warning btn-square btn-xs"><i class="fa fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-square btn-xs" onclick="eliminar('<?php echo $inc->id;?>')"><i class="fa fa-trash-o"></i></button>
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
<?php
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	
	$id_registro = intval($_REQUEST['id_registro']);
	$table_name = mysqli_real_escape_string($con,(strip_tags($_REQUEST['table'], ENT_QUOTES)));
	$sWhere=" id <> 0";
	//Se construye la consulta sql dependiendo de los parametros ingresados
	if(isset($id_registro) && !empty($id_registro)!=0){
		$sWhere.=" and registro_id=".$id_registro;
	}
	if(isset($table_name) && !empty($table_name)!=0){
		$sWhere.=" and tabla='".$table_name."'";
	}
	

	$query=ChangeLogData::query($sWhere);
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
		<!-- Se cambia estructura de la tabla para mostrar nuevos parametros en los egresos -->
		<th>Fecha</th>
		<th>Descripción</th>
		<th>Importe</th>
		<th>Entidad</th>
		<th>Usuario</th>
		<th>Estado Pago</th>
		<?php if($table_name !="result"){echo"<th>Documento</th>";} ?>
		<th>Fecha modificacion</th>
	</thead>
	<tbody>
		<?php
		 	$finales=0;
		 	foreach($query as $log){

		 	$created_at=$log->fecha;
            list($date)=explode(" ",$created_at);
            list($Y,$m,$d)=explode("-",$date);
            $date=$d."-".$m."-".$Y;

			$finales++;
		?>
		<tr>
			<!-- Se  muestran los nombres de los campos dependiendo de los id's -->
			<td><?php echo $date; ?></td>
			<td><?php echo $log->description; ?></td>
			<td><?php echo number_format($log->amount,2); ?></td>
			<td><?php if($log->entidad!=null){echo  EntityData::getById($log->entidad)->name; }else{ echo "<center>----</center>"; }  ?></td>
			<td><?php if($log->user_id!=null){echo UserData::getById($log->user_id)->name; }else{ echo "<center>----</center>"; }  ?></td>
			<td><?php if($log->pagado!=null && $log->pagado){echo "<span style='color: #00a65a;'>Pagado</span>"; }else{ echo "<span>Impago</span>"; }  ?></td>
			<?php if($table_name !="result"){ ?>
				<td><?php if(isset($log->document_number) && !empty($log->document_number)){echo $log->document_number; }else{ echo "<center>----</center>"; }  ?></td>
			<?php } ?>
			<td><?php if($log->created_at!=null && isset($log->created_at)){echo $log->created_at; }else{ echo "<center>----</center>"; }  ?></td>
		</tr>
		<?php } ?>
	</tbody>
	
</table>
<?php
}else{
	echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	    <strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
}
?>
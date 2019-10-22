<?php
if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);
	$ocuped_entity = false;
	$delete=0;
	if(is_array(EntityData::getExpensesByIdEntity($id)) && count(EntityData::getExpensesByIdEntity($id))>0){
		$ocuped_entity = true;
	}
	elseif(is_array(EntityData::getIncomeByIdEntity($id)) && count(EntityData::getIncomeByIdEntity($id))>0){
		$ocuped_entity = true;
	}
	elseif(is_array(EntityData::getPartnerByIdEntity($id)) && count(EntityData::getPartnerByIdEntity($id))>0){
		$ocuped_entity = true;
	}

	if(!$ocuped_entity){
		$delete=EntityData::delete($id);
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
	}else{
		$aviso="Aviso!";
		$msj="Error al eliminar los datos. la materia se encuentra vinculada.";
		$classM="alert alert-danger";
		$times="&times;";
	}
}

?>
<?php
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$type = intval($_REQUEST['type']);
	$category = intval($_REQUEST['category']);
	$category_type = mysqli_real_escape_string($con,(strip_tags($_REQUEST['category_type'], ENT_QUOTES)));
	$text = mysqli_real_escape_string($con,(strip_tags($_REQUEST['text'], ENT_QUOTES)));
	//$user_id=$_SESSION["user_id"];
	//$sWhere=" user_id=$user_id ";
	$company_id=$_SESSION["company_id"];
	$sWhere=" empresa=$company_id ";
	//Se construye la consulta sql dependiendo de los filtros ingresados
	if($type!=0){
		$sWhere.=" and tipo=".$type;
	}
	if($category_type!=""){
		//Se busca en tabla tipos para obtener por nombre
		$result_types=TypeData::getByType($category_type);
		$count=count($result_types);
		//Se crea query dependiendo de los resultados
		$sWhere.=" and  ( ";
		if($count>0){
			foreach($result_types as $index => $type){
				$sWhere.=" tipo = $type->id or";
			}
			$sWhere=substr($sWhere,0,-2);
		}else{
			//Se envia tipo = 0 para que consulta no de resultado en caso de tener texto en campo de gastos
			$sWhere.= " tipo = 0 ";
		}
		$sWhere.= " ) ";

	}
	if($category!=0){
		$sWhere.=" and category_id=".$category;
	}
	if($text!=""){
		$sWhere.=" and name LIKE '%".$text."%' ";
	}

	include 'res/resources/pagination.php'; //include pagination file
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

	$count_query=EntityData::countQuery($sWhere);
	$numrows = $count_query->numrows;
	$total_pages = ceil($numrows/$per_page);
	$reload = './?view=entities';

	$query=EntityData::query($sWhere, $offset,$per_page);
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
		<th>Nombre</th>
		<th>Origen</th>
		<th>Categoría</th>
		<th>Tipo</th>
		<th></th>
	</thead>
	<tbody>
		<?php
		 	$finales=0;
		 	foreach($query as $ent){

		 	$created_at=$ent->created_at;
            list($date)=explode(" ",$created_at);
            list($Y,$m,$d)=explode("-",$date);
            $date=$d."-".$m."-".$Y;

			$finales++;
		?>
		<tr>
			<!-- Se  muestran los nombres de los campos dependiendo de los id's -->
			<td><?php echo $date; ?></td>
			<td><?php echo $ent->name; ?></td>
			<td><?php echo $ent->getType($ent->tipo)->tipo ?></td>
			<td>
				<?php 
					if($ent->category_id!=null){
						if(!strcmp($ent->getType($ent->tipo)->tipo,"Gasto")){
							echo $ent->getCategoryExpense()->name;
						}
						if(!strcmp($ent->getType($ent->tipo)->tipo,"Ingreso")){
							echo $ent->getCategoryIncome()->name;
						}
						if(!strcmp($ent->getType($ent->tipo)->tipo,"Socio")){
							echo $ent->getType($ent->tipo)->tipo;
						}
					}else{
						 echo "<center>----</center>"; 
					}  
				?>
			</td>
			<td><?php if($ent->tipo!=null){echo $ent->getType($ent->tipo)->name;}else{ echo "<center>----</center>"; }  ?></td>
			<td class="text-right">
                <a href="./?view=editentity&id=<?php echo $ent->id ?>" class="btn btn-warning btn-square btn-xs"><i class="fa fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-square btn-xs" onclick="eliminar('<?php echo $ent->id;?>')"><i class="fa fa-trash-o"></i></button>
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
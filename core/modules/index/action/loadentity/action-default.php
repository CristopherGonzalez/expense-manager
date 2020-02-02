<?php
if (isset($_POST["id"])){//codigo para eliminar 
	$id=$_POST["id"];
	$id=intval($id);
	$ocuped_entity = false;
	$delete=0;
	$ocuped_entity=Core::getQuantityLinkageElements(EntityData::getById($id));

	if(!$ocuped_entity){
		$delete=EntityData::updateStatus($id,0);
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
		$msj="Error al eliminar los datos. La materia se encuentra vinculada.";
		$classM="alert alert-danger";
		$times="&times;";
	}
}

?>
<?php
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$type = intval($_POST['type']);
	$category = intval($_POST['category']);
	$inactive = $_POST['inactive'];
	$category_type = mysqli_real_escape_string($con,(strip_tags($_POST['category_type'], ENT_QUOTES)));
	$text = mysqli_real_escape_string($con,(strip_tags($_POST['text'], ENT_QUOTES)));
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
			//Se envia tipo = 0 para que consulta no de resultado en caso de tener texto en campo de egresos
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
	if($inactive=="true"){
		$sWhere.=" and active=0";
	}else{
		$sWhere.=" and active=1";
	}
	include 'res/resources/pagination.php'; //include pagination file
	$page = (isset($_POST['page']) && !empty($_POST['page']))?$_POST['page']:1;
	$per_page = intval($_POST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

	$count_query=EntityData::countQuery($sWhere);
	$numrows = $count_query->numrows;
	$total_pages = ceil($numrows/$per_page);
	$reload = './?view=entities';

	$query=EntityData::query($sWhere, $offset,$per_page);
?>
<?php 
	if (isset($_POST["id"])){
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
					if($ent->category_id!=null && $ent->category_id!=0){
						if(!strcmp($ent->getType($ent->tipo)->tipo,"Egreso")){
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
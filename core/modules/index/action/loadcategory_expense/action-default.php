<?php
if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);

	$query_validate=ExpensesData::getByCategoryId($id);
	//TODO $count = count($query_validate) Se debe reestablecer pero necesita cambiar antes Expense y todo el MCV relacionado
	$count=0;
	if ($count==0){
		$delete=CategoryExpenseData::delete($id);
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
		$msj="Error al eliminar los datos. la materia se encuentra vinculada con la tabla calificaciones.";
		$classM="alert alert-danger";
		$times="&times;";
	}		
}
?>
<?php
	$con = Database::getCon();
	$name = mysqli_real_escape_string($con,(strip_tags($_REQUEST['f_name'], ENT_QUOTES)));
	$type_expense = mysqli_real_escape_string($con,(strip_tags($_REQUEST['f_type_expense'], ENT_QUOTES)));
	$user_id=$_SESSION["user_id"];
	//$sWhere=" user_id>0 ";
	$sWhere=" user_id=$user_id ";
	//Creacion de query por nombre y/o gasto
	if($name!=""){
		$sWhere.=" and name LIKE '%".$name."%' ";
	}
	if($type_expense!=""){
		//Se busca en tabla tipos para obtener por nombre
		$result_types=TypeData::getLike($type_expense, 'Gasto');
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
	include 'res/resources/pagination.php'; //include pagination file
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	$count_query=CategoryExpenseData::countQuery($sWhere);
	$numrows = $count_query->numrows;
	$total_pages = ceil($numrows/$per_page);
	$reload = './?view=expenses';

	$query=CategoryExpenseData::query($sWhere, $offset,$per_page);
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
		<th>Nombre</th>
		<th>Tipo Gasto</th>
		<th>Fecha</th>
		<th></th>
	</thead>
	<tbody>
		<?php
		 	$finales=0;
		 	foreach($query as $cat){

		 	$created_at=$cat->created_at;
            list($date,$time)=explode(" ",$created_at);
            list($Y,$m,$d)=explode("-",$date);
            $date=$d."-".$m."-".$Y;

			$finales++;
		?>
		<tr>
			<td><?php echo $cat->name; ?></td>
			<!--Se muestra el nombre po id en el grid del historial-->
			<td><?php if($cat->tipo!=null){echo $cat->getTypeExpense()->name;}else{ echo "<center>----</center>"; }  ?></td>
			<td><?php echo $date." ".$time; ?></td>
			<td class="text-right">
                <a href="./?view=editcategory_expense&id=<?php echo $cat->id ?>" class="btn btn-warning btn-square btn-xs"><i class="fa fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-square btn-xs" onclick="eliminar('<?php echo $cat->id;?>')"><i class="fa fa-trash-o"></i></button>
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
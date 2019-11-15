<?php
if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);
	$delete=UserData::delete($id);
	if($delete){
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
	$name = mysqli_real_escape_string($con,(strip_tags($_REQUEST['f_name'], ENT_QUOTES)));
	$company_id=$_SESSION["company_id"];
	//$sWhere=" user_id>0 ";
	$sWhere=" empresa=$company_id ";
	$sWhere=" status in (3,4) ";
	//Creacion de query por nombre y/o egreso
	if($name!=""){
		$sWhere.=" and name LIKE '%".$name."%' ";
	}
	
	include 'res/resources/pagination.php'; //include pagination file
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	$count_query=UserData::countQuery($sWhere);
	$numrows = $count_query->numrows;
	$total_pages = ceil($numrows/$per_page);
	$reload = './?view=users';

	$users=UserData::query($sWhere, $offset,$per_page);
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
	if(count($users)>0){ 
?>
<table class="table table-bordered table-hover">
	<thead>
		<th>Nombre</th>
		<th>Estado/Tipo</th>
		<th>Fecha</th>
		<th></th>
	</thead>
	<tbody>
		<?php
		 	$finales=0;
		 	foreach($users as $user){

		 	$created_at=$user->created_at;
            list($date,$time)=explode(" ",$created_at);
            list($Y,$m,$d)=explode("-",$date);
            $date=$d."-".$m."-".$Y;

			$finales++;
		?>
		<tr>
			<td><?php echo $user->name; ?></td>
			<!--Se muestra el nombre por id en el grid del historial-->
			<td>
				<?php 
					if($user->status!=null){
						if($user->status ==3 ){ echo "Activo";}
						if($user->status ==3 && $user->is_admin == 1){ echo " - Administrador";}
						if($user->status ==4 ){ echo "Inhabilitado";}
					}else{ 
						echo "<center>----</center>"; 
					}  
				?>
			</td>
			<td><?php echo $date." ".$time; ?></td>
			<td class="text-right">
                <a href="./?view=edituser&id=<?php echo $user->id ?>" class="btn btn-warning btn-square btn-xs"><i class="fa fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-square btn-xs" onclick="eliminar('<?php echo $user->id;?>')"><i class="fa fa-trash-o"></i></button>
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
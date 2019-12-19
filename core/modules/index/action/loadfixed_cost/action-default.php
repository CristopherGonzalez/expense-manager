<?php
if(isset($_SESSION["user_id"]) && isset($_SESSION["company_id"])){
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$month = mysqli_real_escape_string($con,(strip_tags($_REQUEST['month'], ENT_QUOTES)));
	$year = mysqli_real_escape_string($con,(strip_tags($_REQUEST['year'], ENT_QUOTES)));
	if(isset($_REQUEST['opt_type']) && !empty($_REQUEST['opt_type']) && is_array($_REQUEST['opt_type'])){
		$opts_type = $_REQUEST['opt_type'];
	}else{
		$opts_type = "";
	}
	
	$sWhere=" id <> 0";
	$sWhere.=" and empresa=".$_SESSION["company_id"]." and active=1 ";

	if(isset($opts_type) && !empty($opts_type) && is_array($opts_type)){
		$sWhere.=" and  ( ";
		foreach($opts_type as $opt){
			$sWhere.=" tipo = $opt or";
		}
		$sWhere=substr($sWhere,0,-2);
		$sWhere.=")";
		
	}
	if($month!=0){
		$sWhere.=" and month(fecha) =".$month;
	}
	if($year!=0){
		$sWhere.=" and year(fecha) = ".$year;
	}

	$query=ExpensesData::dinamycQuery($sWhere);
?>
<?php
	if(isset($_SESSION["user_id"]) && isset($_SESSION["company_id"])){
		if(count($query)>0){ 
			$response = "<select name='slc_fixed_cost' id='slc_fixed_cost' class='form-control' style='width:100%;'>";
			foreach($query as $q){
				$response.= "<option value=".$q->id.">".$q->description." | ".TypeData::getById($q->tipo)->name."</option>";
			}
			$response.= "</select>";
			echo $response;
		}else{
			echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
		}
	}
}
?>
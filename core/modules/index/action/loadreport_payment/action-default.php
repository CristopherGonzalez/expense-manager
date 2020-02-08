<?php
if (isset($_POST)) {
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$month = $_POST['month'] ?? 0;
	$year = $_POST['year'] ?? 0;
	$type_doc = $_POST['type_doc'];
	$text = $_POST['text'];
	$inactive = $_POST['inactive'] ?? 0;
	$company_id = $_SESSION["company_id"];
	$sWhere = " empresa=$company_id and pagado=1 ";
	//Se construye la consulta sql dependiendo de los filtros ingresados

	if ($month != 0) {
		$sWhere .= " and month(payment_specific_date) =" . $month;
	}
	if ($year != 0) {
		$sWhere .= " and year(payment_specific_date) = " . $year;
	}
	if ($inactive == 1) {
		$sWhere .= " and active = $inactive ";
	}
	if ($text != "") {
		$entities = EntityData::getLikeName($text, $company_id);
		if (is_array($entities) && count($entities) > 0) {
			$sWhere .= " and ( ";
			foreach ($entities as $entity) {
				$sWhere .= " entidad = $entity->id or";
			}
			$sWhere = substr($sWhere, 0, -2);
			$sWhere .= " ) ";
		} else {
			$sWhere .= " entidad = 0 ";
		}
	}

	if($type_doc==0 || $type_doc=='expense'){
		$expenses = ExpensesData::dinamycQuery($sWhere);
	}
	if($type_doc==0 || $type_doc=='income'){
		$income = IncomeData::dinamycQuery($sWhere);
	}
	if($type_doc==0 || $type_doc=='partner'){
		$partner = ResultData::dinamycQuery($sWhere);
	}
	if($type_doc==0 || $type_doc=='debt'){
		$debt = DebtsData::dinamycQuery($sWhere);
	}
	var_dump($expenses);
	var_dump($income);
	var_dump($partner);
	var_dump($debt);
	// include 'res/resources/pagination.php'; //include pagination file
	// $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	// $per_page = intval($_REQUEST['per_page']); //how much records you want to show
	// $adjacents  = 4; //gap between pages after number of adjacents
	// $offset = ($page - 1) * $per_page;

	// $count_query = ExpensesData::countQuery($sWhere);
	// $numrows = $count_query->numrows;
	// $total_pages = ceil($numrows / $per_page);
	// $reload = './?view=expenses';

	// $query = ExpensesData::query($sWhere, $offset, $per_page);

	// si hay registro
	//if (count($query) > 0) {
?>
		 
	<?php
} else {
	echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.</div>';
}
	?>
<?php

// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
$con = Database::getCon();
//Se capturan los datos enviados por ajax
$month = intval($_REQUEST['month']??0);
$year = intval($_REQUEST['year']??0);
$text = mysqli_real_escape_string($con, (strip_tags($_REQUEST['text'], ENT_QUOTES)));
$inactive = (isset($_REQUEST['inactive']) && $_REQUEST['inactive'] == "true") ? 0 : 1;
$not_paid = (isset($_REQUEST['payment']) && $_REQUEST['payment'] == "true") ? 0 : 1;
$company_id = $_SESSION["company_id"];
$sWhere = " empresa=$company_id";
//Se construye la consulta sql dependiendo de los filtros ingresados

if ($month != 0) {
	$sWhere .= " and month(fecha) =" . $month;
}
if ($year != 0) {
	$sWhere .= " and year(fecha) = " . $year;
}
if ($text != "") {
	$sWhere .= " and (LOWER(description) LIKE LOWER('%" . $text . "%')) ";
	
}
if (!$not_paid) {
	$sWhere .= " and pagado = " . $not_paid;
}
if ($inactive == 1) {
	$sWhere .= " and active = $inactive ";
}
include 'res/resources/pagination.php'; //include pagination file
$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
$per_page = intval($_REQUEST['per_page']); //how much records you want to show
$adjacents  = 4; //gap between pages after number of adjacents
$offset = ($page - 1) * $per_page;
switch ($_REQUEST['type']) {
	case 'income':

		$type = intval($_REQUEST['type_category']);
		$category = intval($_REQUEST['category']);
		if ($type != 0) {
			$sWhere .= " and tipo=" . $type;
		}
		if ($category != 0) {
			$sWhere .= " and category_id=" . $category;
		}

		$query_sql = IncomeData::queryExcel($sWhere, $offset,$per_page);
		$name = "Ingresos";
		break;
	case 'expense':
		$type = intval($_REQUEST['type_category']);
		$category = intval($_REQUEST['category']);
		if ($type != 0) {
			$sWhere .= " and tipo=" . $type;
		}
		if ($category != 0) {
			$sWhere .= " and category_id=" . $category;
		}

		$query_sql = ExpensesData::queryExcel($sWhere, $offset,$per_page);
		$name = "Egresos";
		break;
	case 'partner':
		$query_sql = ResultData::queryExcel($sWhere, $offset, $per_page);
		$name = "Socios";
		break;
	case 'stock':
		$query_sql = StockData::queryExcel($sWhere, $offset, $per_page);
		$name = "Valores";
		break;
	case 'debt':
		$query_sql = DebtsData::queryExcel($sWhere, $offset, $per_page);
		$name = "Deudas";
		break;
	case 'entity':

		$type = intval($_REQUEST['type_category']);
		$category = intval($_REQUEST['category']);
		$inactive = $_REQUEST['inactive'];
		$category_type = mysqli_real_escape_string($con, (strip_tags($_REQUEST['category_type'], ENT_QUOTES)));
		$text = mysqli_real_escape_string($con, (strip_tags($_REQUEST['text'], ENT_QUOTES)));
		//$user_id=$_SESSION["user_id"];
		//$sWhere=" user_id=$user_id ";
		$company_id = $_SESSION["company_id"];
		$sWhere = " empresa=$company_id ";
		//Se construye la consulta sql dependiendo de los filtros ingresados
		if ($type != 0) {
			$sWhere .= " and tipo=" . $type;
		}
		if ($category_type != "") {
			//Se busca en tabla tipos para obtener por nombre
			$result_types = TypeData::getByType($category_type);
			$count = count($result_types);
			//Se crea query dependiendo de los resultados
			$sWhere .= " and  ( ";
			if ($count > 0) {
				foreach ($result_types as $index => $type) {
					$sWhere .= " tipo = $type->id or";
				}
				$sWhere = substr($sWhere, 0, -2);
			} else {
				//Se envia tipo = 0 para que consulta no de resultado en caso de tener texto en campo de egresos
				$sWhere .= " tipo = 0 ";
			}
			$sWhere .= " ) ";
		}
		if ($category != 0) {
			$sWhere .= " and category_id=" . $category;
		}
		if ($text != "") {
			$sWhere .= " and (LOWER(name) LIKE LOWER('%" . $text . "%') or LOWER(document_number) LIKE LOWER('%" . $text . "%') )";
		}
		if ($inactive == "true") {
			$sWhere .= " and active=0";
		} else {
			$sWhere .= " and active=1";
		}
		$query_sql = EntityData::queryExcel($sWhere, $offset, $per_page);
		$name = "Entidades";
		break;
	case 'payment':
		$query_sql = StockData::queryExcel($sWhere, $offset, $per_page);
		$name = "Valores";
		break;
	case 'expiration':
		$query_sql = StockData::queryExcel($sWhere, $offset, $per_page);
		$name = "Valores";
		break;
	
	default:
		$query_sql = array();
		$name = "Fallo";
		break;
}

$data = $query_sql;

function cleanData(&$str)
{
	if ($str == 't') $str = 'TRUE';
	if ($str == 'f') $str = 'FALSE';
	if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
		$str = "$str";
	}
	if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}


$out = fopen("php://output", 'w');

$flag = false;
foreach ($data as $row) {
	$row = json_decode(json_encode($row), true);
	if (!$flag) {
		// display field/column names as first row
		fputcsv($out, array_keys($row), ',', '"');
		$flag = true;
	}
	array_walk($row, __NAMESPACE__ . '\cleanData');
	fputcsv($out, array_values($row), ',', '"');
}

fclose($out);
exit;
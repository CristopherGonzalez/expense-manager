<?php

// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
$con = Database::getCon();
//Se capturan los datos enviados por ajax
$month = intval($_REQUEST['month']);
$year = intval($_REQUEST['year']);
$type = intval($_REQUEST['type_category']);
$category = intval($_REQUEST['category']);
$text = mysqli_real_escape_string($con, (strip_tags($_REQUEST['text'], ENT_QUOTES)));
$inactive = (isset($_REQUEST['inactive']) && $_REQUEST['inactive'] == "true") ? 0 : 1;
$not_paid = (isset($_REQUEST['payment']) && $_REQUEST['payment'] == "true") ? 0 : 1;
$company_id = $_SESSION["company_id"];
$sWhere = " empresa=$company_id";
//Se construye la consulta sql dependiendo de los filtros ingresados
if ($category != 0) {
	$sWhere .= " and category_id=" . $category;
}
if ($type != 0) {
	$sWhere .= " and tipo=" . $type;
}
if ($month != 0) {
	$sWhere .= " and month(fecha) =" . $month;
}
if ($year != 0) {
	$sWhere .= " and year(fecha) = " . $year;
}
if ($text != "") {
	$sWhere .= " and description LIKE '%" . $text . "%' ";
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
		$query_sql = IncomeData::query($sWhere, $offset,$per_page);
		$name = "Ingresos";
		break;
	case 'expense':
		$query_sql = ExpensesData::query($sWhere, $offset,$per_page);
		$name = "Egresos";
		break;
	case 'partner':
		$query_sql = ResultData::query($sWhere, $offset, $per_page);
		$name = "Socios";
		break;
	case 'stock':
		$query_sql = StockData::query($sWhere, $offset, $per_page);
		$name = "Valores";
		break;
	case 'debt':
		$query_sql = DebtsData::query($sWhere, $offset, $per_page);
		$name = "Deudas";
		break;

	default:
		$query_sql = array();
		$name = "Fallo";
		break;
}

$data = $query_sql;
$colnames = array(
	'id' => "ID",
	'description' => "Descripcion",
	'amount' => "Monto",
	'document_number' => "Numero Documento",
	'fecha' => "Fecha",
	'active' => "Active"
);

function map_colnames($input)
{
	global $colnames;
	return isset($colnames[$input]) ? $colnames[$input] : $input;
}

function cleanData(&$str)
{
	if ($str == 't') $str = 'TRUE';
	if ($str == 'f') $str = 'FALSE';
	if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
		$str = "'$str";
	}
	if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

// filename for download
$filename = $name."_" . date('Ymd') . ".csv";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: text/csv");

$out = fopen("php://output", 'w');

$flag = false;
foreach ($data as $row) {
	if (!$flag) {
		// display field/column names as first row
		$firstline = array_map(__NAMESPACE__ . '\map_colnames', array_keys($row));
		fputcsv($out, $firstline, ',', '"');
		$flag = true;
	}
	array_walk($row, __NAMESPACE__ . '\cleanData');
	fputcsv($out, array_values($row), ',', '"');
}

fclose($out);
exit;

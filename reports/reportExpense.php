<?php
session_start();
if (PHP_SAPI == 'cli')
	die('Este reporte sólo se puede ejecutar desde un navegador Web');

/** Incluye PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
// Crear nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

include "../core/autoload.php";
include "../core/modules/index/model/ExpensesData.php";
include "../core/modules/index/model/CategoryExpenseData.php";

// Propiedades del documento
$objPHPExcel->getProperties()->setCreator("Abisoft")
							 ->setLastModifiedBy("Abisoft")
							 ->setTitle("Office 2010 XLSX Documento de gastos")
							 ->setSubject("Office 2010 XLSX Documento de gastos")
							 ->setDescription("Documento de gastos para Office 2010 XLSX, generado usando clases de PHP.")
							 ->setKeywords("office 2010 openxml php")
							 ->setCategory("Archivo con resultado de gastos");



// Combino las celdas desde A1 hasta E1
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'REPORTE DE GASTOS')
            ->setCellValue('A2', 'DESCRIPCION')
            ->setCellValue('B2', 'CANTIDAD')
            ->setCellValue('C2', 'CATEGORIA')
			->setCellValue('D2', 'FECHA');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(23);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);


$con=Database::getCon();

	$query=ExpensesData::getAll($_SESSION['user_id']);
	$cel=3;//Numero de fila donde empezara a crear  el reporte
	//while ($row=mysqli_fetch_array($query)){
	foreach($query as $exp){
			
			if($exp->category_id!=null){
				$category_id= $exp->getCategory()->name;
			}else{
				$category_id= "<center>----</center>";
			}

			$a="A".$cel;
			$b="B".$cel;
			$c="C".$cel;
			$d="D".$cel;

			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $exp->description)
            ->setCellValue($b, $exp->amount)
            ->setCellValue($c, $category_id)
            ->setCellValue($d, $exp->created_at);
		$cel+=1;
	}
			
	//}

/*Fin extracion de datos MYSQL*/
$rango="A2:$d";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte de gastos');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_gastos.xls"');
header('Cache-Control: max-age=0');
// Si usted está sirviendo a IE 9 , a continuación, puede ser necesaria la siguiente
header('Cache-Control: max-age=1');

// Si usted está sirviendo a IE a través de SSL , a continuación, puede ser necesaria la siguiente
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['amount'])) {
           $errors[] = "Cantidad vacío";
        }else if (empty($_POST['category'])) {
           $errors[] = "Categoria vacío";
        }else if (empty($_POST['date'])) {
           $errors[] = "Fecha vacío";
        }else if (
        	!empty($_POST['mod_id'])
			&& !empty($_POST['amount'])
        	&& !empty($_POST['category'])
        	&& !empty($_POST['date'])
		){

    	$con = Database::getCon(); 
		$id=intval($_POST['mod_id']);
		$expense = ExpensesData::getById($id);
		$expense->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
		$expense->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
		$expense->category_id = intval($_POST['category']);
		//$expense->created_at = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
		//Se capturan los nuevos datos de los egresos
		$expense->entidad = intval($_POST['entity']);
		$expense->tipo = intval($_POST['type_expense']);
		$expense->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
		$expense->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
		//Se realiza guardado de imagenes de pago y documento
		$expense->document_number = mysqli_real_escape_string($con,(strip_tags($_POST["document_number"],ENT_QUOTES)));
		$expense->documento = "";
		$expense->pago = "";
		if(isset($_POST["document_image"]) && !empty($_POST["document_image"])){
			$expense->documento = $_POST["document_image"];
		}
		
		if(isset($_POST["payment_image"]) && !empty($_POST["payment_image"])){
			$expense->pago = $_POST["payment_image"];
		}
		$query_update=$expense->update();

		if ($query_update){
			$messages[] = "El egreso ha sido actualizado satisfactoriamente.";
			//print("<script>window.location='./?view=expenses'</script>");
		} else{
			$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
		}
		$change_log = new ChangeLogData();
		$change_log->tabla = "expenses";
		$change_log->registro_id = $expense->id;
		$change_log->description = $expense->description;
		$change_log->amount = $expense->amount;
		$change_log->entidad = $expense->entidad;
		$change_log->fecha = $expense->fecha;
		$change_log->document_number = $expense->document_number;
		$change_log->pagado = $expense->pagado;
		$change_log->user_id = $expense->user_id;
		$result = $change_log->add();
		if (isset($result) && !empty($result) && $result[0]){
			$messages[] = " El registro de cambios ha sido actualizado satisfactoriamente.";
		} else{
			$errors []= " Lo siento algo ha salido mal en el registro de errores.";
		}

	} else {
		$errors []= "Error desconocido.";
	}
	if (isset($errors)){
			
?>
<div class="alert alert-danger" role="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Error!</strong> 
	<?php
		foreach ($errors as $error) {
			echo $error;
		}
	?>
</div>
<?php
	}
	if (isset($messages)){
?>
	<div class="alert alert-success" role="alert">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>¡Bien hecho!</strong>
		<?php
			foreach ($messages as $message) {
				echo $message;
			}
		?>
	</div>
<?php
	}
?>
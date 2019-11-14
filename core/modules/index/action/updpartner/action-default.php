<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['amount'])) {
           $errors[] = "Cantidad vacío";
        }else if (empty($_POST['date'])) {
           $errors[] = "Fecha vacío";
		 }else if (empty($_POST['entity'])) {
			$errors[] = "No ha seleccionado una entidad vacГ­o.";
		 }else if (
        	!empty($_POST['mod_id'])
			&& !empty($_POST['amount'])
			&& !empty($_POST['date'])
			&& !empty($_POST['entity'])
		){

    	$con = Database::getCon(); 
		$id=intval($_POST['mod_id']);
		$partner = ResultData::getById($id);
		$partner->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
		$partner->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
		$partner->created_at = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
		//Se capturan los nuevos datos de los gastos
		$partner->entidad = intval($_POST['entity']);
		$partner->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
		$partner->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
		//Se realiza guardado de imagenes de pago y documento
		$partner->documento = "";
		$partner->pago = "";
		if(isset($_POST["document_image"]) && !empty($_POST["document_image"])){
			$partner->documento = $_POST["document_image"];
		}
		
		if(isset($_POST["payment_image"]) && !empty($_POST["payment_image"])){
			$partner->pago = $_POST["payment_image"];
		}
		$query_update=$partner->update();

		if ($query_update){
			$messages[] = "El registro ha sido actualizado satisfactoriamente.";
		} else{
			$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
		}
		$change_log = new ChangeLogData();
		$change_log->tabla = "result";
		$change_log->registro_id = $partner->id;
		$change_log->description = $partner->description;
		$change_log->amount = $partner->amount;
		$change_log->entidad = $partner->entidad;
		$change_log->fecha = $partner->fecha;
		$change_log->pagado = $partner->pagado;
		$change_log->user_id = $partner->user_id;
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
<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['amount'])) {
           $errors[] = "Cantidad vacío";
        }  elseif (empty($_POST['date'])) {
            $errors[] = "Fecha está vacío.";
        }  elseif (empty($_POST['type_debt'])) {
            $errors[] = "No ha seleccionado el tipo de deuda";
        }	elseif (empty($_POST['entity'])) {
            $errors[] = "No ha seleccionado una entidad vacío.";
        }	elseif (
        	!empty($_POST['amount'])
        	&& !empty($_POST['date'])
			&& !empty($_POST['type_debt'])
			&& !empty($_POST['entity'])
        ){

    	$con = Database::getCon(); 
		$id=intval($_POST['mod_id']);
		$debt = DebtsData::getById($id);
		$debt->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
		$debt->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
		//Se capturan los nuevos datos de los egresos
		$debt->entidad = intval($_POST['entity']);
		$debt->tipo = intval($_POST['type_debt']);
		$debt->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
		$debt->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
		//Se realiza guardado de imagenes de pago y documento
		$debt->document_number = mysqli_real_escape_string($con,(strip_tags($_POST["document_number"],ENT_QUOTES)));
		if($debt->pagado){
			$debt->fecha_pago = mysqli_real_escape_string($con,(strip_tags($_POST["payment_date"],ENT_QUOTES)));
		}else{
			$debt->fecha_pago = "00/00/0000";
		}
		$debt->documento = "";
		$debt->pago = "";
		if(isset($_POST["document_image"]) && !empty($_POST["document_image"])){
			$debt->documento = $_POST["document_image"];
		}
		
		if(isset($_POST["payment_image"]) && !empty($_POST["payment_image"])){
			$debt->pago = $_POST["payment_image"];
		}
		$query_update=$debt->update();

		if ($query_update){
			$messages[] = "La deuda ha sido actualizada satisfactoriamente.";
			//print("<script>window.location='./?view=debts'</script>");
		} else{
			$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
		}
		$change_log = new ChangeLogData();
		$change_log->tabla = "deudas";
		$change_log->registro_id = $debt->id;
		$change_log->description = $debt->description;
		$change_log->amount = $debt->amount;
		$change_log->entidad = $debt->entidad;
		$change_log->fecha = $debt->fecha;
		$change_log->document_number = $debt->document_number;
		$change_log->active = $debt->active;
		$change_log->payment_date = $debt->fecha_pago;
		$change_log->pagado = $debt->pagado;
		$change_log->user_id = $debt->user_id;
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
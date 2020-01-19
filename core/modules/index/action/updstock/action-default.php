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
        }  elseif (empty($_POST['type_stock'])) {
            $errors[] = "No ha seleccionado el tipo de deuda";
        }	elseif (empty($_POST['entity'])) {
            $errors[] = "No ha seleccionado una entidad vacío.";
        }	elseif (
        	!empty($_POST['amount'])
        	&& !empty($_POST['date'])
			&& !empty($_POST['type_stock'])
			&& !empty($_POST['entity'])
        ){

    	$con = Database::getCon(); 
		$id=intval($_POST['mod_id']);
		$stock = StockData::getById($id);
		$stock->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
		$stock->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
		//Se capturan los nuevos datos de los egresos
		$stock->entidad = intval($_POST['entity']);
		$stock->tipo = intval($_POST['type_stock']);
		$stock->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
		$stock->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
		//Se realiza guardado de imagenes de pago y documento
		$stock->document_number = mysqli_real_escape_string($con,(strip_tags($_POST["document_number"],ENT_QUOTES)));
		if($stock->pagado){
			$stock->fecha_pago = mysqli_real_escape_string($con,(strip_tags($_POST["payment_date"],ENT_QUOTES)));
		}else{
			$stock->fecha_pago = "00/00/0000";
		}
		$stock->documento = "";
		$stock->pago = "";
		if(isset($_POST["document_image"]) && !empty($_POST["document_image"])){
			$stock->documento = $_POST["document_image"];
		}
		
		if(isset($_POST["payment_image"]) && !empty($_POST["payment_image"])){
			$stock->pago = $_POST["payment_image"];
		}
		$query_update=$stock->update();

		if ($query_update){
			$messages[] = "El valor ha sido actualizado satisfactoriamente.";
			//print("<script>window.location='./?view=stocks'</script>");
		} else{
			$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
		}
		$change_log = new ChangeLogData();
		$change_log->tabla = "stocks";
		$change_log->registro_id = $stock->id;
		$change_log->description = $stock->description;
		$change_log->amount = $stock->amount;
		$change_log->entidad = $stock->entidad;
		$change_log->fecha = $stock->fecha;
		$change_log->document_number = $stock->document_number;
		$change_log->active = $stock->active;
		$change_log->payment_date = $stock->fecha_pago;
		$change_log->pagado = $stock->pagado;
		$change_log->user_id = $stock->user_id;
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
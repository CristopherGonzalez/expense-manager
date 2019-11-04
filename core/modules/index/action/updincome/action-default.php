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
        }else if (empty($_POST['type_income'])) {
			$errors[] = "No ha seleccionado el tipo de ingreso";
		 }else if (empty($_POST['entity'])) {
			$errors[] = "No ha seleccionado una entidad vacГ­o.";
		 }else if (
        	!empty($_POST['mod_id'])
			&& !empty($_POST['amount'])
        	&& !empty($_POST['category'])
			&& !empty($_POST['date'])
			&& !empty($_POST['type_income'])
			&& !empty($_POST['entity'])
		){

    	$con = Database::getCon(); 
		$id=intval($_POST['mod_id']);
		$income = IncomeData::getById($id);
		$income->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
		$income->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
		$income->category_id = intval($_POST['category']);
		$income->created_at = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
		//Se capturan los nuevos datos de los gastos
		$income->entidad = intval($_POST['entity']);
		$income->tipo = intval($_POST['type_income']);
		$income->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
		$income->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
		//Se realiza guardado de imagenes de pago y documento
		$income->documento = "";
		$income->pago = "";
		if(isset($_FILES["document"]) && !empty($_FILES["document"])){
			if(isset($_FILES["document"]["tmp_name"]) && !empty($_FILES["document"]["tmp_name"])){
				$doc_file = addslashes(file_get_contents($_FILES["document"]["tmp_name"])); 
				if(isset($doc_file) && !empty($doc_file)){
					$income->documento = $doc_file;
				}
			}
		}
		if(isset($_FILES["payment"]) && !empty($_FILES["payment"])){
			if(isset($_FILES["payment"]["tmp_name"]) && !empty($_FILES["payment"]["tmp_name"])){
				$pay_file = addslashes(file_get_contents($_FILES["payment"]["tmp_name"])); 
				if(isset($pay_file) && !empty($pay_file)){
					$income->pago = $pay_file;
				}
			}
		}
		$query_update=$income->update();

		if ($query_update){
			$messages[] = "El ingreso ha sido actualizado satisfactoriamente.";
		} else{
			$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
		}
		$change_log = new ChangeLogData();
		$change_log->tabla = "income";
		$change_log->registro_id = $income->id;
		$change_log->description = $income->description;
		$change_log->amount = $income->amount;
		$change_log->entidad = $income->entidad;
		$change_log->fecha = $income->fecha;
		$change_log->pagado = $income->pagado;
		$change_log->user_id = $income->user_id;
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
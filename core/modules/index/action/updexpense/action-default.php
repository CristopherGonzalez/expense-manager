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
		//Se capturan los nuevos datos de los gastos
		$expense->entidad = intval($_POST['entity']);
		$expense->tipo = intval($_POST['type_expense']);
		$expense->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
		$expense->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
		//Se realiza guardado de imagenes de pago y documento
		$expense->documento = "";
		$expense->pago = "";
		if(isset($_FILES["document"]) && !empty($_FILES["document"])){
			if(isset($_FILES["document"]["tmp_name"]) && !empty($_FILES["document"]["tmp_name"])){
				$doc_file = addslashes(file_get_contents($_FILES["document"]["tmp_name"])); 
				if(isset($doc_file) && !empty($doc_file)){
					$expense->documento = $doc_file;
				}
			}
		}
		if(isset($_FILES["payment"]) && !empty($_FILES["payment"])){
			if(isset($_FILES["payment"]["tmp_name"]) && !empty($_FILES["payment"]["tmp_name"])){
				$pay_file = addslashes(file_get_contents($_FILES["payment"]["tmp_name"])); 
				if(isset($pay_file) && !empty($pay_file)){
					$expense->pago = $pay_file;
				}
			}
		}
		$query_update=$expense->update();

		if ($query_update){
			$messages[] = "El gasto ha sido actualizado satisfactoriamente.";
			//print("<script>window.location='./?view=expenses'</script>");
		} else{
			$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
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
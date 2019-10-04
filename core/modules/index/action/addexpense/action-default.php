<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
//Se validan nuevos parametros de los gastos
	if (empty($_POST['amount'])){
			$errors[] = "Cantidad está vacío.";
		}  elseif (empty($_POST['category'])) {
            $errors[] = "No ha seleccionado el categoria";
        }  elseif (empty($_POST['date'])) {
            $errors[] = "Fecha está vacío.";
        }  elseif (empty($_POST['type_expense'])) {
            $errors[] = "No ha seleccionado el tipo de gasto";
        }	elseif (empty($_POST['entity'])) {
            $errors[] = "No ha seleccionado una entidad vacío.";
        }	elseif (
        	!empty($_POST['amount'])
        	&& !empty($_POST['category'])
			&& !empty($_POST['date'])
			&& !empty($_POST['type_expense'])
			&& !empty($_POST['entity'])
        ){
        	$con = Database::getCon(); 
			$expense = new ExpensesData();
			$expense->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
			$expense->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
			$expense->user_id = $_SESSION['user_id'];
			$expense->category_id = intval($_POST['category']);
			//Se capturan los nuevos datos de los gastos
			$expense->entidad = intval($_POST['entity']);
			$expense->tipo = intval($_POST['type_expense']);
			$expense->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
			$expense->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
			//Se realiza guardado de imagenes de pago y documento
			if(isset($_FILES["document"]) && !empty($_FILES["document"])){
				$doc_file = addslashes(file_get_contents($_FILES["document"]["tmp_name"])); 
				if(isset($doc_file) && !empty($doc_file)){
					$expense->documento = $doc_file;
				}
			}
			if(isset($_FILES["payment"]) && !empty($_FILES["payment"])){
				$pay_file = addslashes(file_get_contents($_FILES["payment"]["tmp_name"])); 
				if(isset($pay_file) && !empty($pay_file)){
					$expense->pago = $pay_file;
				}
			}
			$query_new=$expense->add();
            if ($query_new) {
                $messages[] = "El gasto ha sido agregado con éxito.";
            } else {
                $errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
            }
		} else {
			$errors[] = "desconocido.";	
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
<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
//Se validan nuevos parametros de los ingresos
	if (empty($_POST['amount'])){
			$errors[] = "Cantidad está vacío.";
		}  elseif (empty($_POST['category'])) {
            $errors[] = "No ha seleccionado el categoria";
        }  elseif (empty($_POST['date'])) {
            $errors[] = "Fecha está vacío.";
        }  elseif (empty($_POST['type_income'])) {
            $errors[] = "No ha seleccionado el tipo de ingreso";
        }	elseif (empty($_POST['entity'])) {
            $errors[] = "No ha seleccionado una entidad vacío.";
        }  elseif (
        	!empty($_POST['amount'])
        	&& !empty($_POST['category'])
        	&& !empty($_POST['date'])
			&& !empty($_POST['type_income'])
			&& !empty($_POST['entity'])
        ){
        	$con = Database::getCon(); 
			$income = new IncomeData();
			$income->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
			$income->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
			$income->user_id = $_SESSION['user_id'];
			$income->category_id = intval($_POST['category']);
			//Se capturan los nuevos datos de los gastos
			$income->entidad = intval($_POST['entity']);
			$income->tipo = intval($_POST['type_income']);
			$income->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
			$income->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
			//Se realiza guardado de imagenes de pago y documento
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
			$query_new=$income->add();
            if ($query_new) {
                $messages[] = "El ingreso ha sido agregado con éxito.";
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
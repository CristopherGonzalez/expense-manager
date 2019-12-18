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
			$income->empresa = $_SESSION['company_id'];
			$income->category_id = intval($_POST['category']);
			//Se capturan los nuevos datos de los egresos
			$income->entidad = intval($_POST['entity']);
			$income->tipo = intval($_POST['type_income']);
			$income->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
			$income->document_number = mysqli_real_escape_string($con,(strip_tags($_POST["document_number"],ENT_QUOTES)));
			$income->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
			//Se realiza guardado de imagenes de pago y documento
			$income->documento = "";
			$income->pagado_con = mysqli_real_escape_string($con,(strip_tags($_POST["pay_with"],ENT_QUOTES)));
			if($income->pagado){
				$income->pagado_con = mysqli_real_escape_string($con,(strip_tags($_POST["pay_with"],ENT_QUOTES)));
				$income->payment_date = mysqli_real_escape_string($con,(strip_tags($_POST["payment_date"],ENT_QUOTES)));
	
			}else{
				$income->pagado_con = "";
			}
			$income->pago = "";
			if(isset($_POST["document_image"]) && !empty($_POST["document_image"])){
				$income->documento = $_POST["document_image"];
			}
			
			if(isset($_POST["payment_image"]) && !empty($_POST["payment_image"])){
				$income->pago = $_POST["payment_image"];
			}
			
			$query_new=$income->add();
            if ($query_new) {
				$messages[] = "El ingreso ha sido agregado con éxito.";
				$change_log = new ChangeLogData();
				$change_log->tabla = "income";
				$change_log->registro_id = $query_new[1];
				$change_log->description = $income->description;
				$change_log->amount = $income->amount;
				$change_log->document_number = $income->document_number;
				$change_log->entidad = $income->entidad;
				$change_log->fecha = $income->fecha;
				$change_log->pagado = $income->pagado;
				$change_log->active = $income->active;
				$change_log->payment_date = $income->payment_date;
				$change_log->user_id = $income->user_id;
				$result = $change_log->add();
				if (isset($result) && !empty($result) && $result[0]){
					$messages[] = " El registro de cambios ha sido actualizado satisfactoriamente.";
				} else{
					$errors []= " Lo siento algo ha salido mal en el registro de errores.";
				}										
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
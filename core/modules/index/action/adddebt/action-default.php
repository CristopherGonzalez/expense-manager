<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
//Se validan nuevos parametros de los egresos
	if (empty($_POST['amount'])){
			$errors[] = "Cantidad está vacío.";
		}  elseif (empty($_POST['date'])) {
            $errors[] = "Fecha está vacío.";
        }  elseif (!isset($_POST['payment_fees']) || (!is_numeric($_POST['payment_fees']) || $_POST['payment_fees']<0 || $_POST['payment_fees']>60)) {
            $errors[] = "No se han ingresado la cantidad de cuotas o el formato no es invalido.";
        }  elseif (empty($_POST['type_debt'])) {
            $errors[] = "No ha seleccionado el tipo de egreso";
        }	elseif (empty($_POST['entity'])) {
            $errors[] = "No ha seleccionado una entidad vacío.";
        }	elseif (
        	!empty($_POST['amount'])
        	&& !empty($_POST['date'])
			&& !empty($_POST['type_debt'])
			&& !empty($_POST['entity'])
        ){
        	$con = Database::getCon(); 
			$debt = new DebtsData();
			$debt->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
			$debt->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
			$debt->user_id = $_SESSION['user_id'];
			$debt->empresa = $_SESSION['company_id'];
			//Se capturan los nuevos datos de los egresos
			$debt->entidad = intval($_POST['entity']);
			$debt->tipo = intval($_POST['type_debt']);
			$debt->fecha = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
			$debt->pagado = (isset($_POST['pay_out']) && $_POST['pay_out'] == "true") ? 1 : 0;
			//Se realiza guardado de imagenes de pago y documento
			$debt->documento = "";
			$debt->document_number = mysqli_real_escape_string($con,(strip_tags($_POST["document_number"],ENT_QUOTES)));
			$debt->pago = "";
			if(isset($_POST["document_image"]) && !empty($_POST["document_image"])){
				$debt->documento = $_POST["document_image"];
			}
			if(isset($_POST["payment_image"]) && !empty($_POST["payment_image"])){
				$debt->pago = $_POST["payment_image"];
			}
			
			$query_new=$debt->add();
            if (isset($query_new) && is_array($query_new) && $query_new[0]) {
				$messages[] = "El egreso ha sido agregado con éxito.";
				$change_log = new ChangeLogData();
				$change_log->tabla = "debts";
				$change_log->registro_id = $query_new[1];
				$change_log->description = $debt->description;
				$change_log->amount = $debt->amount;
				$change_log->entidad = $debt->entidad;
				$change_log->fecha = $debt->fecha;
				$change_log->pagado = $debt->pagado;
				$change_log->active = $debt->active;
				$change_log->document_number = $debt->document_number;
				$change_log->user_id = $debt->user_id;
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
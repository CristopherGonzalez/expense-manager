<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
//Se validan nuevos parametros de los ingresos
	if (empty($_POST['amount'])){
			$errors[] = "Cantidad está vacío.";
		}  elseif (empty($_POST['date'])) {
            $errors[] = "Fecha está vacío.";
        }	elseif (empty($_POST['entity'])) {
            $errors[] = "No ha seleccionado una entidad vacío.";
        }  elseif (
        	!empty($_POST['amount'])
        	&& !empty($_POST['date'])
			&& !empty($_POST['entity'])
        ){
        	$con = Database::getCon(); 
			$partner = new ResultData();
			$partner->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
			$partner->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
			$partner->user_id = $_SESSION['user_id'];
			$partner->empresa = $_SESSION['company_id'];
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
			
			$query_new=$partner->add();
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
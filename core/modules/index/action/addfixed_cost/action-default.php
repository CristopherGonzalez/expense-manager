<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
//Se validan nuevos parametros de los gastos
	if (empty($_REQUEST['year'])){
			$errors[] = "No ha seleccionado el año.";
		}  elseif (empty($_REQUEST['month'])) {
            $errors[] = "No ha seleccionado el mes.";
        }  elseif (empty($_REQUEST['expenses'] || !is_array($_REQUEST['expenses']))) {
            $errors[] = "No se han cargado los gastos.";
        }	elseif (
        	!empty($_REQUEST['year'])
        	&& !empty($_REQUEST['month'])
			&& !empty($_REQUEST['expenses'])
			&& is_array($_REQUEST['expenses'])
        ){
			$con = Database::getCon(); 
			//'TODO traer expense por id, cambiar la fecha y agregar como nuevo'
			foreach($_REQUEST['expenses'] as $expense_base){
				$expense = "";
				$expense = new ExpensesData();
				$expense = $expense->getById($expense_base);
				$expense->user_id = $_SESSION['user_id'];
				$day="";
				$day = date("d",strtotime($expense->fecha));
				if(intval($day)>28){$day='28';};
				$date="";
				$date = $_REQUEST['year']."-".$_REQUEST['month']."-".$day;
				$date = date_create_from_format('Y-m-d',$date);
				$expense->fecha = $date->date;
				$expense->pagado = 0;
				$expense->created_at = "NOW()";
				//Se realiza guardado de imagenes de pago y documento
				$expense->documento = "";
				$expense->pago = "";
			
				$query_new=$expense->add();
				if ($query_new) {
					$messages[] = "El gasto ".$expense->description." ha sido agregado con éxito."."\r\n";
				} else {
					$errors[] = "Lo sentimos, el registro ".$expense->description." falló. Por favor, regrese y vuelva a intentarlo."."\r\n";
				}
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
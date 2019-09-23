<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
	if (empty($_POST['amount'])){
			$errors[] = "Cantidad está vacío.";
		}  elseif (empty($_POST['category'])) {
            $errors[] = "Categoria está vacío.";
        }  elseif (empty($_POST['date'])) {
            $errors[] = "Fecha está vacío.";
        }  elseif (
        	!empty($_POST['amount'])
        	&& !empty($_POST['category'])
        	&& !empty($_POST['date'])
        ){
        	$con = Database::getCon(); 
			$expense = new ExpensesData();
			$expense->description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
			$expense->amount = mysqli_real_escape_string($con,(strip_tags($_POST["amount"],ENT_QUOTES)));
			$expense->user_id = $_SESSION['user_id'];
			$expense->category_id = intval($_POST['category']);
			$expense->created_at = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
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
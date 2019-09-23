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
		$expense->created_at = mysqli_real_escape_string($con,(strip_tags($_POST["date"],ENT_QUOTES)));
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
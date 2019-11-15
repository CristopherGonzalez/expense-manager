<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
//Se agregan validacion para nuevo campo de egreso
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['name']) || empty($_POST['egreso']) ) {
           $errors[] = "Todos los campos son requeridos";
        }else if (
        	!empty($_POST['mod_id'])
			&& !empty($_POST['name'])
			&& !empty($_POST['egreso'])
		){

    	$con = Database::getCon(); 
		$id=intval($_POST['mod_id']);
		$category_expense = CategoryExpenseData::getById($id);
		$category_expense->name = mysqli_real_escape_string($con,(strip_tags($_POST["name"],ENT_QUOTES)));
		//Se agrega tipo para corregir update
		$category_expense->tipo = $_POST['egreso'];
		$query_update=$category_expense->update();

		if ($query_update){
			$messages[] = "La categoria ha sido actualizada satisfactoriamente.";
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
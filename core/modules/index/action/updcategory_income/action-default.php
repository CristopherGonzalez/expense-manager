<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['name'])) {
           $errors[] = "Nombre vacío";
        }else if (
        	!empty($_POST['mod_id'])
			&& !empty($_POST['name'])
		){

    	$con = Database::getCon(); 
		$id=intval($_POST['mod_id']);
		$category_income = CategoryIncomeData::getById($id);
		$category_income->name = mysqli_real_escape_string($con,(strip_tags($_POST["name"],ENT_QUOTES)));
		$query_update=$category_income->update();

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
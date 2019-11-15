<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
	if (empty($_POST['mod_id'])) {
		$errors[] = "ID vacío";
	}else if (empty($_POST['origin'])) {
		$errors[] = "Debes seleccionar un origen.";
	}else if (empty($_POST['type'])) {
		$errors[] = "Debes seleccionar el tipo ";
		}else if (empty($_POST['name_entity'])) {
		$errors[] = "Debes ingresar un nombre";
		}else if (
		!empty($_POST['mod_id'])
		&& !empty($_POST['origin'])
		&& !empty($_POST['type'])
		&& !empty($_POST['name_entity'])
	){

		$con = Database::getCon(); 
		$id=intval($_POST['mod_id']);
		$entity = EntityData::getById($id);
		$entity->name = mysqli_real_escape_string($con,(strip_tags($_POST["name_entity"],ENT_QUOTES)));
		$entity->tipo = intval($_POST['type']);
		$entity->category_id = intval($_POST['category_id']);
		$query_update=$entity->update();

		if ($query_update){
			$messages[] = "El egreso ha sido actualizado satisfactoriamente.";
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
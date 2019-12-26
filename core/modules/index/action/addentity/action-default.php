<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
//Se validan nuevos parametros de los egresos
	if (empty($_POST['name_entity'])){
			$errors[] = "No se ha ingresado nombre.";
		}  elseif (empty($_POST['type'])) {
            $errors[] = "No ha seleccionado el tipo";
        }  elseif (
        	!empty($_POST['name_entity'])
        	&& !empty($_POST['type'])
        ){
        	$con = Database::getCon(); 
			$entity = new EntityData();
			$entity->name = mysqli_real_escape_string($con,(strip_tags($_POST["name_entity"],ENT_QUOTES)));
			$entity->tipo = intval($_POST['type']);
			$entity->user_id = $_SESSION['user_id'];
			$entity->empresa = $_SESSION['company_id'];
			$entity->category_id = intval($_POST['category']);
			//Se capturan los nuevos datos de los egresos
			$query_new=$entity->add();
            if ($query_new) {
                $messages[] = "La entidad ha sido agregada con éxito.";
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
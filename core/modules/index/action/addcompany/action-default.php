<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
	if (empty($_POST['name'])){
			$errors[] = "No ha ingresado un nombre.";
		}  elseif (empty($_POST['password'])) {
            $errors[] = "No ha ingresado la contraseña.";
        }  elseif (empty($_POST['country_company'])) {
            $errors[] = "No ha seleccionado un pais.";
        }  elseif (empty($_POST['city_company'])) {
            $errors[] = "No ha seleccionado una ciudad.";
        }	elseif (empty($_POST['types_bussiness'])) {
            $errors[] = "No ha seleccionado un tipo de negocio.";
        }	elseif (empty($_POST['email'])) {
            $errors[] = "No ha ingresado un email.";
        }	elseif (empty($_POST['license'])) {
            $errors[] = "No ha ingresado una licencia MRC.";
        }	elseif (
        	!empty($_POST['name'])
        	&& !empty($_POST['password'])
        	&& !empty($_POST['country_company'])
        	&& !empty($_POST['city_company'])
			&& !empty($_POST['types_bussiness'])
			&& !empty($_POST['email'])
			&& !empty($_POST['license'])
        ){
        	$con = Database::getCon(); 
			$company = new CompanyData();
			$company->name = mysqli_real_escape_string($con,(strip_tags($_POST["name"],ENT_QUOTES)));
			$company->password = Core::encrypt_decrypt('encrypt',mysqli_real_escape_string($con,(strip_tags($_POST["password"],ENT_QUOTES))));
			$company->email = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
			$company->licenciaMRC = mysqli_real_escape_string($con,(strip_tags($_POST["license"],ENT_QUOTES)));
			$company_license = CompanyData::getByLicense($company->licenciaMRC);

			if(isset($company_license) && !empty($company_license)){
				$errors[] = "Ya existe una empresa asociada a esa licencia MRC.";
			}else{
				$company->pais = intval($_POST['country_company']);
				$company->ciudad = intval($_POST['city_company']);
				$company->tipo_negocio = intval($_POST['types_bussiness']);
				if(isset($_FILES["profile_pic"]) && !empty($_FILES["profile_pic"])){
					if(isset($_FILES["profile_pic"]["tmp_name"]) && !empty($_FILES["profile_pic"]["tmp_name"])){
						$file = addslashes(file_get_contents($_FILES["profile_pic"]["tmp_name"])); 
						if(isset($file) && !empty($file)){
							$company->profile_pic = $file;
						}
					}
				}
				$query_new=$company->add();
				if ($query_new && is_array($query_new) && $query_new[0]) {
					$messages[] = "La empresa ha sido agregada con éxito.";
				} else {
					$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
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
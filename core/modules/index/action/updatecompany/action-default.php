<?php
if (!isset($_SESSION['user_id'])) {
	Core::redir("./"); //Redirecciona 
	exit;
}
if (empty($_POST['name'])) {
	$errors[] = "No ha ingresado un nombre.";
} elseif (empty($_POST['country_company'])) {
	$errors[] = "No ha seleccionado un pais.";
} elseif (empty($_POST['city_company'])) {
	$errors[] = "No ha seleccionado una ciudad.";
} elseif (empty($_POST['telefono'])) {
	$errors[] = "No ha ingresado el telefono.";
} elseif (empty($_POST['direccion'])) {
	$errors[] = "No ha ingresado una direccion.";
} elseif (empty($_POST['types_bussiness'])) {
	$errors[] = "No ha seleccionado un tipo de negocio.";
} elseif (empty($_POST['email'])) {
	$errors[] = "No ha ingresado un email.";
} elseif (empty($_POST['license'])) {
	$errors[] = "No ha ingresado una licencia MRC.";
} elseif (
	!empty($_POST['name'])
	&& !empty($_POST['country_company'])
	&& !empty($_POST['city_company'])
	&& !empty($_POST['types_bussiness'])
	&& !empty($_POST['direccion'])
	&& !empty($_POST['telefono'])
	&& !empty($_POST['mod_id'])
	&& !empty($_POST['email'])
	&& !empty($_POST['license'])
) {
	$con = Database::getCon();
	$id = intval($_POST['mod_id']);
	$company = CompanyData::getById($id);
	$company->name = mysqli_real_escape_string($con, (strip_tags($_POST["name"], ENT_QUOTES)));
	$company->email = mysqli_real_escape_string($con, (strip_tags($_POST["email"], ENT_QUOTES)));
	$company->licenciaMRC = mysqli_real_escape_string($con, (strip_tags($_POST["license"], ENT_QUOTES)));

	$company->direccion = mysqli_real_escape_string($con, (strip_tags($_POST["direccion"], ENT_QUOTES)));
	$company->telefono = mysqli_real_escape_string($con, (strip_tags($_POST["telefono"], ENT_QUOTES)));
	if (!empty($_POST['status']) && isset($_POST['status'])) {
		$company->status = "2";
	} else {
		$company->status = "1";
	}
	//$company->status = mysqli_real_escape_string($con,(strip_tags($_POST["status"],ENT_QUOTES)));
	$company_license = CompanyData::getByLicense($company->licenciaMRC);
	if (isset($company_license) && !empty($company_license) && $company_license->id != $company->id) {
		$errors[] = "Ya existe una empresa asociada a esa licencia MRC.";
	} else {
		$company->pais = intval($_POST['country_company']);
		$company->ciudad = intval($_POST['city_company']);
		$company->tipo_negocio = intval($_POST['types_bussiness']);
		if (isset($_POST["profile_pic"]) && !empty($_POST["profile_pic"])) {
			$company->profile_pic = $_POST["profile_pic"];
		}
		$query_new = $company->update();
		if (isset($query_new) && !empty($query_new) && $query_new) {
			$messages[] = "La empresa ha sido actualizada con éxito.";
		} else {
			$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
		}
	}
} else {
	$errors[] = "Error desconocido.";
}
if (isset($errors)) {

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
if (isset($messages)) {
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
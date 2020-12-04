<?php
include "../../core/autoload.php";


include "../../core/modules/index/model/CompanyData.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //echo json_encode($_POST);
    $response = array();
    //Se validan nuevos parametros de los ingresos

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
	&& !empty($_POST['telefono'])
	&& !empty($_POST['direccion'])
	&& !empty($_POST['city_company'])
	&& !empty($_POST['types_bussiness'])
	&& !empty($_POST['email'])
	&& !empty($_POST['license'])
) {
	$con = Database::getCon();
	$company = new CompanyData();
	$company->name = mysqli_real_escape_string($con, (strip_tags($_POST["name"], ENT_QUOTES)));
	$company->email = mysqli_real_escape_string($con, (strip_tags($_POST["email"], ENT_QUOTES)));
	$company->direccion = mysqli_real_escape_string($con, (strip_tags($_POST["direccion"], ENT_QUOTES)));
	$company->telefono = mysqli_real_escape_string($con, (strip_tags($_POST["telefono"], ENT_QUOTES)));
	$company->licenciaMRC = mysqli_real_escape_string($con, (strip_tags($_POST["license"], ENT_QUOTES)));
	$company_license = CompanyData::getByLicense($company->licenciaMRC);

	if (isset($company_license) && !empty($company_license)) {
        $response[] = [
                    "id_transaction" => 0,
                    "status_transaction" => "Ya existe una empresa asociada a esa licencia MRC."
                ];
	} else {
		$company->pais = intval($_POST['country_company']);
		$company->ciudad = intval($_POST['city_company']);
		$company->tipo_negocio = intval($_POST['types_bussiness']);
		if (isset($_POST["profile_pic"]) && !empty($_POST["profile_pic"])) {
			$company->profile_pic = $_POST["profile_pic"];
		}
		$query_new = $company->add();
		if ($query_new && is_array($query_new) && $query_new[0]) {
            $response[] = [
                    "id_transaction" => $query_new[1],
                    "status_transaction" => "La empresa ha sido agregada con éxito."
                ];
		
		} else {
            $response[] = [
                    "id_transaction" => 0,
                    "status_transaction" => "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo."
                ];
		}
	}
} else {
      $response[] = [
                    "id_transaction" => 0,
                    "status_transaction" => "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo."
                ];
}


    header("HTTP/1.1 200 OK");
    echo json_encode($response);
    exit();
}
// Crear un nuevo get
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header("HTTP/1.1 400 OK");
    exit();
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    header("HTTP/1.1 400 OK");
    exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    header("HTTP/1.1 400 OK");
    exit();
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

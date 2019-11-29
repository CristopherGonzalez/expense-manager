<?php
include "../../core/autoload.php";
include "../../core/modules/index/model/ExpensesData.php";
include "../../core/modules/index/model/IncomeData.php";
include "../../core/modules/index/model/EntityData.php";
include "../../core/modules/index/model/ChangeLogData.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //echo json_encode($_POST);
    $response = array();
    //Se validan nuevos parametros de los ingresos
    if (empty($_POST['amount']) || !isset($_POST['amount'])){
        $response[] = "La cantidad no es esta informada.";
    }  elseif (empty($_POST['type']) || !isset($_POST['type'])){
        $response[] = "El tipo no esta informado.";
    }  elseif (empty($_POST['id_company']) || !isset($_POST['id_company'])){
        $response[] = "El id de empresa no esta informado.";
    }  elseif (empty($_POST['id_entity']) || !isset($_POST['id_entity'])){
        $response[] = "El id de la entidad no esta informado.";
    }	elseif (empty($_POST['date']) || !isset($_POST['date'])){
        $response[] = "La fecha no esta informada.";
    }	elseif (empty($_POST['document_number']) || !isset($_POST['document_number'])){
        $response[] = "El numero de documento no esta informado.";
    }	elseif (empty($_POST['description']) || !isset($_POST['description'])){
        $response[] = "La descripcion no esta informada.";
    }	elseif (empty($_POST['paid']) || !isset($_POST['paid'])){
        $response[] = "El estado del pago no esta informado.";
    }  elseif (
        !empty($_POST['type'])
        && !empty($_POST['id_company'])
        && !empty($_POST['id_entity'])
        && !empty($_POST['date'])
        && !empty($_POST['document_number'])
        && !empty($_POST['amount'])
        && !empty($_POST['description'])
        && !empty($_POST['paid'])
    ){
        $con = Database::getCon(); 
        if($_POST['type']=="expenses"){
            $element = new ExpensesData();
        }
        if($_POST['type']=="income"){
            $element = new IncomeData();
        }
        if(isset($element) || !empty(!element)){
            $element->description = $_POST["description"];
            $element->amount = $_POST["amount"];
            $element->user_id = "1";
            $element->empresa = $_POST['id_company'];
            $element->entidad = intval($_POST['id_entity']);
            $entity = EntityData::getById($element->entidad);
            $element->fecha = $_POST['date'];
            $element->document_number = $_POST['document_number'];
            $element->pagado =  $_POST['paid'];
            $element->documento = "";
            $element->pagado_con ="";
            $element->pago = "";
            $element->category_id = $entity->category_id;
            $element->tipo = $entity->tipo;

            $query_new=$element->add();
            if (isset($query_new) && !empty($query_new) && $query_new[0]) {
                $response[] = [
                    "id_transaction"=> $query_new[1],
                    "status_transaction"=> "El registro se ha guardado satisfactoriamente."
                ];
                $messages[] = "El ingreso ha sido agregado con éxito.";
                $change_log = new ChangeLogData();
                $change_log->tabla = $_POST['type'];
                $change_log->registro_id = $query_new[1];
                $change_log->description = $element->description;
                $change_log->amount = $element->amount;
                $change_log->document_number = $element->document_number;
                $change_log->entidad = $element->entidad;
                $change_log->fecha = $element->fecha;
                $change_log->pagado = $element->pagado;
                $change_log->user_id = $element->user_id;
                $result = $change_log->add();
                if (isset($result) && !empty($result) && $result[0]){
                    $response[] = [
                            "id_transaction"=> $result[1],
                            "status_transaction"=> "El registro de cambios ha sido actualizado satisfactoriamente."
                        ];
                } else{
                    $response[] = [
                        "id_transaction"=> "0",
                        "status_transaction"=> "Lo siento algo ha salido mal en el registro de errores."
                    ];
                }										
            } else {
                $response[] = [
                    "id_transaction"=> "0",
                    "status_transaction"=> "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo."
                ];
            }
        }

    } else {
        $response[] = [
            "id_transaction"=> "0",
            "status_transaction"=> "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo."
        ];
    }
    header("HTTP/1.1 200 OK");
    echo json_encode($response);
    exit();
}
// Crear un nuevo get
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    //echo json_encode($_GET);
    $response = array();
    //Se validan nuevos parametros de los ingresos
    if (empty($_GET['amount']) || !isset($_GET['amount'])){
        $response[] = "La cantidad no es esta informada.";
    }  elseif (empty($_GET['type']) || !isset($_GET['type'])){
        $response[] = "El tipo no esta informado.";
    }  elseif (empty($_GET['id_company']) || !isset($_GET['id_company'])){
        $response[] = "El id de empresa no esta informado.";
    }  elseif (empty($_GET['id_entity']) || !isset($_GET['id_entity'])){
        $response[] = "El id de la entidad no esta informado.";
    }	elseif (empty($_GET['date']) || !isset($_GET['date'])){
        $response[] = "La fecha no esta informada.";
    }	elseif (empty($_GET['document_number']) || !isset($_GET['document_number'])){
        $response[] = "El numero de documento no esta informado.";
    }	elseif (empty($_GET['description']) || !isset($_GET['description'])){
        $response[] = "La descripcion no esta informada.";
    }	elseif (empty($_GET['paid']) || !isset($_GET['paid'])){
        $response[] = "El estado del pago no esta informado.";
    }  elseif (
        !empty($_GET['type'])
        && !empty($_GET['id_company'])
        && !empty($_GET['id_entity'])
        && !empty($_GET['date'])
        && !empty($_GET['document_number'])
        && !empty($_GET['amount'])
        && !empty($_GET['description'])
        && !empty($_GET['paid'])
    ){
        $con = Database::getCon(); 
        if($_GET['type']=="expenses"){
            $element = new ExpensesData();
        }
        if($_GET['type']=="income"){
            $element = new IncomeData();
        }
        if(isset($element) || !empty(!element)){
            $element->description = $_GET["description"];
            $element->amount = $_GET["amount"];
            $element->user_id = "1";
            $element->empresa = $_GET['id_company'];
            $element->entidad = intval($_GET['id_entity']);
            $entity = EntityData::getById($element->entidad);
            $element->fecha = $_GET['date'];
            $element->document_number = $_GET['document_number'];
            $element->pagado =  $_GET['paid'];
            $element->documento = "";
            $element->pagado_con ="";
            $element->pago = "";
            $element->category_id = $entity->category_id;
            $element->tipo = $entity->tipo;
            echo json_encode($element);

            $query_new=$element->add();
            if (isset($query_new) && !empty($query_new) && $query_new[0]) {
                $response[] = [
                    "id_transaction"=> $query_new[1],
                    "status_transaction"=> "El registro se ha guardado satisfactoriamente."
                ];
                $messages[] = "El ingreso ha sido agregado con éxito.";
                $change_log = new ChangeLogData();
                $change_log->tabla = $_GET['type'];
                $change_log->registro_id = $query_new[1];
                $change_log->description = $element->description;
                $change_log->amount = $element->amount;
                $change_log->document_number = $element->document_number;
                $change_log->entidad = $element->entidad;
                $change_log->fecha = $element->fecha;
                $change_log->pagado = $element->pagado;
                $change_log->user_id = $element->user_id;
                echo json_encode($change_log);

                $result = $change_log->add();
                if (isset($result) && !empty($result) && $result[0]){
                    $response[] = [
                            "id_transaction"=> $result[1],
                            "status_transaction"=> "El registro de cambios ha sido actualizado satisfactoriamente."
                        ];
                } else{
                    $response[] = [
                        "id_transaction"=> "0",
                        "status_transaction"=> "Lo siento algo ha salido mal en el registro de errores."
                    ];
                }										
            } else {
                $response[] = [
                    "id_transaction"=> "0",
                    "status_transaction"=> "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo."
                ];
            }
        }

    } else {
        $response[] = [
            "id_transaction"=> "0",
            "status_transaction"=> "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo."
        ];
    }
    header("HTTP/1.1 200 OK");
    echo json_encode($response);
    exit();
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    header("HTTP/1.1 200 OK");
    echo "Request delete";
    exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{ 
    header("HTTP/1.1 200 OK");
    echo "Request put";
    exit();
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

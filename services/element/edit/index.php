<?php
include "../../../core/autoload.php";
include "../../../core/modules/index/model/ExpensesData.php";
include "../../../core/modules/index/model/IncomeData.php";
include "../../../core/modules/index/model/EntityData.php";
include "../../../core/modules/index/model/ChangeLogData.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //echo json_encode($_POST);
    $response = array();
    //Se validan nuevos parametros de los ingresos
    if (empty($_POST['id']) || !isset($_POST['id'])) {
        $response[] = "El identificador del elemento no ha sido informado.";
    } elseif (empty($_POST['amount']) || !isset($_POST['amount'])) {
        $response[] = "La cantidad no es esta informada.";
    } elseif (empty($_POST['type']) || !isset($_POST['type'])) {
        $response[] = "El tipo no esta informado.";
    } elseif (empty($_POST['id_company']) || !isset($_POST['id_company'])) {
        $response[] = "El id de empresa no esta informado.";
    } elseif (empty($_POST['id_entity']) || !isset($_POST['id_entity'])) {
        $response[] = "El id de la entidad no esta informado.";
    } elseif (empty($_POST['date']) || !isset($_POST['date'])) {
        $response[] = "La fecha no esta informada.";
    } elseif (empty($_POST['document_number']) || !isset($_POST['document_number'])) {
        $response[] = "El numero de documento no esta informado.";
    } elseif (empty($_POST['description']) || !isset($_POST['description'])) {
        $response[] = "La descripcion no esta informada.";
    } elseif (empty($_POST['paid']) || !isset($_POST['paid'])) {
        $response[] = "El estado del pago no esta informado.";
    } elseif (
        !empty($_POST['type'])
        && !empty($_POST['id_company'])
        && !empty($_POST['id'])
        && !empty($_POST['id_entity'])
        && !empty($_POST['date'])
        && !empty($_POST['document_number'])
        && !empty($_POST['amount'])
        && !empty($_POST['description'])
        && !empty($_POST['paid'])
    ) {

        $id = $_POST["id"];
        $id = intval($id);
        $con = Database::getCon();


        if ($_POST['type'] == "expenses") {
            $element = ExpensesData::getById($id);
        }
        if ($_POST['type'] == "income") {
            $element = IncomeData::getById($id);
        }
        if (isset($element) || !empty(!$element)) {
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
            $element->pagado_con = "";
            $element->pago = "";
            if ($element->pagado) {
                $element->payment_date = $_POST['date'];
            }
            $element->category_id = $entity->category_id;
            $element->tipo = $entity->tipo;

            $query_new = $element->update();
            if (isset($query_new) && !empty($query_new) && $query_new) {
                $response[] = [
                    "id_transaction" => $id,
                    "status_transaction" => "El registro se ha actualizado satisfactoriamente."
                ];
                $change_log = new ChangeLogData();
                $change_log->tabla = $_POST['type'];
                $change_log->registro_id = $element->id;
                $change_log->description = $element->description;
                $change_log->amount = $element->amount;
                $change_log->document_number = $element->document_number;
                $change_log->entidad = $element->entidad;
                $change_log->fecha = $element->fecha;
                $change_log->active = $element->active;
                $change_log->payment_date = $element->payment_date;
                $change_log->pagado = $element->pagado;
                $change_log->user_id = $element->user_id;
                $result = $change_log->add();
                if (isset($result) && !empty($result)) {
                    $response[] = [
                        "id_transaction" => $result[1],
                        "status_transaction" => "El registro de cambios ha sido actualizado satisfactoriamente."
                    ];
                } else {
                    $response[] = [
                        "id_transaction" => "0",
                        "status_transaction" => "Lo siento algo ha salido mal en el registro de errores."
                    ];
                }
            } else {
                $response[] = [
                    "id_transaction" => "0",
                    "status_transaction" => "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo."
                ];
            }
        }
    } else {
        $response[] = [
            "id_transaction" => "0",
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
    echo json_encode($response);
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

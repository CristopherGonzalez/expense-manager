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
    if (empty($_POST['amount']) || !isset($_POST['amount'])) {
        $response[] = "La cantidad no es esta informada.";
    } elseif (empty($_POST['type']) || !isset($_POST['type'])) {
        $response[] = "El tipo no esta informado.";
    } elseif (empty($_POST['id_company']) || !isset($_POST['id_company'])) {
        $response[] = "El id de empresa no esta informado.";
    } elseif (empty($_POST['id_entity']) || !isset($_POST['id_entity'])) {
        $response[] = "El id de la entidad no esta informado.";
    } elseif (empty($_POST['date']) || !isset($_POST['date'])) {
        $response[] = "La fecha no esta informada.";
    } elseif (empty($_POST['date_expires']) || !isset($_POST['date_expires'])) {
        $response[] = "La fecha de expiración no esta informada.";
    } elseif (empty($_POST['document_number']) || !isset($_POST['document_number'])) {
        $response[] = "El numero de documento no esta informado.";
    } elseif (empty($_POST['description']) || !isset($_POST['description'])) {
        $response[] = "La descripcion no esta informada.";
    } elseif (empty($_POST['paid']) || !isset($_POST['paid'])) {
        $response[] = "El estado del pago no esta informado.";
    } elseif (
        !empty($_POST['type'])
        && !empty($_POST['id_company'])
        && !empty($_POST['id_entity'])
        && !empty($_POST['date'])
        && !empty($_POST['date_expires'])
        && !empty($_POST['document_number'])
        && !empty($_POST['amount'])
        && !empty($_POST['description'])
        && !empty($_POST['paid'])
    ) {
        $con = Database::getCon();
        if ($_POST['type'] == "expenses") {
            $element = new ExpensesData();
        }
        if ($_POST['type'] == "income") {
            $element = new IncomeData();
        }
        if (isset($element) || !empty(!$element)) {
            $element->description = $_POST["description"];
            $element->amount = $_POST["amount"];
            $element->user_id = "1";
            $element->empresa = $_POST['id_company'];
            $element->entidad = intval($_POST['id_entity']);
            $entity = EntityData::getById($element->entidad);
            $element->fecha = $_POST['date'];
            $element->fecha_vence = mysqli_real_escape_string($con, (strip_tags($_POST["date_expires"], ENT_QUOTES)));
            $element->document_number = $_POST['document_number'];
            $element->pagado =  $_POST['paid'];
            $element->documento = "";
            $element->pago = "";
            //Se realiza guardado de imagenes de pago y documento
            if ($element->pagado && !empty($_POST['pay_with']) && isset($_POST['pay_with']) && !empty($_POST['payment_date']) && isset($_POST['payment_date'])) {
                $element->pagado_con = $_POST['pay_with'];
                $element->payment_date = $_POST['payment_date'];
            }else{
                $element->pagado =  '0';
                $element->pagado_con = '';
                $element->payment_date = '00/00/0000';
            }
            $element->category_id = $entity->category_id;
            $element->tipo = $entity->tipo;

            $query_new = $element->add();
            if (isset($query_new) && !empty($query_new) && $query_new[0]) {
                $response[] = [
                    "id_transaction" => $query_new[1],
                    "status_transaction" => "El registro se ha guardado satisfactoriamente."
                ];
                $messages[] = "El ingreso ha sido agregado con éxito.";
                $change_log = new ChangeLogData();
                $change_log->tabla = $_POST['type'];
                $change_log->registro_id = $query_new[1];
                $change_log->description = $element->description;
                $change_log->amount = $element->amount;
                $change_log->document_number = $element->document_number;
                $change_log->entidad = $element->entidad;
                $change_log->active = $element->active;
                $change_log->payment_date = $element->payment_date;
                $change_log->fecha = $element->fecha;
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
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
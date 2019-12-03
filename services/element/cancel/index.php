<?php
include "../../../core/autoload.php";
include "../../../core/modules/index/model/ExpensesData.php";
include "../../../core/modules/index/model/IncomeData.php";
include "../../../core/modules/index/model/ChangeLogData.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //echo json_encode($_POST);
    $response = array();
    //Se validan nuevos parametros de los ingresos
    if (empty($_POST['id']) || !isset($_POST['id'])){
        $response[] = "El identificador del elemento no ha sido informado.";
    }  elseif (empty($_POST['type']) || !isset($_POST['type'])){
        $response[] = "El tipo no esta informado.";
    }  elseif (
        !empty($_POST['type'])
        && !empty($_POST['id'])
    ){
        $id=$_POST["id"];
        $id=intval($id);
        $con = Database::getCon(); 
        if($_POST['type']=="expenses"){
            $element = ExpensesData::getById($id);
        }
        if($_POST['type']=="income"){
            $element = IncomeData::getById($id);;
        }
        
        if(isset($element) || !empty(!element)){
            $element->amount = 0;
            $element->document = "";
            $element->pago="";
            $query_new=$element->update();
            if (isset($query_new) && !empty($query_new) && $query_new) {
                $response[] = [
                    "id_transaction"=> $element->id,
                    "status_transaction"=> "El registro se ha anulado satisfactoriamente."
                ];
                $change_log = new ChangeLogData();
                $change_log->tabla = $_POST['type'];
                $change_log->registro_id =  $element->id;
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
      header("HTTP/1.1 400 OK");
      exit();
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    header("HTTP/1.1 400 OK");
    exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{ 
    header("HTTP/1.1 400 OK");
    exit();
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
